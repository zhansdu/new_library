<?php


use App\Database\BaseInsertPermissions;

class AddBooksHistoryStatusesPermission extends BaseInsertPermissions
{
    /**
     * @var array|string[][]
     */
    protected array $permissions = [
        [
            'method_name' => 'book_history-statuses',
            'display_name' => 'Получить список статусов для истории книг',
            'description' => 'Получить список статусов для истории книг',
            'module_name' => 'books_history',
        ],
    ];
}
