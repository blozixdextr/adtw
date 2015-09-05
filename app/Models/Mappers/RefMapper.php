<?php

namespace App\Models\Mappers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Ref;
use Request;
use Auth;

class RefMapper
{
    public static function type($type, $rootOnly = true)
    {
        $refs = Ref::whereType($type);
        if ($rootOnly) {
            $refs->wherePid(0);
        }

        return $refs->get();
    }

}