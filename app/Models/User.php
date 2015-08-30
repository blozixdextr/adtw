<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $dates = ['last_activity'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'provider', 'oauth_id', 'last_visit', 'twitch_profile'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public function setTwitchProfileAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['twitch_profile'] = serialize($value);
        } else {
            $this->attributes['twitch_profile'] = null;
        }
    }

    public function getTwitchProfileAttribute($value)
    {
        if ($value !== null) {
            return unserialize($value);
        } else {
            return null;
        }
    }
}
