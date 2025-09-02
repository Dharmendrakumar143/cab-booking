<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PreventRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();

            $admin_role = $admin->roles->first()->name ?? null;

            // Ensure the user has the correct role
            if ($admin_role === 'super-admin' || $admin_role === 'admin') {
                return $next($request);
            }

            return redirect()->route('dashboard');
        }
    }
}
