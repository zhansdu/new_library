<?php

namespace App\Http\Controllers\Api\Report;

use App\Common\Helpers\Controller\CustomPaginate;
use App\Exports\Report\KsuReportExport;
use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class KsuReportController extends Controller
{
    public function getReport(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);

        $reportBuilder = $this->getBuilder();

        return response()->json([
            'res' => CustomPaginate::getPaginate($reportBuilder->get(), $request, $perPage),
        ]);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $reportBuilder = $this->getBuilder();

        return Excel::download(
            new KsuReportExport($reportBuilder->get(), $request->get('locale', app()->getLocale())),
            'ksu_report_' . CarbonImmutable::now()->toDateString() . '.xlsx'
        );
    }

    private function getBuilder(): Builder
    {
        return DB::table('lib_hesablar as h')
            ->select([
                'h.invoice_date',
                DB::raw("nvl(h.hesab_id,mc.hesab_id) as batch_id"),
                's.supplier_name as supplier',
                DB::raw("extract (year from nvl(h.invoice_date, h.create_date)) as doc_year"),
                'mc.in_balance_items as in_balance_items',
                'mc.in_balance_titles',
                'mc.in_balance_price',
                'mc.not_in_balance_items',
                'mc.not_in_balance_titles',
                'mc.not_in_balance_price',
                DB::raw("(mc.in_balance_items + mc.not_in_balance_items) as total_items"),
                DB::raw("(mc.in_balance_titles + mc.not_in_balance_titles) as total_titles"),
                DB::raw("(mc.in_balance_price + mc.in_balance_price) as total_price"),
                'sg.scientific_literature',
                'sg.textbooks',
                'sg.educational_methodical_literature',
                'sg.fiction',
                'sg.popular_science_literature',
                'sg.other',
                'mc.language_ru as ru_lang_materials',
                'mc.language_kz as kz_lang_materials',
                'mc.other_language as other_lang_materials',
                'mc.null_lang',
                DB::raw("nvl(mc.disc_titles, 0) as disc_titles"),
                DB::raw("nvl(mc.disc_items, 0) as disc_items"),
                DB::raw("nvl(mc.disc_totalcost, 0) as disc_totalcost"),
                'mc.disc_language_kz',
                'mc.disc_language_ru',
                'mc.disc_language_other',
            ])
            ->leftJoin('lib_suppliers as s', 's.supplier_id', '=', 'h.supplier_id')
            ->leftJoin('VIEW_MATERIAL_COUNTS as mc', 'mc.hesab_id', '=', 'h.hesab_id')
            ->leftJoin('view_statistics_genre as sg', 'sg.hesab_id', '=', 'h.hesab_id')
            ->orderBy('batch_id');
    }
}
