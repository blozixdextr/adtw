<?php

namespace App\Http\Controllers;

use Auth;

class IndexController extends Controller
{
    public function index() {
        $user = Auth::user();

        return view('app.pages.index', compact('user'));
    }
}
