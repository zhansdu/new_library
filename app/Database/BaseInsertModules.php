<?php

declare(strict_types=1);

namespace App\Database;

use Carbon\CarbonImmutable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class BaseInsertModules.
 */
abstract class BaseInsertModules extends Migration
{
    /**
     * [
     *      'route_name'    => 'string',
     *      'display_name'  => 'string',
     *      'description'   => 'string',
     *      'parent_id'     => 'integer',
     * ]
     *
     * @var array $modules
     */
    protected array $modules = [];

    final public function up()
    {
        $this->getDatabaseBuilder()
            ->insert($this->getModules());
    }

    final public function down()
    {
        $this->getDatabaseBuilder()
            ->whereIn('id', $this->getModulesIds())
            ->delete();
    }

    /**
     * @return Builder
     */
    final protected function getDatabaseBuilder(): Builder
    {
        return DB::table('modules');
    }

    /**
     * @return int[]
     */
    final protected function getModulesIds(): array
    {
        $modulesNames = $this->getModulesNames();

        $modules = $this->getDatabaseBuilder()
            ->select('id')
            ->whereIn('route_name', $modulesNames)
            ->get();

        return $modules
            ->pluck('id')
            ->toArray();
    }

    /**
     * @return string[]
     */
    final protected function getModulesNames(): array
    {
        $result = [];

        foreach ($this->modules as $module) {
            $result[] = $module['route_name'];
        }

        return $result;
    }

    /**
     * @return array
     */
    final protected function getModules(): array
    {
        $result = [];

        foreach ($this->modules as $module) {
            $result[] = [
                'route_name' => $module['route_name'],
                'display_name' => $module['display_name'],
                'description' => $module['description'] ?? null,
                'parent_id' => $module['parent_id'] ?? null,
            ];
        }

        return $result;
    }
}
