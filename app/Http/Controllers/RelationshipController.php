<?php

namespace App\Http\Controllers;

use App\Http\Requests\Relationships\DestroyRelationshipRequest;
use App\Http\Requests\Relationships\StoreRelationshipRequest;
use App\Http\Requests\Relationships\UpdateRelationshipRequest;
use App\Http\Resources\RelationshipResource;
use App\Http\Resources\UserCollection;
use App\Relationship;
use Illuminate\Support\Facades\Auth;

class RelationshipController extends Controller
{

    /**
     * @OA\Get(
     * path="/relationships",
     * summary="Get auth user's friend requests",
     * tags={"friend requests"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *          @OA\Property(property="data", type="array",
     *              @OA\Items(type="object", example={
     *                                      "id": 2,
     *                                      "name": "John",
     *                                      "surname": "Doe",
     *                                      "avatar": "http://site.com/img/default.png",
     *                                      "join_date": "4 days ago",
     *                                      "receiver_user_id": 3,
     *                                      "relationship": {"id": 2, "status": "pending", "is_sender": true},
     *                                      "relationship_id": 2,
     *                                      "sender_user_id": 2,
     *                                      "status": 0,
     *                                      "created_at": "2021-01-16T13:49:17",
     *                                      "updated_at": "2021-01-16T13:49:17"
     *                              })
     *          ),
     *       )
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
    public function index()
    {
        // todo paginate, there can be many requests in the future
        $requestedUsers = Auth::user()->friendRequests;
        foreach ($requestedUsers as $user){
            $user->appendRelationshipAttributes();
        }
        return UserCollection::make($requestedUsers);
    }

    /**
     * @OA\Post(
     * path="/relationships",
     * summary="Send friend request",
     * tags={"friend requests"},
     * security={},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user id",
     *    @OA\JsonContent(
     *       required={"user_id"},
     *       @OA\Property(property="user_id", type="integer", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Success response",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="integer", example="1 | Relationship id"),
     *       @OA\Property(property="receiver_user_id", type="integer", example="2"),
     *       @OA\Property(property="sender_user_id", type="integer", example="1"),
     *       @OA\Property(property="status", type="integer", example="pending"),
     *
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
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param StoreRelationshipRequest $request
     * @return RelationshipResource
     */
    public function store(StoreRelationshipRequest $request)
    {
        $relationship = Relationship::create([
            'sender_user_id' => Auth::id(),
            'receiver_user_id' => $request->user_id,
            'status' => Relationship::$statuses['pending']
        ]);

        return RelationshipResource::make($relationship);
    }

    /**
     * @OA\Put(
     * path="/relationships/{relationship}",
     * summary="Respond to friend request",
     * tags={"friend requests"},
     * security={},
     * @OA\Parameter(
     *      name="relationship",
     *      description="Relationship id",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *  ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass status",
     *    @OA\JsonContent(
     *       required={"user_id"},
     *       @OA\Property(property="status", example="accepted",
     *          @OA\Schema(type="string", enum={"accepted", "rejected"})
     *      ),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success response",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="integer", example="1 | Relationship id"),
     *       @OA\Property(property="receiver_user_id", type="integer", example="2"),
     *       @OA\Property(property="sender_user_id", type="integer", example="1"),
     *       @OA\Property(property="status", type="integer", example="accepted"),
     *
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
     *    response=404,
     *    description="Relationship not found"
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Validation errors"
     * )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param UpdateRelationshipRequest $request
     * @param Relationship $relationship
     * @return RelationshipResource|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateRelationshipRequest $request, Relationship $relationship)
    {
        $relationship->status = Relationship::$statuses[$request->status];
        $relationship->save();

        return RelationshipResource::make($relationship);
    }

    /**
     * @OA\Delete(
     * path="/relationships/{relationship}",
     * summary="Unfriend",
     * tags={"friend requests"},
     * security={},
     * @OA\Parameter(
     *      name="relationship",
     *      description="Relationship id",
     *      required=true,
     *      in="path",
     *      @OA\Schema(
     *          type="integer"
     *      )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Success response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Relationship successfully deleted"),
     *       @OA\Property(property="data", type="object", example={}),
     *
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
     *    response=404,
     *    description="Relationship not found"
     * ),
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param Relationship $relationship
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(DestroyRelationshipRequest $request, Relationship $relationship)
    {
        $relationship->delete();
        return $this->sendSuccess('Relationship successfully deleted');
    }
}
