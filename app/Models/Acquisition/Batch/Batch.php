<?php

namespace App\Models\Acquisition\Batch;

use App\Common\Interfaces\Query\DefaultQueryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Batch extends Model implements DefaultQueryInterface
{
    use BatchAdditional;
    use BatchManage;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'lib_hesablar as b';
    protected $primaryKey = 'b.hesab_id';
    protected $fillable = [
        'invoice_date', 'create_date', 'items_no', 'titles_no', 'doc_no',
        'supply_type', 'supplier_id', 'contract_no', 'invoice_details', 'cost', 'user_id',
        'edit_date'
    ];

    public static function defaultQuery(): Builder
    {
        return static::query()->select(
            'b.status as status_key',
            DB::raw("(select s.title_" . app()->getLocale() . " from lib_batch_status s where s.id = b.status) as status"),
            'b.hesab_id as id',
            'b.invoice_date',
            DB::raw("(select st.title_" . app()->getLocale() . " from lib_supply_types st where st.key = b.supply_type) as sup_type"),
            'b.supply_type as sup_key',
            DB::raw("(select ls.supplier_name from lib_suppliers ls where ls.supplier_id = b.supplier_id) as supplier"),
            'b.supplier_id as sup_id',
            'b.titles_no',
            'b.items_no',
            DB::raw("(select count(*) from lib_hesab_mats bm where b.hesab_id = bm.hesab_id group by bm.hesab_id) as titles_ma"),
            'total_ma.items_ma',
            'total_ma.price',
            'total_ma.currency',
            'b.doc_no as doc_no', 'b.contract_no', 'b.invoice_details', 'b.create_date as create_date', 'b.edit_date', 'b.cost',
            DB::raw("(select (e.name||' '||e.sname) from dbmaster.employee e where e.emp_id = b.user_id) as created_by"),
            DB::raw("(select (e.name||' '||e.sname) from dbmaster.employee e where e.emp_id = b.edited_by) as edited_by")
        )
            ->leftJoin(DB::raw("(select h.hesab_id, count(*) as items_ma, sum(i.price) as price, max(i.currency) as currency from lib_hesab_mats h
                        left join lib_inventory i on nvl(i.book_id, 1) = nvl(h.book_id, 1) and nvl(i.j_issue_id, 1) = nvl(h.j_issue_id, 1) and nvl(i.disc_id, 1) = nvl(h.disc_id, 1) and i.hesab_id = h.hesab_id
                        where  i.inv_id is not null
                        group by h.hesab_id) total_ma"), 'total_ma.hesab_id', '=', 'b.hesab_id');
    }
}
