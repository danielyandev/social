<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAuthUser(Request $request)
    {
        return UserResource::make($request->user());
    }

    public function search(Request $request)
    {
        if (!$request->phrase){
            return [];
        }
        $users = User::where('name', 'like', $request->phrase . '%')
            ->orWhere('surname', 'like', $request->phrase . '%')
            ->paginate(5);
        return UserCollection::make($users);
    }

    public function show(User $user)
    {
        return UserResource::make($user);
    }
}
