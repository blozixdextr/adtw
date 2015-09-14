<?php

namespace App\Models\Mappers;

use App\Models\Banner;
use App\Models\BannerStream;
use App\Models\User;
use App\Models\UserProfile ;
use App\Models\UserPayment;
use App\Models\UserTransfer;
use App\Models\Stream;


class StreamMapper
{
    public static function byClient(User $user, $filters = [], $limit = 50)
    {
        //$streams = $user->streams();

        $streams = Stream::select('streams.*')->join('banner_stream', 'streams.id', '=', 'banner_stream.stream_id');
        $streams->join('banners', 'banners.id', '=', 'banner_stream.banner_id');
        $streams->where('banners.client_id', $user->id);
        $streams->orderBy('time_start', 'desc')->distinct();

        if (count($filters) > 0) {
            if (isset($filters['active'])) {
                if ($filters['active'] == 1) {
                    $streams->where('streams.time_end', null);
                }
                if ($filters['active'] == 0) {
                    $streams->where('streams.time_end', '<', \DB::raw('NOW()'));
                }
            }
        }

        $streams = $streams->paginate($limit);

        return $streams;
    }

    public static function byTwitcher(User $user, $filters = [], $limit = 50)
    {
        //$streams = $user->streams();

        $streams = Stream::whereUserId($user->id);
        $streams->orderBy('time_start', 'desc')->distinct();

        if (count($filters) > 0) {
            if (isset($filters['active'])) {
                if ($filters['active'] == 1) {
                    $streams->where('streams.time_end', null);
                }
                if ($filters['active'] == 0) {
                    $streams->where('streams.time_end', '<', \DB::raw('NOW()'));
                }
            }
        }

        $streams = $streams->paginate($limit);

        return $streams;
    }

    public static function checkOwner(User $user, Banner $banner, Stream $stream)
    {
        if ($banner->client_id != $user->id) {
            return false;
        }
        foreach ($stream->banners as $b) {
            if ($b->id == $banner->id) {
                return true;
            }
        }

        return false;
    }

    public static function getPivot(Banner $banner, Stream $stream)
    {
        return BannerStream::whereBannerId($banner->id)->whereStreamId($stream->id)->first();
    }

    public static function pay(User $user, Banner $banner, Stream $stream)
    {
        $pivot = self::getPivot($banner, $stream);

        $twitcher = $stream->user;
        $twitcher->balance = $twitcher->balance + $pivot->amount;
        $twitcher->save();

        $user->balance_blocked = $user->balance_blocked - $pivot->amount;
        if ($user->balance_blocked < 0) {
            $user->balance_blocked = 0;
        }
        $user->balance = $user->balance - $pivot->amount;
        if ($user->balance < 0) {
            $user->balance = 0;
        }
        $user->save();
        $transfer = UserTransfer::create([
            'buyer_id' => $user->id,
            'seller_id' => $twitcher->id,
            'title' => 'Paid banner#'.$banner->id,
            'amount' => $pivot->amount,
            'currency' => 'USD',
        ]);

        $pivot->status = 'accepted';
        $pivot->transfer_id = $transfer->id;
        $pivot->save();

        return $transfer;
    }

    public static function decline(Banner $banner, Stream $stream)
    {
        $pivot = self::getPivot($banner, $stream);

        $twitcher = $stream->user;
        $twitcher->balance = $twitcher->balance + $pivot->amount;
        $twitcher->save();

        $client = $banner->client;

        $client->balance_blocked = $client->balance_blocked - $pivot->amount;
        if ($client->balance_blocked < 0) {
            $client->balance_blocked = 0;
        }
        $client->save();

        $pivot->status = 'declined';
        $pivot->save();

        return $pivot;
    }


}