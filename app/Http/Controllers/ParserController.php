<?php

namespace App\Http\Controllers;

use App\Models\ProxyList;
use App\Models\Useragent;
use App\Models\Source;
use App\Models\ProcessParsingLog;
use App\ParseIt;
use Illuminate\Http\Request;
use Validator;
use \Curl\MultiCurl;

class ParserController extends Controller
{
    public $getData_resluts;

    public static function getCookieFileName( $donorClassName,  $proxy = '')
    {
        if ( !empty($proxy) )
        {
            $proxy = '-'.str_replace(":", '-', $proxy);
        }
        $filename = __DIR__.'/../../Donors'.'/cookie/'.$donorClassName.'/'.$donorClassName.$proxy.'.txt';
        return $filename;
    }

    public static function getDonorSources($donorClassName)
    {
        if ( env("PROCESS_PARSING_LOG", false) )
        {
            $process = [
                'process' => 'getDonorSources',
                'begin' => date('Y-m-d H:i:s'),
                'donor_class_name' => $donorClassName,
            ];
            $log_id = ProcessParsingLog::create($process)->id;
        }

        set_time_limit(0);
        $results = [
            'result' => [
                'count' => 0,
                'new' => 0,
                'update' => 0
            ]
        ];

        $class = "\\App\\Donors\\".$donorClassName;
        if ( class_exists($class) )
        {
            $donor = new $class();
            $donor->proxy = ProxyList::getNextProxy();

            $opt = [];
            if ( config('parser.use_proxy_list') )
            {
                $proxy = ProxyList::getNextProxy();
                if ( empty($proxy) )
                {
                    $message = 'Список проксей пуст!';
                    LoggerController::logToFile($message, 'error');
                    return;
                }
                $donor->proxy = $proxy->proxy;
                $donor->cookieFile = static::getCookieFileName($donorClassName, $proxy->proxy);
                $opt['proxy'] = $proxy->proxy;
                $opt['user-agent'] = $proxy->userAgent()->useragent;
            }
            $opt['cookieFile'] = $donor->cookieFile;
            $sources = $donor->getSources($opt);

            if ( env("PROCESS_PARSING_LOG", false) )
            {
                ProcessParsingLog::find($log_id)->update([
                    'write_begin' => date('Y-m-d H:i:s'),
                ]);
            }

            if ( count($sources) )
            {
                Source::where(['available' => 1, 'donor_class_name' => $donorClassName])->update(['available' => 0]);
            }

            if ( is_array($sources))
            {
                $count_events = 0;
                foreach ( $sources as $source )
                {
                    $results['result']['source'][] = $source;
                    // Сохраняем мероприятия
                    $validator = Validator::make($source, Source::rules());
                    if ($validator->fails())
                    {
                        $message = $validator->errors()->first();
                        LoggerController::logToFile($message, 'info', $source, true);
                    }
                    else
                    {
                        $count_events++ ;
                        $results['result']['count']++;
                        if ( Source::saveOrUpdate($source) === 'insert')
                        {
                            $results['result']['new']++;
                        }
                        else
                        {
                            $results['result']['update']++;
                        }
                    }
                }

                if ( isset($count_events) && $count_events == 0 )
                {
                    $server_url = env('SERVER_URL');
                    LoggerController::logToFile("Нет источников", 'info', [
                        'донор' => $donorClassName,
                        "<a target='_blank' href='{$server_url}/parseit/sources?d={$donorClassName}'>проверить</a>"
                    ], true);
                }
            }
            else
            {
                $server_url = env('SERVER_URL');
                LoggerController::logToFile("ИСТОЧНИКИ не парсятся, по техническим причинам", 'warning', [
                    'донор' => $donorClassName,
                    "<a target='_blank' href='{$server_url}/parseit/sources?d={$donorClassName}'>проверить</a>"
                ], true);
            }

            if ( env("PROCESS_PARSING_LOG", false) )
            {
                ProcessParsingLog::find($log_id)->update([
                    'write_end' => date('Y-m-d H:i:s'),
                    'message' => "Источников найдено: ".$results['result']['count']
                ]);
            }
        }
        else
        {
            $message = "Донор '{$class}' не найден";
            LoggerController::logToFile($message, 'error');
        }

        if ( env("PROCESS_PARSING_LOG", false) )
        {
            ProcessParsingLog::find($log_id)->update([
                'end' => date('Y-m-d H:i:s'),
            ]);
        }

        return $results;
    }

    public static function getDonorData($donorClassName, $url)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if ( env("PROCESS_PARSING_LOG", false) )
        {
            $process = [
                'process' => 'getData',
                'begin' => date('Y-m-d H:i:s'),
                'donor_class_name' => $donorClassName,
                'source' => $url
            ];
            $log_id = ProcessParsingLog::create($process)->id;
        }

