<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = ['user_id', 'title', 'subtitle', 'type', 'seen_at'];

    protected $dates = ['seen_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
