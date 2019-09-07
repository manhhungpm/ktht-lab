<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class CheckPermission
{
    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if ($this->auth->guest() || !$request->user()->can(explode('|', $permissions))) {
            abort(403);
        }

        return $next($request);
    }
}