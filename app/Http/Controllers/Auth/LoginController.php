<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @OA\Post(
     * path="/login",
     * summary="Sign in",
     * description="Login by email, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully logged in"),
     *       @OA\Property(property="data", type="object", example={
     *                                          "access_token": "Generated token",
     *                                          "expires_in": "Expiration time in seconds",
     *                                          "token_type": "Bearer",
     *                                          "refresh_token": "Generated refresh token"
     *                                      })
     *
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Invalid credentials"),
     *       @OA\Property(property="errors", type="object", example={ "email": {"Invalid email and/or password"} })
     *        )
     *     )
     * )
     *
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
            return $this->sendError('The given data was invalid.', [
                'email' => 'Invalid email and/or password'
            ], 422);
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
