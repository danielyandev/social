<?php


namespace App\Contracts\Api\V1;

use App\Contracts\SendResponse as SendResponseContract;

class SendResponse implements SendResponseContract
{

    /**
     * @param array $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($message = '', $data = [])
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
    public function error($message = '', $errors = [], $status = 400)
    {
        return response()->json(
            compact('errors', 'message'),
            $status
        );
    }
}
