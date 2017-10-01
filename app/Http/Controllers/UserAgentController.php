<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Useragent;
use App\Models\ProxyList;

class UserAgentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
        $list = Useragent::getListArray($request->list);
        Useragent::truncate();
        $useragent_ids = [];
        foreach ( $list as $useragent )
        {
            $model = Useragent::firstOrCreate(['useragent' => $useragent]);
            $useragent_ids[] = $model->useragent_id;
        }
        foreach ( ProxyList::get()->all() as $proxy )
        {
            $proxy->useragent_id = $useragent_ids[array_rand($useragent_ids)];
            $proxy->save();
        }

        return redirect()->route('proxy.index')->with('alert-success','Список юзер-агентов сохранен!');
    }
}
