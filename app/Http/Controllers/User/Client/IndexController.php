<?php

namespace App\Http\Controllers\User\Client;

use Auth;

class IndexController extends Controller
{
    public function index() {
        return view('app.pages.user.client.index');
    }
}
