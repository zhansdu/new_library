<?php

declare(strict_types=1);

namespace App\Services\Handlers;

use App\Exceptions\ReturnResponseException;
use App\Models\ReserveList;
use App\Services\DTO\CancelReservationDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class CancelReservationHandler.
 */
final class CancelReservationHandler
{
    private const STATUS_DEACTIVATED = 0;

    /**
     * @param CancelReservationDTO $dto
     * @throws ReturnResponseException
     */
    public function handle(CancelReservationDTO $dto): void
    {
        $journalIssueId = $this->getJournalIssueId($dto->materialId);

        $materialId = $journalIssueId ?? $dto->materialId;

        $builder = $this->getReserveListBuilder($materialId, $dto->userCid);

        $affected = (bool) $builder->update([
            'status' => self::STATUS_DEACTIVATED
        ]);

        if (! $affected) {
            throw new ReturnResponseException('Cancel reservation failed');
        }
    }

    /**
     * @param int $materialId
     * @param string $userCid
     * @return Builder
     */
    private function getReserveListBuilder(int $materialId, string $userCid): Builder
    {
        return ReserveList::where('user_cid', $userCid)
            ->where(function ($query) use ($materialId) {
                $query->where('book_id', $materialId)
                    ->orWhere('disc_id', $materialId)
                    ->orWhere('j_issue_id', $materialId);
            });
    }

    /**
     * @param int $journalId
     * @return int|null
     */
    private function getJournalIssueId(int $journalId): ?int
    {
        $journalIssue = DB::table('lib_journal_issues')
            ->where('journal_id', $journalId)
            ->select('j_issue_id')
            ->first();

        return $journalIssue?->j_issue_id;
    }
}
