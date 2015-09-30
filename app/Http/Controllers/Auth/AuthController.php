<?php

namespace App\Http\Controllers\Auth;


use App\Models\Mappers\NotificationMapper;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use Mail;
use App\Models\Mappers\UserMapper;
use App\Models\Mappers\LogMapper;
use App\Models\User;
use App\Models\UserProfile;


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

    public function twitch()
    {
        $twitch = app('twitch');
        $url = $twitch->getLoginUrl();

        return redirect($url);
    }

    public function updateTwitchProfile($user, $twitchIdentity)
    {
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

    public function twitchCallback(Request $request)
    {
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
                if ($localUser->is_active == 0) {
                    LogMapper::log('client_login', $localUser->id, 'banned');
                    return redirect('/')->withErrors(['login' => 'Your account is deactivated. Please contact admin ASAP']);
                }
                Auth::loginUsingId($localUser->id);
                $this->updateTwitchProfile($localUser, $identity);
                $localUser->last_activity = \Carbon\Carbon::now();
                LogMapper::log('twitch_login', $localUser->id);
                return redirect('/user/twitcher');
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
                LogMapper::log('twitch_register', $localUser->id);
                NotificationMapper::registration($localUser);

                return redirect('/user/twitcher/profile');
            }
        } else {
            return Redirect::back()->withErrors(['twitch' => 'Failed twitch login']);
        }

    }

    public function randomPassword()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }

    public function sendClientWelcome($user, $password)
    {
        $token = UserMapper::generateAuthToken($user);

        Mail::send('app.emails.auth_client', ['user' => $user, 'token' => $token, 'password' => $password], function ($m) use ($user) {
            $m->to($user->email)->subject('Confirm your email address');
        });

    }

    public function clientConfirm($userId, $token)
    {
        $user = User::findOrFail($userId);
        $result = UserMapper::checkAuthToken($user, $token);
        if ($result) {
            if ($user->is_active == 1) {
                return Redirect::to('/')->withErrors(['client' => 'You already confirmed your email']);
            }
            $user->is_active = 1;
            $user->save();
            $user->authToken()->delete();
            Auth::loginUsingId($user->id, true);
            LogMapper::log('client_login', $user->id, 'finish');
            return redirect('/user/client');
        } else {
            LogMapper::log('client_confirm_failed', $user->id, 'token_mismatch');
            return Redirect::to('/')->withErrors(['client' => 'Wrong confirmation token']);
        }
    }

    public function clientSignUp()
    {
        return view('app.pages.user.client.sign_up');
    }

    public function clientLogin()
    {
        return view('app.pages.user.client.login');
    }

    public function postClientSignUp(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6|max:20',
            'password2' => 'required|same:password'
        ];
        $this->validate($request, $rules);
        $name = $request->get('name', '');
        $email = $request->get('email');
        $password = $request->get('password');
        $localUser = UserMapper::getByEmail($email);
        if ($localUser && $localUser->type == 'client') {
            return Redirect::back()->withErrors(['email' => 'Client with this email already exists'])->withInput($request->all());
        }
        if ($name == '') {
            $name = anonymizeEmail($email);
        }
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];
        $localUser = $this->create($data);
        $localUser->is_active = 0;
        $localUser->role = 'user';
        $localUser->type = 'client';
        $localUser->save();
        LogMapper::log('client_register', $localUser->id);
        NotificationMapper::registration($localUser);
        $this->sendClientWelcome($localUser, $password);

        $user = $localUser;

        return view('app.pages.auth.client', compact('user'));
    }

    public function postClientLogin(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6|max:20'
        ];
        $this->validate($request, $rules);
        $email = $request->get('email');
        $password = $request->get('password');
        if (Auth::attempt(['email' => $email, 'password' => $password], false, false)) {
            $user = Auth::getLastAttempted();
            if ($user->is_active == 0) {
                return Redirect::to('/auth/client/login')->withErrors(['email' => 'Your account is not active']);
            }
            Auth::login($user, true);
            return Redirect::to('/user/client');
        }
        LogMapper::log('client_login_failed', $email);

        return Redirect::to('/auth/client/login')->withErrors(['email' => 'Wrong email or password']);
    }

    public function admin()
    {
        return view('app.pages.auth.admin');
    }

    public function postAdmin(Request $request)
    {
        $rules = [
            'login' => 'required|email',
            'password' => 'required|alpha_num|max:12|min:6',
        ];
        $this->validate($request, $rules);
        $throttles = $this->isUsingThrottlesLoginsTrait();
        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }
        $email = $request->get('login');
        $password = $request->get('password');
        if (Auth::attempt(['email' => $email, 'password' => $password], $request->has('remember'))) {
            if ($throttles) {
                $this->clearLoginAttempts($request);
            }
            $user = Auth::user();
            LogMapper::log('admin_login', $user->id);
            return redirect('/admin');
        }
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect('/auth/admin')
            ->withInput($request->only('login', 'remember'))
            ->withErrors(['login' => 'Wrong email or password']);
    }

}
