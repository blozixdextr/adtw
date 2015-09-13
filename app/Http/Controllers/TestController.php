<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use App\Models\User;

class TestController extends Controller
{
    public function index() {

        Mail::raw('test mailgun', function ($message) {
            $message->to('info@ifrond.com', 'Ravil')->subject('Test subject');
        });

    }

    public function loginAs($userId) {
        $user = User::findOrFail($userId);
        Auth::loginUsingId($user->id, true);

        return redirect('/profile');
    }
}
