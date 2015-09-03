<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    protected $fillable = ['client_id', 'twitcher_id', 'type', 'title', 'description', 'file', 'is_active', 'status', 'amount_limit'];

}

