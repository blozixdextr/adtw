<?php

namespace App\Http\Controllers\User\Twitcher;

use Auth;

class IndexController extends Controller
{
    public function index() {

        dd(Auth::user());

    }
}
