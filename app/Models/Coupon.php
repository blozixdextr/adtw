<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    protected $table = 'coupons';

    protected $fillable = ['code', 'title', 'subtitle'];

    protected $dates = ['deleted_at'];


    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_paid');
    }


}

