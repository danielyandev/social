<?php

namespace App\Http\Controllers;

use App\Http\Resources\RelationshipResource;
use App\Http\Resources\UserCollection;
use App\Relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelationshipController extends Controller
{
    public function index()
    {
        $requestedUsers = Auth::user()->friendRequests;
        foreach ($requestedUsers as $user){
            $user->appendRelationshipAttributes();
        }
        return UserCollection::make($requestedUsers);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return RelationshipResource|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'user_id' => ['required', 'integer']
        ];
        $this->validate($request, $rules);
        $exists = Relationship::whereIds(Auth::id(), $request->user_id)->exists();
        if ($exists){
            return $this->sendError('Relationship had already been created');
        }

        $relationship = Relationship::create([
            'sender_user_id' => Auth::id(),
            'receiver_user_id' => $request->user_id,
            'status' => Relationship::$statuses['pending']
        ]);

        return RelationshipResource::make($relationship);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Relationship $relationship
     * @return RelationshipResource|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Relationship $relationship)
    {
        $rules = [
            'status' => ['string', 'in:accepted,rejected']
        ];
        $this->validate($request, $rules);

        if ($relationship->status == $request->status){
            return $this->sendError('Relationship had already been ' . $request->status);
        }

        $relationship->status = Relationship::$statuses[$request->status];
        $relationship->save();

        return RelationshipResource::make($relationship);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Relationship $relationship
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Relationship $relationship)
    {
        $relationship->delete();
        return $this->sendSuccess('Relationship successfully deleted');
    }
}
