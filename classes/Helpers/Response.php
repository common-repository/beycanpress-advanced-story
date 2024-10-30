<?php
namespace BeycanPress\Story\Helpers;

/**
 * A helper class to return meaningful and regular responses.
 */
class Response
{
    private static function json($data = null, int $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        die;
    }
    
    public static function error(string $message = null, $data = null, int $statusCode = 400, string $errorCode = null) {
        self::json([
            'success' => false,
            'errorCode' => $errorCode ? $errorCode : "ER".$statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
    
    public static function success(string $message = null, $data = null, int $statusCode = 200) {
        self::json([
            'success' => true,
            'errorCode' => null,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}