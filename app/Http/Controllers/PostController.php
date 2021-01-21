<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\StorePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * @OA\Get(
     * path="/users/{user}/posts",
     * summary="Get user's feed posts",
     * tags={"feed"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *      name="user",
     *      description="User id",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(ref="#/components/schemas/PostCollection")
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated"),
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="User not found"
     * )
     *
     * )
     *
     * Display a listing of the resource.
     * @param Request $request
     * @param User $user
     * @return PostCollection
     */
    public function index(Request $request, User $user)
    {
        $authUser = Auth::user();
        $take_only_public = true;

        // User requested own or friend's feed
        if ($authUser->id == $user->id || $authUser->hasFriend($user)){
            $take_only_public = false;
        }

        $posts = $user->posts()->with('postedUser');
        if ($take_only_public){
            $posts->public();
        }
        if ($request->skip){
            $posts->skip($request->skip);
        }
        $posts = $posts->orderByDesc('id')->take(10)->get();

        return PostCollection::make($posts);
    }

    /**
     * @OA\Post(
     * path="/users/{user}/posts",
     * summary="Post to user's feed",
     * tags={"feed"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *      name="user",
     *      description="User id",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(ref="#/components/schemas/PostResource")
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="Bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="This user is not in friends list"),
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthenticated error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated"),
     *        )
     *     ),
     * @OA\Response(
     *    response=403,
     *    description="Unauthorized error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="This action is unauthorized"),
     *    )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Validation errors"
     * )
     *
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param StorePostRequest $request
     * @param $user_id
     * @return PostResource
     */
    public function store(StorePostRequest $request, $user_id)
    {
        $post = new Post();
        $post->fill($request->only(['text', 'is_public']));
        $post->posted_user_id = Auth::id();
        $post->user_id = $user_id;
        $post->save();

        $post->load('postedUser');

        return PostResource::make($post);
    }
}
