<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('Seller'))) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}
