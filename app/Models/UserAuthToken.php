<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAuthToken extends Model
{
    protected $table = 'user_auth_token';

    protected $fillable = [ 'ip', 'user_id', 'token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}


