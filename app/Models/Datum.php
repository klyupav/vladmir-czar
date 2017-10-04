<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Datum
 *
 * @property int $id
 * @property string $donor_class_name
 * @property string $name
 * @property string $image
 * @property string $desc
 * @property string $hash
 * @property string $source
 * @property string $category
 * @property bool $parseit
 * @property bool $available
 * @property int $version
 * @property string $serialize
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereAvailable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereDesc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereDonorClassName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereHash($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereParseit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereSerialize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereSource($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Datum whereVersion($value)
 * @mixin \Eloquent
 */
class Datum extends Model
{
    protected $table = 'data';

    public $timestamps = true;

    protected $fillable = [
        'donor_class_name',
        'name',
        'image',
        'desc',
        'hash',
        'source',
        'category',
        'parseit',
        'version',
        'available'
    ];

    public static function rules()
    {
        return [
            'donor_class_name' => 'required',
            'name' => 'required',
            'hash' => 'required',
            'source' => 'required',
            'serialize' => 'required',
        ];
    }

    protected $guarded = [];

    public function countDatumByDonor()
    {
        return static::where(['donor_class_name' => $this->donor_class_name])
            ->where(['available' => 1])->get(['id'])->count();
    }

    public function countDatumParsingByDonor()
    {
        return static::where(['donor_class_name' => $this->donor_class_name])
            ->where(['available' => 1])
            ->where(['parseit' => 1])
            ->get(['id'])->count();
    }

    public static function saveOrUpdate($attr = [])
    {
        if ( isset($attr['hash_old']) )
        {
            if ( $model = static::where(['hash' => $attr['hash_old']])->get()->first() )
            {
                $model->update([
                    'name' => $attr['name'],
                    'hash' => $attr['hash'],
                    'category' => @$attr['category'],
                    'serialize' => serialize($attr['serialize']),
                    'image' => empty(@$attr['image']) ? $model->image : $attr['image'],
                    'desc' => empty(@$attr['desc']) ? $model->desc : $attr['desc'],
                    'available' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        if ( $model = static::where(['hash' => $attr['hash']])->get()->first() )
        {
            $model->update([
                'name' => $attr['name'],
                'hash' => $attr['hash'],
                'category' => @$attr['category'],
                'serialize' => serialize($attr['serialize']),
                'image' => empty(@$attr['image']) ? $model->image : $attr['image'],
                'desc' => empty(@$attr['desc']) ? $model->desc : $attr['desc'],
                'available' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return 'update';
        }
        else
        {
            static::insert([
                'donor_class_name' => $attr['donor_class_name'],
                'name' => $attr['name'],
                'image' => empty(@$attr['image']) ? null : $attr['image'],
                'desc' => empty(@$attr['desc']) ? null : $attr['desc'],
                'hash' => $attr['hash'],
                'source' => $attr['source'],
                'category' => @$attr['category'],
                'serialize' => serialize($attr['serialize']),
                'created_at' => date('Y-m-d H:i:s'),
                'version' => $attr['version']
            ]);
            return 'insert';
        }
    }
}