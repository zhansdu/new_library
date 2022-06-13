<?php


namespace App\Common\Fields\Report;


use App\Common\Constants\SearchFieldConstants;
use App\Common\Interfaces\Fields\FieldInterface;

class BookHistoryFields extends FieldInterface
{
    protected static array $addSearchFields = [
        ['key' => 'barcode', 'title' => 'Barcode', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'id', 'title' => 'Inventory No', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'author', 'title' => 'Author', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'title', 'title' => 'Barcode', 'method' => SearchFieldConstants::LIKE],
        ['key' => 'status', 'title' => 'Status', 'method' => SearchFieldConstants::EQUALS],
        ['key' => 'borrow_date', 'title' => 'Borrow date', 'method' => SearchFieldConstants::RANGE_DATE],
        ['key' => 'due_date', 'title' => 'Borrow date', 'method' => SearchFieldConstants::RANGE_DATE],
        ['key' => 'delivery_date', 'title' => 'Borrow date', 'method' => SearchFieldConstants::RANGE_DATE],
    ];
}
