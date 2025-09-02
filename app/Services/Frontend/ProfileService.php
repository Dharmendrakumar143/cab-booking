<?php 

namespace App\Services\Frontend;

use Carbon\Carbon;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class ProfileService
{
    public function updateUserProfile($request)
    {
        try {
            // Get the currently authenticated user
            $user = Auth::user();
            $user_id = $user->id;

            // Retrieve the user from the database
            $user = User::find($user_id);

            // Return an error if the user does not exist
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User does not exist.',
                ];
            }

            // Prepare the updated data from the request
            $user_data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
            ];

            // Update the user's profile with the new data
            $user->update($user_data);

            // Return a success message
            return [
                'success' => true,
                'message' => 'Profile updated successfully.',
            ];

        } catch (\Exception $e) {
            // Handle any exceptions that occur and return a generic error message
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

    public function changeUserPassword($request)
    {
        try {
            // Get the currently authenticated user
            $user = Auth::user(); // This will retrieve the authenticated user for the 'user' guard.
            $user_id = $user->id;

            // Find the user record in the database
            $user = User::find($user_id);

            // Return an error if the user does not exist
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User does not exist.',
                ];
            }

            // Check if the current password matches the existing password
            if (!Hash::check($request->current_password, $user->password)) {
                return [
                    'success' => false,
                    'message' => 'Current password is incorrect.',
                ];
            }

            // Update the user's password
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            // Return a success message
            return [
                'success' => true,
                'message' => 'Password changed successfully.',
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
?>
