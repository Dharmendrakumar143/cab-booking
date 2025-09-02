<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;


class ProfileController extends AdminBaseController
{
    /**
     * Show the admin profile page with current user details.
     *
     * @return \Illuminate\View\View The profile view with admin user data.
     */
    public function profile()
    {
        // Get the currently authenticated admin user
        $admin_user = Auth::guard('admin')->user();
        $admin_user_id = $admin_user->id;

        // Retrieve admin user details from the database
        $admin_user = Admin::with('driverDocuments')->find($admin_user_id);

        // echo "<pre>admin_user==";
        // print_r($admin_user);
        // die;

        // Return the profile view with admin user data
        return view('admin.profile.index', compact('admin_user'));
    }

    /**
     * Update the admin profile information.
     *
     * @param \Illuminate\Http\Request $request The request object containing updated profile details.
     * @return \Illuminate\Http\RedirectResponse Redirects to the profile page or back with success/error message.
     */
    public function updateProfile(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required',
            'gender' => 'required|in:male,female,other',
        ]);

        // Call the profile service to update the profile
        $result = $this->profile_service->updateAdminProfile($request);

        // Handle the response from the service
        if ($result['success']) {
            // Flash a success message and redirect to the profile page
            $request->session()->flash('success', $result['message']);
            return redirect()->route('admin-profile');
        }

        // Flash an error message and redirect back to the profile update page
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    /**
     * Change the admin password.
     *
     * @param \Illuminate\Http\Request $request The request object containing current and new password details.
     * @return \Illuminate\Http\RedirectResponse Redirects to the profile page or back with success/error message.
     */
    public function changePassword(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|same:password',
        ]);

        // Call the profile service to change the password
        $result = $this->profile_service->changeAdminPassword($request);

        // Handle the response from the service
        if ($result['success']) {
            // Flash a success message and redirect to the profile page
            $request->session()->flash('success', $result['message']);
            return redirect()->route('admin-profile');
        }

        // Flash an error message and redirect back to the password change page
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    public function uploadProfileImage(Request $request, $id)
    {
        // Ensure the user is authenticated and is uploading for themselves (or has permissions)
        if (Auth::guard('admin')->id() !== (int)$id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ]);
        }

        // Validate the image file
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max size
        ]);

        // Retrieve the authenticated admin
        $admin = Admin::find($id);

        // If the admin is not found
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found.'
            ]);
        }

        // Check if the request contains a file
        if ($request->hasFile('profile_picture')) {

            // Check and delete the old profile picture if it exists
            if ($admin->profile_pic) {
                Storage::delete('profile/' . $admin->profile_pic); // Delete old image
            }

            // Generate a unique file name for the uploaded image
            $file_name = uniqid() . "." . $request->file('profile_picture')->getClientOriginalExtension();

            // Store the new profile picture in the 'profile' folder
            $request->file('profile_picture')->storeAs('profile', $file_name);

            // Update the admin's profile picture in the database
            $admin->profile_pic = $file_name;
            $admin->save();

            // Return the URL of the newly uploaded image
            $imageUrl = asset('storage/profile/' . $file_name);

            return response()->json([
                'success' => true,
                'image_url' => $imageUrl
            ]);
        }

        // Return error if no image was uploaded
        return response()->json([
            'success' => false,
            'message' => 'No image uploaded.'
        ]);
    }


    public function uploadDocuments(Request $request)
    {
        // Validate the image file
        $request->validate([
            'license' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max size
            'registration' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max size
            'insurance' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max size
            'ownership_tesla_model' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max size
        ]);

        // Call the profile service to change the password
        $result = $this->profile_service->uploadDriverDocuments($request);

        // Handle the response from the service
        if ($result['success']) {
            // Flash a success message and redirect to the profile page
            $request->session()->flash('success', $result['message']);
            return redirect()->route('admin-profile');
        }

        // Flash an error message and redirect back to the password change page
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }


}
