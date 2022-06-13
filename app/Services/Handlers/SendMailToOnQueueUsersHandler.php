<?php

declare(strict_types=1);

namespace App\Services\Handlers;

use App\Exceptions\ReturnResponseException;
use App\Mail\OnQueueMail;
use App\Models\Acquisition\Item\Item;
use App\Models\Media\Book;
use App\Models\Media\Disc;
use App\Models\Media\Journal;
use App\Models\ReserveList;
use App\Models\User\User;
use App\Services\Entities\ServiceActionEntity;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use DB;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendMailToOnQueueUsersHandler.
 */
final class SendMailToOnQueueUsersHandler
{
    /**
     * @param ServiceActionEntity $serviceActionEntity
     * @throws ReturnResponseException
     */
    public function handle(ServiceActionEntity $serviceActionEntity): void
    {
        $this->setDependencies($serviceActionEntity);
        $queue = $this->getMaterialQueue($serviceActionEntity);

        if ($queue->isEmpty()) {
            return;
        }

        $users = $this->getUserCards(
            $queue->pluck('user_cid')
                ->toArray()
        );

        $emails = $this->getEmails($users);

        $this->sendMail($emails, $serviceActionEntity);
        $this->setSentEmailDates($queue);
    }

    /**
     * @param ServiceActionEntity $serviceActionEntity
     */
    private function setDependencies(ServiceActionEntity $serviceActionEntity): void
    {
        $item = Item::find($serviceActionEntity->invId);

        $serviceActionEntity->setBookId($item->book_id === null ? null : (int) $item->book_id);
        $serviceActionEntity->setDiscId($item->disc_id === null ? null : (int) $item->disc_id);
        $serviceActionEntity->setJournalIssueId($item->j_issue_id === null ? null : (int) $item->j_issue_id);

        $this->setMaterialInfo($serviceActionEntity);
    }

    /**
     * @param ServiceActionEntity $serviceActionEntity
     * @return Collection
     * @throws ReturnResponseException
     */
    private function getMaterialQueue(ServiceActionEntity $serviceActionEntity): Collection
    {
        /** @var Builder|null $builder */
        $builder = null;

        if ($serviceActionEntity->bookId !== null) {
            $builder = ReserveList::where('book_id', $serviceActionEntity->bookId);
        } else if ($serviceActionEntity->journalIssueId !== null) {
            $builder = ReserveList::where('j_issue_id', $serviceActionEntity->journalIssueId);
        } else if ($serviceActionEntity->discId !== null) {
            $builder = ReserveList::where('disc_id', $serviceActionEntity->discId);
        }

        if ($builder === null) {
            throw new ReturnResponseException('Material ID is required', 400);
        }

        return $builder
            ->where('status', 1)
            ->get();
    }

    /**
     * @param array $userCIDs
     * @return Collection
     */
    private function getUserCards(array $userCIDs): Collection
    {
        return User::with(['student', 'employee'])
            ->where('user_cid', $userCIDs)
            ->get();
    }

    /**
     * @param Collection $users
     * @return array
     */
    private function getEmails(Collection $users): array
    {
        return $users->map(function (User $user) {
            if ($user->stud_id !== null) {
                return [
                    'email' => $user->student->stud_id.'@stu.sdu.edu.kz',
                ];
            } else if ($user->emp_id !== null) {
                return [
                    'email' => $user->employee->hname.'@sdu.edu.kz',
                ];
            }

            return ['email' => null];
        })
            ->whereNotNull('email')
            ->pluck('email')
            ->toArray();
    }

    /**
     * @param array $emails
     * @param ServiceActionEntity $serviceActionEntity
     */
    private function sendMail(array $emails, ServiceActionEntity $serviceActionEntity): void
    {
        Mail::to($emails)->send(
            new OnQueueMail($serviceActionEntity->getOnQueueMail())
        );
    }

    /**
     * @param Collection $queue
     */
    private function setSentEmailDates(Collection $queue): void
    {
        $queue->each(function (ReserveList $item) {
            $item->update(['email_send_date' => CarbonImmutable::now()->toDateString()]);
        });
    }

    /**
     * @param ServiceActionEntity $serviceActionEntity
     */
    private function setMaterialInfo(ServiceActionEntity $serviceActionEntity): void
    {
        if ($serviceActionEntity->bookId !== null) {
            $book = Book::select([
                'title',
                'pub_year',
                DB::raw("(select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.book_id = b.book_id group by a.book_id) as author"),
            ])
                ->findOrFail($serviceActionEntity->bookId);

            $serviceActionEntity->setTitle($book->title);
            $serviceActionEntity->setPubYear((int) $book->pub_year);
            $serviceActionEntity->setAuthor($book->author);

            return;
        }

        if ($serviceActionEntity->discId !== null) {
            $disc = Disc::select([
                'name',
                'pub_year',
                DB::raw("(select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.disc_id = d.disc_id group by a.disc_id) as author"),
            ])
                ->findOrFail($serviceActionEntity->discId);

            $serviceActionEntity->setTitle($disc->name);
            $serviceActionEntity->setPubYear((int) $disc->pub_year);
            $serviceActionEntity->setAuthor($disc->author);

            return;
        }

        if ($serviceActionEntity->journalIssueId !== null) {
            $journal = Journal::select([
                'title',
                'pub_year',
                DB::raw("(select listagg(a.name||a.surname, ', ') within group(order by a.name)
                            from lib_book_authors a where a.j_issue_id = ji.j_issue_id group by a.j_issue_id) as author"),
            ])
                ->join('lib_journal_issues as ji', 'j.journal_id', '=', 'ji.journal_id')
                ->where('ji.j_issue_id', $serviceActionEntity->journalIssueId)
                ->firstOrFail();

            $serviceActionEntity->setTitle($journal->title);
            $serviceActionEntity->setPubYear((int) $journal->pub_year);
            $serviceActionEntity->setAuthor($journal->author);
        }
    }
}
