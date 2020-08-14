<?php


namespace App\Services;


class ResponseService
{
    /*
     * $status = SUCCESS or FAILED
     * $http_code = 200 or 400 or ...
     * $data = if success
     * $message = string in persian
     */
    public static function response($success,$http_code,$message = '',$data = []){
        $status = $success ? 'SUCCESS' : 'FAILED' ;
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message
        ],$http_code);
    }

}
