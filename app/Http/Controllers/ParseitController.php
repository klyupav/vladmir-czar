<?php

namespace App\Http\Controllers;

use App\Models\Datum;
use App\Models\Source;
use App\ParseIt;
use Illuminate\Http\Request;
use Activity;
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
        if ( isset($request->donor_class_name) && !empty($request->donor_class_name) )
        {
            $query = $query->where(['donor_class_name' => $request->donor_class_name]);
        }
        if ( isset($request->source) && !empty($request->source) )
        {
            $query = $query->where(['source' => $request->source]);
        }
//        $query = $query->where(['available' => 1]);
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
        $sources = $query->paginate(20);

        return view('sources.view',[
            'version_name' => $version_name,
            'sources' => $sources,
            'donor_list' => $donor_list,
        ]);
    }

    public function categories()
    {
        set_time_limit(0);
        $sources = Source::where(['donor_class_name' => 'BartecDe_Categories_Eng', 'parseit' => 1, 'available' => 1])->get()->all();
        if ( empty($sources) )
        {
            Source::where(['donor_class_name' => 'BartecDe_Categories_Eng', 'source' => 'https://www.bartec.de/en/products/'])->update(['available' => 1]);
            echo 'Парсинг категорий закончен.';
//            ParserController::getDonorSources('BartecDe_Categories_Eng');
//            $sources = Source::where(['donor_class_name' => 'BartecDe_Categories_Eng', 'parseit' => 1, 'available' => 1])->get()->all();
        }
        else
        {
            echo 'Обновите страницу.';
            foreach ($sources  as $source )
            {
                $result = ParserController::getDonorSources($source->donor_class_name, ['url' => $source->source]);
//                echo "<pre>";
//                print_r($result);
//                echo "</pre>";
                $result = ParserController::getDonorData($source->donor_class_name, $source->source);
//                echo "<pre>";
//                print_r($result);
//                echo "</pre>";
                Source::where(['source' => $source->source, 'donor_class_name' => $source->donor_class_name])->update(['available' => 0]);
            }
        }
    }

    public function products()
    {
        set_time_limit(0);
        $cats = Datum::where(['donor_class_name' => 'BartecDe_Categories_Eng', 'parseit' => 1, 'available' => 1])->get()->all();
        if ( empty($cats) )
        {
            $products = Source::where(['donor_class_name' => 'BartecDe_Products_Eng', 'parseit' => 1, 'available' => 1])->get()->all();
            if ( empty($products) )
            {
                echo 'Парсинг продуктов закончен.';
            }
            else
            {
                echo 'Обновите страницу.';
                foreach ($products  as $product )
                {
                    $result = ParserController::getDonorData($product->donor_class_name, $product->source);
                    Source::where(['source' => $product->source, 'donor_class_name' => $product->donor_class_name])->update(['available' => 0]);
                }
            }
        }
        else
        {
            echo 'Обновите страницу.';
            foreach ($cats  as $cat )
            {
                $result = ParserController::getDonorSources('BartecDe_Products_Eng', ['url' => $cat->source]);
                Datum::where(['source' => $cat->source, 'donor_class_name' => $cat->donor_class_name])->update(['available' => 0]);
            }
        }
    }

    public function getSources(Request $request)
    {
        set_time_limit(0);
        $opt = $request->toArray();
        if ( isset($request->d) )
        {
            $donor = $request->d;
            $result = ParserController::getDonorSources($donor, $opt);
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
            $donor = $request->d;
            $link = $request->link;
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
            'hash' => 'required',
        ]);
        $results = ['error' => 'Источника нет'];
        if ( $model = Source::where(['hash' => $request->hash])->get()->first() )
        {
            Activity::log("Изменил статус parseit = '{$request->parseit}'  у hash = '{$request->hash}'");
            $results = ['ok' => 'changed', 'change' =>$request->parseit];
            Source::where(['hash' => $request->hash])->update(['parseit' => isset($request->parseit) && $request->parseit === 'true' ? 1 : 0]);
            if ( $request->parseit != 'true' )
            {
                Datum::where(['hash' => $request->hash])->delete();
            }
        }

        $response = \Response::make($results, isset($results['error']) ? 500 : 200 );
        $response->header('Content-Type', 'text/json');

        return $response;
    }
}