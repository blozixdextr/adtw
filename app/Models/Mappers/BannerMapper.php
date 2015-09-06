<?php

namespace App\Models\Mappers;

use App\Models\BannerStream;
use App\Models\StreamTimelog;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Ref;
use App\Models\Banner;
use App\Models\Stream;
use Request;
use Auth;
use Config;
use Session;
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

    public static function activeTwitcher(User $user, $bannerType = 0)
    {
        $banners = Banner::whereTwitcherId($user->id)->whereIsActive(1);
        if ($bannerType > 0) {
            $banners->whereTypeId($bannerType);
        }

        return $banners->get();
    }

    public static function activeTwitcherCount(User $user, $bannerType = 0)
    {
        $banners = Banner::whereTwitcherId($user->id)->whereIsActive(1);
        if ($bannerType > 0) {
            $banners->whereTypeId($bannerType);
        }

        return $banners->count();
    }

    public static function activeClient(User $user, $bannerType = 0)
    {
        $banners = Banner::whereClientId($user->id)->whereIsActive(1);
        if ($bannerType > 0) {
            $banners->whereTypeId($bannerType);
        }

        return $banners->get();
    }

    public static function activeClientCount(User $user, $bannerType = 0)
    {
        $banners = Banner::whereClientId($user->id)->whereIsActive(1);
        if ($bannerType > 0) {
            $banners->whereTypeId($bannerType);
        }

        return $banners->count();
    }

    public static function bannersToStream(Stream $stream, $banners)
    {
        $bannersClean = [];
        foreach ($banners as $b) {
            $bannersClean[] = $b->id;
        }
        $stream->banners()->sync($bannersClean);
    }

    /**
     * @param User $user
     * @param $banners
     * @return Stream
     */
    public static function getStream(User $user, $banners)
    {
        $streamId = Session::get('stream', false);
        if (!$streamId) {
            $stream = Stream::create([
               'user_id' => $user->id,
               'time_start' => \Carbon\Carbon::now()
            ]);
            self::bannersToStream($stream, $banners);
            Session::set('stream', $stream->id);
        } else {
            $stream = Stream::findOrFail($streamId);
        }

        return $stream;
    }

    public static function trackBanner(Banner $banner, Stream $stream, StreamTimelog $streamTimelog) {
        $price = $streamTimelog->price();
        $duration = $streamTimelog->duration();
        $pivotBannerStream = BannerStream::whereBannerId($banner->id)->whereStreamId($stream->id)->first();
        $pivotBannerStream->minutes = $pivotBannerStream->minutes + $duration;
        $pivotBannerStream->viewers = $pivotBannerStream->viewers + $streamTimelog->viewers;
        $pivotBannerStream->amount = $pivotBannerStream->amount + $price;
        $pivotBannerStream->save();

        $client = $banner->client;
        $client->balance_blocked = $client->balance_blocked + $price;
        $client->save();

        $usedAmount = BannerStream::whereBannerId($banner->id)->sum('amount');
        if ($banner->amount_limit <= $usedAmount || $client->availableBalance() <= 0) {
            $banner->is_active = 0;
            $banner->status = 'finished';
            $banner->save();
        }

        return $pivotBannerStream;
    }

    /**
     * @param Stream $stream
     * @param $response
     * @return StreamTimelog
     */
    public static function trackStream(Stream $stream, $response) {
        $prevTrackTime = Session::get('stream.lastPing', time());
        if ($response->stream != null) {
            $viewers = $response->stream->viewers;
            $screenshot = $response->stream->preview->medium;
            if ($screenshot) {
                $screenshotFile = public_path('/assets/app/upload/t').uniqid($stream->id.'_'.date('YmdHis')).'.jpg';
                if (copy($screenshot, $screenshotFile)) {
                    $screenshot = $screenshotFile;
                } else {
                    $screenshot = '';
                }
            } else {
                $screenshot = '';
            }
            $status = 'live';
        } else {
            $viewers = 0;
            $screenshot = '';
            $status = 'died';
        }
        $streamTimelog = StreamTimelog::create([
            'stream_id' => $stream->id,
            'timeslot_start' => \Carbon\Carbon::createFromTimestamp($prevTrackTime),
            'timeslot_end' => \Carbon\Carbon::now(),
            'viewers' => $viewers,
            'status' => $status,
            'screenshot' => $screenshot,
            'response' => $response
        ]);
        Session::set('stream.lastPing', time());

        return $streamTimelog;
    }

}