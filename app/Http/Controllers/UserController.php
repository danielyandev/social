<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAuthUser(Request $request)
    {
        $user = $request->user()->append('friends_count', 'friend_requests_count');
        return UserResource::make($user);
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
        $user->appendRelationshipAttributes();
        $user->append('friends_count');
        return UserResource::make($user);
    }
}
