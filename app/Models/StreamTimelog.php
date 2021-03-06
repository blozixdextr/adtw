<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Config;
use Illuminate\Database\Eloquent\SoftDeletes;

class StreamTimelog extends Model
{
    use SoftDeletes;

    protected $table = 'stream_timelogs';

    protected $fillable = ['stream_id', 'timeslot_start', 'timeslot_end', 'viewers', 'status', 'amount', 'screenshot', 'response'];

    protected $dates = ['timeslot_start', 'timeslot_end', 'deleted_at'];

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

    public function duration()
    {
        return $this->timeslot_start->diffInMinutes($this->timeslot_end);
    }

    public function price()
    {
        return $this->amount;
    }

    public function calcAmount()
    {
        $minutes = $this->duration();
        $language = '';
        if ($this->stream->user->language_id > 0) {
            $language = strtolower($this->stream->user->language->title);
        }
        $prices = Config::get('banner.prices');
        if (isset($prices[$language])) {
            $price = $prices[$language];
        } else {
            $price = $prices['default'];
        }
        $pricePerMinute = $price / 60;
        $price = $minutes * $this->viewers * $pricePerMinute;

        $this->amount = $price;
        $this->save();

        return $price;
    }

}