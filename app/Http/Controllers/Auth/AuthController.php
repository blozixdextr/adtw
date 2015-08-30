<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserProfile;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Redirect;
use Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    public $redirectPath = '/profile';
    public $redirectTo = '/profile';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'last_activity' => \Carbon\Carbon::now(),
        ]);
        $profile = new UserProfile([
            'first_name' => $data['name'],
        ]);
        $profile->user_id = $user->id;
        $profile->save();

        return $user;
    }

    public function twitch() {
        $twitch = app('twitch');
        $url = $twitch->getLoginUrl();

        return redirect($url);
    }

    public function updateTwitchProfile($user, $twitchIdentity) {
        $user->twitch_profile = $twitchIdentity;
        $profile = $user->profile;
        if ($twitchIdentity->display_name) {
            $profile->first_name = $twitchIdentity->display_name;
        }
        if ($twitchIdentity->bio) {
            $profile->about = $twitchIdentity->bio;
        }
        if ($twitchIdentity->logo) {
            $profile->avatar = $twitchIdentity->logo;
        }

        $user->save();
        $profile->save();

        return $user;
    }

    public function twitchCallback(Request $request) {
        $twitch = app('twitch');
        $code = $request->get('code');
        $state = $request->get('state');
        $result = $twitch->checkAuth($code, $state);
        if ($result) {
            $identity = $twitch->getIdentity();
            if (!$identity) {
                return Redirect::back()->withErrors(['twitch' => 'Failed twitch login']);
            }
            $localUser = User::oauth($identity->_id, 'twitch')->first();
            if ($localUser) {
                Auth::loginUsingId($localUser->id);
                $this->updateTwitchProfile($localUser, $identity);
                return redirect($this->redirectPath);
            } else {
                $data = [
                    'name' => $identity->name,
                    'email' => $identity->email,
                    'password' => ''
                ];
                $localUser = $this->create($data);
                $localUser->role = 'user';
                $localUser->type = 'twitcher';
                $localUser->provider = 'twitch';
                $localUser->oauth_id = $identity->_id;
                $localUser->save();
                Auth::loginUsingId($localUser->id);
                $this->updateTwitchProfile($localUser, $identity);

                return redirect($this->redirectPath);
            }
        } else {
            return Redirect::back()->withErrors(['twitch' => 'Failed twitch login']);
        }

    }
}
