<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mappers\LogMapper;
use App\Models\Mappers\NotificationMapper;
use Redirect;
use Config;
use Illuminate\Http\Request;
use App\Models\BannerStream;
use App\Models\Banner;
use App\Models\Stream;
use App\Models\Mappers\StreamMapper;

class DeclineController extends Controller
{
    public function index()
    {
        $bannerStreams = BannerStream::whereStatus('declining')->paginate(50);

        return view('admin.pages.decline.index', compact('bannerStreams'));
    }

    public function show($bannerStreamId)
    {
        $bannerStream = BannerStream::findOrFail($bannerStreamId);

        return view('admin.pages.decline.show', compact('bannerStream'));
    }

    public function client($bannerStreamId)
    {
        $bannerStream = BannerStream::findOrFail($bannerStreamId);
        $banner = $bannerStream->banner;
        $stream = $bannerStream->stream;
        $client = $banner->client;
        StreamMapper::decline($banner, $stream);
        LogMapper::log('banner_declined', $banner->id, $stream->id);
        LogMapper::log('decline_resolve', $banner->id, $stream->id, ['resolution' => 'client wins']);
        NotificationMapper::bannerPayDeclined($banner, $stream, $bannerStream->amount);

        return redirect('/admin/decline')->with(['success' => 'You accepted client\'s point of view']);
    }

    public function streamer($bannerStreamId)
    {
        $bannerStream = BannerStream::findOrFail($bannerStreamId);
        $banner = $bannerStream->banner;
        $stream = $bannerStream->stream;
        $client = $banner->client;
        StreamMapper::pay($client, $banner, $stream);
        LogMapper::log('banner_paid', $banner->id, $stream->id);
        LogMapper::log('decline_resolve', $banner->id, $stream->id, ['resolution' => 'streamer wins']);
        NotificationMapper::bannerPayAccept($banner, $stream, $bannerStream->amount);

        return redirect('/admin/decline')->with(['success' => 'You accepted streamer\'s point of view']);
    }

    public function stream($bannerStreamId)
    {
        $bannerStream = BannerStream::findOrFail($bannerStreamId);
        $stream = $bannerStream->stream;

        return view('admin.pages.decline.stream', compact('stream', 'bannerStream'));
    }
}
