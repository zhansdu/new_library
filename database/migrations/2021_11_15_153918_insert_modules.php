<?php

use App\Database\BaseInsertModules;

/**
 * Class InsertModules.
 */
class InsertModules extends BaseInsertModules
{
    protected array $modules = [
        [
            'route_name' => 'batches',
            'display_name' => 'Доступ к разделу Партии',
            'description' => 'Доступ к разделу Партии',
            'parent_id' => null,
        ],
        [
            'route_name' => 'items',
            'display_name' => 'Доступ к разделу Экземпляры',
            'description' => 'Доступ к разделу Экземпляры',
            'parent_id' => null,
        ],
        [
            'route_name' => 'suppliers',
            'display_name' => 'Доступ к разделу Поставщики',
            'description' => 'Доступ к разделу Поставщики',
            'parent_id' => null,
        ],
        [
            'route_name' => 'publishers',
            'display_name' => 'Доступ к разделу Издатели',
            'description' => 'Доступ к разделу Издатели',
            'parent_id' => null,
        ],
        [
            'route_name' => 'attendance',
            'display_name' => 'Доступ к разделу Посещаемость',
            'description' => 'Доступ к разделу Посещаемость',
            'parent_id' => null,
        ],
        [
            'route_name' => 'mrbooks',
            'display_name' => 'Доступ к разделу Самые читаемые книги',
            'description' => 'Доступ к разделу Самые читаемые книги',
            'parent_id' => null,
        ],
        [
            'route_name' => 'books_history',
            'display_name' => 'Доступ к разделу История книг',
            'description' => 'Доступ к разделу История книг',
            'parent_id' => null,
        ],
        [
            'route_name' => 'inventory_books',
            'display_name' => 'Доступ к разделу Инвентарные книги',
            'description' => 'Доступ к разделу Инвентарные книги',
            'parent_id' => null,
        ],
        [
            'route_name' => 'ksu',
            'display_name' => 'Доступ к разделу КСУ',
            'description' => 'Доступ к разделу КСУ',
            'parent_id' => null,
        ],
        [
            'route_name' => 'report',
            'display_name' => 'Доступ к разделу Отчеты',
            'description' => 'Доступ к разделу Отчеты',
            'parent_id' => null,
        ],
        [
            'route_name' => 'service_desk',
            'display_name' => 'Доступ к разделу Служба обслуживания',
            'description' => 'Доступ к разделу Служба обслуживания',
            'parent_id' => null,
        ],
        [
            'route_name' => 'cataloging',
            'display_name' => 'Доступ к разделу Каталогизация',
            'description' => 'Доступ к разделу Каталогизация',
            'parent_id' => null,
        ],
        [
            'route_name' => 'website',
            'display_name' => 'Доступ к разделу Настройка конфигурации админки',
            'description' => 'Доступ к разделу Настройка конфигурации админки',
            'parent_id' => null,
        ],
    ];
}
