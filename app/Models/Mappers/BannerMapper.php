<?php

namespace App\Models\Mappers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Ref;
use App\Models\Banner;
use Request;
use Auth;
use Config;
use App\Models\Mappers\LogMapper;
use App\Models\Mappers\NotificationMapper;

class BannerMapper
{

    protected $maxBannerPerTwitcher = 5;

    public static function twitcher(User $user, $bannerType)
    {
        $banners = Banner::whereTwitcherId($user->id)->whereTypeId($bannerType);

        return $banners->get();
    }

    public static function acceptBanner(Banner $banner)
    {
        $banner->is_active = 1;
        $banner->status = 'accepted';
        $banner->save();

        LogMapper::log('banner_accept', $banner->id);
        NotificationMapper::bannerAccept($banner);

        return $banner;
    }

    public static function declineBanner(Banner $banner)
    {
        $banner->is_active = 0;
        $banner->status = 'declined';
        $banner->save();

        LogMapper::log('banner_decline', $banner->id);
        NotificationMapper::bannerDecline($banner);

        return $banner;
    }

    public static function twitcherFree(User $user, $bannerType)
    {
        $banners = Banner::whereTwitcherId($user->id)->whereTypeId($bannerType)->count();
        $maxBannerPerTwitcher = Config::get('banner.twitcher_limit');

        return $banners < $maxBannerPerTwitcher;
    }

    public static function addForTwitcher(User $twitcher, User $client, Ref $bannerType, $file, $limit)
    {
        $banner = Banner::create([
            'client_id' => $client->id,
            'twitcher_id' => $twitcher->id,
            'type_id' => $bannerType->id,
            'title' => $client->name.' '.$bannerType->title,
            'file' => $file,
            'is_active' => 0,
            'status' => 'waiting',
            'amount_limit' => $limit,
        ]);

        return $banner;
    }

}