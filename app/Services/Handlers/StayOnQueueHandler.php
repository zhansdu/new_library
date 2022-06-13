<?php

declare(strict_types=1);

namespace App\Services\Handlers;

use App\Models\ReserveList;
use App\Services\DTO\StayOnQueueDTO;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonImmutable;

/**
 * Class StayOnQueueHandler.
 */
final class StayOnQueueHandler
{
    /**
     * @param StayOnQueueDTO $dto
     */
    public function handle(StayOnQueueDTO $dto): void
    {
        DB::transaction(function () use ($dto) {
            $input = $this->getInput($dto);

            $this->insertToReserveList($input);
        });
    }

    /**
     * @param array $input
     */
    private function insertToReserveList(array $input): void
    {
        ReserveList::create($input);
    }

    /**
     * @param StayOnQueueDTO $dto
     * @return array
     */
    private function getInput(StayOnQueueDTO $dto): array
    {
        $input = [
            'user_cid' => $dto->userCid,
            'book_id' => $dto->bookId,
            'j_issue_id' => null,
            'disc_id' => $dto->discId,
            'end_date' => $dto->tillDate->toDateString(),
            'status' => 1,
            'email_send_date' => null,
            'action_date' => CarbonImmutable::now()->toDateString(),
        ];

        if ($dto->journalId !== null) {
            $journalIssueId = $this->getJournalIssueId($dto->journalId);

            $input['j_issue_id'] = $journalIssueId;
        }

        return $input;
    }

    /**
     * @param int $journalId
     * @return int
     */
    private function getJournalIssueId(int $journalId): int
    {
        $journalIssue = DB::table('lib_journal_issues')
            ->where('journal_id', $journalId)
            ->select('j_issue_id')
            ->firstOrFail();

        return $journalIssue->j_issue_id;
    }
}
