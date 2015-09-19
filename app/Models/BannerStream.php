<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannerStream extends Model
{
    use SoftDeletes;

    protected $table = 'banner_stream';

    protected $fillable = ['banner_id', 'stream_id', 'transfer_id', 'viewers', 'minutes', 'amount', 'status', 'client_comment', 'twitcher_comment'];

    protected $dates = ['deleted_at'];

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
