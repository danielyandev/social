<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Social Network",
 *    version="1.0.0"
 * )
 * @OA\Server(url="http://social.loc/api")
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendSuccess($message = '', $data = [])
    {
        return ApiHelper::sendSuccess($message, $data);
    }

    public function sendError($message = '', $data = [], $status = 400)
    {
        return ApiHelper::sendError($message, $data, $status);
    }
}
