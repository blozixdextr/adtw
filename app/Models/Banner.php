<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    protected $fillable = ['client_id', 'twitcher_id', 'type_id', 'title', 'description', 'file', 'is_active', 'status', 'amount_limit'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function twitcher()
    {
        return $this->belongsTo(User::class, 'twitcher_id');
    }

    public function streams()
    {
        return $this->belongsToMany(Stream::class)->withPivot(['transfer_id', 'status', 'client_comment', 'twitcher_comment']);
    }

    public function type()
    {
        return $this->belongsTo(Ref::class, 'type_id');
    }

    public function totalAmount()
    {
        $sum = BannerStream::whereBannerId($this->id)->sum('amount');

        return $sum;
    }

}

