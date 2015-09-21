<?php

namespace App\Models\Mappers;

use App\Models\Banner;
use App\Models\Stream;
use App\Models\User;
use App\Models\Notification;
use App\Models\UserProfile;
use App\Models\Mappers\LogMapper;
use App\Models\Withdrawal;
use Request;
use Auth;
use Mail;
use App\Models\UserPayment;

class NotificationMapper
{
    public static function notify(User $user, $title, $type = 'default', $subtitle = '')
    {
        $n = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'subtitle' => $subtitle,
            'type' => $type
        ]);

        return $n;
    }

    public static function registration(User $user)
    {
        self::notify($user, 'You register with <a href="'.url('/').'">Adtw.ch</a>', 'register');
    }

    public static function withdraw(User $user, $amount, $currency, $merchant, $account)
    {
        self::notify($user, 'You withdraw '.$amount.$currency,' to '.$merchant, $merchant);
    }

    public static function bannerAdd(Banner $banner)
    {
        $title = $banner->client->name.' added <a href="/user/twitcher/banner/review/'.$banner->id.'">'.$banner->type->title.' banner</a>';
        self::notify($banner->twitcher, $title, 'banner');

        $title = 'You added banner for <a href="/profile/'.$banner->twitcher_id.'">'.$banner->twitcher->name.'</a>';
        self::notify($banner->client, $title, 'banner');

        Mail::send('app.emails.banner_add', ['banner' => $banner], function ($m) use ($banner) {
            $m->to($banner->twitcher->email)->subject('Someone order banner to you');
        });
    }

    public static function bannerCancel(Banner $banner)
    {
        $title = $banner->client->name.' canceled his order';
        self::notify($banner->twitcher, $title, 'decline');

        $title = 'You canceled order';
        self::notify($banner->client, $title, 'decline');

        Mail::send('app.emails.default', [
            'title' => $banner->client->name.' canceled order',
            'subtitle' => $banner->client->name.' canceled order banner to you',
            'url' => '/user/twitcher'], function ($m) use ($banner) {
                $m->to($banner->client->email)->subject($banner->client->name.' canceled order');
        });
    }

    public static function bannerAccept(Banner $banner)
    {
        $title = $banner->twitcher->name.' accepted your '.$banner->type->title.' banner';
        self::notify($banner->client, $title, 'accept');

        $title = 'You accepted '.$banner->type->title.' banner from '.$banner->client->name;
        self::notify($banner->twitcher, $title, 'accept');

        Mail::send('app.emails.banner_accept', ['banner' => $banner], function ($m) use ($banner) {
            $m->to($banner->client->email)->subject($banner->twitcher->name.' accepted your banner');
        });
    }

    public static function bannerDecline(Banner $banner)
    {
        $title = $banner->twitcher->name.' declined your '.$banner->type->title.' banner';
        self::notify($banner->client, $title, 'decline');

        $title = 'You declined your '.$banner->type->title.' banner from '.$banner->client->name;
        self::notify($banner->twitcher, $title, 'decline');

        Mail::send('app.emails.banner_decline', ['banner' => $banner], function ($m) use ($banner) {
            $m->to($banner->client->email)->subject($banner->twitcher->name.' declined your banner');
        });
    }

    public static function bannerStream(Banner $banner)
    {
        $title = $banner->twitcher->name.' started to stream your '.$banner->type->title.' banner';
        self::notify($banner->client, $title, 'stream');

        $title = 'You started to stream '.$banner->type->title.' banner from '.$banner->client->name;
        self::notify($banner->twitcher, $title, 'stream');

        Mail::send('app.emails.banner_stream', ['banner' => $banner], function ($m) use ($banner) {
            $m->to($banner->client->email)->subject($banner->twitcher->name.' started to stream your banner');
        });
    }

    public static function bannerFinished(Banner $banner)
    {
        $title = $banner->twitcher->name.' runs out of limit for your '.$banner->type->title.' banner';
        self::notify($banner->client, $title, 'banner');

        $title = 'You run out of limit for '.$banner->type->title.' banner from '.$banner->client->name;
        self::notify($banner->twitcher, $title, 'banner');

        Mail::send('app.emails.banner_finished', ['banner' => $banner], function ($m) use ($banner, $title) {
            $m->to($banner->client->email)->subject($title);
        });

        $title = 'You runs out of limit for '.$banner->type->title.' banner';
        self::notify($banner->twitcher, $title, 'banner');
        Mail::send('app.emails.banner_finished_twitcher', ['banner' => $banner], function ($m) use ($banner, $title) {
            $m->to($banner->twitcher->email)->subject($title);
        });
    }

    public static function emptyBalance(Banner $banner)
    {
        $title = 'You run out of your balance';
        self::notify($banner->client, $title, 'balance');
        Mail::send('app.emails.empty_balance', ['banner' => $banner], function ($m) use ($banner, $title) {
            $m->to($banner->client->email)->subject($title);
        });

        $title = $banner->client->name.' run out of his balance';
        self::notify($banner->twitcher, $title, 'balance');
        Mail::send('app.emails.empty_balance_twitcher', ['banner' => $banner], function ($m) use ($banner, $title) {
            $m->to($banner->twitcher->email)->subject($title);
        });
    }



    public static function reviewed(Notification $notification)
    {
        $notification->seen_at = \Carbon\Carbon::now();
        return $notification->save();
    }

    public static function type(User $user, $type, $onlyFresh = true, $limit = 50)
    {
        if (is_string($type)) {
            $type = [$type];
        }
        $notifications = Notification::where('user_id', $user->id);
        $notifications->whereIn('type', $type);
        if ($onlyFresh) {
            $notifications->where('seen_at', null);
        }
        $notifications = $notifications->paginate($limit);

        return $notifications;
    }

    public static function fresh(User $user, $limit = 50)
    {
        $notifications = Notification::where('user_id', $user->id);
        $notifications->where('seen_at', null);
        $notifications->orderBy('created_at', 'desc');
        $notifications = $notifications->paginate($limit);

        return $notifications;
    }

    public static function user(User $user, $limit = 50)
    {
        $notifications = Notification::where('user_id', $user->id);
        $notifications->orderBy('created_at', 'desc');
        $notifications = $notifications->paginate($limit);

        return $notifications;
    }

    public static function bannerPayAccept(Banner $banner, Stream $stream, $amount)
    {
        NotificationMapper::notify($stream->user, $banner->client->name.' paid your '.$amount.'USD', 'transfer');
        NotificationMapper::notify($banner->client, 'You paid '.$amount.'USD to '.$stream->user->name, 'transfer');

        Mail::send('app.emails.default', [
            'title' => 'You got paid',
            'subtitle' => $banner->client->name.' paid your $'.$amount,
            'url' => '/user/twitcher'], function ($m) use ($stream) {
            $m->to($stream->user->email)->subject('You got paid');
        });
    }

    public static function bannerPayDeclining(Banner $banner, Stream $stream, $amount)
    {
        NotificationMapper::notify($stream->user, $banner->client->name.' <a href="/user/twitcher/stream/'.$stream->id.'/'.$banner->id.'/declining">declined</a> your stream', 'decline');
        NotificationMapper::notify($banner->client, 'You declined to pay for stream of '.$stream->user->name, 'decline');

        Mail::send('app.emails.default', [
            'title' => 'Your stream was declined',
            'subtitle' => $banner->client->name.' declined your stream',
            'url' => '/user/twitcher/stream/'.$stream->id   ], function ($m) use ($stream) {
            $m->to($stream->user->email)->subject('Your stream was declined');
        });
    }

    public static function bannerPayDeclined(Banner $banner, Stream $stream, $amount)
    {
        NotificationMapper::notify($stream->user, 'You accepted to decline your stream', 'decline');
        NotificationMapper::notify($banner->client, $stream->user->name.' accepted to decline stream', 'decline');

        Mail::send('app.emails.default', [
            'title' => 'Stream was declined',
            'subtitle' => $stream->user->name.' accepted to decline stream',
            'url' => '/user/client/stream/'.$stream->id], function ($m) use ($stream, $banner) {
            $m->to($banner->client->email)->subject('Stream was declined');
        });
    }

    public static function bannerPayComplained(Banner $banner, Stream $stream, $amount)
    {
        NotificationMapper::notify($stream->user, 'You complained about declining your stream', 'decline');
        NotificationMapper::notify($banner->client, $stream->user->name.' complained about declining stream', 'decline');

        Mail::send('app.emails.default', [
            'title' => 'Declining was complained',
            'subtitle' => 'The final decision will be made by site admin',
            'url' => '/user/client/stream/'.$stream->id], function ($m) use ($stream, $banner) {
            $m->to($banner->client->email)->subject('Declining was complained');
        });
    }

    public static function withdrawDecline(Withdrawal $withdrawal)
    {
        NotificationMapper::notify($withdrawal->user, 'Your withdrawal to '.$withdrawal->merchant.' with '.$withdrawal->amount.$withdrawal->currency.' was declined with comment <em>'.$withdrawal->admin_comment.'</em>', $withdrawal->merchant);

        Mail::send('app.emails.default', [
            'title' => 'Withdrawal declined',
            'subtitle' => 'Your withdrawal to '.$withdrawal->merchant.' with '.$withdrawal->amount.$withdrawal->currency.' was declined with comment <em>'.$withdrawal->admin_comment.'</em>',
            'url' => '/user/twitcher/billing'], function ($m) use ($withdrawal) {
            $m->to($withdrawal->user->email)->subject('Withdrawal declined');
        });
    }

    public static function withdrawAccept(Withdrawal $withdrawal)
    {
        NotificationMapper::notify($withdrawal->user, 'Your withdrawal to '.$withdrawal->merchant.' '.$withdrawal->account.' with '.$withdrawal->amount.$withdrawal->currency.' was successful', $withdrawal->merchant);

        Mail::send('app.emails.default', [
            'title' => 'Withdrawal finished',
            'subtitle' => 'Your withdrawal to '.$withdrawal->merchant.' '.$withdrawal->account.' with '.$withdrawal->amount.$withdrawal->currency.' was successful',
            'url' => '/user/twitcher/billing'], function ($m) use ($withdrawal) {
            $m->to($withdrawal->user->email)->subject('Withdrawal finished');
        });
    }

    public static function refilled(UserPayment $payment)
    {
        $title = 'Your refilled your account with '.$payment->amount.$payment->currency.' from '.$payment->merchant;
        NotificationMapper::notify($payment->user, $title);

        Mail::send('app.emails.default', [
            'title' => 'Account refilled',
            'subtitle' => $title,
            'url' => '/user/client/billing'], function ($m) use ($payment) {
            $m->to($payment->user->email)->subject('Account refilled');
        });
    }

}