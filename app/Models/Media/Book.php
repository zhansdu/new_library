<?php

namespace App\Models\Media;

use App\Common\Interfaces\Query\DefaultQueryInterface;
use App\Models\Acquisition\Item\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Book extends Model implements DefaultQueryInterface
{
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'lib_books as b';
    protected $primaryKey = 'b.book_id';
    protected $fillable = [
        'book_id',
        'isbn',
        'title',
        'publisher_id',
        'pub_year',
        'pub_city',
        'editor',
        'translator',
        'page_num',
        'seriya',
        'sureli',
        'note',
        'old_id',
        'parallel_title',
        'title_related_info',
        'pub_info',
        'issue_number',
        'issue_date',
        'language',
        'type',
        'issn',
        'volume',
        'prog_code',
    ];

    public static function defaultQuery(): Builder
    {
        return static::query()->select(
            'b.book_id as id', 'b.title as title', 'b.state',
            'b.pub_year as year', 'b.language as language', 'b.callnumber as call_number', 'b.pub_city as city',
            DB::raw("(select lm.title_" . app()->getLocale() . " from lib_material_types lm where lm.key = b.type) as type"),
            'b.type as type_key', 'b.isbn', 'b.issn', 'b.volume', 'b.prog_code',
            DB::raw("(select lp.name from lib_publishers lp where lp.publisher_id = b.publisher_id) as publisher"),
            'ba.author',
            DB::raw("(select r.status from lib_reserve_list r where b.book_id = r.book_id) as status"),
            'av.available',
            'av.total'
        )
            ->leftJoin(
                DB::raw("(select listagg(a.name || a.surname, ', ') within group(order by a.name) as author, a.book_id
                  from lib_book_authors a group by a.book_id) ba"),
                'ba.book_id',
                '=',
                'b.book_id'
            )
            ->leftJoin(
                DB::raw("(select i.book_id,
                            sum(nvl(l.loan_id, 1)) as available,
                           count(*) as total
                      from lib_inventory i
                      left outer join lib_loans l on l.inv_id = i.inv_id and l.locked = 0
                     group by i.book_id) av"),
                'av.book_id',
                '=',
                'b.book_id'
            );
    }

    public static function withAdditionalAttributes(Builder $builder): Builder
    {
        return $builder->addSelect(
            'b.title_related_info',
            'b.page_num as page_number',
            'b.parallel_title',
            DB::raw("(select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.book_id = b.book_id and a.is_main = 1 group by a.book_id) as main_author"),
            DB::raw("(select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.book_id = b.book_id and a.is_main = 0 group by a.book_id) as other_author")
        );
    }
}
