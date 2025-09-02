<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminLoginController extends AdminBaseController
{
    /**
     * Display the login form for admin users.
     *
     * @return \Illuminate\View\View
     */
    public function loginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login requests.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing login credentials.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAdmin(Request $request)
    {
        // Validate the login credentials
        $request->validate([
            'email' => 'required|email', // Ensure a valid email is provided
            'password' => 'required',    // Password is required
        ]);

        // Call the login service to authenticate the admin user
        $result = $this->login_service->loginAdminUser($request);

        if ($result['success']) {
            // Flash a success message and redirect to the dashboard
            $request->session()->flash('success', $result['message']);
            return redirect()->route('dashboard');
        }

        // Flash an error message and redirect back to the login page
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    /**
     * Log out the currently authenticated admin user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutAdmin()
    {
        // Retrieve the authenticated admin user
        $admin = Auth::guard('admin')->user();

         // Check if the admin has the 'independent-contractors' role
        if ($admin->hasRole('independent-contractors')) {
            // If the admin has the 'independent-contractors' role, log them out
            Auth::guard('admin')->logout();

            $admin->update([
                'login_check'=>false
            ]);
            
            // Redirect to the login page with an error message
            return redirect()->route('driver-login')->with('success', 'Logged out successfully');
        }

        // Check if the admin has the 'employees' role
        if ($admin->hasRole('employees')) {
            Auth::guard('admin')->logout();

            $admin->update([
                'login_check'=>false
            ]);
            
            // Redirect to the login page with an error message
            return redirect()->route('employee-login')->with('success', 'Logged out successfully');
        }

        // Log out the admin user
        Auth::guard('admin')->logout();

        // Redirect to the login page with a success message
        return redirect()->route('admin-login')->with('success', 'Logged out successfully.');
    }
}
