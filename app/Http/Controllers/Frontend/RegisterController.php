<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use ReCaptcha\ReCaptcha;
use App\Models\User;

class RegisterController extends BaseController
{
    /**
     * Display the user registration form.
     *
     * @return \Illuminate\View\View
     */
    public function registerForm()
    {
        return view('Frontend.AuthCustomer.register');
    }



     /**
     * Display the form for sending OTP to the user's email.
     *
     * @return \Illuminate\View\View
     */
    public function sentOTPForm()
    {
        return view('Frontend.AuthCustomer.RegisterVerification.sent-otp');
    }

    /**
     * Display the OTP verification form.
     *
     * @return \Illuminate\View\View
     */
    public function otpVerificationForm()
    {
        return view('Frontend.AuthCustomer.RegisterVerification.otp-verification');
    }

    /**
     * Display a message indicating that the OTP has been successfully verified.
     *
     * @return \Illuminate\View\View
     */
    public function otpVerifiedMessage()
    {
        return view('Frontend.AuthCustomer.RegisterVerification.otp-verified-message');
    }

    /**
     * Register a new user by validating the input and saving the user information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerUser(Request $request)
    {
        // Validate user input data
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'g-recaptcha-response' => 'required'
        ]);

        // Verify reCAPTCHA
        $recaptcha = new ReCaptcha(env('RECAPTCHA_SECRET_KEY'));
        $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
   
        if (!$response->isSuccess()) {
            $request->session()->flash('error', 'reCAPTCHA verification failed');
            return redirect()->back();
        }
        
        // Call the service method to register the user
        $result = $this->auth_service->register($request);

        // If registration is successful, redirect to the OTP sending page
        if ($result['success']) {
            $request->session()->flash('success', 'Your account has been created successfully. Please verify your account to proceed.');
            return redirect()->route('sent-otp');
        }

         // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();

    }

    /**
     * Send OTP to the user's email for verification.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendOTPOnMail(Request $request)
    {
        // Validate the email address
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Call the service method to send the OTP
        $result = $this->auth_service->sendOTP($request);

        // If OTP is sent successfully, redirect to the OTP verification page
        if ($result['success']) {
            $request->session()->flash('success', 'OTP has been successfully sent to your provided email address. Please check your email.');
            return redirect()->route('otp-verification');
        }

         // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();

    }

    /**
     * Verify the OTP entered by the user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function otpVerification(Request $request)
    {
        // Validate the OTP fields
        $request->validate([
            'otp1' => 'required|numeric|min:0|max:9',
            'otp2' => 'required|numeric|min:0|max:9',
            'otp3' => 'required|numeric|min:0|max:9',
            'otp4' => 'required|numeric|min:0|max:9',
        ]);

        // Call the service method to verify the OTP
        $result = $this->auth_service->otpVerify($request);

        // If OTP verification is successful, redirect to the OTP verified message page
        if ($result['success']) {
            $request->session()->flash('success', 'Your email has been successfully verified! You can now proceed.');
            return redirect()->route('otp-verified');
        }

         // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();

    }

    /**
     * Resend the OTP to the user's email.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendOTPOnMail(Request $request)
    {
        // Call the service method to resend the OTP
        $result = $this->auth_service->resendOTP($request);

        // If OTP is resent successfully, redirect to the OTP verification page
        if ($result['success']) {
            $request->session()->flash('success', 'OTP has been resent to your email address.');
            return redirect()->route('otp-verification');
        }


         // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();

    }
}
