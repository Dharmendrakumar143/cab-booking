<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use ReCaptcha\ReCaptcha;

class DriverAuthController extends AdminBaseController
{
    // Redirect the user to the Google authentication page
    public function driverRedirectToGoogle(Request $request)
    { 
        $request->session()->put('login_type','driver');

        return Socialite::driver('google')->with(['login_type' => 'driver'])->redirect();
    }

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
     * Register a new driver user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerDriver(Request $request)
    {
        // Validate user input data
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8',
            // 'g-recaptcha-response' => 'required'
        ]);

        // Verify reCAPTCHA
        $recaptcha = new ReCaptcha(env('RECAPTCHA_SECRET_KEY'));
        $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());

        if (!$response->isSuccess()) {
            $request->session()->flash('error', 'reCAPTCHA verification failed');
            return redirect()->back();
        }
        
        // Attempt to register the driver
        $result = $this->driver_auth_service->registerDriverUser($request);

        // If registration is successful, redirect to the OTP sending page
        if ($result['success']) {
            $request->session()->flash('success', 'Your account has been created successfully. Please verify your account to proceed.');
            return redirect()->route('driver-sent-otp');
        }

        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    /**
     * Display the OTP sending form for driver verification.
     *
     * @return \Illuminate\View\View
     */
    public function driverSentOTPForm()
    {
        return view('Frontend.AuthCustomer.RegisterVerification.sent-otp');
    }

    /**
     * Send OTP to the driver's email address.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function driverSentOTPOnMail(Request $request)
    {
        // Validate the email address
        $request->validate([
            'email' => 'required|email|exists:admins,email',
        ]);

        // Call the service method to send the OTP
        $result = $this->driver_auth_service->driverSentOTP($request);

        // If OTP is sent successfully, redirect to the OTP verification page
        if ($result['success']) {
            $request->session()->flash('success', 'OTP has been successfully sent to your provided email address. Please check your email.');
            return redirect()->route('driver-otp-verification');
        }

        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    /**
     * Display the OTP verification form for the driver.
     *
     * @return \Illuminate\View\View
     */
    public function driverOTPVerificationForm()
    {
        return view('Frontend.AuthCustomer.RegisterVerification.otp-verification');
    }

    /**
     * Verify the OTP entered by the driver.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function driverOTPVerification(Request $request)
    {
        // Validate the OTP fields
        $request->validate([
            'otp1' => 'required|numeric|min:0|max:9',
            'otp2' => 'required|numeric|min:0|max:9',
            'otp3' => 'required|numeric|min:0|max:9',
            'otp4' => 'required|numeric|min:0|max:9',
        ]);

        // Call the service method to verify the OTP
        $result = $this->driver_auth_service->driverOTPVerify($request);

        // If OTP verification is successful, redirect to the OTP verified message page
        if ($result['success']) {
            $request->session()->flash('success', 'Your email has been successfully verified! You can now proceed.');
            return redirect()->route('driver-otp-verified');
        }

        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    /**
     * Display the OTP verified message after successful verification.
     *
     * @return \Illuminate\View\View
     */
    public function driverOTPVerifiedMessage()
    {
        return view('Frontend.AuthCustomer.RegisterVerification.otp-verified-message');
    }

    /**
     * Resend the OTP to the driver's email.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function driverResendOTPOnMail(Request $request)
    {
        // Call the service method to resend the OTP
        $result = $this->driver_auth_service->driverResendOTP($request);

        // If OTP is resent successfully, redirect to the OTP verification page
        if ($result['success']) {
            $request->session()->flash('success', 'OTP has been resent to your email address.');
            return redirect()->route('driver-otp-verification');
        }

        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    /**
     * Display the login form for drivers.
     *
     * @return \Illuminate\View\View
     */
    public function driverLoginForm()
    {
        return view('Frontend.AuthCustomer.login');
    }

    /**
     * Attempt to log the driver into the system.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function driverLoginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->driver_auth_service->driverLogin($request);

        // If login is successful, redirect to the dashboard
        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return redirect()->route('dashboard');
        }

        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }


    public function EmployeeLoginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->driver_auth_service->employeeLogin($request);

        // If login is successful, redirect to the dashboard
        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return redirect()->route('dashboard');
        }

        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }


    /**
     * Disable or update the driver's ride request status.
     *
     * This method is responsible for updating the driver's ride request availability.
     * It interacts with the driver authentication service to toggle the request status.
     *
     * @param Request $request The HTTP request containing necessary data to update the status.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     */
    public function driverRideDisable(Request $request)
    {
        // Call the driver authentication service to update ride request status
        $response = $this->driver_auth_service->updateDriverRideRequest($request);

        // Check if the update was successful
        if ($response['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Status changed successfully.',
            ]);
        }

        // Return an error response if something went wrong
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please check and try again.',
        ]);
    }

    /**
     * Enable or disable the auto-reject ride feature for the authenticated driver.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse JSON response indicating the operation result.
     */
    public function autoRejectRideEnable(Request $request)
    {
        $response = $this->driver_auth_service->updateAutoRejectRide($request);

        // Check if the update was successful
        if ($response['success']) {
            return response()->json([
                'success' => true,
                'message' => $response['message'],
            ]);
        }

        // Return an error response if something went wrong
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please check and try again.',
        ]);
    }

    
}
