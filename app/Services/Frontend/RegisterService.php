<?php 

namespace App\Services\Frontend;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Mail\OtpMail;
use App\Models\User;
use App\Models\OTP;

class RegisterService
{
    
    /**
     * Registers a new user with the provided details.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing user data.
     * @return array The result of the registration operation.
     */
    public function register($request)
    {
        try {

            // Retrieve the driver role
            $userRole = Role::where('name', 'customer')->first();
        
            if (!$userRole) {
                return [
                    'success' => false,
                    'message' => 'User role does not exist.',
                ];
            }

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            if ($user) {

                // Assign the 'independent-contractors' role to the driver
                if (!$user->hasRole('customer')) {
                    $user->assignRole($userRole);
                }

                // User registered successfully, return success message
                return [
                    'success' => true,
                    'message' => 'User registered successfully.',
                ];
            }
    
            // Return failure message if registration fails
            return [
                'success' => false,
                'message' => 'User registration failed.',
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
     * Sends an OTP to the user's email for verification.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the email address.
     * @return array The result of the OTP sending operation.
     */
    public function sendOTP($request)
    {
        try {
            $email = $request->email ?? null;
            $user = User::where('email',$email)->first();

            if (!$user) {
                // Return failure message if the user is not found
                return [
                    'success' => false,
                    'message' => 'User does not exist with this email address.',
                ];
            }

            // Generate a 4-digit OTP
            $otp = rand(1000, 9999);
            
            // Check if an existing OTP record exists for this user
            $existingOtpEntry = OTP::where('user_id', $user->id)->first();

            if ($existingOtpEntry) {
                // Update the OTP if an entry already exists
                $existingOtpEntry->update([
                    'otp' => $otp,
                    'otp_expiry' => Carbon::now()->addMinutes(2), // Reset expiry time to 2 minutes
                ]);
            } else {
                // Create a new OTP record if none exists
                OTP::create([
                    'user_id' => $user->id,
                    'otp' => $otp,
                    'otp_expiry' => Carbon::now()->addMinutes(2), // OTP valid for 2 minutes
                ]);
            }
           
            // Send OTP to the user's email
            Mail::to($user->email)->send(new OtpMail($otp));

            // Store the email in the session for future reference
            $request->session()->put('user_email',$user->email);

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
     * Resends the OTP to the user's email.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     * @return array The result of the resend OTP operation.
     */
    public function resendOTP($request)
    {
        try {
            // Retrieve the stored email address from the session
            $email = $request->session()->get('user_email');
            $user = User::where('email',$email)->first();

            if (!$user) {
                // Return failure message if the user is not found
                return [
                    'success' => false,
                    'message' => 'User does not exist with this email address.',
                ];
            }

            // Generate a new OTP
            $otp = rand(1000, 9999);
            
            // Check if an existing OTP record exists for this user
            $existingOtpEntry = OTP::where('user_id', $user->id)->first();

            if ($existingOtpEntry) {
                // Update the OTP if an entry already exists
                $existingOtpEntry->update([
                    'otp' => $otp,
                    'otp_expiry' => Carbon::now()->addMinutes(2), // Reset expiry time to 2 minutes
                ]);
            } else {
                // Create a new OTP record if none exists
                OTP::create([
                    'user_id' => $user->id,
                    'otp' => $otp,
                    'otp_expiry' => Carbon::now()->addMinutes(2), // OTP valid for 2 minutes
                ]);
            }
           
            // Send OTP to the user's email
            Mail::to($user->email)->send(new OtpMail($otp));

            // Store the email in the session for future reference
            $request->session()->put('user_email',$user->email);

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
     * Verifies the OTP entered by the user.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing OTP input.
     * @return array The result of the OTP verification operation.
     */
    public function otpVerify($request)
    {
        try {
            // Retrieve the email from the session
            $email = $request->session()->get('user_email');

            // Check if the user exists with the given email
            $user = User::where('email', $email)->first();
            $userId = $user->id ?? null;

            if (!$user) {
                // Return failure message if the user is not found
                return [
                    'success' => false,
                    'message' => 'User email does not exist.',
                ];
            }

            // Concatenate the OTP digits from the request
            $otp = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4;

            // Retrieve the OTP entry for the user
            $otpEntry = OTP::where('user_id', $userId)
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
            $user->is_email_verified = true;
            $user->email_verified_at = now(); 
            $user->save();
            
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

}
?>
