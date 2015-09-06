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

        return view('app.pages.user.twitcher.banner.popup', compact('banners', 'bgColor', 'bannerType'));
    }

    public function ping($bannerType) {
        $bannerType = Ref::findOrFail($bannerType);
        if ($bannerType->type != 'banner_type') {
            return redirect('/user/twitcher');
        }
        $banners = BannerMapper::activeTwitcher($this->user, $bannerType->id);
        $stream = BannerMapper::getStream($this->user, $banners);
        $bannersClean = [];
        foreach ($banners as $b) {
            $bannersClean[] = $b->file;
            BannerMapper::pay($b, $stream);
        }

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