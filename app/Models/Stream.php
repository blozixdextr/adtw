<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    protected $table = 'streams';

    protected $fillable = ['user_id', 'title', 'time_start', 'time_end'];

    protected $dates = ['time_start', 'time_end'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function banners()
    {
        return $this->belongsToMany(Banner::class)->withPivot(['transfer_id', 'status', 'client_comment', 'twitcher_comment']);
    }

}