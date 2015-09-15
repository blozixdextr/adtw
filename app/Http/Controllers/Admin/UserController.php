<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Mappers\UserMapper;
use Input;
use Redirect;
use Auth;
use App\Models\Mappers\BannerMapper;

class UserController extends Controller
{
    public function index()
    {
        $name = Input::get('name', '');
        $email = Input::get('email', '');
        $onlyActive = Input::get('only_active', '');
        $perPage = Input::get('per_page', 50);
        $users = User::orderBy('created_at');
        $users->whereRole('user');
        if ($name) {
            $users->where('name', 'like', '%'.$name.'%');
        }
        if ($email) {
            $users->where('email', 'like', '%'.$email.'%');
        }
        if ($onlyActive) {
            $users->where('is_active', 1);
        }
        $users = $users->with('profile')->paginate($perPage);

        return view('admin.pages.user.index', compact('users', 'onlyActive', 'name', 'email'));
    }

    public function show($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return Redirect::back()->withErrors(['user' => 'No such user']);
        }
        $banners = [];
        if ($user->type == 'client') {
            $banners = BannerMapper::activeClient($user);
        }
        if ($user->type == 'twitcher') {
            $banners = BannerMapper::activeTwitcher($user);
        }

        return view('admin.pages.user.show', compact('user', 'banners'));
    }

    public function loginAs($userId)
    {
        $user = User::findOrFail($userId);
        Auth::loginUsingId($user->id, true);

        return redirect('/user/'.$user->type);
    }
}
