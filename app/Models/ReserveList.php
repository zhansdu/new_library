<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Acquisition\Item\Item;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ReserveList.
 * @property int $id
 * @property int $lib_reserve_id
 * @property string $user_cid
 * @property int $book_id
 * @property int $disc_id
 * @property int $j_issue_id
 * @property CarbonImmutable|string $action_date
 * @property CarbonImmutable|string $end_date
 * @property int $status
 * @property CarbonImmutable|string $email_send_date
 */
final class ReserveList extends Model
{
    /**
     * @var string
     */
    protected $table = 'lib_reserve_list';

    /**
     * @var string
     */
    protected $primaryKey = 'lib_reserve_id';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_cid',
        'book_id',
        'j_issue_id',
        'disc_id',
        'action_date',
        'end_date',
        'status',
        'email_send_date',
    ];

    protected $appends = [
        'id'
    ];

    /**
     * @return int
     */
    public function getIdAttribute(): int
    {
        return (int) $this->lib_reserve_id;
    }
}
