<?php

namespace App\Http\Controllers\Admin;

use Auth;

class IndexController extends Controller
{
    public function index() {
        return view('app.pages.user.client.index');
    }
}
