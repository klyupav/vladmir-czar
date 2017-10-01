<?php

namespace App\Http\Controllers;

use App\Models\ProxyList;
use App\Models\Useragent;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
        $this->middleware('auth')->except([
            'checkNextProxy',
        ]);
    }

    public function index()
    {
        $proxy_list = '';
        foreach ( ProxyList::where('fails', '<', 3)->get()->all() as $row)
        {
            $proxy_list .= $row->proxy."\n";
        }
        $useragent_list = '';
        foreach ( Useragent::get()->all() as $row)
        {
            $useragent_list .= $row->useragent."\n";
        }
        return view('proxy.index', [
            'proxy_list' => $proxy_list,
            'useragent_list' => $useragent_list,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  IlluminateHttpRequest  $request
     * @param  int  $id
     * @return IlluminateHttpResponse
     */
    public function update(Request $request, $id)
    {
        // validation
        $this->validate($request,[
            'list' => 'required',
        ]);
        $list = ProxyList::getListArray($request->list);
        ProxyList::truncate();
        $useragent_ids = [];
        foreach ( Useragent::get()->all() as $useragent )
        {
            $useragent_ids[] = $useragent->useragent_id;
        }
        foreach ( $list as $proxy )
        {
            ProxyList::insert(['proxy' => $proxy, 'useragent_id' => @$useragent_ids[array_rand($useragent_ids)]]);
        }

        return redirect()->route('proxy.index')->with('alert-success','Список прокси сохранен!');
    }

    public function checkNextProxy(Request $request)
    {
        $this->validate($request , [
            'proxy' => 'required'
        ]);
        $config = [
            'timeout'   => 15,
            'check'     => ['get'],
        ];

        /*
        *	$url [required1]
        */
        $url = "http://sochi.kassir.ru";
//        $url = "http://www.icetickets.ru";

        $proxies[] = $request->proxy;

        $proxyCheckObject = new \SahusoftCom\ProxyChecker\ProxyCheckerService($url, $config);
        $result = $proxyCheckObject->checkProxies($proxies);
        foreach ($result as $item) {
            if (isset($item['error']) && $item['error'] === 'Empty content' )
            {
                ProxyList::where(['proxy' => $request->proxy])->update(['fails' => 1]);
            }
        }
        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }
}
