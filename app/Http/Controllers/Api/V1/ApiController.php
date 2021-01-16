<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Api\V1\ResponseHelper;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * @param array $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSuccess($message = '', $data = [])
    {
        return ResponseHelper::success($message, $data);
    }

    /**
     * @param string $message
     * @param array $errors
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($message = '', $errors = [], $status = 400)
    {
        return ResponseHelper::error($message, $errors, $status);
    }
}
