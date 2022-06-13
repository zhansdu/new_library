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
 * Class GivePermissionsHandler.
 */
final class GivePermissionsHandler
{
    /**
     * @param ManagePermissionsDTO $dto
     * @param User $user
     */
    public function handle(ManagePermissionsDTO $dto, User $user): void
    {
        DB::transaction(function() use ($dto, $user) {
            if (count($dto->modulesIds) > 0) {
                $this->givePermissionsToModules($dto->modulesIds, $user->user_cid);
                $this->giveDefaultModulePermissions($dto->modulesIds, $user->user_cid);
            }

            if (count($dto->permissionsIds) > 0) {
                $this->givePermissions($dto->permissionsIds, $user->user_cid);
            }
        });
    }

    /**
     * @param int[] $modulesIds
     * @param string $userCid
     */
    private function givePermissionsToModules(array $modulesIds, string $userCid): void
    {
        foreach ($modulesIds as $moduleId) {
            if (!ModuleUser::where('module_id', $moduleId)->where('user_cid', $userCid)->exists()) {
                ModuleUser::create([
                    'module_id' => $moduleId,
                    'user_cid' => $userCid,
                ]);
            }
        }
    }

    /**
     * @param array $permissionsIds
     * @param string $userCid
     */
    private function givePermissions(array $permissionsIds, string $userCid): void
    {
        foreach ($permissionsIds as $permissionId) {
            $this->createPermissionUser($permissionId, $userCid);
        }
    }

    /**
     * @param int $permissionId
     * @param string $userCid
     */
    private function createPermissionUser(int $permissionId, string $userCid): void
    {
        if (!PermissionUser::where('permission_id', $permissionId)->where('user_cid', $userCid)->exists()) {
            PermissionUser::create([
                'permission_id' => $permissionId,
                'user_cid' => $userCid,
            ]);
        }
    }

    /**
     * @param int[] $modulesIds
     * @param string $userCid
     */
    private function giveDefaultModulePermissions(array $modulesIds, string $userCid): void
    {
        foreach ($modulesIds as $moduleId) {
            /** @var Module $module */
            $module = Module::find($moduleId);

            $permissions = $module->permissions()
                ->where('is_shown', false);

            $permissions->each(function (Permission $permission) use ($userCid) {
                $this->createPermissionUser($permission->id, $userCid);
            });
        }
    }
}
