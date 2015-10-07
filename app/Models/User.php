<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

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
    protected $fillable = ['name', 'email', 'password', 'provider', 'oauth_id', 'last_activity', 'twitch_profile', 'referral_id', 'nickname'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['last_activity', 'twitch_updated', 'deleted_at'];


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

    public function setTwitchChannelAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['twitch_channel'] = serialize($value);
        } else {
            $this->attributes['twitch_channel'] = null;
        }
    }

    public function getTwitchChannelAttribute($value)
    {
        if ($value !== null) {
            return unserialize($value);
        } else {
            return null;
        }
    }

    public static function scopeOauth($query, $oauthId, $provider)
    {
        return $query->where('provider', '=', $provider)->where('oauth_id', '=', $oauthId);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function authToken()
    {
        return $this->hasMany(UserAuthToken::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function banners()
    {
        if ($this->type == 'client') {
            $foreignFieldName = 'client_id';
        } else {
            $foreignFieldName = 'twitcher_id';
        }
        return $this->hasMany(Banner::class, $foreignFieldName);
    }

    public function refs()
    {
        return $this->belongsToMany(Ref::class);
    }

    public function games()
    {
        return $this->refs()->whereType('game');
    }

    public function bannerTypes()
    {
        return $this->refs()->whereType('banner_type');
    }

    public function language()
    {
        return $this->belongsTo(Ref::class, 'language_id');
    }

    public function availableBalance()
    {
        return $this->balance - $this->balance_blocked;
    }

    public function streams()
    {
        if ($this->type == 'client') {
            return $this->hasManyThrough(Stream::class, Banner::class, 'client_id', 'banner_id');
        } else {
            return $this->hasMany(User::class);
        }
    }

    public function getNameAttribute($value)
    {
        return anonymizeEmail($value);
    }
}
