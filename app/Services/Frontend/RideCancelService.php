<?php 

namespace App\Services\Frontend;

use Carbon\Carbon;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Events\CustomerCancelRideNotification;

use App\Models\CancellationReasons;
use App\Models\RideCancellations;
use App\Models\RideRequests;
use App\Models\Booking;

use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerCancelRideCustomerMail;
use App\Mail\CustomerCancelRideDriverMail;

class RideCancelService
{
    
    public function userRideCancel($request)
    {
        try {
            $user = Auth::user();
            $user_id = $user->id;
            $ride_id = $request->session()->get('ride_id');
            $user_email = $user->email;

            $ride_details = RideRequests::with(['booking', 'users','review'])
            ->where('customer_id', $user_id)
            ->orderBy('id', 'desc')
            ->find($ride_id);
            
            $booking_id = $ride_details->booking->id ?? null;

            $super_admin = config('global-constant.admin_email');

            $booking_ride = Booking::with(['rideRequests','admin','user'])->find($booking_id);
            $admin_mail = $booking_ride->admin->email ?? $super_admin;

            $booking_ride = addSurgePriceOnMail($booking_ride);
         
            // Check if ride details exist
            if (!$ride_details) {
                // Redirect back with an error message if ride not found
                return redirect()->back()->with('error', 'Ride not found.');
            }
            $driver_id = $ride_details->booking->driver_id ?? null;
            $cancellation_reason_id = $request->cancellation_reason ?? null;
            $otherReasonText = $request->otherReasonTextarea ?? null;
    
            RideCancellations::create([
                'customer_id'=>$user_id,
                'driver_id'=>$driver_id,
                'ride_id'=>$ride_id,
                'cancel_id'=>$cancellation_reason_id,
                'cancellation_reason'=>$otherReasonText,
            ]);

            $booking = Booking::where('ride_id',$ride_id)->where('customer_id',$user_id)->first();   
            $booking->update([
                'booking_status'=>'Cancelled'
            ]);

            event(new CustomerCancelRideNotification([
                'title' => 'Ride Cancelled by Customer',
                'type' => 'customer',
                'notification_type' => 'ride_cancelled',
                'message' => "The customer has cancelled their ride request.",
                'user_id' => $user_id,
            ]));  

            if($admin_mail){
                Mail::to($admin_mail)->send(new CustomerCancelRideDriverMail($booking_ride));
            }

            if($user_email){
                Mail::to($user_email)->send(new CustomerCancelRideCustomerMail($booking_ride));
            }

            return [
                'success' => true,
                'message' => 'User ride cancel successfully.',
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
