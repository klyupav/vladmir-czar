<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\DonorsEvent;
use App\Models\TicketsSource;
use App\Models\Ticket;
use App\Models\Source;
use App\ParseIt;
use Illuminate\Http\Request;
Use Validator;
use \Curl\MultiCurl;

class ParseitController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth')->only([
//            'index'
//        ]);
    }

    /**
     * Показываем панель управления сбором данных.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( \Auth::guest() )
        {
            return redirect('/login');
        }

        return view('parseit.index', [
            'parsing_info' => []
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return IlluminateHttpResponse
     */
    public function sources(Request $request)
    {
        $query = Source::query();
        $query = $query->where(['available' => 1]);
        $versions = explode(',', env('DONOR_VERSION', ''));
        $version_list = config('parser.version');
        $version_id = [];
        foreach ( $versions as $version )
        {
            if ( isset( $version_list[$version] ) )
            {
                $version_id[$version] = $version_list[$version];
            }
        }
        $query = $query->where(['available' => 1]);
        $uri = preg_replace("%\?.*?$%uis", '', $request->getRequestUri());
        $version_name = explode('/', $uri)[2];
        $query = $query->where(['version' => @$version_id[$version_name]]);
        $donor_list = [];
        $donors = @config('parser.donors')[$version_name];
        if ( is_array($donors) )
        {
            foreach ( $donors as $donor => $class )
            {
                $donor_list[] = $donor;
            }
        }
        $sources = $query->paginate(10);

        return view('sources.view',[
            'version_name' => $version_name,
            'sources' => $sources,
            'donor_list' => $donor_list,
        ]);
    }

    public function getSources(Request $request)
    {
        set_time_limit(0);
        if ( isset($request->d) )
        {
            $donor = $request->d;
            $result = ParserController::getDonorSources($donor);
            echo "<pre>";
            print_r($result);
            echo "</pre>";
        }
        else
        {
            $versions = explode(',', env('DONOR_VERSION', ''));
            foreach ( $versions as $version )
            {
                $donor_list = @config('parser.donors')[$version];
                if (is_array( $donor_list ))
                {
                    foreach ($donor_list as $donorClassName => $fullClass)
                    {
                        $result = ParserController::getDonorSources($donorClassName);
                        echo "<pre>";
                        print_r($result);
                        echo "</pre>";
                    }
                }
            }
        }
    }

    public function getData(Request $request)
    {
        if ( isset($request->d) && isset($request->link) )
        {
            // Собираем билеты с определенного ивента у донора
            $donor = $request->d;
            $link = $request->link;
            sleep(random_int(0,6));
            $result = ParserController::getDonorData($donor, $link);
            echo "<pre>";
            print_r($result);
            echo "</pre>";
        }
        elseif ( isset($request->d) )
        {
            $multi_curl = new MultiCurl();
            $multi_curl->setPort(80);
            $multi_curl->setTimeout(0);
            $multi_curl->setConcurrency(env('GRABBER_THREADS', 20));    //  потоки

            $multi_curl->success(function($instance) {
//                echo 'call to "' . $instance->url . '" was successful.' . "\n";
//                echo 'response:' . "\n";
//                var_dump($instance->response);
            });
            $multi_curl->error(function($instance) {
                echo 'call to "' . $instance->url . '" was unsuccessful.' . "\n";
                echo 'error code: ' . $instance->errorCode . "\n";
                echo 'error message: ' . $instance->errorMessage . "\n";
                echo 'response:' . "\n";
                var_dump($instance->response);
            });
            $multi_curl->complete(function($instance) {
//                echo 'call completed' . "\n";
            });
            // Собираем билеты по всем ивентам которые были сопоставлены
            foreach ( Source::where(['available' => 1, 'parseit' => 1])
                          ->where(['donor_class_name' => $request->d])
                          ->get()->all() as $source )
            {
                $multi_curl->addGet(env('APP_URL').'/parseit/data', array(
                    'd' => $source->donor_class_name,
                    'link' => urlencode($source->source),
                ));
//                $result = ParserController::getDonorData($source->donor_class_name, $source->source);
//                echo "<pre>";
//                print_r($result);
//                echo "</pre>";
            }
            $multi_curl->start();
        }
        else
        {
            $multi_curl = new MultiCurl();
            $multi_curl->setPort(80);
            $multi_curl->setTimeout(0);
            $multi_curl->setConcurrency(env('GET_TICKETS_THREADS', 20));    //  потоки

            $multi_curl->success(function($instance) {
//                echo 'call to "' . $instance->url . '" was successful.' . "\n";
//                echo 'response:' . "\n";
//                var_dump($instance->response);
            });
            $multi_curl->error(function($instance) {
                echo 'call to "' . $instance->url . '" was unsuccessful.' . "\n";
                echo 'error code: ' . $instance->errorCode . "\n";
                echo 'error message: ' . $instance->errorMessage . "\n";
                echo 'response:' . "\n";
                var_dump($instance->response);
            });
            $multi_curl->complete(function($instance) {
//                echo 'call completed' . "\n";
            });
            // Собираем билеты по всем ивентам которые были сопоставлены
            foreach ( Source::where(['available' => 1, 'parseit' => 1])
                          ->get()->all() as $source )
            {
                $multi_curl->addGet(env('APP_URL').'/parseit/data', array(
                    'd' => $source->donor_class_name,
                    'link' => urlencode($source->source),
                ));
//                $result = ParserController::getDonorData($source->donor_class_name, $source->source);
//                echo "<pre>";
//                print_r($result);
//                echo "</pre>";
            }
            $multi_curl->start();
        }
    }

    public function cleanSourcesTable()
    {
        $time_range = time()-(24*60*60);
        print_r( ['вчера' => date('Y-m-d H:i:s', $time_range), 'сегодня' => date('Y-m-d H:i:s')]);
        Source::where(['updated_at' => NULL, 'created_at' => NULL])->delete();
        Source::whereNotNull('created_at')->where(['updated_at' => NULL])->where('created_at', '<' , date('Y-m-d H:i:s', $time_range))->delete();
        Source::whereNotNull('updated_at')->where('updated_at', '<' , date('Y-m-d H:i:s', $time_range))->delete();
        Source::where('date_event', '<' , date('Y-m-d H:i:s', $time_range))->delete();
    }

    public function parseitOffOn(Request $request)
    {
        set_time_limit(0);
        $this->validate($request, [
            'event_hash' => 'required',
        ]);
        $results = ['error' => 'Мероприятия нет'];
        if ( $model = TicketsSource::where(['event_hash' => $request->event_hash])->get()->first() )
        {
            Activity::log("Изменил статус parseit = '{$request->parseit}'  у event_hash = '{$request->event_hash}'");
            $results = ['ok' => 'changed', 'change' =>$request->parseit];
            TicketsSource::where(['event_hash' => $request->event_hash])->update(['parseit' => isset($request->parseit) && $request->parseit === 'true' ? 1 : 0]);
            if ( $request->parseit != 'true' )
            {
                Ticket::where(['event_hash' => $request->event_hash])->delete();
            }
        }

        if ( $source = TicketsSource::where(['event_hash' => $request->event_hash])->get()->first() )
        {
            $source->update(['moderation' => $source->needModeration()]);
            $results['moderation'] = $source->needModeration();
        }

        $response = \Response::make($results, isset($results['error']) ? 500 : 200 );
        $response->header('Content-Type', 'text/json');

        return $response;
    }
}