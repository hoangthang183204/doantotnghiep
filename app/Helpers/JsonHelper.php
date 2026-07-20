<?php
// app/Helpers/JsonHelper.php

namespace App\Helpers;

class JsonHelper
{
    /**
     * Trả về JSON response thành công
     */
    public static function success($message, $data = null, $status = 200)
    {
        $response = ['success' => true, 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        return response()
            ->json($response, $status)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ->header('Content-Type', 'application/json; charset=utf-8');
    }

    /**
     * Trả về JSON response lỗi
     */
    public static function error($message, $status = 400, $data = null)
    {
        $response = ['success' => false, 'message' => $message];
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        return response()
            ->json($response, $status)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ->header('Content-Type', 'application/json; charset=utf-8');
    }
}