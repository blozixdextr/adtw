<?php

namespace App\Http\Controllers\User\Client;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index() {

        $user = $this->user;
        $profile = $user->profile;

        return view('app.pages.user.client.billing.index', compact('user', 'profile'));
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
        $profile = $user->profile;
        $profile->first_name = $firstName;
        $profile->last_name = $lastName;
        $profile->save();

        return redirect('/user/client/profile');
    }
}
