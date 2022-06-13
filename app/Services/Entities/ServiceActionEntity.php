<?php

declare(strict_types=1);

namespace App\Services\Entities;

/**
 * Class ServiceActionEntity.
 */
final class ServiceActionEntity
{
    /**
     * @param int $invId
     * @param int|null $bookId
     * @param int|null $discId
     * @param int|null $journalIssueId
     * @param string|null $title
     * @param string|null $author
     * @param int|null $pubYear
     */
    public function __construct(
        public int $invId,
        public ?int $bookId = null,
        public ?int $discId = null,
        public ?int $journalIssueId = null,
        public ?string $title = null,
        public ?string $author = null,
        public ?int $pubYear = null,
    ) {}

    /**
     * @param int|null $bookId
     */
    public function setBookId(?int $bookId): void
    {
        $this->bookId = $bookId;
    }

    /**
     * @param int|null $discId
     */
    public function setDiscId(?int $discId): void
    {
        $this->discId = $discId;
    }

    /**
     * @param int|null $journalIssueId
     */
    public function setJournalIssueId(?int $journalIssueId): void
    {
        $this->journalIssueId = $journalIssueId;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string|null $author
     */
    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    /**
     * @param int|null $pubYear
     */
    public function setPubYear(?int $pubYear): void
    {
        $this->pubYear = $pubYear;
    }

    /**
     * @return OnQueueMailMessage
     */
    public function getOnQueueMail(): OnQueueMailMessage
    {
        return new OnQueueMailMessage(
            $this->title,
            $this->author,
            $this->pubYear,
        );
    }
}