        set_time_limit(0);
        $results = [
            'result' => [
                'tickets' => [
                    'count' => 0,
                    'new' => 0,
                    'update' => 0
                ]
            ]
        ];

        $class = "\\App\\Donors\\".$donorClassName;
        if ( class_exists($class) )
        {
            $donor = new $class();
            $opt = [];
            if ( config('parser.use_proxy_list') )
            {
                $proxy = ProxyList::getNextProxy();
                if ( empty($proxy) )
                {
                    $message = 'Список проксей пуст!';
                    LoggerController::logToFile($message, 'error');
                    return;
                }
                $donor->proxy = $proxy->proxy;
                $donor->cookieFile = static::getCookieFileName($donorClassName, $proxy->proxy);
                $opt['proxy'] = $proxy->proxy;
                $opt['user-agent'] = $proxy->userAgent()->useragent;
            }
            $opt['cookieFile'] = $donor->cookieFile;
            $data = $donor->getData($url, $opt);

//            return $events;
            if ( $data != false )
            {
                // Ставим статус "нет в наличии" у всех билетов, далее статус обновиться только у найденных
                $multi_curl = new MultiCurl();
                $multi_curl->setPort(80);
                $multi_curl->setTimeout(0);
                $multi_curl->setConcurrency(env('SAVE_THREADS', 20));    //  потоки

                $multi_curl->success(function($instance) {
//                echo 'call to "' . $instance->url . '" was successful.' . "\n";
//                echo 'response:' . "\n";
//                var_dump($instance->response);
                });
                $multi_curl->error(function($instance) {
//                echo 'call to "' . $instance->url . '" was unsuccessful.' . "\n";
//                echo 'error code: ' . $instance->errorCode . "\n";
//                echo 'error message: ' . $instance->errorMessage . "\n";
//                echo 'response:' . "\n";
//                var_dump($instance->response);
                });
                $multi_curl->complete(function($instance) {
//                echo 'call completed' . "\n";
                });

                if ( env("PROCESS_PARSING_LOG", false) ) {
                    ProcessParsingLog::find($log_id)->update([
                        'write_begin' => date('Y-m-d H:i:s'),
                    ]);
                }
                $results['tickets_found'] = [];
//                foreach ( @$events as $event )
//                {
//                    $validator = Validator::make($event, TicketsSource::rules());
//                    if ($validator->fails())
//                    {
//                        $message = $validator->errors()->first();
//                        LoggerController::logToFile($message, 'error', $event);
//                        continue;
//                    }
//                    if ( TicketsSource::saveOrUpdate($event) === 'insert')
//                    {
//                        continue;
//                    }
//                    $kassirclub_event_id = null;
//                    $kassirclub_location_id = null;
//                    if ( $tickets_source = TicketsSource::where(['event_hash' => $event['event_hash']])->get()->first() )
//                    {
//                        $kassirclub_event_id = $tickets_source->kassirclub_event_id;
//                        $kassirclub_location_id = $tickets_source->kassirclub_location_id;
//                        if ( empty($kassirclub_location_id) )
//                        {
//                            $kassirclub_location_id = KassirclubLocation::findLocationIdFromSynonyms($tickets_source->location);
//                        }
//                    }
//
//                    $results['result']['events'][] = $event;
//                    $count_tickets += count($event['tickets']);
////                $tickets_result = static::saveEvent($kassirclub_event_id, $kassirclub_location_id, $url, $event, $donorClassName);
//                    $multi_curl->addPost(env('APP_URL')."/parser/save-event", array(
//                        'donor_class_name' => $donorClassName,
//                        'kassirclub_event_id' => $kassirclub_event_id,
//                        'kassirclub_location_id' => $kassirclub_location_id,
//                        'url' => $url,
//                        'event' => serialize($event),
//                    ));
//                    if ( isset($results['tickets_found'][$event['event_hash']]) )
//                    {
//                        $results['tickets_found'][$event['event_hash']] += count($event['tickets']);
//                    }
//                    else
//                    {
//                        $results['tickets_found'][$event['event_hash']] = count($event['tickets']);
//                    }
//                }
                $multi_curl->start(); // Blocks until all items in the queue have been processed.

                if ( env("PROCESS_PARSING_LOG", false) ) {
                    ProcessParsingLog::find($log_id)->update([
                        'write_end' => date('Y-m-d H:i:s'),
//                        'message' => "Билетов найдено: " . $count
                    ]);
                }
            }
            else
            {
                $link = urlencode($url);
                $server_url = env('SERVER_URL');
                LoggerController::logToFile("ДАННЫЕ не парсятся, по техническим причинам", 'warning', [
                    "<a target='_blank' href='{$server_url}/parseit/data?d={$donorClassName}&link={$link}'>проверить</a>",
                    'донор' => $donorClassName,
                    'ссылка' => "<a target='_blank' href='{$url}' >{$url}</a>",
                ], true);
//                Ticket::where([
//                    'source' => $url,
//                ])->update(['updated_at' => date('Y-m-d H:i:s')]);
            }
        }
        else
        {
            $message = "Донор '{$class}' не найден";
            LoggerController::logToFile($message, 'error');
        }

