<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Attribute
 *
 * @property int $id
 * @property int $product_id
 * @property string $title
 * @property int $price
 * @property string $instock
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereInstock($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Attribute extends Model
{
    protected $table = 'attributes';

    public $timestamps = true;

    protected $fillable = [
        'product_id',
        'title',
        'price',
        'instock'
    ];

    public static function rules()
    {
        return [
            'title' => 'required',
        ];
    }

    protected $guarded = [];

        
}