<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @property int $id
 * @property string $title
 * @property string $desc
 * @property string $images
 * @property string $sku Артикул
 * @property string $unit Ед.Изм.
 * @property int $price
 * @property int $instock На складе
 * @property string $hash
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereDesc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereImages($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereInstock($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereSku($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereUnit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    protected $table = 'products';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'desc',
        'images',
        'sku',
        'unit',
        'price',
        'instock',
        'hash'
    ];

    public static function rules()
    {
        return [
            'title' => 'required',
            'sku' => 'required',
            'hash' => 'required',
        ];
    }

    protected $guarded = [];

    public static function saveOrUpdate($attr = [])
    {
        if ( isset($attr['hash_old']) )
        {
            if ( $model = static::where(['hash' => $attr['hash_old']])->get()->first() )
            {
                $model->update([
                    'title' => $attr['title'],
                    'desc' => @$attr['desc'],
                    'images' => isset($attr['images']) ? serialize($attr['images']) : '',
                    'sku' => $attr['sku'],
                    'unit' => @$attr['unit'],
                    'price' => @$attr['price'],
                    'instock' => @$attr['instock'],
                    'hash' => $attr['hash'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                return $model->id;
            }
        }
        if ( $model = static::where(['hash' => $attr['hash']])->get()->first() )
        {
            $model->update([
                'title' => $attr['title'],
                'desc' => @$attr['desc'],
                'images' => isset($attr['images']) ? serialize($attr['images']) : '',
                'sku' => $attr['sku'],
                'unit' => @$attr['unit'],
                'price' => @$attr['price'],
                'instock' => @$attr['instock'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return $model->id;
        }
        else
        {
            $insert = static::insert([
                'title' => $attr['title'],
                'desc' => @$attr['desc'],
                'images' => isset($attr['images']) ? serialize($attr['images']) : '',
                'sku' => $attr['sku'],
                'unit' => @$attr['unit'],
                'price' => @$attr['price'],
                'instock' => @$attr['instock'],
                'hash' => $attr['hash'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            if ($insert)
            {
                return static::where(['hash' => $attr['hash']])->get()->first()->id;
            }

        }
    }
}