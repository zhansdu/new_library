<?php

declare(strict_types=1);

namespace App\Services\DTO;

use App\Exceptions\ReturnResponseException;
use App\Models\Media\MaterialTypeFactory;
use Carbon\CarbonImmutable;

/**
 * Class StayOnQueueDTO.
 */
final class StayOnQueueDTO
{
    public string $userCid;

    public ?int $bookId;

    public ?int $discId;

    public ?int $journalId;

    public CarbonImmutable $tillDate;

    /**
     * @param array $data
     * @return static
     * @throws ReturnResponseException
     */
    public static function fromArray(array $data): self
    {
        $self = new self();

        $self->userCid = $data['user_cid'];
        $self->tillDate = CarbonImmutable::parse($data['till_date']);

        $self->setId(
            (int) $data['id'],
            $data['material_type']
        );

        return $self;
    }

    /**
     * @param int $id
     * @param string $type
     * @throws ReturnResponseException
     */
    private function setId(int $id, string $type): void
    {
        $this->bookId = null;
        $this->discId = null;
        $this->journalId = null;

        if (in_array($type, MaterialTypeFactory::TYPES_BOOK)) {
            $this->bookId = $id;
            return;
        }

        if (in_array($type, MaterialTypeFactory::TYPES_JOURNAL)) {
            $this->journalId = $id;
            return;
        }

        if (in_array($type, MaterialTypeFactory::TYPES_DISC)) {
            $this->discId = $id;
        }

        throw new ReturnResponseException('Incorrect type', 422);
     }
}
