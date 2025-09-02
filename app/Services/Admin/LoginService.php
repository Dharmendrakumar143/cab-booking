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
use App\Models\User;
use App\Models\OTP;

class LoginService
{
    /**
     * Authenticate an admin user.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing login credentials (email, password).
     * @return array An associative array with the following keys:
     *               - 'success' (bool): Whether the login was successful.
     *               - 'message' (string): A descriptive message regarding the login status.
     */
    public function loginAdminUser($request)
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
                if ($user->hasRole('independent-contractors') || $user->hasRole('employees')) {
                    // Logout and return an error if the user does not have the correct role
                    Auth::guard('admin')->logout();
                    return [
                        'success' => false,
                        'message' => 'Invalid email or password. Please try again.',
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
}
