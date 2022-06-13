<?php

use App\Database\BaseInsertModules;

class InsertMainAdminPagePermissionAsModule extends BaseInsertModules
{
    protected array $modules = [
        [
            'route_name'    => 'admin',
            'display_name'  => 'Базовый модуль: Доступ к странице админа',
            'description'   => 'Базовый модуль: Доступ к странице админа',
        ],
        [
            'route_name'    => 'main-admin',
            'display_name'  => 'Базовый модуль: Главный администратор',
            'description'   => 'Базовый модуль: Главный администратор',
        ],
    ];
}
