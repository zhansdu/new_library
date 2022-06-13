<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'Api\Auth\LoginController')->name('login');
Route::post('cgi-bin/check.cgi', 'Api\Auth\ProxyAuthController')->name('cgi-login');

Route::middleware(['auth:api-student,api-employee'])->group(function () {
    Route::post('logout', 'Api\Auth\LogoutController')->name('logout');
    Route::get('user', 'Api\Auth\UserController')->name('user');
    Route::get('user/my-books', 'Api\Service\ShowController@userInfo')->name('my-books');
    Route::post('media/stay_on_queue', [\App\Http\Controllers\Api\Service\ActionsController::class, 'stayOnQueue'])->name('media.stay_on_queue');
    Route::get('user/reserve_list', [\App\Http\Controllers\Api\Service\ShowController::class, 'getUserReserveList'])->name('user.reserve_list');
    Route::post('media/cancel_reservation', [\App\Http\Controllers\Api\Service\ActionsController::class, 'cancelReservation'])->name('media.cancel_reservation');
    Route::post('media/renew', [\App\Http\Controllers\Api\Service\ActionsController::class, 'renew'])->name('service-media_renew');

    // Admin routes
    Route::middleware(['api-admin'])->group(function () {
//    Route::group([], function () {
        // Acquisition routes

        // Batch routes
        Route::group(['prefix' => 'batch'], function () {
            Route::get('index', 'Api\Acquisition\Batch\ShowController@index')->name('batches-index');
            Route::get('show/{id}', 'Api\Acquisition\Batch\ShowController@show')->name('batches-show');
            Route::get('last-created', 'Api\Acquisition\Batch\ShowController@lastCreated')->name('batches-last_created');
            Route::get('sort-fields', 'Api\Acquisition\Batch\ShowController@sortFields')->name('batches-sort_fields');
            Route::get('search-fields', 'Api\Acquisition\Batch\ShowController@searchFields')->name('batches-search_fields');
            Route::get('filter-fields', 'Api\Acquisition\Batch\ShowController@filterFields')->name('batches-filter_fields');

            Route::post('create', 'Api\Acquisition\Batch\ManageController@create')->name('batches-create');
            Route::put('update', 'Api\Acquisition\Batch\ManageController@update')->name('batches-update');
            Route::delete('delete/{id}', 'Api\Acquisition\Batch\ManageController@delete')->name('batches-delete');

            Route::post('search', 'Api\Acquisition\Batch\SearchController@search')->name('batches-search');

            Route::get('numbers', 'Api\Acquisition\Batch\AdditionalController@numbers')->name('batches-numbers');
            Route::get('status/{id}', 'Api\Acquisition\Batch\AdditionalController@status')->name('batches-status');
            Route::get('statuses', 'Api\Acquisition\Batch\AdditionalController@statuses')->name('batches-statuses');
        });

        // Supplier routes
        Route::group(['prefix' => 'supplier'], function () {
            Route::get('index', 'Api\Acquisition\Supplier\ShowController@index')->name('suppliers-index');
            Route::get('show/{id}', 'Api\Acquisition\Supplier\ShowController@show')->name('suppliers-show');
            Route::get('last-created', 'Api\Acquisition\Supplier\ShowController@lastCreated')->name('suppliers-last_created');
            Route::get('sort-fields', 'Api\Acquisition\Supplier\ShowController@sortFields')->name('suppliers-sort_fields');
            Route::get('search-fields', 'Api\Acquisition\Supplier\ShowController@searchFields')->name('suppliers-search_fields');
            Route::get('filter-fields', 'Api\Acquisition\Supplier\ShowController@filterFields')->name('suppliers-filter_fields');

            Route::post('create', 'Api\Acquisition\Supplier\ManageController@create')->name('suppliers-create');
            Route::put('update', 'Api\Acquisition\Supplier\ManageController@update')->name('suppliers-update');
            Route::delete('delete/{id}', 'Api\Acquisition\Supplier\ManageController@delete')->name('suppliers-delete');

            Route::post('search', 'Api\Acquisition\Supplier\SearchController@search')->name('suppliers-search');
            Route::post('autocomplete', 'Api\Acquisition\Supplier\SearchController@autocomplete')->name('suppliers-autocomplete');

            Route::get('names', 'Api\Acquisition\Supplier\AdditionalController@names')->name('suppliers-names');
            Route::get('types', 'Api\Acquisition\Supplier\AdditionalController@types')->name('suppliers-types');
        });

        // Publisher routes
        Route::group(['prefix' => 'publisher'], function () {
            Route::get('index', 'Api\Acquisition\Publisher\ShowController@index')->name('publishers-index');
            Route::get('show/{id}', 'Api\Acquisition\Publisher\ShowController@show')->name('publishers-show');
            Route::get('last-created', 'Api\Acquisition\Publisher\ShowController@lastCreated')->name('publishers-last_created');
            Route::get('sort-fields', 'Api\Acquisition\Publisher\ShowController@sortFields')->name('publishers-sort_fields');
            Route::get('search-fields', 'Api\Acquisition\Publisher\ShowController@searchFields')->name('publishers-search_fields');
            Route::get('filter-fields', 'Api\Acquisition\Publisher\ShowController@filterFields')->name('publishers-filter_fields');

            Route::post('create', 'Api\Acquisition\Publisher\ManageController@create')->name('publishers-create');
            Route::put('update', 'Api\Acquisition\Publisher\ManageController@update')->name('publishers-update');
            Route::delete('delete/{id}', 'Api\Acquisition\Publisher\ManageController@delete')->name('publishers-delete');

            Route::post('search', 'Api\Acquisition\Publisher\SearchController@search')->name('publishers-search');
            Route::post('autocomplete', 'Api\Acquisition\Publisher\SearchController@autocomplete')->name('publishers-autocomplete');

            Route::get('names', 'Api\Acquisition\Publisher\AdditionalController@names')->name('publishers-names');
        });

        // Item routes
        Route::group(['prefix' => 'item'], function () {
            Route::get('index', 'Api\Acquisition\Item\ShowController@index')->name('items-index');
            Route::get('show/{id}', 'Api\Acquisition\Item\ShowController@show')->name('items-show');
            Route::get('last-created', 'Api\Acquisition\Item\ShowController@lastCreated')->name('items-last_created');
            Route::get('sort-fields', 'Api\Acquisition\Item\ShowController@sortFields')->name('items-sort_fields');
            Route::get('search-fields', 'Api\Acquisition\Item\ShowController@searchFields')->name('items-search_fields');
            Route::get('filter-fields', 'Api\Acquisition\Item\ShowController@filterFields')->name('items-filter_fields');

            Route::post('create', 'Api\Acquisition\Item\ManageController@create')->name('items-create');
            Route::put('update', 'Api\Acquisition\Item\ManageController@update')->name('items-update');
            Route::delete('delete/{id}', 'Api\Acquisition\Item\ManageController@delete')->name('items-delete');
            Route::post('recreate', 'Api\Acquisition\Item\ManageController@recreate')->name('items-recreate');

            Route::post('search', 'Api\Acquisition\Item\SearchController@search')->name('items-search');
            Route::get('autocomplete', 'Api\Acquisition\Item\SearchController@autocomplete')->name('items-autocomplete');

            Route::get('create-data', 'Api\Acquisition\Item\AdditionalController@createData')->name('items-create_data');
            Route::get('by/batch/{batch_id}', 'Api\Acquisition\Item\AdditionalController@byBatchId')->name('items-by_batch');
            Route::get('specialities', 'Api\Acquisition\Item\AdditionalController@specialities')->name('items-specialities');
        });

        // Reports

        // Inventory books report
        Route::group(['prefix' => 'inventory-books'], function () {
            Route::get('sort-fields', 'Api\Report\InventoryBooks\ShowController@sortFields')->name('inventory_books-sort_fields');
            Route::get('search-fields', 'Api\Report\InventoryBooks\ShowController@searchFields')->name('inventory_books-search_fields');
            Route::get('filter-fields', 'Api\Report\InventoryBooks\ShowController@filterFields')->name('inventory_books-filter_fields');

            Route::post('search', 'Api\Report\InventoryBooks\SearchController')->name('inventory_books-search');

            Route::post('export', 'Api\Report\InventoryBooks\ExportController')->name('inventory_books-export');
            Route::post('print', 'Api\Report\InventoryBooks\PrintController')->name('inventory_books-print');
        });

        Route::group(['prefix' => 'book-history'], function () {
            Route::get('sort-fields', 'Api\Report\BookHistory\ShowController@sortFields')->name('book_history-sort_fields');
            Route::get('search-fields', 'Api\Report\BookHistory\ShowController@searchFields')->name('book_history-search_fields');
            Route::get('filter-fields', 'Api\Report\BookHistory\ShowController@filterFields')->name('book_history-filter_fields');

            Route::post('search', 'Api\Report\BookHistory\SearchController')->name('book_history-search');

            Route::post('export', 'Api\Report\BookHistory\ExportController')->name('book_history-export');

            Route::get('users/{inventory_id}', [\App\Http\Controllers\Api\Report\BookHistory\ShowController::class, 'getUserHistory'])->name('book_history-users');
            Route::get('statuses', [\App\Http\Controllers\Api\Report\BookHistory\ShowController::class, 'getStatuses'])->name('book_history-statuses');
        });

        Route::group(['prefix' => 'most-read'], function () {
            Route::get('sort-fields', 'Api\Report\MostReadBooks\ShowController@sortFields')->name('most_read-sort_fields');
            Route::get('search-fields', 'Api\Report\MostReadBooks\ShowController@searchFields')->name('most_read-search_fields');
            Route::get('filter-fields', 'Api\Report\MostReadBooks\ShowController@filterFields')->name('most_read-filter_fields');

            Route::post('search', 'Api\Report\MostReadBooks\SearchController')->name('most_read-search');

            Route::post('export', 'Api\Report\MostReadBooks\ExportController')->name('most_read-export');
        });

        Route::group(['prefix' => 'attendance'], function () {
            Route::get('virtual', 'Api\Report\Attendance\AttendanceController@getVirtualAttendance')->name('attendance-virtual');
            Route::get('departments', 'Api\Report\Attendance\AttendanceController@getDepartments')->name('attendance-departments');
        });

        // Items module
        Route::group(['prefix' => 'barcode'], function () {
            Route::get('sort-fields', 'Api\Report\Barcode\ShowController@sortFields')->name('barcode-sort_fields');
            Route::get('search-fields', 'Api\Report\Barcode\ShowController@searchFields')->name('barcode-search_fields');
            Route::get('filter-fields', 'Api\Report\Barcode\ShowController@filterFields')->name('barcode-filter_fields');

            Route::post('search', 'Api\Report\Barcode\SearchController')->name('barcode-search');

            Route::post('print', 'Api\Report\Barcode\PrintController')->name('barcode-print');
            Route::get('init/{id}', 'Api\Report\Barcode\InitializeController')->name('barcode-init');
        });

        Route::group(['prefix' => 'service'], function () {
            //

            Route::get('sort-fields', 'Api\Service\ShowController@sortFields')->name('service-sort_fields');
            Route::get('search-fields', 'Api\Service\ShowController@searchFields')->name('service-search_fields');
            Route::get('filter-fields', 'Api\Service\ShowController@filterFields')->name('service-filter_fields');
            Route::get('user/types', 'Api\Service\ShowController@types')->name('service-user_types');

            Route::get('user/{user_cid}', [\App\Http\Controllers\Api\Service\ShowController::class, 'getUser'])->name('service-show_user-by_cid');
            Route::get('user/{type}/{id}', 'Api\Service\ShowController@show')->name('service-show_user');

            Route::post('user/{type}/search', 'Api\Service\SearchController@search')->name('service-user_search');
            Route::get('/media/search', 'Api\Service\SearchController@searchMedia')->name('service-media_search');
            Route::get('/media/search/by-inventory', 'Api\Service\SearchController@getMediaWithStatusesByInventory')->name('service-media_search-by_inventory');

            Route::post('/media/back', 'Api\Service\ActionsController@backMaterial')->name('service-media_back');
            Route::post('/media/give', 'Api\Service\ActionsController@giveMaterial')->name('service-media_give');
        });

        Route::group(['prefix' => 'cataloging'], function () {
            Route::post('material/search', 'Api\Cataloging\SearchController')->name('cataloging-search');
            Route::get('material/search-fields', 'Api\Cataloging\ShowController@searchFields')->name('cataloging-search_fields');
            Route::get('material/genres', [\App\Http\Controllers\Api\Cataloging\ShowController::class, 'getGenres'])->name('cataloging-genres');
            Route::get('material/types', 'Api\Cataloging\ShowController@getTypes')->name('cataloging-types');
            Route::get('material/{type}/{id}', 'Api\Cataloging\ShowController@getMaterialById')->name('cataloging-show');
            Route::get('material/export/{type}/{id}', 'Api\Cataloging\ShowController@exportXml')->name('cataloging-export');
            Route::get('material/print/{type}/{id}', 'Api\Cataloging\PrintController')->name('cataloging-print');
            Route::post('material/{type}/{id}/edit', 'Api\Cataloging\EditMaterialController')->name('cataloging-edit');

            Route::get('authority', 'Api\Cataloging\AuthorityDataController')->name('cataloging-authority');
            Route::post('material/{type}/{id}/complete', [\App\Http\Controllers\Api\Cataloging\ShowController::class, 'completeCataloging'])->name('cataloging-complete');
            Route::get('material/{type}/{id}/history', [\App\Http\Controllers\Api\Cataloging\ShowController::class, 'madeByHistory'])->name('cataloging-history');
        });

        Route::prefix('ksu_report')->group(function () {
            Route::get('get', [\App\Http\Controllers\Api\Report\KsuReportController::class, 'getReport'])->name('ksu-get');
            Route::get('export', [\App\Http\Controllers\Api\Report\KsuReportController::class, 'export'])->name('ksu-export');
        });

        Route::prefix('stat_report')->group(function () {
            Route::get('get', [\App\Http\Controllers\Api\Report\StatReportController::class, 'getReport'])->name('stat-get');
            Route::get('export', [\App\Http\Controllers\Api\Report\StatReportController::class, 'export'])->name('stat-export');
        });

        Route::get('admin/configuration', [\App\Http\Controllers\Api\AdminController::class, 'getAdminConfigurationFile'])
            ->withoutMiddleware(['auth:api-student,api-employee', 'api-admin'])->name('admin_configuration-get');
        Route::post('admin/configuration', [\App\Http\Controllers\Api\AdminController::class, 'storeAdminConfigurationFile'])->name('admin_configuration-edit');

        Route::prefix('manage')->group(function () {
            Route::get('modules', [\App\Http\Controllers\Api\PermissionController::class, 'getModules'])->name('manage-modules');
            Route::get('permissions', [\App\Http\Controllers\Api\PermissionController::class, 'getPermissions'])->name('manage-permissions');
            Route::post('users/by_module/search', [\App\Http\Controllers\Api\PermissionController::class, 'getUsersByModule'])->name('manage-users-by_module');
            Route::get('users/{user}/modules', [\App\Http\Controllers\Api\PermissionController::class, 'getUserModules'])->name('manage-users-modules');
            Route::get('users/{user}/permissions', [\App\Http\Controllers\Api\PermissionController::class, 'getUserPermissions'])->name('manage-users-permissions');
            Route::get('tree', [\App\Http\Controllers\Api\PermissionController::class, 'getTree'])->name('manage-tree');
            Route::get('visualization', [\App\Http\Controllers\Api\PermissionController::class, 'getVisualization'])->name('manage-visualization');
            Route::get('users/{user}/tree', [\App\Http\Controllers\Api\PermissionController::class, 'getUserTree'])->name('manage-users-tree');
            Route::get('users/{user}/visualization', [\App\Http\Controllers\Api\PermissionController::class, 'getUserVisualization'])->name('manage-users-visualization');
            Route::post('users/{user}/give_permissions', [\App\Http\Controllers\Api\PermissionController::class, 'givePermissions'])->name('manage-users-give_permissions');
            Route::post('users/{user}/delete_permissions', [\App\Http\Controllers\Api\PermissionController::class, 'deletePermissions'])->name('manage-users-delete_permissions');
            Route::get('users/{user}/visualization/permissions', [\App\Http\Controllers\Api\PermissionController::class, 'userPermissionsForVisualization'])->name('manage-users-visualization_permissions');
        });
    });
});

// Media routes
Route::group(['prefix' => 'media'], function () {
    Route::get('autocomplete', 'Api\Media\AutocompleteController@autocomplete');
    Route::post('search', 'Api\Media\SearchController@search');

    Route::get('show/{id}', 'Api\Media\ShowController@show');
    Route::post('save-excel', 'Api\Media\ExportController@export');
    Route::get('search-fields', 'Api\Media\ShowController@searchFields');
    Route::get('sort-fields', 'Api\Media\ShowController@sortFields');
    Route::get('filter-fields', 'Api\Media\ShowController@filterFields');
});
