<?php

namespace App\Http\Controllers\User\Client;

use App\Models\Mappers\NotificationMapper;
use Illuminate\Http\Request;
use Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('app.pages.user.client.profile.index');
    }

    public function save(Request $request)
    {
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

        NotificationMapper::notify($this->user, 'You updated your profile');

        return redirect('/user/client/profile');
    }

    public function password(Request $request)
    {
        $rules = [
            'password' => 'required|min:6|max:20',
            'new_password' => 'required|min:6|max:20|different:password',
            'new_password2' => 'required|same:new_password',
        ];
        $this->validate($request, $rules);
        $password = $request->get('password');
        $newPassword = $request->get('new_password');
        if (!Hash::check($password, $this->user->password)) {
            return redirect('/user/client/profile')->withErrors(['password' => 'Wrong current password']);
        }
        $this->user->password = bcrypt($newPassword);
        $this->user->save();
        NotificationMapper::notify($this->user, 'You changed your password');


        return redirect('/user/client/profile')->with('success', 'Your password was changed');
    }

}
