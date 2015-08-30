<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Location;

class UserProfile extends Model
{
    protected $table = 'user_profiles';

    protected $fillable = [ 'first_name', 'last_name', 'sex', 'dob', 'about', 'avatar', 'mobile', 'paypal', 'confirmed_paypal'];

    protected $dates = ['dob'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}


