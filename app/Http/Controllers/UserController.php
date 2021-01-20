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
     * @OA\Get(
     * path="/user",
     * summary="Get auth user",
     * tags={"user"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="object", ref="#/components/schemas/User")
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     )
     *
     * )
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

    /**
     * @OA\Get(
     * path="/user/search?phrase={phrase}&friends={friends}&page={page}",
     * summary="Search in all users",
     * tags={"users"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *      name="phrase",
     *      description="Phrase to search. Formats: 'name', 'surname', 'name surname', 'surname name'",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *          type="string"
     *      )
     *  ),
     * @OA\Parameter(
     *      name="friends",
     *      description="Phrase to search in friends list. Formats: 'name', 'surname', 'name surname', 'surname name'",
     *      required=false,
     *      in="path",
     *      @OA\Schema(
     *          type="boolean",
     *          default=false
     *      )
     *  ),
     * @OA\Parameter(
     *      name="page",
     *      description="Paginates data",
     *      required=false,
     *      in="path",
     *      @OA\Schema(
     *          type="integer",
     *          default=1
     *      )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(ref="#/components/schemas/UserCollection")
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated"),
     *        )
     *     )
     *
     * )
     *
     * @return UserCollection|array
     */
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

    /**
     * @OA\Get(
     * path="/user/friends?page={page}",
     * summary="Get auth user friends",
     * tags={"user"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *      name="page",
     *      description="Paginates data",
     *      required=false,
     *      in="path",
     *      @OA\Schema(
     *          type="integer",
     *          default=1
     *      )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(ref="#/components/schemas/UserCollection")
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated"),
     *        )
     *     )
     *
     * )
     *
     * @return UserCollection
     */
    public function getFriends()
    {
        $friends = Auth::user()->friends()->paginate(10);
        return UserCollection::make($friends);
    }
}
