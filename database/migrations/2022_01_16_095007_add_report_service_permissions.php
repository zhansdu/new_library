<?php

use App\Database\BaseInsertPermissions;

class AddReportServicePermissions extends BaseInsertPermissions
{
    /**
     * @var array|string[][]
     */
    protected array $permissions = [
        [
            'method_name' => 'book_history-users',
            'display_name' => 'Получить список пользователей, которые брали инвентарь (книгу)',
            'description' => 'Получить список пользователей, которые брали инвентарь (книгу)',
            'module_name' => 'books_history',
        ],
        [
            'method_name' => 'service-show_user-by_cid',
            'display_name' => 'Получить информацию о пользователе',
            'description' => 'Получить информацию о пользователе',
            'module_name' => 'service_desk',
        ],
    ];
}
