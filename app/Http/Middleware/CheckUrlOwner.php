<?php

namespace App\Http\Middleware;

use App\Services\ResponseService;
use App\Url;
use Closure;

class CheckUrlOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $short_url = $request->route('short_url');
        $url = Url::where('short_url',$short_url)->first();
        if (!$url || $url->user_id != auth()->id()){
            return ResponseService::response(0,403,'this url is not yours!');
        }
        return $next($request);
    }
}
