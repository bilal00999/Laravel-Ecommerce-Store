<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (!auth($guard)->check()) {
            if ($guard === 'api') {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect('/login');
        }

        if (!auth($guard)->user()->is_admin) {
            if ($guard === 'api') {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
