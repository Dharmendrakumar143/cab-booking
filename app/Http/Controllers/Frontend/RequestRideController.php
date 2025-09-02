<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Card;

class RequestRideController extends BaseController
{
    public function requestRideForm()
    {
        return view('Frontend.bookride.request-ride');
    }


    public function bookingRideForm(Request $request)
    {
        $ride_request = $request->session()->get('user_ride_request_data');

        if (!$ride_request) {
            return redirect()->route('home');
        }

        $pickup_lat = $ride_request['pick_up_latitude'];
        $pickup_lng = $ride_request['pick_up_longitude'];
        $dropoff_lat = $ride_request['drop_off_latitude'];
        $dropoff_lng = $ride_request['drop_off_longitude'];

        $google_api_key = config('global-constant.google_map_api_key');
        $google_api_url = config('global-constant.google_map_api_key_url');

        // $url = "{$google_api_url}distancematrix/json?origins={$pickup_lat},{$pickup_lng}&destinations={$dropoff_lat},{$dropoff_lng}&units=metric&departure_time=now&traffic_model=best_guess&key={$google_api_key}";
        $url = "{$google_api_url}distancematrix/json?origins={$pickup_lat},{$pickup_lng}&destinations={$dropoff_lat},{$dropoff_lng}&units=metric&departure_time=now&traffic_model=best_guess&avoid=tolls|ferries&key={$google_api_key}";

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }

            curl_close($ch);

            $data = json_decode($response, true);


            // echo "<pre>data==";
            // print_r($data);
            // die;
            \Log::error('Google API Response:', $data);
            if (!isset($data['rows'][0]['elements'][0]['distance']['text'])) {
                $request->session()->flash('error', 'Unable to fetch distance information. Ride cannot be booked for this location.');
                return redirect()->back();
            }

            $distance_text = $data['rows'][0]['elements'][0]['distance']['text'];
            $distance = preg_replace('/[^\d.]/', '', $distance_text);
            $duration = $data['rows'][0]['elements'][0]['duration']['text'];

        } catch (Exception $e) {
            \Log::error('Google Maps API Error: ' . $e->getMessage());
            $request->session()->flash('error', 'Unable to fetch distance information. Please try again later.');
            return redirect()->back();
        }


        $rate_per_miles = config('global-constant.ride_per_km_charge.rate_per_km');
        $miles_distance = round($distance * 0.621371, 2);
        $calculated_amount = $miles_distance * $rate_per_miles;

        $ride_request['distance'] = $distance;
        $ride_request['miles_distance'] = $miles_distance;
        $ride_request['ride_amount'] = $calculated_amount;
        $ride_request['duration'] = $duration;

        $user = Auth::user();
        $cards = [];
        if($user){
            $cards = Card::where('user_id',$user->id)->get();
        }
        $ride_request = addSurgePrice($ride_request);
        $encrypted_ride_request = Crypt::encryptString(json_encode($ride_request));

        return view('Frontend.bookride.book-ride', compact('cards','ride_request', 'encrypted_ride_request'));
    }


    public function rideRequest(Request $request)
    {
        // Validate user input data
        $request->validate([
            'pickup_address' => 'required|string',
            'ride_date' => 'required|date|after_or_equal:today',
            'total_passenger' => 'required|integer|min:1|max:4',
            'dropoff_location' => 'required|min:8',
        ]);

        $result = $this->ride_request_service->userRideRequest($request);

        if ($result['success']) {
            $request->session()->flash('success', 'Ride request for fare calculation send successfull');
            return redirect()->route('book-ride');
        }

        $request->session()->flash('error', $result['message']);
        return redirect()->back();

    }

    public function bookRide(Request $request)
    {
        $encrypted_ride_data = $request->ride_request;
        $decrypted_ride_data = json_decode(Crypt::decryptString($encrypted_ride_data), true);
        $decrypted_ride_data['payment_method'] = $request->payment_method;
        $decrypted_ride_data['user_selected_card'] = $request->user_selected_card;
 
        $result = $this->ride_request_service->bookRideRequest($request, $decrypted_ride_data);
         
        if ($result['success']) {
            return response()->json([
                'status' => true,
                'message' => $result['message']
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => $result['message']
        ]);

    }
 
}
