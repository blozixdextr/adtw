<?php

namespace App\Models\Mappers;

use App\Models\User;
use Config;
use Session;
use App\Models\Mappers\LogMapper;
use App\Models\Mappers\NotificationMapper;

class CouponMapper
{

    public static function usedByUser(User $user, $code)
    {
        return true;
    }

    public static function paidByUser(User $user, $code)
    {
        return true;
    }

    public static function isAvailable(User $user, $code)
    {
        if (self::isValid($code)) {
            if (!self::usedByUser($user, $code)) {
                return true;
            }
        }

        return false;
    }

    public static function isValid($code)
    {
        return true;
    }

}