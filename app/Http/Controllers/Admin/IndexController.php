<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Mappers\LogMapper;

class IndexController extends Controller
{
    public function index()
    {
        $logs = LogMapper::all();
        dd($logs);

        return view('app.pages.admin.index', compact('logs'));
    }
}
