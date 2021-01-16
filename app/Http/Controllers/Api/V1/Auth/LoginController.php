<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\ApiController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends ApiController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => ['required', 'string'],
            'password' => ['required', 'string']
        ];
        $this->validate($request, $rules);

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->sendError('Invalid credentials', ['User not found']);
        }

        return $this->sendSuccess('Successfully logged in', $this->getToken($request));
    }

    public function getToken(Request $request)
    {
        $route = config('app.url') . '/oauth/token';
        $params = [
            'grant_type' => 'password',
            'client_id' => config('passport.password_client_id'),
            'client_secret' => config('passport.password_client_secret'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ];

        $response = Http::post($route, $params);
        return $response->object();
    }
}
