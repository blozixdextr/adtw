<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    protected $table = 'payments';

    protected $fillable = ['user_id', 'merchant', 'transaction_number', 'title', 'amount', 'currency', 'response', 'cart'];

    /**
     * Get the user of this profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getResponseAttribute($value)
    {
        return unserialize($value);
    }

    public function setResponseAttribute($value)
    {
        $this->attributes['response'] = serialize($value);
    }

    public function getCartAttribute($value)
    {
        return unserialize($value);
    }

    public function setCartAttribute($value)
    {
        $this->attributes['cart'] = serialize($value);
    }

}
