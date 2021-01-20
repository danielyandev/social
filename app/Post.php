<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 *      @OA\Xml(name="Post"),
 *      @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *      @OA\Property(property="user_id", type="integer", readOnly="true", example="1"),
 *      @OA\Property(property="posted_user_id", type="integer", readOnly="true", example="2"),
 *      @OA\Property(property="posted_user", type="object", ref="#/components/schemas/User"),
 *      @OA\Property(property="is_public", type="boolean", readOnly="true", example="true"),
 *      @OA\Property(property="text", type="string", example="Post text"),
 *      @OA\Property(property="created_at", type="string", readOnly="true", format="date-time", description="Registration date and time", example="2021-01-15 12:59:20"),
 *      @OA\Property(property="updated_at", type="string", readOnly="true", format="date-time", description="Last updated date and time", example="2021-01-20 04:25:15"),
 * )
 *
 * Class Post
 *
 */
class Post extends Model
{
    protected $fillable = ['text', 'user_id', 'is_public'];

    protected $casts = [
        'is_public' => 'boolean'
    ];
    /**
     * Encrypt text field
     *
     * @param $text
     */
    public function setTextAttribute($text)
    {
        $this->attributes['text'] = encrypt($text);
    }

    /**
     * Decrypt text field
     *
     * @param $text
     * @return mixed|null
     */
    public function getTextAttribute($text)
    {
        try {
            return decrypt($text);
        }catch (\Exception $exception){
            return null;
        }
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function postedUser()
    {
        return $this->belongsTo(User::class, 'posted_user_id');
    }

    /**
     * Take only public posts
     *
     * @param $query
     * @return mixed
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
