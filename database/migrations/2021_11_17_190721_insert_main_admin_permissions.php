<?php

use App\Database\BaseInsertPermissions;

class InsertMainAdminPermissions extends BaseInsertPermissions
{
    protected array $permissions = [
        [
            'method_name' => 'manage-modules',
            'display_name' => 'Получить список модулей',
            'description' => 'Получить список модулей',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'manage-permissions',
            'display_name' => 'Получить список методов',
            'description' => 'Получить список методов',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'manage-users-by_module',
            'display_name' => 'Получить список пользователей по модулю',
            'description' => 'Получить список пользователей по модулю',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'manage-users-modules',
            'display_name' => 'Получить список модулей, на которых пользователь имеет доступ',
            'description' => 'Получить список модулей, на которых пользователь имеет доступ',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'manage-users-permissions',
            'display_name' => 'Получить список методов, на которых пользователь имеет доступ',
            'description' => 'Получить список методов, на которых пользователь имеет доступ',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'manage-tree',
            'display_name' => 'Получить список модулей с привязанными методами',
            'description' => 'Получить список модулей с привязанными методами',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'manage-visualization',
            'display_name' => 'Получить список модулей с привязанными методами для визуализации',
            'description' => 'Получить список модулей с привязанными методами для визуализации',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'manage-users-tree',
            'display_name' => 'Получить список модулей с привязанными методами, на которых пользователь имеет доступ',
            'description' => 'Получить список модулей с привязанными методами, на которых пользователь имеет доступ',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'manage-users-visualization',
            'display_name' => 'Получить список модулей с привязанными методами, на которых пользователь имеет доступ для визуализации',
            'description' => 'Получить список модулей с привязанными методами, на которых пользователь имеет доступ для визуализации',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'manage-users-give_permissions',
            'display_name' => 'Дать доступы пользователю',
            'description' => 'Дать доступы пользователю',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'manage-users-delete_permissions',
            'display_name' => 'Удалить доступы пользователя',
            'description' => 'Удалить доступы пользователя',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'manage-users-visualization_permissions',
            'display_name' => 'Получить список методов для визуализации, на которых пользователь имеет доступ',
            'description' => 'Получить список методов для визуализации, на которых пользователь имеет доступ',
            'module_name' => 'main-admin',
        ],
        [
            'method_name' => 'log-into-admin',
            'display_name' => 'Доступ в админ панель',
            'description' => 'Доступ в админ панель',
            'module_name' => 'admin',
        ],
    ];
}
