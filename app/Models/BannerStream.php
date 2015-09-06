<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerStream extends Model
{
    protected $table = 'banner_stream';

    protected $fillable = ['banner_id', 'stream_id', 'transfer_id', 'viewers', 'minutes', 'amount', 'status', 'client_comment', 'twitcher_comment'];

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function userTransfer()
    {
        return $this->belongsTo(UserTransfer::class);
    }

}
