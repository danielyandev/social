<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
