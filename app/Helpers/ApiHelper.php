<?php


namespace App\Helpers;


class ApiHelper
{
    /**
     * @param array $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendSuccess($message = '', $data = [])
    {
        return response()->json(
            compact('data', 'message')
        );
    }

    /**
     * @param string $message
     * @param array $errors
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendError($message = '', $errors = [], $status = 400)
    {
        return response()->json(
            compact('errors', 'message'),
            $status
        );
    }
}