        if ( isset($count_tickets) && $count_tickets == 0 )
        {
            $link = urlencode($url);
            $server_url = env('SERVER_URL');
            LoggerController::logToFile("Нет данных", 'info', [
                "<a target='_blank' href='{$server_url}/parseit/data?d={$donorClassName}&link={$link}'>проверить</a>",
                'донор' => $donorClassName,
                'ссылка' => "<a target='_blank' href='{$url}' >{$url}</a>",
            ], true);
        }

        if ( env("PROCESS_PARSING_LOG", false) ) {
            ProcessParsingLog::find($log_id)->update([
                'end' => date('Y-m-d H:i:s'),
            ]);
        }

        return $results;
    }

    public static function saveEvent($kassirclub_event_id, $kassirclub_location_id, $url, $event, $donorClassName)
    {
        if ( env("PROCESS_PARSING_LOG", false) ) {
            $process = [
                'process' => 'saveEvent',
                'begin' => date('Y-m-d H:i:s'),
                'donor_class_name' => $donorClassName,
                'source' => $url
            ];
            $log_id = ProcessParsingLog::create($process)->id;
        }

        set_time_limit(0);
        $results = [
            'count' => 0,
            'new' => 0,
            'update' => 0,
        ];

        Ticket::where([
            'event_hash' => $event['event_hash'],
            'date_event' => $event['date_event'],
        ])->
        delete();
        TicketsSource::where([
            'event_hash' => $event['event_hash'],
        ])->
        update([
            'location' => $event['location'],
        ]);

        if ( env("PROCESS_PARSING_LOG", false) )
        {
            ProcessParsingLog::find($log_id)->update([
                'write_begin' => date('Y-m-d H:i:s'),
            ]);
        }

        if ( is_array($event['tickets']) )
        {

            $last_sector = '';
            $last_location = '';
            foreach ( $event['tickets'] as $ticket )
            {
                if ( ($last_sector != $ticket['sector']) || ($last_location != $ticket['location']) )
                {
                    // Сохраняем сектора площадки
                    $donor_sector = [
                        'donor_class_name' => $donorClassName,
                        'location' => $event['location'],
                        'sector' => $ticket['sector'],
                    ];

                    $kassirclub_sector_id = null;
                    $validator = Validator::make($donor_sector, DonorSector::rules());
                    if ($validator->fails())
                    {
//                    $message = $validator->errors()->first();
//                    LoggerController::logToFile($message, 'error', $ticket);
                        continue;
                    }
                    else
                    {
                        $donor_sector_model = DonorSector::firstOrCreate($donor_sector);
//                        if ( empty($donor_sector_model->kassirclub_sector_id) )
//                        {
//                            $donor_sector_model->kassirclub_sector_id = $donor_sector_model->kassirclubSector()->kassirclub_sector_id;
//                            $donor_sector_model->save();
//                        }
                        $kassirclub_sector_id = $donor_sector_model->kassirclub_sector_id;
                    }
                }

                if (empty($kassirclub_sector_id))
                {
                    continue;
                }
                $row = $ticket;
                $row['kassirclub_event_id'] = $kassirclub_event_id;
                $row['kassirclub_location_id'] =  $kassirclub_location_id;
                $row['kassirclub_sector_id'] =  $kassirclub_sector_id;
                $row['date_event'] = $event['date_event'];//date('Y-m-d H:i:s', strtotime($event['date_event']));
                $row['source'] = $event['source'];
                $row['event_hash'] = $event['event_hash'];
                $row['location'] = $event['location'];

                // Сохраняем билет
                $validator = Validator::make($row, Ticket::rules());
                if ($validator->fails())
                {
//                    continue;
                    $message = $validator->errors()->first();
                    LoggerController::logToFile($message, 'error', $ticket);
                }
                else
                {
                    $results['count']++;
                    if ( Ticket::saveOrUpdate($row) === 'insert')
                    {
                        $results['new']++;
                    }
                    else
                    {
                        $results['update']++;
                    }
                }
            }
        }

        if ( env("PROCESS_PARSING_LOG", false) )
        {
            ProcessParsingLog::find($log_id)->update([
                'write_end' => date('Y-m-d H:i:s'),
                'message' => "Билетов сохранено: " . $results['count']
            ]);
        }

        if ( $source = TicketsSource::where(['event_hash' => $event['event_hash']])->get()->first() )
        {
            $source->update(['moderation' => $source->needModeration()]);
        }

        if ( env("PROCESS_PARSING_LOG", false) ) {
            ProcessParsingLog::find($log_id)->update([
                'end' => date('Y-m-d H:i:s'),
            ]);
        }

        return $results;
    }

    public function saveSourcePost(Request $request)
    {
        set_time_limit(0);
        $this->validate($request , [
            'url' => 'required',
            'event' => 'required'
        ]);
        $results = [
            'count' => 0,
            'new' => 0,
            'update' => 0,
        ];
        $donor_class_name = @$request->donor_class_name;
        $kassirclub_event_id = @$request->kassirclub_event_id;
        $kassirclub_location_id = @$request->kassirclub_location_id;
        $url = $request->url;
        $event = unserialize($request->event);

        if ( env("PROCESS_PARSING_LOG", false) ) {
            $process = [
                'process' => 'saveSourcePost',
                'begin' => date('Y-m-d H:i:s'),
                'donor_class_name' => $donor_class_name,
                'source' => $url
            ];
            $log_id = ProcessParsingLog::create($process)->id;
        }

        Ticket::where([
            'event_hash' => $event['event_hash'],
            'date_event' => $event['date_event'],
        ])->
        delete();
        TicketsSource::where([
            'event_hash' => $event['event_hash'],
        ])->
        update([
            'location' => $event['location'],
        ]);

        if ( env("PROCESS_PARSING_LOG", false) ) {
            ProcessParsingLog::find($log_id)->update([
                'write_begin' => date('Y-m-d H:i:s'),
            ]);
        }

        if ( is_array($event['tickets']) )
        {
            $last_sector = '';
            $last_location = '';
            foreach ( $event['tickets'] as $ticket )
            {
                if ( ($last_sector != $ticket['sector']) || ($last_location != $ticket['location']) )
                {
                    // Сохраняем сектора площадки
                    $donor_sector = [
                        'donor_class_name' => $donor_class_name,
                        'location' => $event['location'],
                        'sector' => $ticket['sector'],
                    ];

                    $kassirclub_sector_id = null;
                    $validator = Validator::make($donor_sector, DonorSector::rules());
                    if ($validator->fails())
                    {
                        continue;
                        $message = $validator->errors()->first();
                        LoggerController::logToFile($message, 'error', $ticket);
                    }
                    else
                    {
                        $donor_sector_model = DonorSector::firstOrCreate($donor_sector);
//                        if ( empty($donor_sector_model->kassirclub_sector_id) )
//                        {
//                            $donor_sector_model->kassirclub_sector_id = $donor_sector_model->kassirclubSector()->kassirclub_sector_id;
//                            $donor_sector_model->save();
//                        }
                        $kassirclub_sector_id = $donor_sector_model->associateSector($kassirclub_location_id)->kassirclub_sector_id;
                    }
                }
                $row = $ticket;
                $row['kassirclub_event_id'] = $kassirclub_event_id;
                $row['kassirclub_location_id'] =  $kassirclub_location_id;
                $row['kassirclub_sector_id'] =  $kassirclub_sector_id;
                $row['date_event'] = $event['date_event'];
                $row['source'] = $event['source'];
                $row['event_hash'] = $event['event_hash'];
                $row['location'] = $event['location'];
                // Сохраняем билеты
                $validator = Validator::make($row, Ticket::rules());
                if ($validator->fails())
                {
                    continue;
                    $message = $validator->errors()->first();
                    LoggerController::logToFile($message, 'error', $ticket);
                }
                else
                {
                    $results['count']++;
                    if ( Ticket::saveOrUpdate($row) === 'insert')
                    {
                        $results['new']++;
                    }
                    else
                    {
                        $results['update']++;
                    }
                }
            }
        }

        if ( env("PROCESS_PARSING_LOG", false) ) {
            ProcessParsingLog::find($log_id)->update([
                'write_end' => date('Y-m-d H:i:s'),
                'message' => "Билетов сохранено: " . $results['count']
            ]);
        }

        if ( $source = TicketsSource::where(['event_hash' => $event['event_hash']])->get()->first() )
        {
            $source->update(['moderation' => $source->needModeration()]);
        }

        $response = \Response::make($results);
        $response->header('Content-Type', 'text/json');

        if ( env("PROCESS_PARSING_LOG", false) ) {
            ProcessParsingLog::find($log_id)->update([
                'end' => date('Y-m-d H:i:s'),
            ]);
        }

        return $response;
    }

    public function test(Request $request)
    {
        $model = TicketsSource::where(['event_hash' => '3748ef7a5acb5545e5bdaacf40a40d5f'])->get()->first();
        print_r($model->image);
    }
}
