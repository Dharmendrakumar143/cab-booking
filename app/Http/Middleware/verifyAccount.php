<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class verifyAccount
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
            if ($admin_role === 'admin' || $admin_role === 'super-admin') {
                return $next($request);
            }else{
                $verification_status = $admin->verification_status;
                if($verification_status=='verified' || $verification_status=='suspended'){
                    return $next($request);
                }else{
                    $request->session()->flash('error', 'Please upload and verify your documents before proceeding.');
                    return redirect()->route('admin-profile');
                }
            }

        }
    }
}
