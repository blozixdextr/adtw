<?php

namespace App\Http\Controllers\User\Twitcher;

use App\Models\Banner;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use App\Models\Mappers\LogMapper;
use Redirect;
use Input;
use Session;
use App\Models\Mappers\PaymentMapper;
use App\Models\Mappers\NotificationMapper;
use App\Models\Mappers\StreamMapper;
use App\Models\Stream;
use Config;

class StreamController extends Controller
{

    public function index()
    {
        $streams = StreamMapper::byTwitcher($this->user);

        return view('app.pages.user.twitcher.stream.index', compact('streams'));
    }

    public function stream($streamId)
    {
        $stream = Stream::findOrFail($streamId);
        $share = Config::get('banner.withdrawal_share');
        $twitcherShare = (100 - $share)/100;

        return view('app.pages.user.twitcher.stream.show', compact('stream', 'twitcherShare'));
    }

    public function acceptDecline($streamId, $bannerId, Request $request)
    {
        $stream = Stream::findOrFail($streamId);
        $banner = Banner::findOrFail($bannerId);
        if ($stream->user_id != $this->user->id) {
            return Redirect::to('/user/twitcher/streams')->withErrors('You have no rights for this');
        }
        $pivot = StreamMapper::getPivot($banner, $stream);
        if ($pivot->status != 'declining') {
            return Redirect::to('/user/twitcher/stream/'.$stream->id)->withErrors('Stream is not declining');
        }
        StreamMapper::decline($banner, $stream);

        LogMapper::log('banner_declined', $banner->id, $stream->id);
        NotificationMapper::bannerPayDeclined($banner, $stream, $pivot->amount);

        return Redirect::to('/user/twitcher/stream/'.$stream->id)->with(['success' => 'Your stream was declined. We are sorry about this']);
    }

    public function complainDecline($streamId, $bannerId)
    {
        $stream = Stream::findOrFail($streamId);
        $banner = Banner::findOrFail($bannerId);
        if ($stream->user_id != $this->user->id) {
            return Redirect::to('/user/twitcher/streams')->withErrors('You have no rights for this');
        }
        $pivot = StreamMapper::getPivot($banner, $stream);
        if ($pivot->status != 'declining') {
            return Redirect::to('/user/twitcher/stream/'.$stream->id)->withErrors('Stream is not declining');
        }

        return view('app.pages.user.twitcher.stream.complain', compact('stream', 'banner'));
    }

    public function complainDeclineSave($streamId, $bannerId, Request $request)
    {
        $stream = Stream::findOrFail($streamId);
        $banner = Banner::findOrFail($bannerId);
        if ($stream->user_id != $this->user->id) {
            return Redirect::to('/user/twitcher/streams')->withErrors('You have no rights for this');
        }
        $pivot = StreamMapper::getPivot($banner, $stream);
        if ($pivot->status != 'declining') {
            return Redirect::to('/user/twitcher/stream/'.$stream->id)->withErrors('Stream is not declining');
        }

        $this->validate($request, ['comment' => 'required|min:5']);
        $comment = $request->get('comment');

        $pivot->status = 'complain';
        $pivot->twitcher_comment = $comment;
        $pivot->save();

        LogMapper::log('banner_complained', $banner->id, $stream->id);
        NotificationMapper::bannerPayComplained($banner, $stream, $pivot->amount);

        return Redirect::to('/user/twitcher/stream/'.$stream->id)->with(['success' => 'You complained about banner declined']);
    }


}
