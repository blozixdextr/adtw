<?php

namespace App\Models\Mappers;

use App\Models\User;
use App\Models\Referral;

class ReferralMapper
{
    public static function referrerLog(User $user, $limit = 50)
    {
        return Referral::whereReferralId($user->id)->paginate($limit);
    }

    public static function referrals(User $user, $limit = 50)
    {
        return User::whereReferralId($user->id)->paginate($limit);
    }

}