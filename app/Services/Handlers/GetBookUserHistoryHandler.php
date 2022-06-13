<?php

declare(strict_types=1);

namespace App\Services\Handlers;

use App\Models\Media\Loan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class GetBookUserHistoryHandler.
 */
final class GetBookUserHistoryHandler
{
    /**
     * @param int $inventoryId
     * @return array
     */
    public function handle(int $inventoryId): array
    {
        return $this->getLoans($inventoryId)
            ->toArray();
    }

    /**
     * @param int $inventoryId
     * @return Collection
     */
    private function getLoans(int $inventoryId): Collection
    {
        return Loan::where('l.inv_id', $inventoryId)
            ->select([
                DB::raw("(select (case when u.stud_id is not null
                                        then (select t.name||' '||t.surname from dbmaster.students t where u.stud_id = t.stud_id)
                                        when u.emp_id is not null
                                        then (select e.name||' '||e.sname from dbmaster.employee e where e.emp_id = u.emp_id) end)
                                        from lib_user_cards u where u.user_cid = l.user_cid) as username"),
                'l.user_cid',
                'l.borrow_date',
                'l.due_date',
                'l.delivery_date',
                DB::raw("(case when delivery_date is not null and i.status = 1 then 'returned'
                            else (case
                                when current_date <= due_date then 'issued'
                                when current_date > due_date then 'overdue'
                                end)
                                 end) as status")
            ])
            ->leftJoin('lib_inventory as i', 'l.inv_id', '=', 'i.inv_id')
            ->get();
    }
}
