<?php

namespace App\Http\Controllers\User\Client;

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

class StreamController extends Controller
{
    public function index()
    {
        $streams = StreamMapper::byClient($this->user);

        return view('app.pages.user.client.stream.index', compact('streams'));
    }

    public function stream($streamId)
    {
        $stream = Stream::findOrFail($streamId);

        return view('app.pages.user.client.stream.show', compact('stream'));
    }

    public function accept($streamId, $bannerId, Request $request)
    {
        $stream = Stream::findOrFail($streamId);
        $banner = Banner::findOrFail($bannerId);
        if (!StreamMapper::checkOwner($this->user, $banner, $stream)) {
            return Redirect::to('/user/client/streams')->withErrors('You have no rights for this');
        }
        if ($stream->time_end == null) {
            return Redirect::to('/user/client/stream/'.$stream->id)->withErrors('Stream is still live');
        }
        $pivot = StreamMapper::getPivot($banner, $stream);
        if ($pivot->status != 'waiting') {
            return Redirect::to('/user/client/stream/'.$stream->id)->withErrors('Stream is not for paying');
        }
        $pivot = StreamMapper::pay($this->user, $banner, $stream);
        $pivot->status = 'accepted';
        $pivot->save();

        LogMapper::log('banner_paid', $banner->id, $pivot->amount);
        NotificationMapper::bannerPayAccept($banner, $stream, $pivot->amount);

        return Redirect::to('/user/client/stream/'.$stream->id)->with(['success' => 'You accepted and payed the banner in this stream']);
    }

    public function decline($streamId, $bannerId)
    {
        $stream = Stream::findOrFail($streamId);
        $banner = Banner::findOrFail($bannerId);
        if (!StreamMapper::checkOwner($this->user, $banner, $stream)) {
            return Redirect::to('/user/client/streams')->withErrors('You have no rights for this');
        }
        if ($stream->time_end == null) {
            return Redirect::to('/user/client/stream/'.$stream->id)->withErrors('Stream is still live');
        }
        $pivot = StreamMapper::getPivot($banner, $stream);
        if ($pivot->status != 'waiting') {
            return Redirect::to('/user/client/stream/'.$stream->id)->withErrors('Stream is not for paying');
        }

        return view('app.pages.user.client.stream.decline', compact('stream', 'banner'));
    }

    public function declineSave($streamId, $bannerId, Request $request)
    {
        $stream = Stream::findOrFail($streamId);
        $banner = Banner::findOrFail($bannerId);
        if (!StreamMapper::checkOwner($this->user, $banner, $stream)) {
            return Redirect::to('/user/client/streams')->withErrors('You have no rights for this');
        }
        if ($stream->time_end == null) {
            return Redirect::to('/user/client/stream/'.$stream->id)->withErrors('Stream is still live');
        }
        $pivot = StreamMapper::getPivot($banner, $stream);
        if ($pivot->status != 'waiting') {
            return Redirect::to('/user/client/stream/'.$stream->id)->withErrors('Stream is not for paying');
        }

        $this->validate($request, ['comment' => 'required:min:5']);
        $comment = $request->get('comment');

        $pivot->status = 'declining';
        $pivot->client_comment = $comment;
        $pivot->save();

        LogMapper::log('banner_declined', $banner->id, $pivot->amount);
        NotificationMapper::bannerPayDeclining($banner, $stream, $pivot->amount);

        return Redirect::to('/user/client/stream/'.$stream->id)->with(['success' => 'You declined to pay the banner in this stream']);
    }
}
