<?php

use App\Database\BaseInsertPermissions;

class AddCatalogingGenresPermission extends BaseInsertPermissions
{
    /**
     * @var array|string[][]
     */
    protected array $permissions = [
        [
            'method_name' => 'cataloging-genres',
            'display_name' => 'Получить список категории для материала',
            'description' => 'Получить список категории для материала',
            'module_name' => 'cataloging',
        ],
    ];
}
