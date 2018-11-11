<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Specification
 *
 * @property int $id
 * @property string $title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Specification whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Specification whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Specification whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Specification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Specification extends Model
{
    protected $table = 'specifications';

    public $timestamps = true;

    protected $fillable = [
        'title'
    ];

    protected $guarded = [];

        
}