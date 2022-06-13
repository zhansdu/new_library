<?php

declare(strict_types=1);

namespace App\Models\Permission;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class Permission.
 * @property int $id
 * @property string $method_name
 * @property string $display_name
 * @property string $description
 * @property boolean $is_shown
 * @property int $module_id
 * @property-read Module $module
 * @property-read Collection|User[] $users
 */
final class Permission extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'method_name',
        'display_name',
        'description',
        'is_shown',
        'route_name',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'route_name', 'route_name');
    }

    /**
     * @return HasManyThrough
     */
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            PermissionUser::class,
            'permission_id',
            'user_cid',
            'id',
            'user_cid',
        );
    }
}
