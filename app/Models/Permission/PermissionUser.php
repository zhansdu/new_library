<?php

declare(strict_types=1);

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PermissionUser.
 * @property int $id
 * @property int $permission_id
 * @property string $user_cid
 */
final class PermissionUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'permission_user';

    /**
     * @var string[]
     */
    protected $fillable = [
        'permission_id',
        'user_cid',
    ];

    public $timestamps = false;
}
