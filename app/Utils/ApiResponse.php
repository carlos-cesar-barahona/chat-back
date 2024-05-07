<?php


namespace App\Utils;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    public static function success($message = '', $data = []){
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public static function successWithData($data = [], $message = ''){
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], 200);
    }

    public static function error($message = '', $code, $data = []){
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $data
        ], $code);
    }
}