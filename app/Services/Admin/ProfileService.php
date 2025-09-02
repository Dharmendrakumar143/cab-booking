<?php 

namespace App\Services\Admin;

use App\Events\UploadDocumentNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\DriverDocuments;

class ProfileService
{
    /**
     * Update the profile information of the currently authenticated admin user.
     *
     * @param \Illuminate\Http\Request $request The request object containing the updated profile data.
     * @return array An array containing the success status and a message.
     */
    public function updateAdminProfile($request)
    {
        try {
            // Get the authenticated admin user
            $admin_user = Auth::guard('admin')->user();
            $admin_id = $admin_user->id;

            // Find the admin record in the database
            $admin = Admin::find($admin_id);

            // Return an error if the admin does not exist
            if (!$admin) {
                return [
                    'success' => false,
                    'message' => 'User not exist.',
                ];
            }

            // Prepare the updated data
            $admin_user_data = [
                'name' => $request->name,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'car_model' => $request->car_model,
                'car_number_plate' => $request->car_number_plate,
            ];

            // Update the admin profile
            $admin->update($admin_user_data);

            // Return a success message
            return [
                'success' => true,
                'message' => 'Profile updated successfully.',
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
     * Change the password of the currently authenticated admin user.
     *
     * @param \Illuminate\Http\Request $request The request object containing the current and new password data.
     * @return array An array containing the success status and a message.
     */
    public function changeAdminPassword($request)
    {
        try {
            // Get the authenticated admin user
            $admin_user = Auth::guard('admin')->user();
            $admin_id = $admin_user->id;

            // Find the admin record in the database
            $admin = Admin::find($admin_id);

            // Return an error if the admin does not exist
            if (!$admin) {
                return [
                    'success' => false,
                    'message' => 'User not exist.',
                ];
            }

            // Check if the current password matches the existing password
            if (!Hash::check($request->current_password, $admin->password)) {
                return [
                    'success' => false,
                    'message' => 'Current password is incorrect.',
                ];
            }

            // Update the admin's password
            $admin->update([
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


    public function uploadDriverDocuments($request)
    {
        try {
            // Get the authenticated admin user
            $admin_user = Auth::guard('admin')->user();
            $admin_id = $admin_user->id;

            $data = [
                'driver_id'=>$admin_id,  
                'verification_status'=>'requested'
            ];

            // Check if a record already exists for the current user
            $user_documents = DriverDocuments::where('driver_id', $admin_id)->first();

            if ($request->hasFile('license')) {
                // Delete existing file if exists
                $existing_license = $user_documents->license ?? null;
                if($existing_license){
                    $licensePath = public_path('storage/documents/' . $existing_license);
                    if (file_exists($licensePath)) {
                        unlink($licensePath);
                    }
                }

                $file_name = uniqid() . "." . $request->file('license')->getClientOriginalExtension();
                $request->file('license')->storeAs('documents', $file_name);
                $data['license'] = $file_name;
            }

            if ($request->hasFile('registration')) {

                $existing_registration = $user_documents->registration ?? null;

                if($existing_registration){
                    $registrationPath = public_path('storage/documents/' . $existing_registration);
                    if (file_exists($registrationPath)) {
                        unlink($registrationPath);
                    }    
                }

                $file_name = uniqid() . "." . $request->file('registration')->getClientOriginalExtension();
                $request->file('registration')->storeAs('documents', $file_name);
                $data['registration'] = $file_name;
            }

            if ($request->hasFile('insurance')) {
                
                $existing_insurance = $user_documents->insurance ?? null;
                if($existing_insurance){
                    $insurancePath = public_path('storage/documents/' . $existing_insurance);
                    if (file_exists($insurancePath)) {
                        unlink($insurancePath);
                    }    
                }

                $file_name = uniqid() . "." . $request->file('insurance')->getClientOriginalExtension();
                $request->file('insurance')->storeAs('documents', $file_name);
                $data['insurance'] = $file_name;
            }

            if ($request->hasFile('ownership_tesla_model')) {
                
                $existing_ownership_tesla_model = $user_documents->ownership_tesla_model ?? null;
                if($existing_ownership_tesla_model){
                    $ownership_tesla_modelPath = public_path('storage/documents/' . $existing_ownership_tesla_model);
                    if (file_exists($ownership_tesla_modelPath)) {
                        unlink($ownership_tesla_modelPath);
                    }    
                }

                $file_name = uniqid() . "." . $request->file('ownership_tesla_model')->getClientOriginalExtension();
                $request->file('ownership_tesla_model')->storeAs('documents', $file_name);
                $data['ownership_tesla_model'] = $file_name;
            }

            event(new UploadDocumentNotification([
                'title' => $admin_user->name.' uploads their business documents',
                'notification_type' => 'profile_status',
                'type' => 'customer ',
                'message' => 'The driver has uploaded their business documents',
                'admin_id' => $admin_user->id,
            ]));
     
            if ($user_documents) {

                // If the record exists, update it with new data
                $user_documents->update($data);
                return [
                    'success' => true,
                    'message' => 'Documents updated successfully',
                    'data' => $user_documents
                ];
            } else {

                // If the record does not exist, create a new one
                $user_documents = DriverDocuments::create($data);
                return [
                    'success' => true,
                    'message' => 'Document uploaded successfully',
                    'data' => $user_documents
                ];
            }

            // Return a success message
            return [
                'success' => false,
                'message' => 'Something went wrong.',
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
