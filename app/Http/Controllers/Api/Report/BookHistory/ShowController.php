<?php

namespace App\Http\Controllers\Api\Report\BookHistory;

use App\Common\Fields\Report\BookHistoryFields;
use App\Common\Helpers\Show\FilterFields;
use App\Common\Helpers\Show\SearchFields;
use App\Common\Helpers\Show\SortFields;
use App\Http\Controllers\Controller;
use App\Services\Handlers\GetBookUserHistoryHandler;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{
    public function searchFields(): JsonResponse
    {
        return response()->json([
            'res' => SearchFields::searchFields(new BookHistoryFields())
        ]);
    }

    public function sortFields(): JsonResponse
    {
        return response()->json([
            'res' => SortFields::sortFields(new BookHistoryFields())
        ]);
    }

    public function filterFields(): JsonResponse
    {
        return response()->json([
            'res' => FilterFields::filterFields(new BookHistoryFields())
        ]);
    }

    /**
     * @param int $inventoryId
     * @param GetBookUserHistoryHandler $handler
     * @return JsonResponse
     */
    public function getUserHistory(int $inventoryId, GetBookUserHistoryHandler $handler): JsonResponse
    {
        $userHistory = $handler->handle($inventoryId);

        return response()->json([
            'res' => $userHistory
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getStatuses(): JsonResponse
    {
        return response()->json([
            'res' => [
                'returned', 'overdue', 'issued'
            ]
        ]);
    }
}
