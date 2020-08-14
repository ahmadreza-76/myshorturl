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

use App\Services\RedirectLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Jenssegers\Agent\Agent;

Route::get('/r/{short_url}','URLController@redirect');

Route::get('/', function (\Illuminate\Http\Request $request){
    return Carbon::now()->startOfDay();
   //return Agent
});
