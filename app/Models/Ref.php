<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ref extends Model
{
    use SoftDeletes;

    protected $table = 'refs';

    protected $fillable = ['pid', 'type', 'title'];

    protected $dates = ['deleted_at'];

    public function children()
    {
        return $this->hasMany(Ref::class, 'pid');
    }

    public function parent()
    {
        return $this->belongsTo(Ref::class, 'pid');
    }

    public function siblings()
    {
        if ($this->pid > 0) {
            return $this->parent->children;
        } else {
            return $this->type($this->type)->get();
        }
    }

    public function scopeTypeRoot($query, $type) {
        return $query->where('type', $type)->where('pid', 0);
    }

}

