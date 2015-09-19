<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stream extends Model
{
    use SoftDeletes;

    protected $table = 'streams';

    protected $fillable = ['user_id', 'title', 'time_start', 'time_end'];

    protected $dates = ['time_start', 'time_end', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function banners()
    {
        return $this->belongsToMany(Banner::class)->withPivot(['transfer_id', 'status', 'viewers', 'minutes', 'amount', 'client_comment', 'twitcher_comment']);
    }

    public function clientsBanners(User $client)
    {
        return $this->belongsToMany(Banner::class)->where('client_id', $client->id)
            ->withPivot(['transfer_id', 'status', 'viewers', 'minutes', 'amount', 'client_comment', 'twitcher_comment']);
    }

    public function clients()
    {
        return $this->hasManyThrough(User::class, Banner::class, 'banner_id', 'client_id');
    }

    public function timelogs()
    {
        return $this->hasMany(StreamTimelog::class);
    }
}