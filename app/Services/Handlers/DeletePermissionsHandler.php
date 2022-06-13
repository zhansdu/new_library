<?php

declare(strict_types=1);

namespace App\Services\Handlers;

use App\Models\Permission\Module;
use App\Models\Permission\ModuleUser;
use App\Models\Permission\Permission;
use App\Models\Permission\PermissionUser;
use App\Models\User\User;
use App\Services\DTO\ManagePermissionsDTO;
use Illuminate\Support\Facades\DB;

/**
 * Class DeletePermissionsHandler.
 */
final class DeletePermissionsHandler
{
    /**
     * @param ManagePermissionsDTO $dto
     * @param User $user
     */
    public function handle(ManagePermissionsDTO $dto, User $user): void
    {
        DB::transaction(function () use ($dto, $user) {
            if (count($dto->modulesIds) > 0) {
                $this->deletePermissionsToModules($dto->modulesIds, $user->user_cid);
                $this->deleteModuleDefaultPermissions($dto->modulesIds, $user->user_cid);
            }

            if (count($dto->permissionsIds) > 0) {
                $this->deletePermissions($dto->permissionsIds, $user->user_cid);
            }
        });
    }

    /**
     * @param int[] $modulesIds
     * @param string $userCid
     */
    private function deletePermissionsToModules(array $modulesIds, string $userCid): void
    {
        ModuleUser::whereIn('module_id', $modulesIds)
            ->where('user_cid', $userCid)
            ->delete();
    }

    /**
     * @param int[] $permissionsIds
     * @param string $userCid
     */
    private function deletePermissions(array $permissionsIds, string $userCid): void
    {
        foreach ($permissionsIds as $permissionId) {
            $this->deletePermissionUser($permissionId, $userCid);
        }
    }

    /**
     * @param int $permissionId
     * @param string $userCid
     */
    private function deletePermissionUser(int $permissionId, string $userCid): void
    {
        PermissionUser::where('permission_id', $permissionId)
            ->where('user_cid', $userCid)
            ->delete();
    }

    /**
     * @param int[] $modulesIds
     * @param string $userCid
     */
    private function deleteModuleDefaultPermissions(array $modulesIds, string $userCid): void
    {
        foreach ($modulesIds as $moduleId) {
            /** @var Module $module */
            $module = Module::find($moduleId);

            $permissions = $module
                ->permissions()
                ->where('is_shown', false)
                ->get();

            $permissions->each(function (Permission $permission) use ($userCid) {
                $this->deletePermissionUser($permission->id, $userCid);
            });
        }
    }
}
