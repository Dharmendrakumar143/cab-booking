<?php 

namespace App\Services\Frontend;

use Carbon\Carbon;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\RideRequests;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\RideOTP;
use App\Models\Admin;

use App\Events\RideRequestNotification;
use Illuminate\Support\Facades\Log;

use App\Mail\NewRideRequestMail;
use App\Mail\NewRideRequestCustomerMail;
use App\Mail\CustomerRideOTPMail;
use App\Mail\NewRideRequestDriverMail;

class RideRequestService
{
    
    public function userRideRequest($request)
    {
        try {
            // Delete session data at the top
            $request->session()->forget('user_ride_request_data');

            $ride_date = Carbon::parse($request->ride_date);
            $current_time = Carbon::now();

            // Check if the ride is at least 30 minutes from now
            if ($ride_date->lessThanOrEqualTo($current_time->copy()->addMinutes(30))) {
                return [
                    'success' => false,
                    'message' => 'You cannot book a ride less than 30 minutes from now. Please choose a later time.',
                ];
            }

            // Separate the date and time
            $pick_up_date = $ride_date->toDateString();  // '2024-12-23'
            $pick_up_time = $ride_date->format('g:i A');  // '1:25 PM'

            $ride_data = [
                'pick_up_address'=>$request->pickup_address,
                'pick_up_city'=>$request->pickup_city,
                'pick_up_state'=>$request->pickup_state,
                'pick_up_country'=>$request->pickup_country,
                'pick_up_latitude'=>$request->pickup_latitude,
                'pick_up_longitude'=>$request->pickup_longitude,
                'pick_up_date'=>$pick_up_date,
                'pick_up_time'=>$pick_up_time,
                'total_passenger'=>$request->total_passenger,
                'drop_off_address'=>$request->dropoff_location,
                'drop_off_city'=>$request->dropoff_city,
                'drop_off_state'=>$request->dropoff_state,
                'drop_off_country'=>$request->dropoff_country,
                'drop_off_latitude'=>$request->dropoff_latitude,
                'drop_off_longitude'=>$request->dropoff_longitude,
                'user_phone_number'=>$request->user_phone_number,
            ];

            $ride_data['ride_date'] = $request->ride_date;
            $request->session()->put('user_ride_request_data',$ride_data);

            return [
                'success' => true,
                'message' => 'User ride request successfully.',
            ];
       
        } catch (\Exception $e) {
            // Catch any exception and return the error message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    public function bookRideRequest($request, $decrypted_ride_data)
    {

        try {

            $user = Auth::user();
            $customer_id = $user->id;
            $customer_email = $user->email;

            $admin_email  = config('global-constant.admin_email');
            $admin = Admin::where('email',$admin_email)->first();
            $auto_reject_ride = $admin->auto_reject_ride;

            if (!$customer_id) {
                return [
                    'success' => false,
                    'message' => 'You are not logged in. Please log in to continue.'
                ];
            }

            $checkPending = Booking::where('customer_id',$customer_id)
            ->where('booking_status','In Progress')
            ->where('payment_method','card')->first();

            if ($checkPending) {
                return [
                    'success' => false,
                    'message' => 'Your ride payment is pending. Please update your card details or contact your driver for further assistance.'
                ];
            }

            $ride_date = Carbon::parse($decrypted_ride_data['ride_date']);

            // Separate the date and time
            $booking_date = $ride_date->toDateString();  // '2024-12-23'
            $booking_time = $ride_date->format('g:i A');  // '1:25 PM'

            // Prepare ride data
            $request_ride_data = [
                'customer_id'=>$customer_id,
                'pick_up_address'=>$decrypted_ride_data['pick_up_address'],
                'pick_up_city'=>$decrypted_ride_data['pick_up_city'],
                'pick_up_state'=>$decrypted_ride_data['pick_up_state'],
                'pick_up_country'=>$decrypted_ride_data['pick_up_country'],
                'pick_up_latitude'=>$decrypted_ride_data['pick_up_latitude'],
                'pick_up_longitude'=>$decrypted_ride_data['pick_up_longitude'],
                'pick_up_date'=>$booking_date,
                'pick_up_time'=>$booking_time,
                'total_passenger'=>$decrypted_ride_data['total_passenger'],
                'drop_off_address'=>$decrypted_ride_data['drop_off_address'],
                'drop_off_city'=>$decrypted_ride_data['drop_off_city'],
                'drop_off_state'=>$decrypted_ride_data['drop_off_state'],
                'drop_off_country'=>$decrypted_ride_data['drop_off_country'],
                'drop_off_latitude'=>$decrypted_ride_data['drop_off_latitude'],
                'drop_off_longitude'=>$decrypted_ride_data['drop_off_longitude'],
                'ride_amount' => $decrypted_ride_data['ride_amount'],
            ];

            if(isset($decrypted_ride_data['person_name'])){
                $request_ride_data['person_name'] = $decrypted_ride_data['person_name'];
            }

            if(isset($decrypted_ride_data['phone_number'])){
                $request_ride_data['phone_number'] = $decrypted_ride_data['phone_number'];
            }

            // Create the ride request
            $ride = RideRequests::create($request_ride_data);

            if (!$ride) {
                return [
                    'success' => false,
                    'message' => 'Failed to create the ride request.'
                ];
            }
            
            event(new RideRequestNotification([
                'title'=>'New Ride Request',
                'type' => 'customer',
                'notification_type' => 'ride_request',
                'message'=>"A new ride request has been created",
                'user_id' => $ride->customer_id,
            ]));

            // Prepare booking data
            $booking_data = [
                'customer_id' => $customer_id,
                'ride_id' => $ride->id,
                'booking_date' => $ride_date,
                'booking_time' => $booking_time,
                'ride_amount' => $decrypted_ride_data['ride_amount'],
                'ride_booking_amount' => $decrypted_ride_data['ride_amount'],
                'distance' => $decrypted_ride_data['distance'],
                'duration' => $decrypted_ride_data['duration'],
                'miles_distance' => $decrypted_ride_data['miles_distance'],
                'payment_method' => $decrypted_ride_data['payment_method'],
                'user_card' => $decrypted_ride_data['user_selected_card'],
            ];

            if(isset($decrypted_ride_data['user_phone_number'])){
                $booking_data['user_phone_number'] = $decrypted_ride_data['user_phone_number'];
            }

            if($auto_reject_ride){
                $booking_data['reject_by_super_admin'] = 1;
            }
            
            $booking_data['booking_number'] = 'BK' . date('YmdHis');
            // Create the booking
            $booking = Booking::create($booking_data);

            $payment_data = [
                'ride_id' => $ride->id,
                'amount' => $decrypted_ride_data['ride_amount'],
                'payment_method'=> $decrypted_ride_data['payment_method'],
                'status'=> 'unpaid',
            ];

            Payment::create($payment_data);

            if (!$booking) 
            {
                return [
                    'success' => false,
                    'message' => 'Booking request failed.',
                ];
            }
            $request_ride_data['customer_name'] = $user->first_name.' '.$user->last_name;

            // Generate a 4-digit OTP
            $otp = rand(1000, 9999);

            // Create a new OTP record if none exists
            RideOTP::create([
                'ride_id' => $ride->id,
                'otp' => $otp,
                'otp_expiry' => now(),
            ]);

            $request_ride_data = addSurgePrice($request_ride_data);

            $booking_id = $booking->id;
            $booking_ride = Booking::with(['rideRequests','user'])->find($booking_id);
            $booking_ride = addSurgePriceOnMail($booking_ride);

            // Send new ride of all employee which is auto reject
            if($auto_reject_ride){

                $employee_role = config('global-constant.employee_role');
                $employees = Admin::where('status', true)
                            ->where('is_email_verified', true)
                            ->where('login_check', true)
                            ->whereHas('roles', function($query) use ($employee_role) {
                                $query->where('name', $employee_role);
                            })
                            ->get();  

                // Send email notifications to the employee
                foreach ($employees as $employee) {
                    $employee_email = $employee->email;
        
                    if ($employee_email) {
                        Mail::to($employee_email)->send(new NewRideRequestDriverMail($booking_ride, 'employee'));
                    }
                }
            }
          
            // Send OTP to the user's email
            if($admin_email){
                Mail::to($admin_email)->send(new NewRideRequestMail($request_ride_data));
            }

            if($customer_email){
                Mail::to($customer_email)->send(new NewRideRequestCustomerMail($request_ride_data));
                Mail::to($customer_email)->send(new CustomerRideOTPMail($otp, $request_ride_data));
            }
            
            return [
                'success' => true,
                'message' => 'Booking request sent successfully.'
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
