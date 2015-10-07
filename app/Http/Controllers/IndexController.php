<?php

namespace App\Http\Controllers;

use App\Models\Mappers\UserMapper;
use Auth;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Session;
use Illuminate\Cookie\CookieJar;

class IndexController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('app.pages.index', compact('user'));
    }

    public function referrer($nickname)
    {
        $referrer = UserMapper::getTwitcherByNickName($nickname);
        if ($referrer) {
            return redirect('/')->withCookie(cookie('referrer', $referrer->id, 86400));
        }

        return redirect('/');
    }

    public function contactUs()
    {
        $user = Auth::user();
        $username = '';
        if ($user) {
            $username = $user->name;
        }

        return view('app.pages.contact-us.index', compact('username', 'user'));
    }

    public function contactUsPost(Request $request)
    {
        $rules = [
            'title' => 'required|min:2',
            'message' => 'required|min:5',
        ];
        $this->validate($request, $rules);
        $text = nl2br(filter_var($request->get('message'), FILTER_SANITIZE_STRING));
        $title = filter_var($request->get('title'), FILTER_SANITIZE_STRING);
        $user = Auth::user();

        $email = 'blozixdextr@gmail.com';

        //dd(compact('message', 'title', 'user'));

        Mail::send('app.emails.admin.contact_us', compact('text', 'title', 'user'), function ($m) use ($email) {
            $m->to($email)->subject('Contact from ADTW.CH');
        });

        return view('app.pages.contact-us.send', compact('user'));

    }
}
