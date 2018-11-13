<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SpecificationToProduct
 *
 * @property int $id
 * @property int $product_id
 * @property int $specific_id
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SpecificationToProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SpecificationToProduct whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SpecificationToProduct whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SpecificationToProduct whereSpecificId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SpecificationToProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SpecificationToProduct whereValue($value)
 * @mixin \Eloquent
 * @property int $isset
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SpecificationToProduct whereIsset($value)
 */
class SpecificationToProduct extends Model
{
    protected $table = 'specification_to_product';

    public $timestamps = true;

    protected $fillable = [
        'product_id',
        'specific_id',
        'isset',
        'value'
    ];

    protected $guarded = [];

        
}