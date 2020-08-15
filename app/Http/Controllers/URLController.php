<?php

namespace App\Http\Controllers;

use App\Analytic;
use App\Jobs\LogRedirect;
use App\Services\RedirectLogService;
use App\Services\URLService;
use App\Services\ResponseService;
use App\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class URLController extends Controller
{
    /**
     * create a new short url
     * suggestion is optional
     */
    public function create(Request $request){
        //check validation
        $validator = Validator::make($request->all(),
            [
                'original_url' => ['required', 'string'],
                'suggestion' => ['nullable', 'string', 'max:8','min:4']
            ]
        );
        if ($validator->fails()) {
            return ResponseService::response(0, 400, 'wrong inputs', $validator->errors());
        }
        //check if orginal_url is a valid url
        if (!URLService::validate_url($request->original_url)) {
            return ResponseService::response(0, 400, 'invalid url! example: http://google.com');
        }

        //if there isn't a suggestion put first 8 characters of encode of orginal_url in short_url
        $short_url = is_null($request->suggestion) ? URLService::encodingURL($request->orginal_url) : $request->suggestion;

        //if short_url is not unique this while loop repeats until we have a unique short_url
        if (Url::where('short_url',$short_url)->count() > 0) {
            do{
                $short_url = URLService::changeShortURL($short_url);
                $count = Url::where('short_url',$short_url)->count();
            }while($count > 0);
        }
        $url = new Url([
            'original_url' => $request->original_url,
            'short_url' => $short_url,
            'user_id' => auth()->id()
        ]);
        $url->save();

        $today = new Analytic(['short_url'=>$short_url,'type'=>'today']);
        $today->save();
        $yesterday = new Analytic(['short_url'=>$short_url,'type'=>'yesterday']);
        $yesterday->save();
        $week = new Analytic(['short_url'=>$short_url,'type'=>'week']);
        $week->save();
        $month = new Analytic(['short_url'=>$short_url,'type'=>'month']);
        $month->save();


        /**
         * we use our redis in memory database as LRU (Least Recently Used Cache)
         * we set maxmemory to 0 (unlimited) and maxmemory-policy to allkeys-lru
         * with these settings if memory get full it will automatically delete LRU keys
         * so set short_url as key and original_url as value with unlimited ttl
         * https://redis.io/topics/lru-cache
         */
        Redis::set("redirect:".$short_url,$request->original_url);


        return ResponseService::response(1,200,null,$url);

    }

    /**
     * redirect to original url from short url
     */
    public function redirect($short_url){

        //try to get original url from cache
        $url = Redis::get("redirect:".$short_url);

        //if not found in cache
        if (!$url){

            //try to get original url from database
            $url = Url::where('short_url',$short_url)->first();

            //if not found in database
            if (!$url) {
                //return not found page
                abort(404);
            }

            $url = $url->original_url;
            //set short url and original url as key value in redis
            Redis::set("redirect:".$short_url,$url);
        }

        //get browser-agent data and ip
        $agent = new Agent();
        $device = $agent->isPhone() ? 'mobile' : ( $agent->isDesktop() ? 'desktop' : 'others');
        $browser = $agent->browser();
        //could use cache, Etag, local storage etc to find unique users but i used ip address
        $ip = RedirectLogService::getIpAddr();
        //dispatch a job (using redis queue) to store log of this redirect in DB
        LogRedirect::dispatch($device,$browser,$ip,$short_url);


        //redirect to original url
        header("Location: ".$url);
        die();

    }

    /**
     * get all of your urls
     */
    public function getAll(){
        $urls = auth()->user()->urls;
        return ResponseService::response(1,200,null,$urls);
    }

    /**
     * check analytics of a short url
     */
    public function analytic($short_url){
        $analytics = Analytic::where('short_url',$short_url)->get();
        return ResponseService::response(1,200,null,$analytics);
    }
}
