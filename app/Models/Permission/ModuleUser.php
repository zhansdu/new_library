<?php

declare(strict_types=1);

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ModuleUser.
 * @property int $id
 * @property int $module_id
 * @property string $user_cid
 */
final class ModuleUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'module_user';

    /**
     * @var string[]
     */
    protected $fillable = [
        'module_id',
        'user_cid',
    ];

    public $timestamps = false;
}
