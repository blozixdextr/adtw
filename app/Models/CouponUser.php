<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponUser extends Model
{
    use SoftDeletes;

    protected $table = 'coupon_user';

    protected $fillable = ['user_id', 'coupon_id', 'is_paid'];

    protected $dates = ['deleted_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }


}

