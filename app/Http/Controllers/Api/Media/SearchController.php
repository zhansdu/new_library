<?php

namespace App\Http\Controllers\Api\Media;

use App\Common\Fields\Media\MediaFields;
use App\Common\Helpers\Controller\CustomPaginate;
use App\Common\Helpers\Controller\Search;
use App\Common\Helpers\Models\Media\GetModels;
use App\Common\Helpers\Query\QueryHelper;
use App\Common\Helpers\Search\FilterHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(SearchRequest $request): JsonResponse
    {
        $perPage = $request->get('per_page') ?? 10;

        $searchOptions = Arr::get($request->validated(), 'search_options') ?? [];
        $firstOption = Arr::get($searchOptions, 0) ?? [];
        $key = Arr::get($firstOption, 'key');

        if ($key === 'all') {
            $data = $this->searchByKeywords(Arr::get($firstOption, 'value'));
            $data = collect($data);
        } else {
            $dataByContent = $this->contentSearch($request);
            $data = Search::search($request, QueryHelper::unionAll(...GetModels::getModels()), new MediaFields());
            $data = $data->merge($dataByContent);
        }

        $forFilter = FilterHelper::forFilter($data, MediaFields::getFilterFields());

        return response()->json([
            'res' => CustomPaginate::getPaginate($data, $request, $perPage),
            'filter' => $forFilter,
            'all' => $data->pluck('id')->toArray()
        ]);
    }

    /**
     * @param SearchRequest $request
     * @return \Tightenco\Collect\Support\Collection|\Illuminate\Support\Collection
     */
    private function contentSearch(SearchRequest $request): \Tightenco\Collect\Support\Collection|\Illuminate\Support\Collection
    {
        $data = collect([]);
        $options = $request->input('search_options');

        if ($options[0]['key'] === 'all') {
            $value = $options[0]['value'];

            $searchResults = DB::select(DB::raw(
                "select coalesce(book_id, journal_id, disc_id) as id
                        from LIB_BIBLIOGRAPHIC_INFO t
                        where CONTAINS(t.xml_data, '$value INPATH (//TreeList/Nodes/Node/NodeData[Cell=\"650.x\"])', 1) > 0
                        ORDER BY SCORE(1) DESC"
            ));

            $ids = [];

            foreach ($searchResults as $result) {
                $ids[] = $result->id;
            }

            $data = QueryHelper::unionAll(...GetModels::getModels())->select()->whereIn('id', $ids)->get();
        }

        return $data;
    }

    /**
     * @param mixed $value
     * @return array
     */
    private function searchByKeywords(mixed $value): array
    {
        return DB::select("select * from (select B.BOOK_ID as ID,
               B.TITLE as TITLE,
               B.STATE,
               B.PUB_YEAR as YEAR,
               B.LANGUAGE as LANGUAGE,
               B.CALLNUMBER as CALL_NUMBER,
               B.PUB_CITY as CITY,
               (select lm.title_en
                  from lib_material_types lm
                 where lm.key = b.type) as type,
               B.TYPE as TYPE_KEY,
               B.ISBN,
               B.ISSN,
               B.VOLUME,
               B.PROG_CODE,
               lp.name as publisher,
               ba.author,
               (select r.status
                  from lib_reserve_list r
                 where b.book_id = r.book_id) as status,
                 AV.AVAILABLE,
                 AV.TOTAL
          from LIB_BOOKS b
          left outer join (select listagg(a.name || a.surname, ', ') within group(order by a.name) as author, a.book_id
                  from lib_book_authors a group by a.book_id) ba on ba.book_id = b.book_id
          left join (select I.BOOK_ID,
                           sum(nvl((select 0
                                     from lib_loans l
                                    where l.inv_id = i.inv_id
                                      and l.locked = 0),
                                   1)) as available,
                           count(*) as total
                      from LIB_INVENTORY i
                     group by I.BOOK_ID) AV
            on AV.BOOK_ID = B.BOOK_ID
           left outer join lib_publishers lp on lp.publisher_id = b.publisher_id
           WHERE EXISTS(select t.info_id from LIB_BIBLIOGRAPHIC_INFO t where CONTAINS(t.xml_data, ?||' INPATH (//TreeList/Nodes/Node/NodeData)', 1) > 0
        AND t.book_id = b.book_id) order by score(1))
        ", [$value]);
    }
}
