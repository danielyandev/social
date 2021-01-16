<?php


namespace App\Helpers\Api\V1;


class ResponseHelper
{
    /**
     * @param array $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($message = '', $data = [])
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
    public static function error($message = '', $errors = [], $status = 400)
    {
        return response()->json(
            compact('errors', 'message'),
            $status
        );
    }
}
