<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerStream extends Model
{
    protected $table = 'banner_stream';

    protected $fillable = ['banner_id', 'stream_id', 'transfer_id', 'status', 'client_comment', 'twitcher_comment'];

}
