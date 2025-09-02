<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends BaseController
{
    /**
     * Displays the Forgot Password form.
     *
     * @return \Illuminate\View\View
     */
    public function forgotPasswordForm()
    {
        return view('Frontend.AuthCustomer.forgot-password');
    }

    /**
     * Displays the Reset Password form.
     *
     * @param string|null $token The token used for password reset (if available).
     * @return \Illuminate\View\View
     */
    public function resetPasswordForm($token = null)
    {
        return view('Frontend.AuthCustomer.reset-password', compact('token'));
    }

    /**
     * Handles the Forgot Password request.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing the user's email.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forgotPassword(Request $request)
    {
        // Validate user input data to ensure the email is in the correct format
        $request->validate([
            'email' => 'required|email',
        ]);
        
        // Call the service to process the password reset request
        $result = $this->forgot_password_service->forgotUserPassword($request);

        // Check if the password reset request was successful
        if ($result['success']) {
            // Flash a success message to the session and redirect back
            $request->session()->flash('success', $result['message']);
            return redirect()->back();
        }

        // Flash an error message to the session and redirect back
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    /**
     * Handles the Reset Password request.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing the new password and its confirmation.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        // Validate user input data to ensure the password meets the required criteria
        $request->validate([
            'password' => 'required|min:8|confirmed', // Password must be at least 8 characters long and confirmed
            'password_confirmation' => 'required_with:password|same:password', // Password confirmation must match the password
        ]);

        // Call the service to reset the user's password
        $result = $this->forgot_password_service->resetUserPassword($request);

        // Check if the password reset was successful
        if ($result['success']) {
            // Flash a success message to the session and redirect to the login page
            $request->session()->flash('success', $result['message']);
            return redirect()->route('login');
        }

        // Flash an error message to the session and redirect back
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }
}
