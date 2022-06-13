<?php

declare(strict_types=1);

namespace App\Services\Entities;

/**
 * Class OnQueueMailMessage.
 */
final class OnQueueMailMessage
{
    /**
     * @param string $title
     * @param string $author
     * @param int $pubYear
     */
    public function __construct(
        public string $title,
        public string $author,
        public int $pubYear
    ) {}
}
