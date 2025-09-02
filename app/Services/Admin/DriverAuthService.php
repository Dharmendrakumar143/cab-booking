<?php 

namespace App\Services\Admin;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\OtpMail;
use App\Models\Admin;
use App\Models\DriverOTP;

use App\Mail\DriverRegister;

class DriverAuthService
{
    /**
     * Registers a new driver user.
     *
     * @param \Illuminate\Http\Request $request
     * @return array The result of the driver registration process
     */
    public function registerDriverUser($request)
    {
        try {
            // Retrieve the driver role
            $driverRole = Role::where('name', 'independent-contractors')->first();
            
            if (!$driverRole) {
                return [
                    'success' => false,
                    'message' => 'Driver role does not exist.',
                ];
            }
    
            // Create the driver
            $driver = Admin::create([
                'name' =>$request->input('first_name').' '.$request->input('last_name'), 
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);
    
            // Ensure the driver instance was created
            if (!$driver) {
                return [
                    'success' => false,
                    'message' => 'Driver registration failed. Please try again.',
                ];
            }
    
            // Assign the 'independent-contractors' role to the driver
            if (!$driver->hasRole('independent-contractors')) {
                $driver->assignRole($driverRole);
            }

            $admin_email  = config('global-constant.admin_email');
            // Send OTP to the user's email
            Mail::to($admin_email)->send(new DriverRegister($driver));
    
            return [
                'success' => true,
                'message' => 'Driver registered successfully.',
            ];
        } catch (\Exception $e) {
            // Catch any exception and return the error message
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Sends an OTP to the driver's email address for verification.
     *
     * @param \Illuminate\Http\Request $request
     * @return array The result of the OTP sending process
     */
    public function driverSentOTP($request)
    {
        try {
            $email = $request->email ?? null;
            $driver = Admin::where('email',$email)->first();

            if (!$driver) {
                // Return failure message if the user is not found
                return [
                    'success' => false,
                    'message' => 'Driver does not exist with this email address.',
                ];
            }

            // Generate a 4-digit OTP
            $otp = rand(1000, 9999);
            
            // Check if an existing OTP record exists for this user
            $existingOtpEntry = DriverOTP::where('driver_id', $driver->id)->first();

            if ($existingOtpEntry) {
                // Update the OTP if an entry already exists
                $existingOtpEntry->update([
                    'otp' => $otp,
                    'otp_expiry' => Carbon::now()->addMinutes(2), // Reset expiry time to 2 minutes
                ]);
            } else {
                // Create a new OTP record if none exists
                DriverOTP::create([
                    'driver_id' => $driver->id,
                    'otp' => $otp,
                    'otp_expiry' => Carbon::now()->addMinutes(2), // OTP valid for 2 minutes
                ]);
            }
           
            // Send OTP to the user's email
            Mail::to($driver->email)->send(new OtpMail($otp));

            // Store the email in the session for future reference
            $request->session()->put('user_email',$driver->email);

            // Return success message
            return [
                'success' => true,
                'message' => 'OTP sent successfully to your email address. Please check your email.',
            ];                

        } catch (\Exception $e) {
            // Catch any exception and return the error message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verifies the OTP entered by the driver.
     *
     * @param \Illuminate\Http\Request $request
     * @return array The result of the OTP verification process
     */
    public function driverOTPVerify($request)
    {
        try {
            // Retrieve the email from the session
            $email = $request->session()->get('user_email');

            // Check if the user exists with the given email
            $admin = Admin::where('email', $email)->first();
            $adminId = $admin->id ?? null;

            if (!$admin) {
                // Return failure message if the user is not found
                return [
                    'success' => false,
                    'message' => 'Driver email does not exist.',
                ];
            }

            // Concatenate the OTP digits from the request
            $otp = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4;

            // Retrieve the OTP entry for the user
            $otpEntry = DriverOTP::where('driver_id', $adminId)
                        ->where('otp', $otp)
                        ->first();

            if (!$otpEntry) {
                // Return failure message if OTP does not match
                return [
                    'success' => false,
                    'message' => 'Invalid OTP.',
                ];
            }

            // Check if OTP has expired
            if (Carbon::now()->greaterThan($otpEntry->otp_expiry)) {
                return [
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new OTP.',
                ];
            }

            // OTP is valid, update user verification status
            $admin->is_email_verified = true;
            $admin->email_verified_at = now(); 
            $admin->save();
            
            // Optionally, delete the OTP record after successful verification
            $otpEntry->delete();

            // Forget the email from the session after successful verification
            $request->session()->forget('user_email');

            return [
                'success' => true,
                'message' => 'OTP verified successfully.',
            ];

        } catch (\Exception $e) {
            // Catch any exception and return the error message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Resends an OTP to the driver's email address.
     *
     * @param \Illuminate\Http\Request $request
     * @return array The result of the OTP resend process
     */
    public function driverResendOTP($request)
    {
        try {
            // Retrieve the stored email address from the session
            $email = $request->session()->get('user_email');
            $admin = Admin::where('email',$email)->first();

            if (!$admin) {
                // Return failure message if the user is not found
                return [
                    'success' => false,
                    'message' => 'Driver does not exist with this email address.',
                ];
            }

            // Generate a new OTP
            $otp = rand(1000, 9999);
            
            // Check if an existing OTP record exists for this user
            $existingOtpEntry = DriverOTP::where('driver_id', $admin->id)->first();

            if ($existingOtpEntry) {
                // Update the OTP if an entry already exists
                $existingOtpEntry->update([
                    'otp' => $otp,
                    'otp_expiry' => Carbon::now()->addMinutes(2), // Reset expiry time to 2 minutes
                ]);
            } else {
                // Create a new OTP record if none exists
                DriverOTP::create([
                    'driver_id' => $admin->id,
                    'otp' => $otp,
                    'otp_expiry' => Carbon::now()->addMinutes(2), // OTP valid for 2 minutes
                ]);
            }
           
            // Send OTP to the user's email
            Mail::to($admin->email)->send(new OtpMail($otp));

            // Store the email in the session for future reference
            $request->session()->put('user_email',$admin->email);

            // Return success message
            return [
                'success' => true,
                'message' => 'OTP sent successfully to your email address. Please check your email.',
            ];                

        } catch (\Exception $e) {
            // Catch any exception and return the error message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handles the login process for the driver.
     *
     * @param \Illuminate\Http\Request $request
     * @return array The result of the login attempt
     */
    public function driverLogin($request)
    {
        try {
            // Extract only the email and password from the request
            $credentials = $request->only(['email', 'password']);
            
            $remember = $request->has('remember');

            // Attempt to log in the admin user using the 'admin' guard
            if (Auth::guard('admin')->attempt($credentials, $remember)) {
                // Retrieve the authenticated user
                $user = Auth::guard('admin')->user();

                // Check if the user has the "contract user" role
                if (!$user->hasRole('independent-contractors')) {
                    // Logout and return an error if the user does not have the correct role
                    Auth::guard('admin')->logout();
                    return [
                        'success' => false,
                        'message' => 'Access denied. You are not registered as a driver. Please log in with the correct credentials.',
                    ];
                }

                // Check if the email is verified (optional)
                if (!$user->is_email_verified) {
                    // Logout the user if the email is not verified
                    Auth::logout();

                    return [
                        'success' => false,
                        'message' => 'Your email is not verified. Please verify your email before logging in.',
                    ];
                }

                // Check if the user account is active
                if (!$user->status) {
                    // Logout and return an error if the account is disabled
                    Auth::guard('admin')->logout();
                    return [
                        'success' => false,
                        'message' => 'Your admin account is disabled. Please contact support for assistance.',
                    ];
                }

                $user->update([
                    'login_check'=>true
                ]);

                // If login is successful, return success response
                return [
                    'success' => true,
                    'message' => 'Login successful.',
                ];
            }

            // Return an error response for invalid credentials
            return [
                'success' => false,
                'message' => 'Invalid email or password. Please try again.',
            ];

        } catch (\Exception $e) {
            // Handle unexpected exceptions and return the error message
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Handles the login process for the employee.
     *
     * @param \Illuminate\Http\Request $request
     * @return array The result of the login attempt
     */
    public function employeeLogin($request)
    {
        try {
            // Extract only the email and password from the request
            $credentials = $request->only(['email', 'password']);
            
            $remember = $request->has('remember');

            // Attempt to log in the admin user using the 'admin' guard
            if (Auth::guard('admin')->attempt($credentials, $remember)) {
                // Retrieve the authenticated user
                $user = Auth::guard('admin')->user();

                // Check if the user has the "contract user" role
                if (!$user->hasRole('employees')) {
                    // Logout and return an error if the user does not have the correct role
                    Auth::guard('admin')->logout();
                    return [
                        'success' => false,
                        'message' => 'Access denied. You are not registered as a employee. Please log in with the correct credentials.',
                    ];
                }

                // Check if the email is verified (optional)
                if (!$user->is_email_verified) {
                    // Logout the user if the email is not verified
                    Auth::logout();

                    return [
                        'success' => false,
                        'message' => 'Your email is not verified. Please verify your email before logging in.',
                    ];
                }

                // Check if the user account is active
                if (!$user->status) {
                    // Logout and return an error if the account is disabled
                    Auth::guard('admin')->logout();
                    return [
                        'success' => false,
                        'message' => 'Your admin account is disabled. Please contact support for assistance.',
                    ];
                }

                $user->update([
                    'login_check'=>true
                ]);

                // If login is successful, return success response
                return [
                    'success' => true,
                    'message' => 'Login successful.',
                ];
            }

            // Return an error response for invalid credentials
            return [
                'success' => false,
                'message' => 'Invalid email or password. Please try again.',
            ];

        } catch (\Exception $e) {
            // Handle unexpected exceptions and return the error message
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }


    /**
     * Toggle the driver's ride request availability status.
     *
     * This method retrieves the authenticated admin user, toggles their `login_check` status, 
     * updates it in the database, and returns a response indicating success or failure.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object (not explicitly used here but can be extended).
     * @return array An array containing the success status and a message.
     */
    public function updateDriverRideRequest($request)
    {
        try {
            // Retrieve the authenticated admin user
            $user = Auth::guard('admin')->user();

            // Check if user is found
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User not found.',
                ];
            }

            // Toggle the login_check status (1 to 0, or 0 to 1)
            $login_check = $user->login_check ? 0 : 1;

            // Update the user's status in the database
            $user->update(['login_check' => $login_check]);

            // Return a success response
            return [
                'success' => true,
                'message' => 'Status changed successfully.',
            ];
        
        } catch (\Exception $e) {
            // Handle unexpected exceptions and return an error message
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Toggle the "auto reject ride" setting for the authenticated admin user.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance (unused but required for signature compatibility).
     * @return array An array containing the success status and a message.
     */
    public function updateAutoRejectRide($request)
    {
        try {
            // Retrieve the authenticated admin user
            $user = Auth::guard('admin')->user();

            // Check if user is found
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User not found.',
                ];
            }

            // Toggle the login_check status (1 to 0, or 0 to 1)
            $auto_reject_ride = $user->auto_reject_ride ? 0 : 1;

            // Update the user's status in the database
            $user->update(['auto_reject_ride' => $auto_reject_ride]);

            if($auto_reject_ride){
                $message = 'Auto-reject ride feature enabled successfully.';
            }else{
                $message = 'Auto-reject ride feature disabled successfully.';
            }

            // Return a success response
            return [
                'success' => true,
                'message' => $message,
            ];
        
        } catch (\Exception $e) {
            // Handle unexpected exceptions and return an error message
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }


}
