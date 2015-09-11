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

class StreamController extends Controller
{
    public function index()
    {
        // @todo Show all streams with client banner. Mark payed/declined/waiting/alive
        return view('app.pages.user.client.stream.index');
    }

    public function stream($streamId)
    {
        // @todo Show stream info (alive or finished, timeslots and screenshort)
        return view('app.pages.user.client.stream.show');
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
