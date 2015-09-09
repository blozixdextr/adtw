<?php

namespace App\Http\Controllers;

use Auth;
use Mail;

class TestController extends Controller
{
    public function index() {

        Mail::raw('test mailgun', function ($message) {
            $message->to('info@ifrond.com', 'Ravil')->subject('Test subject');
        });

    }
}
