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

class ForgotPasswordService
{
    /**
     * Handles the process of requesting a password reset.
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return array The success status and message of the operation.
     */
    public function forgotUserPassword($request)
    {
        try {
            // Find the user by email provided in the request
            $user = User::where('email', $request->email)->first();

            // If the user is not found, return an error message
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User not found.',
                ];
            }

            // Generate a random reset token of length 60 characters
            $resetToken = Str::random(60);

            // Store the reset token in the 'password_reset_tokens' table
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => $resetToken,
                    'created_at' => Carbon::now()  // Record the time the token was created
                ]
            );

            // Generate a reset password link including the token
            $resetLink = url('/user/reset-password/' . $resetToken);

            // Send the reset password link to the user's email
            Mail::to($user->email)->send(new PasswordResetMail($resetLink));

            return [
                'success' => true,
                'message' => 'Password reset link sent to your email.',
            ];

        } catch (\Exception $e) {
            // If there was an error, return the exception message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handles the process of resetting a user's password.
     *
     * @param \Illuminate\Http\Request $request
     * @return array The success status and message of the operation.
     */
    public function resetUserPassword($request)
    {
        try {
            // Find the password reset record associated with the provided token
            $resetRecord = DB::table('password_reset_tokens')->where('token', $request->token)->first();

            // If no record is found for the token, return an error message
            if (!$resetRecord) {
                return [
                    'success' => false,
                    'message' => 'Invalid or expired token.',
                ];
            }

            // Check if the token is expired (expiration time: 60 minutes)
            if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
                return [
                    'success' => false,
                    'message' => 'Token has expired.',
                ];
            }

            // Find the user associated with the email in the reset token record
            $user = User::where('email', $resetRecord->email)->first();

            // If the user is not found, return an error message
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User not found.',
                ];
            }

            // Update the user's password with the new password provided in the request
            $user->password = Hash::make($request->password);  // Hash the new password
            $user->save();  // Save the updated user record

            // Delete the reset token from the database to prevent reuse
            DB::table('password_reset_tokens')->where('token', $request->token)->delete();

            return [
                'success' => true,
                'message' => 'Password has been successfully reset.',
            ];

        } catch (\Exception $e) {
            // If there was an error, return the exception message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
