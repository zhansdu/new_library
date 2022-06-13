<?php

declare(strict_types=1);

namespace App\Services\Handlers;

use App\Exceptions\ReturnResponseException;
use App\Models\Media\Loan;
use App\Models\User\Employee;
use App\Models\User\Image;
use App\Models\User\Student;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class GetUserHandler.
 */
final class GetUserHandler
{
    private const STATUS_ISSUED = 'issued';
    private const STATUS_RETURNED = 'returned';
    private const STATUS_OVERDUE = 'overdue';

    private const DEFAULT_DURATION_DAYS = 21;

    /**
     * @param User $user
     * @return array
     * @throws ReturnResponseException
     */
    public function handle(User $user): array
    {
        $userInformation = $this->getUserInfoBuilder($user)->first();

        if ($userInformation === null) {
            throw new ReturnResponseException('Wrong user data', 400);
        }

        $image = $this->getUserImage(
            (int) $userInformation->id,
            $userInformation->type
        );

        $userHistory = $this->getUserHistory($user->user_cid);

        $total = $this->countMaterialsByStatus($userHistory);
        $borrowedMaterials = $this->getBorrowedMaterials($userHistory);

        $duration = $this->getUserDurationBuilder($user->user_cid)->first();

        return [
            'info' => $userInformation,
            'photo' => $image,
            'history' => $userHistory,
            'total' => $total,
            'return' => $borrowedMaterials,
            'duration' => $duration !== null ? $duration->data : self::DEFAULT_DURATION_DAYS
        ];
    }

    /**
     * @param User $user
     * @return Builder
     * @throws ReturnResponseException
     */
    private function getUserInfoBuilder(User $user): Builder
    {
        if ($user->emp_id !== null) {
            return Employee::fullInfo((int) $user->emp_id);
        }

        if ($user->stud_id !== null) {
            return Student::fullInfo((int) $user->stud_id);
        }

        throw new ReturnResponseException('Wrong user data', 400);
    }

    /**
     * @param int $userId
     * @param string $type
     * @return string|null
     */
    private function getUserImage(int $userId, string $type): ?string
    {
        $image = Image::find($userId);

        if ($image !== null) {
            return 'data:image/' . $type . ';base64,' . base64_encode($image->image);
        }

        return null;
    }

    /**
     * @param string $userCid
     * @return Collection
     */
    private function getUserHistory(string $userCid): Collection
    {
        return Loan::userHistory($userCid)->get();
    }

    /**
     * @param Collection $materials
     * @return array
     */
    private function countMaterialsByStatus(Collection $materials): array
    {
        $borrowed = $materials
            ->where('status', self::STATUS_ISSUED)
            ->count();

        $returned = $materials
            ->where('status', self::STATUS_RETURNED)
            ->count();

        $dept = $materials
            ->where('status', self::STATUS_OVERDUE)
            ->count();

        return [
            'borrowed' => $borrowed,
            'returned' => $returned,
            'dept' => $dept
        ];
    }

    /**
     * @param Collection $materials
     * @return Collection
     */
    private function getBorrowedMaterials(Collection $materials): Collection
    {
        return $materials
            ->whereIn('status', [self::STATUS_ISSUED, self::STATUS_OVERDUE]);
    }

    /**
     * @param string $userCid
     * @return Builder
     */
    private function getUserDurationBuilder(string $userCid): Builder
    {
        return DB::table('lib_cfg as lc')
            ->select(['lc.data'])
            ->leftJoin('user_groups as ug', 'ug.group_id', '=', 'lc.group_id')
            ->where('lc.cfg_key', '=', 'BORROW_PERIOD')
            ->where('ug.user_cid', '=', $userCid)
            ->orderBy('data', 'desc');
    }
}
