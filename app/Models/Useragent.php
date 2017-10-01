<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Useragent
 *
 * @property int $useragent_id
 * @property string $useragent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Useragent whereUseragent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Useragent whereUseragentId($value)
 * @mixin \Eloquent
 */
class Useragent extends Model
{
    protected $table = 'useragent';

    protected $primaryKey = 'useragent_id';

	public $timestamps = false;

    protected $fillable = [
        'useragent'
    ];

    protected $guarded = [];

    public static function getListArray ($list)
    {
        $list = str_replace("\r", "\n", $list);
        $list = str_replace("\n\n", "\n", $list);
        $array = explode("\n", $list);

        foreach ( $array as $k => $ar)
        {
            if ( empty( trim($ar) ) )
            {
                unset($array[$k]);
            }
        }

        return $array;
    }
}