<?php

namespace App\Http\Controllers\Api\Cataloging;

use App\Common\Fields\Cataloging\MediaFields;
use App\Common\Helpers\Show\SearchFields;
use App\Http\Controllers\Api\Cataloging\Handler\MarcFieldsHandler;
use App\Http\Controllers\Api\Cataloging\Handler\MarcFieldsXmlHandler;
use App\Http\Controllers\Controller;
use App\Models\Media\MaterialTypeFactory;
use App\Models\User\Employee;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ShowController extends Controller
{
    public function getMaterialById(string $type, int $materialId): JsonResponse
    {
        $materialInstance = MaterialTypeFactory::getMaterialClass($type);
        $keyName = explode('.', $materialInstance->getKeyName());

        $marcData = DB::table('view_marc_data')->select()->where($keyName[1] ?? $keyName[0], $materialId)->orderBy('id')->get()->toArray();

        if ($type === 'BK') {
            $actualData = DB::table('lib_books as b')
                ->select(
                    'b.isbn', 'b.title', 'p.name as publisher', 'b.pub_year', 'b.pub_city',
                    'b.parallel_title', 'b.title_related_info', 'b.language',
                    DB::raw("(select listagg(a.name||''||a.surname, ',')
                                within group(order by a.name||''||a.surname)
                                from lib_book_authors a where a.book_id = b.book_id and a.is_main = 1) as main_author"),
                    'b.page_num'
                )
                ->leftJoin('lib_publishers as p', 'p.publisher_id', '=', 'b.publisher_id')
                ->where('b.book_id', $materialId)
                ->first();
            $info = DB::table('lib_bibliographic_info')
                ->select('info_id')
                ->where('book_id', $materialId)
                ->first();
        }

        $result = MarcFieldsHandler::generateArray($marcData, $actualData ?? null);

        if (!empty($info)) {
            $log = $this->getLogBuilder($info->info_id)->first();

            if (!empty($log)) {
                $createdBy = $log->emp_id !== null ?
                    $this->getEmployeeFullName((int) $log->emp_id) :
                    $this->getAuthorFullName($log->user_cid);

                $createdInfo = [
                    'created_by' => $createdBy,
                    'created_at' => $log->action_date,
                ];
            }
        }

        return response()->json([
            'res' => $result,
            'created_info' => $createdInfo ?? [],
            'state' => $materialInstance
                    ->query()
                    ->select('state')
                    ->where($keyName[1] ?? $keyName[0], $materialId)
                    ->first()?->state ?? 'not_started',
        ]);
    }

    public function getTypes(): JsonResponse
    {
        return response()->json([
            'res' => DB::table('lib_material_types')
                ->select('key as type', 'title_' . app()->getLocale() . ' as type_title')
                ->get()->toArray(),
        ]);
    }

    public function searchFields(): JsonResponse
    {
        return response()->json([
            'res' => SearchFields::searchFields(new MediaFields())
        ]);
    }

    public function exportXml(string $type, int $materialId): BinaryFileResponse
    {
        $keyName = explode('.', MaterialTypeFactory::getMaterialClass($type)->getKeyName());

        $marcData = DB::table('view_marc_data')->select()->where($keyName[1] ?? $keyName[0], $materialId)->orderBy('id')->get()->toArray();
        $template = (new MarcFieldsHandler([], $marcData))->getTemplate();
        $xml = (new MarcFieldsXmlHandler($template))->getXml();

        File::put(storage_path('') . "/material_{$materialId}.xml", $xml);

        return response()->download(storage_path('') . "/material_{$materialId}.xml");
    }

    /**
     * @param string $type
     * @param int $materialId
     * @return JsonResponse
     */
    public function completeCataloging(string $type, int $materialId): JsonResponse
    {
        $materialQuery = MaterialTypeFactory::getMaterialQuery($type, $materialId);

        DB::transaction(function () use ($materialQuery) {
            $materialQuery
                ->update([
                    'state' => 'completed',
                ]);
        });

        return response()->json([
            'res' => 'success'
        ]);
    }

    /**
     * @param string $type
     * @param int $materialId
     * @return JsonResponse
     */
    public function madeByHistory(string $type, int $materialId): JsonResponse
    {
        $materialInstance = MaterialTypeFactory::getMaterialClass($type);
        $keyName = explode('.', $materialInstance->getKeyName());

        $info = DB::table('lib_bibliographic_info')
            ->select('info_id')
            ->where($keyName[1] ?? $keyName[0], $materialId)
            ->first();

        $history = [];

        if (!empty($info)) {
            $logs = $this->getLogBuilder($info->info_id)
                ->get();

            if (!empty($logs)) {
                foreach ($logs as $log) {
                    $madeBy = $log->emp_id !== null ?
                        $this->getEmployeeFullName((int) $log->emp_id) :
                        $this->getAuthorFullName($log->user_cid);

                    $history[] = [
                        'made_by' => $madeBy,
                        'made_at' => $log->action_date,
                    ];
                }
            }
        }

        return response()->json([
            'res' => $history,
        ]);
    }

    private function getLogBuilder(int $logId)
    {
        return DB::table('lib_logs')
            ->where('row_id', $logId)
            ->select([
                'user_cid',
                'action_date',
                'emp_id',
            ])
            ->orderBy('action_date');
    }

    private function getEmployeeFullName(int $empId): ?string
    {
        if ($empId === 0) {
            return null;
        }

        $employee = Employee::find($empId);

        return $employee->name . ' ' . $employee->sname;
    }

    /**
     * @param string|null $userCid
     * @return string|null
     */
    private function getAuthorFullName(?string $userCid): ?string
    {
        if ($userCid === null) {
            return null;
        }

        /** @var User $userCard */
        $userCard = User::find($userCid);

        if ($userCard === null) {
            return null;
        }

        if ($userCard->emp_id !== null) {
            return $userCard->employee->name . ' ' . $userCard->employee->sname;
        }

        if ($userCard->stud_id !== null) {
            return $userCard->student->name . ' ' . $userCard->student->surname;
        }

        return null;
    }

    /**
     * @return JsonResponse
     */
    public function getGenres(): JsonResponse
    {
        $genres = DB::table('genre')
            ->select([
                'code',
                DB::raw("title_en||'/'||title_kz||'/'||title_ru as title"),
            ])
            ->get()
            ->toArray();

        return response()->json([
            'res' => $genres,
        ]);
    }
}
