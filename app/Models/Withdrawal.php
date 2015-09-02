<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $table = 'withdrawals';

    protected $fillable = ['user_id', 'transaction_number', 'title', 'amount', 'currency', 'response', 'approved', 'admin_id'];

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

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}
