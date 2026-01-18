<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || Auth::user()->level !== 'administrator') {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}

