<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class UserProfileController extends BaseController
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $user = User::find($user_id);     
        return view('Frontend.user.profile',compact('user'));
    }

    public function updateProfile(Request $request)
    {

        // Validate the incoming request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required',
            'gender' => 'required|in:male,female,other',
        ]);

        $result = $this->profile_service->updateUserProfile($request);

        // Handle the response from the service
        if ($result['success']) {
            // Flash a success message and redirect to the profile page
            $request->session()->flash('success', $result['message']);
            return redirect()->route('my-profile');
        }

        // Flash an error message and redirect back to the profile update page
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    public function changeUserPassword(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|same:password',
        ]);

        // Call the profile service to change the password
        $result = $this->profile_service->changeUserPassword($request);

        // Handle the response from the service
        if ($result['success']) {
            // Flash a success message and redirect to the profile page
            $request->session()->flash('success', $result['message']);
            return redirect()->route('my-profile');
        }

        // Flash an error message and redirect back to the password change page
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }


    public function uploadUserProfileImage(Request $request, $id)
    {
        // Ensure the user is authenticated and uploading for themselves
        if (Auth::id() !== (int)$id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ]);
        }

        // Validate the image file
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max size
        ]);
        
        // Retrieve the authenticated user
        $user = User::find($id);

        // If the user is not found
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ]);
        }

        // Check if the request contains a file
        if ($request->hasFile('profile_picture')) {
            // Check and delete the old profile picture if it exists
            if ($user->profile_pic) {
                Storage::delete('profile/' . $user->profile_pic); // Delete old image
            }

            // Generate a unique file name for the uploaded image
            $file_name = uniqid() . "." . $request->file('profile_picture')->getClientOriginalExtension();

            // Store the new profile picture in the 'profile' folder
            $request->file('profile_picture')->storeAs('profile', $file_name);

            // Update the user's profile picture in the database
            $user->profile_pic = $file_name;
            $user->save();

            // Return the URL of the newly uploaded image
            $imageUrl = asset('storage/profile/' . $file_name);

            return response()->json([
                'success' => true,
                'image_url' => $imageUrl,
            ]);
        }

        // Return error if no image was uploaded
        return response()->json([
            'success' => false,
            'message' => 'No image uploaded.',
        ]);
    }
}
