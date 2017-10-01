<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::get('/login', 'Auth\LoginController@showLoginForm' );
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');

Route::get('/', 'ParseitController@index');

Route::post('/parser/save-source', 'ParserController@saveSourcePost');
Route::get('/parseit/sources', 'ParseitController@getSources');
Route::get('/parseit/data', 'ParseitController@getData');
Route::get('/parseit/clean-sources', 'ParseitController@cleanSourcesTable');

Route::post('/logs/search', 'LoggerController@ajaxFilterLogs');
Route::get('/logs/{filename]', 'LoggerController@view');
Route::get('/process-log', 'LoggerController@processLog');
Route::resource('/logs', 'LoggerController');

Route::get('/proxy/check-next-proxy', 'ProxyController@checkNextProxy');
Route::resource('/proxy', 'ProxyController');

Route::resource('/useragent', 'UserAgentController');

Route::get('/sources/eng', 'ParseitController@sources');
Route::post('/parseit-off-on', 'ParseitController@parseitOffOn');
Route::get('/test', 'ParserController@test');

Route::get('/log-to-mail/off', function(){
    $environmentName = 'APP_LOGTOEMAIL';
    $configKey = 'parser.log_to_mail';
    file_put_contents(App::environmentFilePath(), str_replace(
        $environmentName . '=' . 'true',
        $environmentName . '=' . 'false',
        file_get_contents(App::environmentFilePath())
    ));

    Config::set($configKey, false);

    // Reload the cached config
    if (file_exists(App::getCachedConfigPath())) {
        Artisan::call("config:cache");
    }

    return 'Оповещения на почту отключены';
});

Route::get('/log-to-mail/on', function(){
    $environmentName = 'APP_LOGTOEMAIL';
    $configKey = 'parser.log_to_mail';
    file_put_contents(App::environmentFilePath(), str_replace(
        $environmentName . '=' . 'false',
        $environmentName . '=' . 'true',
        file_get_contents(App::environmentFilePath())
    ));

    Config::set($configKey, true);

    // Reload the cached config
    if (file_exists(App::getCachedConfigPath())) {
        Artisan::call("config:cache");
    }

    return 'Оповещения на почту включены';
});
