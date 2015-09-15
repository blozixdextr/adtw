<?php

namespace App\Http\Controllers\User\Twitcher;

use Illuminate\Http\Request;
use Auth;
use App\Models\Mappers\LogMapper;
use App\Models\Mappers\RefMapper;
use App\Models\Mappers\BannerMapper;
use App\Models\Ref;
use App\Models\Banner;
use App\Apis\Twitch;
use Illuminate\Support\Facades\Input;
use Redirect;
use Config;

class BannerController extends Controller
{
    public function review($bannerId) {
        $banner = Banner::findOrFail($bannerId);
        if ($banner->twitcher_id != $this->user->id) {
            return redirect('/user/twitcher');
        }
        if ($banner->status != 'waiting') {
            return redirect('/user/twitcher');
        }

        return view('app.pages.user.twitcher.banner.review', compact('banner'));
    }

    public function accept($bannerId) {
        $banner = Banner::findOrFail($bannerId);
        if ($banner->twitcher_id != $this->user->id) {
            return redirect('/user/twitcher');
        }
        if ($banner->is_active == 1 || $banner->status != 'waiting') {
            return redirect('/user/twitcher');
        }
        BannerMapper::acceptBanner($banner);

        return Redirect::to('/user/twitcher')->with(['success' => 'You accepted banner']);
    }

    public function decline($bannerId) {
        $banner = Banner::findOrFail($bannerId);
        if ($banner->twitcher_id != $this->user->id) {
            return redirect('/user/twitcher');
        }
        if ($banner->is_active == 1 || $banner->status != 'waiting') {
            return redirect('/user/twitcher');
        }
        BannerMapper::declineBanner($banner);

        return Redirect::to('/user/twitcher')->with(['success' => 'You declined banner']);
    }

    public function popup($bannerType) {
        $bannerType = Ref::findOrFail($bannerType);
        if ($bannerType->type != 'banner_type') {
            return redirect('/user/twitcher');
        }
        $banners = BannerMapper::activeTwitcher($this->user, $bannerType->id);
        $bgColor = Input::get('color', '00ff00');

        $trackPeriod = 1000 * 60 * Config::get('banner.track_minutes');
        $rotationPeriod = 1000 * Config::get('banner.rotation_seconds');

        return view('app.pages.user.twitcher.banner.popup', compact('banners', 'bgColor', 'bannerType', 'trackPeriod', 'rotationPeriod'));
    }

    public function ping($bannerType) {
        $bannerType = Ref::findOrFail($bannerType);
        if ($bannerType->type != 'banner_type') {
            return redirect('/user/twitcher');
        }
        $banners = BannerMapper::activeTwitcher($this->user, $bannerType->id);
        $stream = BannerMapper::getStream($this->user, $banners);

        $twitchApi = app('twitch');
        $streamInfo = $twitchApi->getStream($this->user);
/*
        $streamInfo = (object)[
            'stream' => (object)[
                'viewers' => rand(0, 100),
                'preview' => (object)[
                    'medium' => 'http://im4.kommersant.ru/Issues.photo/DAILY/2015/162M/KMO_085445_02826_1_t218_222616.jpg'
                ]
            ]
        ];
*/
        $streamTimelog = BannerMapper::trackStream($stream, $streamInfo);
        if ($streamTimelog->status == 'live') {
            foreach ($banners as $b) {
                $bannersClean[] = $b->file;
                BannerMapper::trackBanner($b, $stream, $streamTimelog);
            }
            $banners = BannerMapper::activeTwitcher($this->user, $bannerType->id);
        }
        $bannersClean = [];
        foreach ($banners as $b) {
            $bannersClean[] = $b->file;
        }
        $stream->time_end = \Carbon\Carbon::now();
        $stream->save();

        $result = [
            'banners' => $bannersClean
        ];

        return $result;
    }

    public function show($bannerType) {
        $bannerType = Ref::findOrFail($bannerType);
        if ($bannerType->type != 'banner_type') {
            return redirect('/user/twitcher');
        }
        $banners = BannerMapper::activeTwitcher($this->user, $bannerType->id);

        return view('app.pages.user.twitcher.banner.show', compact('banners', 'bannerType'));
    }

}
