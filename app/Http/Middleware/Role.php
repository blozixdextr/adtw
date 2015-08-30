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
        'admin' => '/admin',
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
            $user = $this->auth->user();
            if (!in_array($user->role, $roles)) {
                $redirectByRole = $this->redirectByRole;
                if (isset($redirectByRole[$user->role])) {
                    return redirect($redirectByRole[$user->role]);
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
