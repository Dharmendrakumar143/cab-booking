<?php 

namespace App\Services\Frontend;

use Carbon\Carbon;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\RideRequests;
use App\Models\Booking;


class SomeoneRideRequestService
{
    
    public function someoneRideRequest($request)
    {
        try {
            // Delete session data at the top
            $request->session()->forget('user_ride_request_data');

            $ride_date = Carbon::parse($request->ride_date);

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
                'person_name'=>$request->person_name,
                'phone_number'=>$request->phone_number,
            ];

            $ride_data['ride_date'] = $request->ride_date;
            $request->session()->put('user_ride_request_data',$ride_data);

            return [
                'success' => true,
                'message' => 'Someone ride request send successfully.',
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
