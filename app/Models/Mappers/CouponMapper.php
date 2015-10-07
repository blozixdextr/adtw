<?php

namespace App\Models\Mappers;

use App\Models\User;
use App\Models\Coupon;
use App\Models\CouponUser;
use Config;
use Session;
use App\Models\Mappers\LogMapper;
use App\Models\Mappers\NotificationMapper;

class CouponMapper
{
    public static function track(User $user, Coupon $coupon)
    {
        return $user->coupons()->attach($coupon->id);
    }

    public static function usedByUser(User $user, Coupon $coupon)
    {
        $couponCount = CouponUser::whereUserId($user->id)->whereCouponId($coupon->id)->count();
        if ($couponCount > 0) {
            return true;
        }

        return false;
    }

    public static function paidByUser(User $user, Coupon $coupon)
    {
        $couponCount = CouponUser::whereUserId($user->id)->whereCouponId($coupon->id)->whereIsPaid(false)->count();
        if ($couponCount > 0) {
            return false;
        }

        return true;
    }

    public static function pay(User $user, Coupon $coupon)
    {
        $couponPivot = CouponUser::whereUserId($user->id)->whereCouponId($coupon->id)->whereIsPaid(false)->first();
        $couponPivot->is_paid = true;

        return $couponPivot->save();
    }

    public static function byCode($code)
    {
        return Coupon::whereCode($code)->first();
    }

    public static function isValid($code)
    {
        $couponCount = Coupon::whereCode($code)->count();
        if ($couponCount > 0) {
            return true;
        }

        return false;
    }

}