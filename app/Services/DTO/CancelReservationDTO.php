<?php

declare(strict_types=1);

namespace App\Services\DTO;

/**
 * Class CancelReservationDTO.
 */
final class CancelReservationDTO
{
    /**
     * @param int $materialId
     * @param string $userCid
     */
    public function __construct(
        public int $materialId,
        public string $userCid
    ) {}
}
