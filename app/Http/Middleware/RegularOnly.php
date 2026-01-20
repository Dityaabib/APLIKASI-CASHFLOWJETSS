<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RegularOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }
        if (Auth::user()->level === 'administrator') {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
