<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTransfer extends Model
{
    protected $table = 'user_transfers';

    protected $fillable = [ 'buyer_id', 'seller_id', 'title', 'amount', 'currency', 'cart'];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
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
