<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverForgotPasswordController extends AdminBaseController
{
    /**
     * Display the "Forgot Password" form for the driver.
     *
     * This form allows the driver to initiate the password reset process 
     * by providing their registered email address.
     *
     * @return \Illuminate\View\View The view for the Forgot Password form.
     */
    public function driverForgotPasswordForm()
    {
        return view('Frontend.AuthCustomer.forgot-password');
    }

    /**
     * Display the "Reset Password" form for the driver.
     *
     * This form allows the driver to reset their password using a 
     * valid reset token provided in the password reset email.
     *
     * @param string|null $token The unique token for password reset (if provided).
     * @return \Illuminate\View\View The view for the Reset Password form.
     */
    public function driverResetPasswordForm($token = null)
    {
        return view('Frontend.AuthCustomer.reset-password', compact('token'));
    }

    /**
     * Handle the Forgot Password request for the driver.
     *
     * This method validates the driver's email address and initiates 
     * the password reset process by calling the appropriate service. 
     * A success or error message is flashed to the session based on the outcome.
     *
     * @param \Illuminate\Http\Request $request The request object containing the user's email.
     * @return \Illuminate\Http\RedirectResponse Redirects back to the Forgot Password form with a status message.
     */
    public function driverForgotPassword(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email',
        ]);
        
        // Call the service to process the Forgot Password request
        $result = $this->driver_forgot_password_service->forgotDriverPassword($request);

        // Flash the appropriate message and redirect back
        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return redirect()->back();
        }

        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    /**
     * Handle the Reset Password request for the driver.
     *
     * This method validates the new password and its confirmation, 
     * and calls the appropriate service to update the driver's password. 
     * A success or error message is flashed to the session based on the outcome.
     *
     * @param \Illuminate\Http\Request $request The request object containing the new password and its confirmation.
     * @return \Illuminate\Http\RedirectResponse Redirects to the login screen or back to the form with a status message.
     */
    public function driverResetPassword(Request $request)
    {
        // Validate the password input
        $request->validate([
            'password' => 'required|min:8|confirmed', // Ensure password is at least 8 characters long and matches confirmation
            'password_confirmation' => 'required_with:password|same:password', // Ensure confirmation matches the password
        ]);

        // Call the service to reset the password
        $result = $this->driver_forgot_password_service->resetDriverPassword($request);

        // Flash the appropriate message and redirect
        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return redirect()->route('driver-login');
        }

        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }
}
