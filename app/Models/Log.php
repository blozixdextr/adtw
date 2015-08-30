<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = ['ip', 'user_id', 'type', 'key_value', 'additional_value', 'review_date', 'var'];

    protected $dates = ['review_date'];

    public function setVarAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['var'] = serialize($value);
        } else {
            $this->attributes['var'] = null;
        }
    }

    public function getVarAttribute($value)
    {
        if ($value !== null) {
            return unserialize($value);
        } else {
            return null;
        }
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}

