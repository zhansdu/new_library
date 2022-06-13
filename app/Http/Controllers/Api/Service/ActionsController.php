<?php

namespace App\Http\Controllers\Api\Service;

use App\Common\Helpers\Controller\LoanProcedure;
use App\Exceptions\ReturnResponseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ActionRequest;
use App\Http\Requests\Service\CancelReservationRequest;
use App\Http\Requests\Service\StayOnQueueRequest;
use App\Models\Media\Loan;
use App\Models\ReserveList;
use App\Models\User\User;
use App\Models\User\WebLog;
use App\Services\Entities\ServiceActionEntity;
use App\Services\Handlers\CancelReservationHandler;
use App\Services\Handlers\SendMailToOnQueueUsersHandler;
use App\Services\Handlers\StayOnQueueHandler;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;

class ActionsController extends Controller
{
    /**
     * @param ActionRequest $request
     * @param SendMailToOnQueueUsersHandler $handler
     * @return JsonResponse
     * @throws ReturnResponseException
     */
    public function backMaterial(ActionRequest $request, SendMailToOnQueueUsersHandler $handler): JsonResponse
    {
        $validated = $this->processInput($request);

        $exists = Loan::where('loan_id', $validated['loan_id'])
            ->whereNotNull('delivery_date')
            ->where('locked', 1)
            ->exists();

        if ($exists === true) {
            throw new ReturnResponseException('Материал уже возвращен', 400);
        }

        $result = LoanProcedure::backMaterial($validated);

        if (!$result['pRes']) {
            throw new ReturnResponseException('Process error', 400);
        }

        $handler->handle(new ServiceActionEntity(
            $validated['inv_id']
        ));

        return response()->json([
            'res' => [
                'message' => 'success',
                'result' => (bool)$result['pRes'],
            ]
        ]);
    }

    /**
     * @param ActionRequest $request
     * @return JsonResponse
     * @throws ReturnResponseException
     */
    public function giveMaterial(ActionRequest $request): JsonResponse
    {
        $validated = $this->processInput($request);

        $exists = Loan::where('inv_id', $validated['inv_id'])
            ->whereNull('delivery_date')
            ->where('locked', 0)
            ->exists();

        if ($exists === true) {
            throw new ReturnResponseException('Материал уже выдан', 400);
        }

        $exists = ReserveList::whereBetween('email_send_date', [
            CarbonImmutable::now()->subDays(1),
            CarbonImmutable::now(),
        ])
            ->where('user_cid', '!=', $validated['user_cid'])
            ->where('status', 1)
            ->exists();

        if ($exists === true) {
            throw new ReturnResponseException('Материал в очереди', 400);
        }

        /** @var User $user */
        $user = User::findOrFail($validated['user_cid']);
        $userType = $user?->student?->edu_level_type ?? $user?->employee?->position_type;
        $rule = $this->getRule($userType, 'borrow');

        $activeItems = Loan::whereNull('delivery_date')
            ->where('locked', 0)
            ->where('user_cid', $user->user_cid)
            ->count();

        $loanRule = $this->getRule($userType, 'loan');

        if ((int)$validated['duration'] > $rule || $activeItems >= $loanRule) {
            throw new ReturnResponseException('Превышен лимит по дням', 400);
        }

        $result = LoanProcedure::giveMaterial($validated);

        if (!$result['pRes']) {
            throw new ReturnResponseException('Process error', 400);
        }

        return response()->json([
            'res' => [
                'message' => 'success',
                'result' => (bool)$result['pRes'],
            ]
        ]);
    }

    private function processInput(ActionRequest $request): array
    {
        $validated = $request->validated();

        $userCID = $request->user()->user_cid;
        $webLog = WebLog::where('user_id', $userCID)->orderBy('log_date', 'desc')->first()->log_id;

        $validated['web_log_id'] = $webLog;

        $validated['due_date'] = !empty($validated['duration']) ?
            Carbon::now()->addDays((int)$validated['duration'])->toDateString() : ($validated['due_date'] ?? Carbon::now()->toDateString());

        return $validated;
    }

    /**
     * @param StayOnQueueRequest $request
     * @param StayOnQueueHandler $handler
     * @return JsonResponse
     * @throws ReturnResponseException
     */
    public function stayOnQueue(StayOnQueueRequest $request, StayOnQueueHandler $handler): JsonResponse
    {
        $handler->handle($request->getDto());

        return response()->json([
            'res' => 'Success',
        ]);
    }

    /**
     * @param CancelReservationRequest $request
     * @param CancelReservationHandler $handler
     * @return JsonResponse
     * @throws ReturnResponseException
     */
    public function cancelReservation(CancelReservationRequest $request, CancelReservationHandler $handler): JsonResponse
    {
        $handler->handle($request->getDto());

        return response()->json([
            'res' => 'Success',
        ]);
    }

    /**
     * @param string $userType
     * @param string $actionType
     * @return int
     */
    private function getRule(string $userType, string $actionType): int
    {
        return (int)config("config.user_types_rules.$actionType.$userType");
    }

    /**
     * @param ActionRequest $request
     * @return JsonResponse
     * @throws ReturnResponseException
     */
    public function renew(ActionRequest $request): JsonResponse
    {
        $loan = Loan::where('loan_id', $request->input('loan_id'))
            ->whereNull('delivery_date')
            ->where('locked', 0)
            ->first();

        if ($loan === null) {
            throw new ReturnResponseException('Срок сдачи материала истек или материал еще не выдан');
        }

        /** @var User $user */
        $user = User::findOrFail($request->user()?->user_cid);
        $userType = $user?->student?->edu_level_type ?? $user?->employee?->position_type;
        $rule = $this->getRule($userType, 'prolong');

        if ($rule === null) {
            throw new ReturnResponseException('Продление не допустимо для этого пользователя', 400);
        }

        if ($request->input('duration') > $rule) {
            throw new ReturnResponseException('Превышен лимит продления для этого пользователя', 422);
        }

        try {
            Loan::where('loan_id', $loan->loan_id)
                ->update([
                    'due_date' => Carbon::parse($loan->due_date)->addDays((int)$request->input('duration'))->toDateString(),
                ]);
        } catch (\Throwable $e) {
            throw new ReturnResponseException('Невозможно продление для этого материала', 400);
        }

        return response()->json([
            'res' => true,
        ]);
    }
}
