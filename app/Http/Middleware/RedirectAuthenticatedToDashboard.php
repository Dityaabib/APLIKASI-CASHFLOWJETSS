<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAuthenticatedToDashboard
{
    /**
     * Handle an incoming request.
     *
     * If user already authenticated, redirect to dashboard. Otherwise continue.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::user()->level === 'administrator') {
                return redirect()->intended('/admin');
            }
            return redirect()->intended('/dashboard');
        }

        return $next($request);
    }
}
