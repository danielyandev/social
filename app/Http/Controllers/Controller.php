<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $response;

    public function __construct()
    {
        $this->response = ApiHelper::getResponseSender();
    }

    public function sendSuccess($message = '', $data = [])
    {
        return $this->response->success($message, $data);
    }

    public function sendError($message = '', $data = [], $status = 400)
    {
        return $this->response->error($message, $data, $status);
    }
}
