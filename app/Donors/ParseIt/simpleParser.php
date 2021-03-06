<?php
namespace App\Donors\ParseIt;

use App\Http\Controllers\LoggerController;
use App\ParseIt;
use App\Models\ProxyList;
use ParseIt\_String;

Class simpleParser
{

    public $project = '';
    public $cookie;
    public $sources = [];
    public $saveToCache = false;
    public $cookieFile = '';

    function __construct()
    {
        $this->cookieFile = 'cookie-'.class_basename(get_class($this)).'.txt';
        $this->project = str_replace('https://', '', trim('/', $this->project) );
        $this->project = str_replace('http://', '', trim('/', $this->project) );
    }

    /*
     * $date - 6 июля
     * $time - 18:15
     * return 2017-09-20 12:00
     */
    public function getDateTime($date, $time)
    {
        if ( !empty($date) && !empty($time))
        {
            $month = [
                1 => 'январ',
                2 => 'феврал',
                3 => 'март',
                4 => 'апрел',
                5 => 'мая',
                6 => 'июн',
                7 => 'июл',
                8 => 'авг',
                9 => 'сентяб',
                10 => 'октяб',
                11 => 'ноябр',
                12 => 'декабр',
            ];
            $day = _String::parseNumber($date);
            $m = preg_replace("%[^а-яА-Я]%uis", '', $date);
            foreach ( $month as $month_number => $string )
            {
                if ( stristr($m, $string) )
                {
                    $date_event = date('Y-m-d H:i', strtotime(date('Y')."-".$month_number."-".$day." ".$time));
                    if ( strtotime($date_event) < time() )
                    {
                        $date_event = date('Y-m-d H:i', strtotime((date('Y')+1)."-".$month_number."-".$day." ".$time));
                    }
                    return $date_event;
                }
            }
        }

        return false;
    }

    public function loadUrl($url, $opts = [])
    {
        $response = static::load($url, $opts);
        if ( isset($opts['returnHeader']) )
        {
            return $response;
        }
        $content = $response['data'];
        if ( !$content )
        {
            if ( isset($opts['proxy']) )
            {
                $proxy = "with ".$opts['proxy'];
//                ProxyList::incrementFails($opts['proxy']);
            }
            else
            {
                $proxy = 'without';
            }
            LoggerController::logToFile("Url {$url} return blank page {$proxy} proxy", 'info', [], false);
            return false;
        }

        return $content; // TODO: Change the autogenerated stub
    }

    public static function load($url, $opt = [], $popitka = 0)
    {
        if (@$opt['sleep']) {
            sleep($opt['sleep']);
        }
        if (@$opt['PurgeURL']) {
            exec('curl -X PURGE '.$url);
        }

        $ch = curl_init();
        if ( isset($opt['timeout']) )
        {
            $timeout = $opt['timeout'];
        }
        else
        {
            $timeout = 30;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (@$opt['returnHeader']) {
            curl_setopt($ch, CURLOPT_HEADER, 1);
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $defaultHeaders = [
            'Accept-Language:ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
            'Accept-Charset: utf-8, Windows-1251, ANSI, ISO-8859-1;q=0.7,*;q=0.7',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding: gzip;q=0, compress;q=0',
        ];
//        $opt['headers'][] =  'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0';
        $headersConfig = array_merge($defaultHeaders, (array) @$opt['headers']);

        if (!empty(@$opt['user-agent'])) {
            $headersConfig[] = $opt['user-agent'];
        }
        else
        {
            $headersConfig[] = 'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0';
        }

        if (@$opt['ajax']) {
            $headersConfig[] = 'X-Requested-With: XMLHttpRequest';
        }

        if (@$opt['referer']) {
            $headersConfig[] = 'Referer: ' . $opt['referer'];
        }

        if (@$opt['host']) {
            $headersConfig[] = 'Host: ' . $opt['host'];
        }

        if (@$opt['origin']) {
            $headersConfig[] = 'Origin: ' . $opt['origin'];
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headersConfig);

        // CURLOPT_FAILONERROR default to true
        curl_setopt($ch, CURLOPT_FAILONERROR, @$opt['failOnError'] === false ? false : true);

        if (empty(@$opt['no-follow'])) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //curl_setopt($ch, CURLOPT_AUTOREFERER, true);

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        if (!empty(@$opt['cookieFile']))
        {
            $dir = dirname($opt['cookieFile']);
            if ( !file_exists($dir) )
            {
                mkdir($dir, 0777, true);
            }
            curl_setopt($ch, CURLOPT_COOKIEFILE, $opt['cookieFile']);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $opt['cookieFile']);
        }

        if (is_array(@$opt['cookie'])) {
            foreach ($opt['cookie'] as $n => $v) {
                $cookie .= $n . '=' . $v . '; ';
            }
        } else {
            $cookie = @$opt['cookie'];
        }

        //curl_setopt($ch, CURLOPT_COOKIE, $cookie);

        // basic authentication
        if (@$opt['auth']) {
            curl_setopt($ch, CURLOPT_USERPWD, $opt['auth']);
        }

        // post data
        if (is_array(@$opt['post'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $opt['post']);
        }
        // post data
        if (is_string(@$opt['post'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $opt['post']);
        }
        if ( isset($opt['user-agent']) )
        {
            curl_setopt($ch, CURLOPT_USERAGENT, $opt['user-agent']);
        }
        /*
                if (in_array(parse_url($url, PHP_URL_HOST), $hostsByProxy) || $opt['proxy']) {
                    if ($proxy = ProxyList::getLiveProxy()) {
                        curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']);
                        curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']);
                    }
                }
        */
        if (@$opt['proxy']) {
            $proxy = $opt['proxy'];
            if ( isset($opt['proxy_user']) && isset($opt['proxy_pwd']) )
            {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, "{$opt['proxy_user']}:{$opt['proxy_pwd']}");
            }
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        curl_setopt($ch, CURLOPT_MAXREDIRS, 15);

        $data = curl_exec($ch);
        $status = curl_getinfo($ch);
        curl_close($ch);

        if (@$opt['json']) {
            $data = json_decode($data);
        }

        $response = [ 'data' => $data, 'status' => $status ];
//        print_r($url);
//        print_r($opt);
//        print_r($status);
        if(@$status['http_code'] != 200)
        {
            if($status['http_code'] == 301 || $status['http_code'] == 302) {
                if (isset($status['redirect_url'])) {
                    $response = static::load($status['redirect_url'], $opt);
                }
            }
        }
        if ( !isset($opt['attempts']) )
        {
            $opt['attempts'] = 3;
        }
        if ( @$opt['attempts'] > 0 ) {
            if(!$data) {
                if( $popitka === 10 ) {
                    return $response;
                } else {
                    if ( isset($opt['proxy']) )
                    {
                        $proxy = ProxyList::getNextProxy();
                        if ( empty($proxy) )
                        {
                            $message = 'Список проксей пуст!';
//                            LoggerController::logToFile($message, 'error');
                            return $response;
                        }
                        $opt['proxy'] = $proxy->proxy;
                        $opt['user-agent'] = $proxy->userAgent()->useragent;
                    }
                    $response = static::load($url, $opt, $popitka+1);
                }
            }
        }

        return $response;
    }

    public function fixUrl($url)
    {
        $url = trim($url);
        if ( preg_match('%^//%', trim($url)) )
        {
            return 'http:'.$url;
        }
        $url = ltrim($url, "/");
        $url = str_replace("&amp;", "&", $url);
        if (!substr_count($url, "http://") || !substr_count($url, "https://"))
        {
//            $url = 'http://' . $this->project . '/' . $url;
            $url = $this->project_link . '/' . $url;
        }

        return $url;
    }
}