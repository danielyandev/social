<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\ApiController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends ApiController
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Register new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        $this->validate($request, $rules);

        $user = new User();
        $user->fill($request->only(array_keys($rules)));
        $user->password = Hash::make($user->password);
        $user->save();

        return $this->sendSuccess('Successfully registered', $user);
    }
}
