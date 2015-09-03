<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StreamTimelog extends Model
{
    protected $table = 'banner_stream';

    protected $fillable = ['stream_id', 'timeslot_start', 'timeslot_end', 'viewers', 'status', 'screenshot', 'response'];

}