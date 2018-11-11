<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoryToProduct
 *
 * @property int $id
 * @property int $product_id
 * @property int $category_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CategoryToProduct whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CategoryToProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CategoryToProduct whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CategoryToProduct whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CategoryToProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoryToProduct extends Model
{
    protected $table = 'category_to_product';

    public $timestamps = true;

    protected $fillable = [
        'product_id',
        'category_id'
    ];

    protected $guarded = [];

        
}