<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Role
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $redirectByRole = [
        'client' => '/user/client',
        'twitcher' => '/user/twitcher',
    ];

    protected $redirectByDefault = '/';

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if ($this->auth->check()) {
            $roles = explode('|', $roles);
            $redirectByRole = $this->redirectByRole;
            $user = $this->auth->user();
            if ($user->role != 'user') {
                return redirect($redirectByRole[$user->role]);
            }
            if (!in_array($user->type, $roles)) {
                if (isset($redirectByRole[$user->type])) {
                    return redirect($redirectByRole[$user->type]);
                } else {
                    return redirect($this->redirectByDefault);
                }
            }
        } else {
            return redirect($this->redirectByDefault);
        }



        return $next($request);
    }
}
