<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 *      @OA\Xml(name="Relationship"),
 *      @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *      @OA\Property(property="sender_user_id", type="integer", readOnly="true", example="1"),
 *      @OA\Property(property="receiver_user_id", type="integer", readOnly="true", example="2"),
 *      @OA\Property(property="status", type="string", readOnly="true", example="pending"),
 * )
 *
 * Class Relationship
 */

class Relationship extends Model
{
    public static $statuses = [
        'pending' => 0,
        'accepted' => 1,
        'rejected' => 2,
    ];

    public $timestamps = false;

    protected $fillable = ['sender_user_id', 'receiver_user_id', 'status'];

    public static function getStatusLabel($status)
    {
        return array_keys(self::$statuses)[$status];
    }

    public function scopeWhereIds($query, $user_id, $friend_id)
    {
        $ids = [$user_id, $friend_id];
        return $query->whereIn('sender_user_id', $ids)->whereIn('receiver_user_id', $ids);
    }

    public function getStatusAttribute($status)
    {
        return self::getStatusLabel($status);
    }

    public function hasMember($user)
    {
        $user_id = $user instanceof User ? $user->id : $user;
        return in_array($user_id, [$this->sender_user_id, $this->receiver_user_id]);
    }
}
