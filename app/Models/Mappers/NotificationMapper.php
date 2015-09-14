<?php

namespace App\Models\Mappers;

use App\Models\Banner;
use App\Models\Stream;
use App\Models\User;
use App\Models\Notification;
use App\Models\UserProfile;
use App\Models\Mappers\LogMapper;
use Request;
use Auth;
use Mail;

class NotificationMapper
{
    public static function notify(User $user, $title, $type = 'default', $subtitle = '')
    {
        $l = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'subtitle' => $subtitle,
            'type' => $type
        ]);

        return $l;
    }

    public static function bannerAdd(Banner $banner)
    {
        $title = $banner->client->name.' added '.$banner->type->title.' banner';
        $subtitle = '<a href="/user/twitcher/banner/review/'.$banner->id.'">review it</a>';
        self::notify($banner->twitcher, $title, 'banner_add', $subtitle);
        Mail::send('app.emails.banner_add', ['banner' => $banner], function ($m) use ($banner) {
            $m->to($banner->twitcher->email)->subject('Someone order banner to you');
        });
    }

    public static function bannerAccept(Banner $banner)
    {
        $title = $banner->twitcher->name.' accepted your '.$banner->type->title.' banner';
        self::notify($banner->client, $title, 'banner_accept');
        Mail::send('app.emails.banner_accept', ['banner' => $banner], function ($m) use ($banner) {
            $m->to($banner->twitcher->email)->subject($banner->twitcher->name.' accepted your banner');
        });
    }

    public static function bannerDecline(Banner $banner)
    {
        $title = $banner->twitcher->name.' declined your '.$banner->type->title.' banner';
        self::notify($banner->client, $title, 'banner_decline');
        Mail::send('app.emails.banner_decline', ['banner' => $banner], function ($m) use ($banner) {
            $m->to($banner->twitcher->email)->subject($banner->twitcher->name.' declined your banner');
        });
    }

    public static function bannerStream(Banner $banner)
    {
        $title = $banner->twitcher->name.' started to stream your '.$banner->type->title.' banner';
        self::notify($banner->client, $title, 'banner_stream');
        Mail::send('app.emails.banner_stream', ['banner' => $banner], function ($m) use ($banner) {
            $m->to($banner->twitcher->email)->subject($banner->twitcher->name.' started to stream your banner');
        });
    }

    public static function bannerFinished(Banner $banner)
    {
        $title = $banner->twitcher->name.' runs out of limit for your '.$banner->type->title.' banner';
        self::notify($banner->client, $title, 'banner_finished');
        Mail::send('app.emails.banner_finished', ['banner' => $banner], function ($m) use ($banner, $title) {
            $m->to($banner->client->email)->subject($title);
        });

        $title = 'You runs out of limit for '.$banner->type->title.' banner';
        self::notify($banner->twitcher, $title, 'banner_finished');
        Mail::send('app.emails.banner_finished_twitcher', ['banner' => $banner], function ($m) use ($banner, $title) {
            $m->to($banner->twitcher->email)->subject($title);
        });
    }

    public static function emptyBalance(Banner $banner)
    {
        $title = 'You run out of your balance';
        self::notify($banner->client, $title, 'banner_finished');
        Mail::send('app.emails.empty_balance', ['banner' => $banner], function ($m) use ($banner, $title) {
            $m->to($banner->client->email)->subject($title);
        });

        $title = $banner->client->name.' run out of his balance';
        self::notify($banner->twitcher, $title, 'banner_finished');
        Mail::send('app.emails.empty_balance_twitcher', ['banner' => $banner], function ($m) use ($banner, $title) {
            $m->to($banner->twitcher->email)->subject($title);
        });
    }



    public static function reviewed(Notification $notification)
    {
        $notification->seen_at = \Carbon\Carbon::now();
        $notification->save();
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
        $notifications = $notifications->paginate($limit);

        return $notifications;
    }

    public static function bannerPayAccept(Banner $banner, Stream $stream, $amount)
    {
        NotificationMapper::notify($stream->user, $banner->client->name.' paid your $'.$amount);
        NotificationMapper::notify($banner->client, 'You paid $'.$amount.' to '.$stream->user->name);
        Mail::send('app.emails.default', [
            'title' => 'You got paid',
            'subtitle' => $banner->client->name.' paid your $'.$amount,
            'url' => '/user/twitcher'], function ($m) use ($stream) {
            $m->to($stream->user->email)->subject('You got paid');
        });
    }

    public static function bannerPayDeclining(Banner $banner, Stream $stream, $amount)
    {
        NotificationMapper::notify($stream->user, $banner->client->name.' declined your stream', 'important', '<a href="/user/twitcher/stream/'.$stream->id.'/'.$banner->id.'/declining">Review it</a>');
        NotificationMapper::notify($banner->client, 'You declined to pay for stream of '.$stream->user->name);
        Mail::send('app.emails.default', [
            'title' => 'Your stream was declined',
            'subtitle' => $banner->client->name.' declined your stream',
            'url' => '/user/twitcher/stream/'.$stream->id   ], function ($m) use ($stream) {
            $m->to($stream->user->email)->subject('Your stream was declined');
        });
    }

    public static function bannerPayDeclined(Banner $banner, Stream $stream, $amount)
    {
        NotificationMapper::notify($stream->user, 'You accepted to decline your stream');
        NotificationMapper::notify($banner->client, $stream->user->name.' accepted to decline stream');
        Mail::send('app.emails.default', [
            'title' => 'Stream was declined',
            'subtitle' => $stream->user->name.' accepted to decline stream',
            'url' => '/user/client/stream/'.$stream->id], function ($m) use ($stream, $banner) {
            $m->to($banner->client->email)->subject('Stream was declined');
        });
    }

    public static function bannerPayComplained(Banner $banner, Stream $stream, $amount)
    {
        NotificationMapper::notify($stream->user, 'You complained about declining your stream');
        NotificationMapper::notify($banner->client, $stream->user->name.' complained about declining stream');
        Mail::send('app.emails.default', [
            'title' => 'Declining was complained',
            'subtitle' => 'The final decision will be made by site admin',
            'url' => '/user/client/stream/'.$stream->id], function ($m) use ($stream, $banner) {
            $m->to($banner->client->email)->subject('Declining was complained');
        });
    }




}