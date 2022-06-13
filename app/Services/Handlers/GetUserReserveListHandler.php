<?php

declare(strict_types=1);

namespace App\Services\Handlers;

use App\Exceptions\ReturnResponseException;
use App\Models\Acquisition\Item\Item;
use App\Models\Media\Book;
use App\Models\Media\Disc;
use App\Models\Media\Journal;
use App\Models\ReserveList;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class GetUserReserveListHandler.
 */
final class GetUserReserveListHandler
{
    /**
     * @param string $userCid
     * @return Collection|\Illuminate\Support\Collection
     */
    public function handle(string $userCid): Collection|\Illuminate\Support\Collection
    {
        $reserveList = $this->getReserveList($userCid);

        return $this->getMappedReserveList($reserveList);
    }

    /**
     * @param Collection $queue
     * @return Collection|\Illuminate\Support\Collection
     */
    private function getMappedReserveList(Collection $queue): Collection|\Illuminate\Support\Collection
    {
        return $queue->map(function (ReserveList $reserveList) {
            $material = $this->getMaterial($reserveList);

            return [
                'reservation_date' => $this->getReservationDate($reserveList),
                'author' => trim($material['author'] ?? ''),
                'title' => $material['title'] ?? '',
                'status' => $this->getStatus($reserveList),
                'material_id' => (int) $material['material_id'],
            ];
        });
    }

    /**
     * @param ReserveList $reserveList
     * @return string
     * @throws ReturnResponseException
     */
    private function getReservationDate(ReserveList $reserveList): string
    {
        try {
            $actionDate = CarbonImmutable::parse($reserveList->action_date);
            $endDate = CarbonImmutable::parse($reserveList->end_date);
        } catch (\Throwable $exception) {
            throw new ReturnResponseException('Incorrect date');
        }

        return $actionDate->toDateString() . ' -> ' . $actionDate->toDateString();
    }

    /**
     * @param ReserveList $reserveList
     * @return string
     * @throws ReturnResponseException
     */
    private function getStatus(ReserveList $reserveList): string
    {
        if (CarbonImmutable::now()->gt($reserveList->end_date)) {
            $reserveList->status = 0;
            $reserveList->save();
            $reserveList->fresh();
        }

        if ((int) $reserveList->status === 0) {
            return 'Expired';
        }

        return 'In queue';
    }

    /**
     * @param string $userCid
     * @return Collection
     */
    private function getReserveList(string $userCid): Collection
    {
        return ReserveList::where('user_cid', $userCid)->distinct(['book_id', 'disc_id', 'journal_id'])->get();
    }

    /**
     * @param ReserveList $reserveList
     * @return array
     * @throws ReturnResponseException
     */
    private function getMaterial(ReserveList $reserveList): array
    {
        $material = null;

        if ($reserveList->book_id !== null) {
            $material = $this->getBook((int) $reserveList->book_id);
        }

        if ($reserveList->disc_id !== null) {
            $material = $this->getDisc((int) $reserveList->disc_id);
        }

        if ($reserveList->j_issue_id !== null) {
            $material = $this->getJournal((int) $reserveList->j_issue_id);
        }

        if ($material === null) {
            throw new ReturnResponseException('Material Id is required', 400);
        }

        return [
            'title' => $material->title,
            'author' => $material->author,
            'material_id' => $material->id,
        ];
    }

    /**
     * @param int $bookId
     * @return Book
     */
    private function getBook(int $bookId): Book
    {
        return Book::select([
            'b.book_id as id',
            'b.title',
            DB::raw("(select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.book_id = b.book_id group by a.book_id) as author"),
        ])
            ->where('b.book_id', $bookId)
            ->firstOrFail();
    }

    /**
     * @param int $discId
     * @return Disc
     */
    private function getDisc(int $discId): Disc
    {
        return Disc::select([
            'd.disc_id as id',
            'd.name as title',
            DB::raw("(select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.disc_id = d.disc_id group by a.disc_id) as author"),
        ])
            ->where('disc_id', $discId)
            ->firstOrFail();
    }

    /**
     * @param int $journalIssueId
     * @return Journal
     */
    private function getJournal(int $journalIssueId): Journal
    {
        return Journal::select([
            'j.journal_id as id',
            'j.title',
            DB::raw("(select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.j_issue_id = ji.j_issue_id group by a.j_issue_id) as author"),
        ])
            ->leftJoin('lib_journal_issues as ji', 'ji.journal_id', '=', 'j.journal_id')
            ->where('ji.j_issue_id', $journalIssueId)
            ->firstOrFail();
    }
}
