<?php
use App\Models\ExtraCharges;
use Carbon\Carbon;

if (!function_exists('calculateDistance')) {
    /**
     * Calculate distance between two coordinates using Haversine formula.
     *
     * @param float $lat1 Latitude of the first point
     * @param float $lng1 Longitude of the first point
     * @param float $lat2 Latitude of the second point
     * @param float $lng2 Longitude of the second point
     * @return float Distance in kilometers
     */
    function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        // Radius of the Earth in kilometers
        $earth_radius = 6371;

        // Convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        // Difference in coordinates
        $lat_diff = $lat2 - $lat1;
        $lng_diff = $lng2 - $lng1;

        // Haversine formula
        $a = sin($lat_diff / 2) * sin($lat_diff / 2) +
            cos($lat1) * cos($lat2) *
            sin($lng_diff / 2) * sin($lng_diff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Calculate the distance
        $distance = $earth_radius * $c;

        // Return distance in kilometers (rounded to 2 decimal places)
        return round($distance, 2);
    }
}

// Add surge price after booking the ride
if (!function_exists('checkSurgePrice')) {
    function checkSurgePrice($ride_details)
    {
        $booking_date = $ride_details->booking->booking_date;        
        $booking_time = $ride_details->booking->booking_time;

        $surge_prices = ExtraCharges::all()->toArray();

        $ride_details->booking->subtotal = $ride_details->booking->ride_booking_amount;
        
        if(!empty($surge_prices)){
            $surge_price = 0;
            foreach($surge_prices as $surge_price)
            {
                $surge_name = $surge_price['name'];

                if($surge_name=='weekend'){

                    $carbon_date = Carbon::parse($booking_date);
                    $day_of_week = $carbon_date->format('l');  // 'l' gives the full day name

                    $weekend_days = explode(",",$surge_price['weekend_days']);

                    if(in_array($day_of_week, $weekend_days)){
                        $surge_price = $surge_price['value'];
                        $ride_details->booking->subtotal = $surge_price + $ride_details->booking->ride_booking_amount;
                        $ride_details->booking->surge_amount = $surge_price;
                    }
                }

                if($surge_name=='holiday'){
                    $holiday_dates = explode(",",$surge_price['holiday_dates']);
                    if(in_array($booking_date, $holiday_dates)){
                        $surge_price = $surge_price['value'];
                        $ride_details->booking->subtotal = $surge_price + $ride_details->booking->ride_booking_amount;
                        $ride_details->booking->surge_amount = $surge_price;
                    }
                }

                if($surge_name=='night' || $surge_name=='night 2' || $surge_name=='rush hour 1' || $surge_name=='rush hour 2'){
                    $start_time = $surge_price['start_time'];
                    $end_time = $surge_price['end_time'];

                    $start = Carbon::createFromFormat('h:i A', $start_time);
                    $end = Carbon::createFromFormat('h:i A', $end_time);
                    $booking = Carbon::createFromFormat('h:i A', $booking_time);

                    if ($booking->between($start, $end)) {
                        $surge_price = $surge_price['value'];
                        $ride_details->booking->subtotal = $surge_price + $ride_details->booking->ride_booking_amount;
                        $ride_details->booking->surge_amount = $surge_price;
                    }
                }
            }
        }
        return $ride_details;
    }
}

// Add surge price before booking ride
if (!function_exists('addSurgePrice')) {
    function addSurgePrice($ride_request)
    {
        $booking_date = $ride_request['pick_up_date'];        
        $booking_time = $ride_request['pick_up_time'];

        $surge_prices = ExtraCharges::all()->toArray();

        $ride_request['subtotal'] = $ride_request['ride_amount'];
        
        if(!empty($surge_prices)){
            $surge_price = 0;
            foreach($surge_prices as $surge_price)
            {
                $surge_name = $surge_price['name'];

                if($surge_name=='weekend'){

                    $carbon_date = Carbon::parse($booking_date);
                    $day_of_week = $carbon_date->format('l');  // 'l' gives the full day name

                    $weekend_days = explode(",",$surge_price['weekend_days']);

                    if(in_array($day_of_week, $weekend_days)){
                        $surge_price = $surge_price['value'];
                        $ride_request['subtotal'] = $surge_price + $ride_request['ride_amount'];
                        $ride_request['surge_amount'] = $surge_price;
                    }
                }

                if($surge_name=='holiday'){
                    $holiday_dates = explode(",",$surge_price['holiday_dates']);
                    if(in_array($booking_date, $holiday_dates)){
                        $surge_price = $surge_price['value'];
                        $ride_request['subtotal'] = $surge_price + $ride_request['ride_amount'];
                        $ride_request['surge_amount'] = $surge_price;
                    }
                }

                if($surge_name=='night' || $surge_name=='night 2' || $surge_name=='rush hour 1' || $surge_name=='rush hour 2'){
                    $start_time = $surge_price['start_time'];
                    $end_time = $surge_price['end_time'];

                    $start = Carbon::createFromFormat('h:i A', $start_time);
                    $end = Carbon::createFromFormat('h:i A', $end_time);
                    $booking = Carbon::createFromFormat('h:i A', $booking_time);

                    if ($booking->between($start, $end)) {
                        $surge_price = $surge_price['value'];
                        $ride_request['subtotal'] = $surge_price + $ride_request['ride_amount'];
                        $ride_request['surge_amount'] = $surge_price;
                    }
                }
            }
        }
        return $ride_request;
    }
}


// Add surge price after booking the ride
if (!function_exists('addSurgePriceOnMail')) {
    function addSurgePriceOnMail($booking_ride)
    {
        $booking_date = $booking_ride->booking_date;        
        $booking_time = $booking_ride->booking_time;

        $surge_prices = ExtraCharges::all()->toArray();

        $booking_ride->subtotal = $booking_ride->ride_booking_amount;
        
        if(!empty($surge_prices)){
            $surge_price = 0;
            foreach($surge_prices as $surge_price)
            {
                $surge_name = $surge_price['name'];

                if($surge_name=='weekend'){

                    $carbon_date = Carbon::parse($booking_date);
                    $day_of_week = $carbon_date->format('l');  // 'l' gives the full day name

                    $weekend_days = explode(",",$surge_price['weekend_days']);

                    if(in_array($day_of_week, $weekend_days)){
                        $surge_price = $surge_price['value'];
                        $booking_ride->subtotal = $surge_price + $booking_ride->ride_booking_amount;
                        $booking_ride->surge_amount = $surge_price;
                    }
                }

                if($surge_name=='holiday'){
                    $holiday_dates = explode(",",$surge_price['holiday_dates']);
                    if(in_array($booking_date, $holiday_dates)){
                        $surge_price = $surge_price['value'];
                        $booking_ride->subtotal = $surge_price + $booking_ride->ride_booking_amount;
                        $booking_ride->surge_amount = $surge_price;
                    }
                }

                if($surge_name=='night' || $surge_name=='night 2' || $surge_name=='rush hour 1' || $surge_name=='rush hour 2'){
                    $start_time = $surge_price['start_time'];
                    $end_time = $surge_price['end_time'];

                    $start = Carbon::createFromFormat('h:i A', $start_time);
                    $end = Carbon::createFromFormat('h:i A', $end_time);
                    $booking = Carbon::createFromFormat('h:i A', $booking_time);

                    if ($booking->between($start, $end)) {
                        $surge_price = $surge_price['value'];
                        $booking_ride->subtotal = $surge_price + $booking_ride->ride_booking_amount;
                        $booking_ride->surge_amount = $surge_price;
                    }
                }
            }
        }
        return $booking_ride;
    }
}