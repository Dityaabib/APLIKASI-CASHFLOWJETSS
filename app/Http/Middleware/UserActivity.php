<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $userId = Auth::id();
        if ($userId) {
            try {
                \App\Models\User::where('id', $userId)->update(['last_seen' => now()]);
            } catch (\Throwable $e) {
                // ignore
            }
            Cache::put('user-is-online-'.$userId, true, now()->addMinutes(5));
        }

        return $response;
    }
}
