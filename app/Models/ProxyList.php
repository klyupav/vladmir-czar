<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProxyList
 *
 * @property int $id
 * @property string $proxy
 * @property int $used
 * @property int $fails
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Donor whereProxy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Donor whereUsed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Donor whereFails($value)
 * @mixin \Eloquent
 * @property int $useragent_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProxyList whereUseragentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProxyList whereId($value)
 */
class ProxyList extends Model
{
    protected $table = 'proxy_list';

    public $timestamps = false;

    protected $fillable = [
        'proxy',
        'used',
        'fails',
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

    public static function getRandomProxy()
    {
        $list = static::getListArray(static::get()->first()->proxy);
        return $list[array_rand($list)];
    }

    public static function getNextProxy()
    {
        if ( ! static::emptyList() )
        {
            //            where(['used' => 0])
//            $rows = static::where('fails', '<', 5)->get()->all();
            $rows = static::where('fails', '<', 3)->get()->all();
            srand((double)microtime()*1000000);
            $key = array_rand($rows);
//            if ( empty($rows) )
//            {
//                static::where(['used' => 1])->update(['used' => 0]);
//                $rows = static::where(['used' => 0])
//                    ->where('fails', '<', 5)
//                    ->get()->all();
//                $key = array_rand($rows);
//            }
//            $proxy = $row->proxy;
//            static::where(['proxy' => $rows[$key]->proxy])->update(['used' => 1]);

            return $rows[$key];
        }

        return false;
    }

    public function userAgent()
    {
        return Useragent::where(['useragent_id' => $this->useragent_id])->get()->first();
    }

    public static function emptyList()
    {
//        if ( static::where('fails', '<', 5)->get()->count() )
        if ( static::where('fails', '<', 3)->get()->count() )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public static function incrementFails( $proxy )
    {
        if ( $model = static::where(['proxy' => $proxy])->get()->first() )
        {
            $model->fails++;
            $model->save();
        }
    }
}