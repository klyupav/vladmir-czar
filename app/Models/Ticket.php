<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Ticket
 *
 * @property int $id
 * @property int $kassirclub_event_id
 * @property int $kassirclub_location_id
 * @property int $kassirclub_sector_id
 * @property string $source
 * @property string $event_hash
 * @property string $date_event
 * @property string $location
 * @property string $sector
 * @property string $line
 * @property string $place
 * @property int $price
 * @property bool $available
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket whereAvailable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket whereDateEvent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket whereKassirclubEventId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket whereKassirclubLocationId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket whereKassirclubSectorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket whereLine($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket whereLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket wherePlace($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket whereSector($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket whereSource($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Ticket whereEventHash($value)
 */
class Ticket extends Model
{
    protected $table = 'tickets';

    public $timestamps = true;

    protected $fillable = [
        'kassirclub_event_id',
        'kassirclub_location_id',
        'kassirclub_sector_id',
        'source',
        'event_hash',
        'date_event',
        'location',
        'sector',
        'line',
        'place',
        'price',
        'available'
    ];

    public static function rules()
    {
        return [
            'kassirclub_event_id' => 'required',
            'kassirclub_location_id' => 'required',
            'kassirclub_sector_id' => 'required',
            'date_event' => 'required',
            'line' => 'required',
            'place' => 'required',
            'price' => 'required',
            'source' => 'required',
            'event_hash' => 'required',
        ];
    }

    protected $guarded = [];

    public function donorSector()
    {
        if ( $model = DonorSector::where(['donor_class_name' => @TicketsSource::where(['event_hash' => $this->event_hash])->get()->first()->donor_class_name, 'location' => $this->location, 'sector' => $this->sector])->get()->first() )
        {
            return $model;
        }
        else
        {
            $model = new DonorSector();
            $model->id = 0;
            return $model;
        }
    }

    public function kassirclubLocation()
    {
        if ( $synonym = KassirclubLocationSynonym::where('synonym', $this->location)->first() )
        {
            return KassirclubLocation::where(['kassirclub_location_id' => $synonym->kassirclub_location_id])->get()->first();
        }
        else
        {
            $model = new KassirclubLocation();
            return $model;
        }
    }

    public function kassirclubSector()
    {
        if ( $synonym = KassirclubSectorSynonym::where('synonym', $this->sector)->first() )
        {
            return KassirclubSector::where(['kassirclub_sector_id' => $synonym->kassirclub_sector_id])->get()->first();
        }
        else
        {
            $model = new KassirclubSector();
            return $model;
        }
    }

    public static function saveOrUpdate($attr = [])
    {
        $where = [
            'kassirclub_event_id' => $attr['kassirclub_event_id'],
            'kassirclub_location_id' => $attr['kassirclub_location_id'],
            'kassirclub_sector_id' => $attr['kassirclub_sector_id'],
            'date_event' => $attr['date_event'],
//            'line' => empty($attr['line']) ? '1' : $attr['line'],
//            'place' => empty($attr['place']) ? '1' : $attr['place'],
            'line' => $attr['line'],
            'place' => $attr['place'],
        ];

        if ($model = static::where($where)->get()->first())
        {
            if ( $model->price >= $attr['price'] )
            {
                $model->update([
                    'location' => empty($attr['location']) ? '' : $attr['location'],
                    'sector' => empty($attr['sector']) ? '' : $attr['sector'],
//                'line' => empty($attr['line']) ? '1' : $attr['line'],
//                'place' => empty($attr['place']) ? '1' : $attr['place'],
                    'line' => $attr['line'],
                    'place' => $attr['place'],
                    'price' => $attr['price'],
                    'source' => $attr['source'],
                    'event_hash' => $attr['event_hash'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'available' => 1,
                ]);
            }
            return 'update';
        }
        else
        {
            static::insert([
                'kassirclub_event_id' => $attr['kassirclub_event_id'],
                'kassirclub_location_id' => empty($attr['kassirclub_location_id']) ? null : $attr['kassirclub_location_id'],
                'kassirclub_sector_id' => empty($attr['kassirclub_sector_id']) ? null : $attr['kassirclub_sector_id'],
                'date_event' => $attr['date_event'],
                'location' => empty($attr['location']) ? '' : $attr['location'],
                'sector' => empty($attr['sector']) ? '' : $attr['sector'],
//                'line' => empty($attr['line']) ? '1' : $attr['line'],
//                'place' => empty($attr['place']) ? '1' : $attr['place'],
                'line' => $attr['line'],
                'place' => $attr['place'],
                'price' => $attr['price'],
                'source' => $attr['source'],
                'created_at' => date('Y-m-d H:i:s'),
                'event_hash' => $attr['event_hash'],
            ]);
            return 'insert';
        }
    }

    public static function countLocationWithNULL()
    {
        return static::whereNull('kassirclub_location_id')->groupBy(['kassirclub_location_id'])->get()->count();
    }

    public static function countSectorWithNULL()
    {
        return static::whereNull('kassirclub_sector_id')->groupBy(['kassirclub_sector_id'])->get()->count();
    }

    public static function getTicketsReadyToExport($kassirclub_event_id, $kassirclub_location_id, $date_event)
    {
        return DB::table('tickets')
            ->where(['tickets.kassirclub_event_id' => $kassirclub_event_id])
            ->where(['tickets.kassirclub_location_id' => $kassirclub_location_id])
            ->whereNotNull('tickets.kassirclub_sector_id')
            ->where(['tickets.date_event' => $date_event])
            ->where(['tickets.available' => 1])
            ->where(['tickets_sources.available' => 1])
            ->where(['tickets_sources.parseit' => 1])
            ->where('tickets.date_event', '>', date('Y-m-d H:i:s'))
            ->join('kassirclub_sectors', 'tickets.kassirclub_sector_id', '=', 'kassirclub_sectors.kassirclub_sector_id')
            ->join('tickets_sources', 'tickets_sources.event_hash', '=', 'tickets.event_hash')
            ->select(
                'kassirclub_sectors.sector',
                'tickets.sector as sector_donor',
                'tickets.line',
                'tickets.place',
                'tickets.id',
                'tickets.price',
                'tickets.event_hash',
                'tickets_sources.donor_class_name',
                'tickets.source',
                'tickets.created_at',
                'tickets.updated_at',
                'tickets.location'
            )
            ->groupBy([
                'tickets.id'
            ])
            ->get()->toArray();
    }

    public static function getEventsReadyToExport()
    {
        return DB::table('tickets')
            ->whereNotNull('tickets.kassirclub_event_id')
            ->whereNotNull('tickets.kassirclub_location_id')
            ->whereNotNull('tickets.kassirclub_sector_id')
            ->where(['tickets.available' => 1])
            ->where(['tickets_sources.available' => 1])
            ->where(['tickets_sources.parseit' => 1])
            ->where('tickets.date_event', '>', date('Y-m-d H:i:s'))
            ->join('kassirclub_sectors', 'tickets.kassirclub_sector_id', '=', 'kassirclub_sectors.kassirclub_sector_id')
            ->join('tickets_sources', 'tickets_sources.event_hash', '=', 'tickets.event_hash')
            ->select(
                'tickets.*',
                'tickets_sources.donor_class_name'
            )
            ->groupBy([
                'tickets.kassirclub_event_id',
                'tickets.kassirclub_location_id',
                'tickets.date_event'
            ])
            ->get()->toArray();
    }
}