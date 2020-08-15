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

//redirect from short url to original url
use App\RedirectLog;
use App\Services\ResponseService;
use Carbon\Carbon;

Route::get('/r/{short_url}','URLController@redirect');

Route::get('/', function (\Illuminate\Http\Request $request){

//    $changed_urls_yesterday = RedirectLog::select('short_url')->whereDate('created_at',Carbon::yesterday())
//        ->distinct()->pluck('short_url');
//    $changed_urls_two_days_ago = RedirectLog::select('short_url')->whereDate('created_at',Carbon::yesterday()->subDay())
//        ->distinct()->pluck('short_url');
//    $merged = $changed_urls_yesterday->merge($changed_urls_two_days_ago);
//    return ResponseService::response(1,200,'',$merged->unique());

    $carbon = new Carbon();
    return Carbon::yesterday()->subWeek()->toDateTimeString();
    $changed_urls = RedirectLog::whereDate('created_at',Carbon::today())
        ->where('state',0)->get();
    return ResponseService::response(1,200,'',$changed_urls->whereNotIn('short_url',['film']));
});
