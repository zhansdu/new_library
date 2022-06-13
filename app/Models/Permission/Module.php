<?php

declare(strict_types=1);

namespace App\Models\Permission;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class Module.
 * @property int $id
 * @property string $route_name
 * @property string $display_name
 * @property string $description
 * @property int $parent_id
 * @property-read Collection|User[] $users
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|Permission[] $visualization
 *
 * @method static Module getModuleByRouteName(string $routeName)
 * @method Builder WithUserPermissions(int $employeeId)
 */
final class Module extends Model
{
    public const MODULE_BATCHES = 'batches';
    public const MODULE_ITEMS = 'items';
    public const MODULE_SUPPLIERS = 'suppliers';
    public const MODULE_PUBLISHERS = 'publishers';
    public const MODULE_ATTENDANCE = 'attendance';
    public const MODULE_MRBOOKS = 'mrbooks';
    public const MODULE_BOOKS_HISTORY = 'books_history';
    public const MODULE_INVENTORY_BOOKS = 'inventory_books';
    public const MODULE_KSU = 'ksu';
    public const MODULE_REPORT = 'report';
    public const MODULE_SERVICE_DESK = 'service_desk';
    public const MODULE_CATALOGING = 'cataloging';
    public const MODULE_WEBSITE = 'website';
    public const MODULE_ADMIN = 'admin';
    public const MODULE_MAIN_ADMIN = 'main-admin';

    /**
     * @var string[]
     */
    protected $fillable = [
        'route_name',
        'display_name',
        'description',
        'parent_id',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return HasManyThrough
     */
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            ModuleUser::class,
            'module_id',
            'user_cid',
            'id',
            'user_cid',
        );
    }

    /**
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'route_name', 'route_name');
    }

    /**
     * @return HasMany
     */
    public function visualization(): HasMany
    {
        return $this->permissions()
            ->where('is_shown', true);
    }

    /**
     * @param Builder $builder
     * @param string $routeName
     * @return Builder|Model
     */
    public function scopeGetModuleByRouteName(Builder $builder, string $routeName): Builder|Model
    {
        return $builder->where('route_name', $routeName)
            ->firstOrFail();
    }
}
