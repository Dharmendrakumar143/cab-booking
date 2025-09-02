<?php 

namespace App\Services\Admin;

use App\Events\AdminVerifyDocumentNotifications;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyDocumentMail;
use App\Models\Admin;
use App\Models\DriverDocuments;

class DriverService
{
    public function verifyDriverDocument($request)
    {
        try {
            $driver_id = $request->driver_id;
            $user = Admin::find($driver_id);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Driver not found.',
                ];
            }

            $documents = DriverDocuments::where('driver_id',$user->id)->first();
    
            $verification_status = $request->verification_status;
            $reason = $request->reason;

            if ($documents) {

                $documents->update([
                    'verify_by'=>$user->id,
                    'verification_status'=>$verification_status,
                    'message'=>$reason,
                ]);

            }else{
                
                DriverDocuments::create([
                    'verify_by'=>$user->id,
                    'verification_status'=>$verification_status,
                    'message'=>$reason,
                    'driver_id'=>$request->driver_id
                ]);   
            }
          
            $message = ($verification_status == 'approved') 
            ? 'Your profile has been approved and is now active.' 
            : 'Your profile has been rejected. Please reupload your documents for review.';

            $title = ($verification_status == 'approved') 
            ? 'Congratulations! Your profile has been approved.' 
            : 'We regret to inform you that your profile has been rejected. Please reupload your documents for review';

            event(new AdminVerifyDocumentNotifications([
                'title' => $title,
                'notification_type' => 'profile_status',
                'type' => 'driver',
                'message' => $message,
                'admin_id' => $user->id,
            ]));

            if($verification_status=='approved'){
                $user->update([
                    'verification_status'=>'verified'
                ]);
            }

            if($verification_status=='rejected'){
                $user->update([
                    'verification_status'=>'rejected'
                ]);
            }

            if($verification_status=='suspended'){
                $user->update([
                    'verification_status'=>'suspended'
                ]);
            }

            $user->message = $message;
            $user->title = $title;
            $user->document_verification_status = $verification_status;
            $user->reason = $reason;

            // Send the reset link to the user's email
            // Mail::to($user->email)->send(new VerifyDocumentMail($user));

            return [
                'success' => true,
                'message' => 'Document status updated successfully.',
            ];

        } catch (\Exception $e) {
            // Handle any exceptions and return the error message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
