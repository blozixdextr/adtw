<?php

namespace App\Http\Controllers\User\Client;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index() {
        return view('app.pages.user.client.profile.index');
    }

    public function save(Request $request) {

        $rules = [
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
        ];
        $this->validate($request, $rules);
        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');
        $user = $this->user;
        $user->name = $firstName.' '.$lastName;
        $user->save();
        $profile = $user->profile;
        $profile->first_name = $firstName;
        $profile->last_name = $lastName;
        $profile->save();

        return redirect('/user/client/profile');
    }
}
