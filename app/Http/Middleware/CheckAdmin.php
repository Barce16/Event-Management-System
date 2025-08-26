<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the logged-in user is an admin
        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return $next($request);
        }

        // Redirect to dashboard if not an admin
        return redirect('/dashboard')->with('error', 'You do not have admin privileges.');
    }
}
