<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $redirectByRole = [
        'admin' => '/admin'
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
    public function handle($request, Closure $next)
    {
        if ($this->auth->check()) {
            $user = $this->auth->user();
            $redirectByRole = $this->redirectByRole;
            if (isset($redirectByRole[$user->role])) {
                return redirect($redirectByRole[$user->role]);
            } else {
                return redirect($this->redirectByDefault);
            }
        }

        return $next($request);
    }
}
