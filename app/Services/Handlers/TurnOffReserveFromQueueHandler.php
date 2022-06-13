<?php

declare(strict_types=1);

namespace App\Services\Handlers;

use App\Exceptions\ReturnResponseException;
use App\Models\Acquisition\Item\Item;
use App\Models\ReserveList;
use App\Services\Entities\ServiceActionEntity;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class TurnOffReserveFromQueueHandler.
 */
final class TurnOffReserveFromQueueHandler
{
    /**
     * @param ServiceActionEntity $serviceActionEntity
     * @param string $userCid
     * @throws ReturnResponseException
     */
    public function handle(ServiceActionEntity $serviceActionEntity, string $userCid): void
    {
        $this->setDependencies($serviceActionEntity);
        $this->turnOffReserve($serviceActionEntity, $userCid);
    }

    /**
     * @param ServiceActionEntity $serviceActionEntity
     */
    private function setDependencies(ServiceActionEntity $serviceActionEntity)
    {
        $item = Item::find($serviceActionEntity->invId);

        $serviceActionEntity->setBookId((int) $item->book_id);
        $serviceActionEntity->setDiscId((int) $item->disc_id);
        $serviceActionEntity->setJournalIssueId((int) $item->j_issue_id);
    }

    /**
     * @param ServiceActionEntity $serviceActionEntity
     * @param string $userCid
     * @throws ReturnResponseException
     */
    private function turnOffReserve(ServiceActionEntity $serviceActionEntity, string $userCid)
    {
        /** @var Builder|null $builder */
        $builder = null;

        if ($serviceActionEntity->bookId !== null) {
            $builder = ReserveList::where('book_id', $serviceActionEntity->bookId);
        }

        if ($serviceActionEntity->discId !== null) {
            $builder = ReserveList::where('disc_id', $serviceActionEntity->discId);
        }

        if ($serviceActionEntity->journalIssueId !== null) {
            $builder = ReserveList::where('j_issue_id', $serviceActionEntity->journalIssueId);
        }

        if ($builder === null) {
            throw new ReturnResponseException('Material ID is required', 400);
        }

        $builder->where('user_cid', $userCid)
            ->where('status', 1)
            ->update(['status' => 0]);
    }
}
