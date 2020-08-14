<?php


namespace App\Services;


use Tuupola\Base62;

class URLService
{

    private static $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    public static function encodingURL($long_url){
        $base62 = new Base62();
        $encoded = md5($long_url.now()->toDateTimeString());
        return substr($base62->encode($encoded),0,config('shorturl.shorturl_length'));
    }

    public static function changeShortURL($url){

        //make short_url's length 8 char
        $change = false;
        while (strlen($url) < config('shorturl.shorturl_length')){
            $url .= (self::$charset)[rand(0,61)];
            $change = true;
        }
        if ($change)
            return $url;
        //randomly change one character of  short_url
        $url[rand(0,config('shorturl.shorturl_length')-1)] = (self::$charset)[rand(0,61)];

        return $url;

    }


    public static function validate_url($url) {
        $path = parse_url($url, PHP_URL_PATH);
        $encoded_path = array_map('urlencode', explode('/', $path));
        $url = str_replace($path, implode('/', $encoded_path), $url);

        return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
    }

}
