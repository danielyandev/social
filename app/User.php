<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

/**
 *
 * @OA\Schema(
 *      @OA\Xml(name="User"),
 *      @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *      @OA\Property(property="name", type="string", maxLength=255, example="John"),
 *      @OA\Property(property="surname", type="string", maxLength=255, example="Doe"),
 *      @OA\Property(property="avatar", type="string", readOnly="true", example="http://site.com/img/avatar.png"),
 *      @OA\Property(property="friends_count", type="integer", readOnly="true", example="355"),
 *      @OA\Property(property="friend_requests_count", type="integer", readOnly="true", example="15"),
 *      @OA\Property(property="email", type="string", readOnly="true", format="email", description="User unique email address", example="user@gmail.com"),
 *      @OA\Property(property="created_at", type="string", readOnly="true", format="date-time", description="Registration date and time", example="2021-01-15 12:59:20"),
 *      @OA\Property(property="updated_at", type="string", readOnly="true", format="date-time", description="Last updated date and time", example="2021-01-20 04:25:15"),
 *      @OA\Property(property="join_date", type="string", readOnly="true", description="Registration date in human radable format", example="10 days ago")
 * )
 *
 * Class User
 *
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'email_verified_at', 'email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar', 'join_date'];

    public function getAvatarAttribute()
    {
        return asset('/img/default.png');
    }

    public function getJoinDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getFriendRequestsAttribute()
    {
        return $this->friendRequests()->get();
    }

    public function getFriendRequestsCountAttribute()
    {
        return $this->friendRequests()->count();
    }

    /**
     * Simulate relation access, allow to access by $user->relationships,
     * because relationships method doesn't return laravel defined relation
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelationshipsAttribute()
    {
        return $this->relationships()->get();
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFriendsAttribute()
    {
        return $this->friends()->get();
    }

    public function getFriendsCountAttribute()
    {
        return $this->friends()->count();
    }

    /**
     * Questionable implementation of model relation,
     * but only this matches requirements
     *
     * Return users query who requested or were requested for relationship
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function relationships()
    {
        $id = $this->id;
        return $this->query()
            ->selectRaw('users.*, relationships.id as relationship_id, relationships.status, relationships.sender_user_id, relationships.receiver_user_id')
            ->join('relationships', function ($join) use ($id){
                $join->on('relationships.sender_user_id', '=', 'users.id')
                    ->orOn('relationships.receiver_user_id', '=', 'users.id');
            })
            ->where(function ($query) use ($id){
                $query->where('relationships.sender_user_id', $id)
                    ->orWhere('relationships.receiver_user_id', $id);
            })
            ->where('users.id', '!=', $id);
    }

    /**
     * Append additional fields to user to determine relationship status
     *
     * @param null|int $other_user_id
     * @return $this
     */
    public function appendRelationshipAttributes($other_user_id = null)
    {
        $other_user_id = $other_user_id ?: Auth::id();
        $relationship = $this->relationships()->find($other_user_id);

        $relationship_status = null;
        $is_sender = null;
        $relationship_id = null;
        if ($relationship){
            $relationship_status = Relationship::getStatusLabel($relationship->status);
            $is_sender = $relationship->sender_user_id == $this->id;
            $relationship_id = $relationship->relationship_id;
        }

        $this->attributes['relationship'] =  [
            'id' => $relationship_id,
            'status' => $relationship_status,
            'is_sender' => $is_sender,
        ];
        return $this;
    }

    /**
     * Accepted relations
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function friends()
    {
        return $this->relationships()->where('status', Relationship::$statuses['accepted']);
    }

    /**
     * Requested users
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function friendRequests()
    {
        return $this->relationships()->where('receiver_user_id', $this->id)->where('status', Relationship::$statuses['pending']);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function hasFriend($user)
    {
        $user_id = $user instanceof User ? $user->id : $user;
        return $this->friends()->where('users.id', $user_id)->exists();
    }

}
