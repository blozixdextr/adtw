<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Mappers\UserMapper;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Auth;
use App\Models\Mappers\BannerMapper;
use Session;

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
        Session::set('fake-auth', 1);
        Auth::loginUsingId($user->id, true);

        return redirect('/user/'.$user->type);
    }

    public function ban($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_active = 0;
        $user->save();

        return Redirect::back()->with(['success' => 'User '.$user->name.' successfully banned']);
    }

    public function unban($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_active = 1;
        $user->save();

        return Redirect::back()->with(['success' => 'User '.$user->name.' successfully unbanned']);
    }

    public function edit($userId)
    {
        $user = User::findOrFail($userId);

        return view('admin.pages.user.edit', compact('user'));
    }

    public function update($userId, Request $request)
    {
        $user = User::findOrFail($userId);
        $user->fill($request->all());
        $user->save();

        return Redirect::back()->with(['success' => 'User '.$user->name.' was changed']);
    }
}
