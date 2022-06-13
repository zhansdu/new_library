<?php

declare(strict_types=1);

namespace App\Database;

use Carbon\CarbonImmutable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

abstract class BaseInsertPermissions extends Migration
{
    /**
     * [
     *      'method_name'   => 'string',
     *      'display_name'  => 'string',
     *      'description'   => 'string',
     *      'module_name'   => 'string',
     * ]
     *
     * @var array $permissions
     */
    protected array $permissions = [];

    final public function up()
    {
        $this->getDatabaseBuilder()
            ->insert($this->getPermissions());
    }

    final public function down()
    {
        $this->getDatabaseBuilder()
            ->whereIn('id', $this->getPermissionsIds())
            ->delete();
    }

    /**
     * @return Builder
     */
    final protected function getDatabaseBuilder(): Builder
    {
        return DB::table('permissions');
    }

    /**
     * @return int[]
     */
    final protected function getPermissionsIds(): array
    {
        $permissionsModules = $this->getPermissionsNames();

        $permissions = $this->getDatabaseBuilder()
            ->select('id')
            ->whereIn('method_name', $permissionsModules)
            ->get();

        return $permissions
            ->pluck('id')
            ->toArray();
    }

    /**
     * @return string[]
     */
    final protected function getPermissionsNames(): array
    {
        $result = [];

        foreach ($this->permissions as $permission) {
            $result[] = $permission['method_name'];
        }

        return $result;
    }

    /**
     * @return array
     */
    final protected function getPermissions(): array
    {
        $result = [];

        foreach ($this->permissions as $permission) {
            $module = DB::table('modules')
                ->where('route_name', $permission['module_name'])
                ->select('route_name')
                ->first();

            if ($module === null) {
                continue;
            }

            $result[] = [
                'method_name' => $permission['method_name'],
                'display_name' => $permission['display_name'],
                'description' => $permission['description'] ?? null,
                'is_shown' => $permission['is_shown'] ?? true,
                'route_name' => $module->route_name,
            ];
        }

        return $result;
    }
}
