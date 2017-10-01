<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ActivityLog
 *
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property string $ip_address
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ActivityLog whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ActivityLog whereIpAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ActivityLog whereText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ActivityLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ActivityLog whereUserId($value)
 * @mixin \Eloquent
 */
class ActivityLog extends Model
{
    protected $table = 'activity_log';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'text',
        'ip_address'
    ];

    protected $guarded = [];

        
}