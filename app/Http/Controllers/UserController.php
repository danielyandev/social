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
    /**
     *
     *
     * @param Request $request
     * @return UserResource
     */
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
        $users = User::where('id', '!=', Auth::id())->where(function ($query) use ($request){
            $spaced = explode(' ', $request->phrase);
            $name = $spaced[0];
            $surname = $spaced[1] ?? null;

            // find by any of columns
            $query->where('name', 'like', $request->phrase . '%')
                ->orWhere('surname', 'like', $request->phrase . '%');

            // find by name and surname search
            if ($surname){
                $query->orWhere(function ($q) use ($name, $surname){
                    $q->where('name', $name)->where('surname', 'like', $surname . '%');
                });
                $query->orWhere(function ($q) use ($name, $surname){
                    $q->where('surname', $name)->where('name', 'like', $surname . '%');
                });
            }
        });

        if ($request->friends){
            $friend_ids = Auth::user()->friends()->pluck('id')->toArray();
            $users->whereIn('id', $friend_ids);
        }

        $users = $users->paginate(10);
        return UserCollection::make($users);
    }

    public function show(User $user)
    {
        $user->appendRelationshipAttributes();
        $user->append('friends_count');
        if ($user->id == Auth::id()){
            $user->append('friend_requests_count');
        }
        return UserResource::make($user);
    }

    public function getFriends()
    {
        $friends = Auth::user()->friends()->paginate(10);
        return UserCollection::make($friends);
    }
}
