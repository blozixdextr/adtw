<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Config;

class StreamTimelog extends Model
{
    protected $table = 'banner_stream';

    protected $fillable = ['stream_id', 'timeslot_start', 'timeslot_end', 'viewers', 'status', 'screenshot', 'response'];

    protected $dates = ['timeslot_start', 'timeslot_end'];

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function setResponseAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['response'] = serialize($value);
        } else {
            $this->attributes['response'] = null;
        }
    }

    public function getResponseAttribute($value)
    {
        if ($value !== null) {
            return unserialize($value);
        } else {
            return null;
        }
    }

    public function duration() {
        return $this->timeslot_start->diffInMinutes($this->timeslot_end);
    }

    public function price() {
        $minutes = $this->duration();
        $price = $minutes/60 * Config::get('banner.price');

        return $price;
    }

}