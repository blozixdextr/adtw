<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Mail;

class IndexController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('app.pages.index', compact('user'));
    }

    public function contactUs()
    {
        $user = Auth::user();
        $username = '';
        if ($user) {
            $username = $user->name;
        }

        return view('app.pages.contact-us', compact('username'));
    }

    public function contactUsPost(Request $request)
    {
        $rules = [
            'title' => 'required|min:2',
            'message' => 'required|min:5',
        ];
        $this->validate($request, $rules);
        $message = nl2br(filter_var($request->get('message'), FILTER_SANITIZE_STRING));
        $title = filter_var($request->get('title'), FILTER_SANITIZE_STRING);
        $user = Auth::user();

        $email = 'blozixdextr@gmail.com';
        $email = 'info@ifrond.com';

        Mail::send('app.emails.admin.contact_us', compact('message', 'title', 'user'), function ($m) use ($email) {
            $m->to($email)->subject('Contact from ADTW.CH');
        });


    }
}
