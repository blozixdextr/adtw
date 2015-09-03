<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    protected $table = 'streams';

    protected $fillable = ['user_id', 'title', 'time_start', 'time_end'];

}