<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['text', 'user_id', 'is_public'];

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
