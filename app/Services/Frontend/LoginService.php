<?php 

namespace App\Services\Frontend;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Mail\OtpMail;
use App\Models\User;
use App\Models\OTP;

class LoginService
{
    
    public function login($request)
    {
        try {
            // Validate the login input
            $credentials = $request->only(['email', 'password']);
            
            // Check if the user wants to be remembered
            $remember = $request->has('remember');

            // Attempt to authenticate the user
            if (Auth::attempt($credentials, $remember)) {
                $user = Auth::user(); // Retrieve the authenticated user

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
                    Auth::logout(); // Logout the user if the account is disabled
                    return [
                        'success' => false,
                        'message' => 'Your account is currently disabled. Please contact support for assistance.',
                    ];
                }


                // Login successful, return success message
                return [
                    'success' => true,
                    'message' => 'Login successful.',
                ];
            }

           // Invalid credentials case
            return [
                'success' => false,
                'message' => 'Invalid email or password. Please try again.',
            ];

        } catch (\Exception $e) {
            // Catch any exception and return the error message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


}
?>
