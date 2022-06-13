<?php

declare(strict_types=1);

namespace App\Models\User;

use App\Models\Permission\Module;
use App\Models\Permission\ModuleUser;
use App\Models\Permission\Permission;
use App\Models\Permission\PermissionUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * Class User.
 * @property string $user_cid
 * @property string $stud_id
 * @property int $emp_id
 * @property string $name
 * @property string $surname
 * @property string $psw
 * @property int $attempt_count
 * @property Carbon|string $attempt_date
 * @property bool $is_active
 * @property int $last_login_info
 * @property-read Employee $employee
 * @property-read Student $student
 * @property-read Collection|Module[] $modules
 * @property-read Collection|Permission[] $permissions
 * @property-read Collection|Module[] $modulesWithPermissions
 * @property-read Collection|Module[] $modulesWithPermissionsVisualization
 */
final class User extends Model
{
    /**
     * @var string
     */
    protected $table = 'lib_user_cards';

    /**
     * @var string
     */
    protected $primaryKey = 'user_cid';

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_cid',
        'stud_id',
        'emp_id',
        'name',
        'surname',
        'psw',
        'attempt_count',
        'attempt_date',
        'is_active',
        'last_login_info',
    ];

    /**
     * @return HasManyThrough
     */
    public function permissions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Permission::class,
            PermissionUser::class,
            'user_cid',
            'id',
            'user_cid',
            'permission_id',
        );
    }

    /**
     * @return HasManyThrough
     */
    public function modules(): HasManyThrough
    {
        return $this->hasManyThrough(
            Module::class,
            ModuleUser::class,
            'user_cid',
            'id',
            'user_cid',
            'module_id',
        );
    }

    /**
     * @return BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'emp_id');
    }

    /**
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'stud_id', 'stud_id');
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        $baseModule = Module::getModuleByRouteName(Module::MODULE_ADMIN);

        return $this->hasPermissionToModule($baseModule->id);
    }

    /**
     * @param int $moduleId
     * @return bool
     */
    public function hasPermissionToModule(int $moduleId): bool
    {
        return ModuleUser::where('module_id', $moduleId)
            ->where('user_cid', $this->user_cid)
            ->exists();
    }

    /**
     * @param int $permissionId
     * @return bool
     */
    public function hasPermission(int $permissionId): bool
    {
        return PermissionUser::where('permission_id', $permissionId)
            ->where('user_cid', $this->user_cid)
            ->exists();
    }

    /**
     * @return HasManyThrough
     */
    public function modulesWithPermissions(): HasManyThrough
    {
        return $this->modules()->with(['permissions' => function (HasMany $permissions) {
            return $this->itsPermissions($permissions);
        }]);
    }

    /**
     * @return HasManyThrough
     */
    public function modulesWithPermissionsVisualization(): HasManyThrough
    {
        return $this->modules()->with(['permissions' => function (HasMany $permissions) {
            return $this->itsPermissions($permissions)
                ->where('is_shown', true);
        }]);
    }

    private function itsPermissions(HasMany $permissions): HasMany
    {
        return $permissions
            ->leftJoin(
                'permission_user',
                'permission_user.permission_id', '=', 'permissions.id'
            )
            ->where('permission_user.user_cid', $this->user_cid);
    }
}
