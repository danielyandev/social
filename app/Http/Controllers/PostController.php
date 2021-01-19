<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param User $user
     * @return PostCollection
     */
    public function index(User $user)
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
        $posts = $posts->orderByDesc('id')->paginate(10);

        return PostCollection::make($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return PostResource|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, $user_id)
    {
        $rules = [
            'text' => ['required', 'string'],
            'is_public' => ['required', 'boolean']
        ];
        $this->validate($request, $rules);

        if (Auth::user()->hasFriend($user_id)){
            return $this->sendError('This user is not in friends list');
        }

        $post = new Post();
        $post->fill($request->only(['text', 'is_public']));
        $post->posted_user_id = Auth::id();
        $post->user_id = $user_id;
        $post->save();

        $post->load('postedUser');

        return PostResource::make($post);
    }
}
