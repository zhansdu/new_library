<?php

declare(strict_types=1);

namespace App\Services\DTO;

/**
 * Class ManagePermissionsDTO.
 */
final class ManagePermissionsDTO
{
    /** @var int[] */
    public array $modulesIds;

    /** @var int[] */
    public array $permissionsIds;

    /**
     * @param string[] $modulesIds
     * @param string[] $permissionsIds
     */
    public function __construct(array $modulesIds, array $permissionsIds)
    {
        $this->modulesIds = array_map('intval', $modulesIds);
        $this->permissionsIds = array_map('intval', $permissionsIds);
    }
}
