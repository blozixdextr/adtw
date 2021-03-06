<?php

namespace App\Models\Mappers;

use App\Models\Banner;
use App\Models\BannerStream;
use App\Models\Referral;
use App\Models\User;
use App\Models\UserProfile ;
use App\Models\UserPayment;
use App\Models\UserTransfer;
use App\Models\Stream;
use Config;


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

        $amount = round($pivot->amount, 2);

        $share = Config::get('banner.withdrawal_share');
        $twitcherShare = (100 - $share)/100;
        $paymentForTwitcher = $pivot->amount * $twitcherShare;

        $twitcher = $stream->user;
        $twitcher->balance = $twitcher->balance + $paymentForTwitcher;
        $twitcher->save();

        $user->balance_blocked = $user->balance_blocked - $amount;
        if ($user->balance_blocked < 0) {
            $user->balance_blocked = 0;
        }
        $user->balance = $user->balance - $amount;
        if ($user->balance < 0) {
            $user->balance = 0;
        }
        $user->save();
        $transfer = UserTransfer::create([
            'buyer_id' => $user->id,
            'seller_id' => $twitcher->id,
            'title' => 'Paid banner#'.$banner->id,
            'amount' => $paymentForTwitcher,
            'currency' => 'USD',
        ]);
        self::referrerPay($transfer);

        $pivot->status = 'accepted';
        $pivot->transfer_id = $transfer->id;
        $pivot->save();

        return $transfer;
    }

    public static function referrerPay(UserTransfer $transfer)
    {
        $seller = $transfer->seller;
        $referrer = $seller->referrer;
        if ($referrer) {
            $share = Config::get('banner.referral_share');
            $referrerShare = $share/100;
            $amount = $transfer->amount * $referrerShare;
            $amount = round($amount, 2);
            if ($amount == 0) {
                return false;
            }
            $referral = Referral::create([
                'user_id' => $transfer->seller_id,
                'referral_id' => $transfer->seller->referral_id,
                'transfer_id' => $transfer->id,
                'amount' => $amount,
                'currency' => $transfer->currency,
            ]);
            $referrer->balance = $referrer->balance + $amount;
            $referrer->save();

            NotificationMapper::referralPaid($referral);

            return true;
        }

        return false;
    }

    public static function decline(Banner $banner, Stream $stream)
    {
        $pivot = self::getPivot($banner, $stream);

        $amount = round($pivot->amount, 2);

        $twitcher = $stream->user;
        $twitcher->balance = $twitcher->balance + $amount;
        $twitcher->save();

        $client = $banner->client;

        $client->balance_blocked = $client->balance_blocked - $amount;
        if ($client->balance_blocked < 0) {
            $client->balance_blocked = 0;
        }
        $client->save();

        $pivot->status = 'declined';
        $pivot->save();

        return $pivot;
    }


}