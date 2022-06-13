<?php

namespace App\Http\Controllers\Api\Acquisition\Item;

use App\Http\Controllers\Controller;
use App\Models\Acquisition\Item\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdditionalController extends Controller
{
    public function createData(): JsonResponse
    {
        $data = Item::createdData();
        return response()->json(['res' => $data]);
    }

    public function byBatchId(int $batchId): JsonResponse
    {
        $items = DB::select("select i.hesab_id,
                listagg(i.inv_id, ',') as inv_list,
                coalesce((select b.title from lib_books b where b.book_id = i.book_id),
                (select j.title from lib_journal_issues ji left outer join lib_journals j on j.journal_id = ji.journal_id where ji.j_issue_id = i.j_issue_id),
                (select d.name from lib_discs d where d.disc_id = i.disc_id)) as title,
                count(*) as count,
                i.price,
                sum(nvl(i.price,0)) as total_sum,
                i.sigle_type,
                i.currency
         from lib_inventory i
         where i.hesab_id = ?
         group by hesab_id, i.book_id, i.j_issue_id, i.disc_id, i.price, i.sigle_type, i.currency", [$batchId]);

        return response()->json([
            'res' => $items,
        ]);
    }

    public function specialities(): JsonResponse
    {
        return response()->json([
            'res' => Item::specialities()->get()->toArray(),
        ]);
    }
}
