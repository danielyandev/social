<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Social Network",
 *    version="1.0.0"
 * )
 * @OA\Server(url=SWAGGER_API_URL)
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Use /login endpoint to get access token",
 *     name="Bearer",
 *     in="header",
 *     scheme="bearer",
 *     securityScheme="bearer"
 * )
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSuccess($message = '', $data = [])
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
    public function sendError($message = '', $errors = [], $status = 400)
    {
        return response()->json(
            compact('errors', 'message'),
            $status
        );
    }
}
