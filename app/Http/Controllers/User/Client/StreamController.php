<?php

namespace App\Http\Controllers\User\Client;

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

    public function accept($streamId, Request $request)
    {
        $comment = $request->get('comment', '');
        // @todo Transfer money to twitcher
        return Redirect::to('/user/client/streams')->with(['success' => 'You accepted and payed stream']);
    }

    public function decline($streamId, Request $request)
    {
        $this->validate($request, ['comment' => 'required:min:5']);
        $comment = $request->get('comment');
        // @todo Add comment to stream and notify twitcher about decline
        return Redirect::to('/user/client/streams')->with(['success' => 'You declined to pay stream']);
    }
}
