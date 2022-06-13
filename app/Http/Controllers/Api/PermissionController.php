<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permissions\ManagePermissionsRequest;
use App\Models\Permission\Module;
use App\Models\Permission\Permission;
use App\Models\User\User;
use App\Services\Handlers\DeletePermissionsHandler;
use App\Services\Handlers\GivePermissionsHandler;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PermissionController.
 */
final class PermissionController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getModules(): JsonResponse
    {
        $modules = Module::all();

        return response()->json([
            'res' => $modules,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getPermissions(): JsonResponse
    {
        $permissions = Permission::all();

        return response()->json([
            'res' => $permissions,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUsersByModule(Request $request): JsonResponse
    {
        $request->validate([
            'add_options' => 'required|array',
            'add_options.0.key' => 'required|string|in:module_id',
            'add_options.0.value' => 'required|integer'
        ], $request->all());

        $moduleId = $request->input('add_options')[0]['value'];

        $module = Module::findOrFail($moduleId);

        $users = $module
            ->users()
            ->with(['student', 'employee'])
            ->paginate(10);

        return response()->json([
            'res' => $users,
        ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getUserModules(User $user): JsonResponse
    {
        $modules = $user->modules;

        return response()->json([
            'res' => $modules,
        ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getUserPermissions(User $user): JsonResponse
    {
        $permissions = $user->permissions;

        return response()->json([
            'res' => $permissions,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getTree(): JsonResponse
    {
        $modulesWithPermissions = Module::with('permissions')->get();

        return response()->json([
            'res' => $modulesWithPermissions,
        ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getUserTree(User $user): JsonResponse
    {
        $modulesWithPermissions = $user->modulesWithPermissions;

        return response()->json([
            'res' => $modulesWithPermissions,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getVisualization(): JsonResponse
    {
        $modulesWithPermissions = Module::with('visualization')->get();

        return response()->json([
            'res' => $modulesWithPermissions,
        ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getUserVisualization(User $user): JsonResponse
    {
        $modulesWithPermissions = $user->modulesWithPermissionsVisualization;

        return response()->json([
            'res' => $modulesWithPermissions,
        ]);
    }

    /**
     * @param ManagePermissionsRequest $request
     * @param User $user
     * @param GivePermissionsHandler $handler
     * @return JsonResponse
     */
    public function givePermissions(ManagePermissionsRequest $request, User $user, GivePermissionsHandler $handler): JsonResponse
    {
        $handler->handle($request->getDto(), $user);

        return response()->json([
            'res' => 'success'
        ]);
    }

    /**
     * @param ManagePermissionsRequest $request
     * @param User $user
     * @param DeletePermissionsHandler $handler
     * @return JsonResponse
     */
    public function deletePermissions(ManagePermissionsRequest $request, User $user, DeletePermissionsHandler $handler): JsonResponse
    {
        $handler->handle($request->getDto(), $user);

        return response()->json([
            'res' => 'success'
        ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function userPermissionsForVisualization(User $user): JsonResponse
    {
        $userPermissions = $user
            ->permissions()
            ->where('is_shown', true)
            ->get()
            ->pluck('id')
            ->toArray();

        $visualization = Module::with(['visualization' => function (HasMany $q) use ($userPermissions) {
            return $q->whereNotIn('permissions.id', $userPermissions);
        }])
            ->get();

        return response()->json([
            'res' => $visualization,
        ]);
    }
}
