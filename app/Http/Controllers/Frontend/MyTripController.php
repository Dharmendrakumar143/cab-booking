<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\RideRequests;
use App\Models\Admin;
use App\Models\ExtraCharges;
use App\Models\Booking;
use App\Models\Review;

class MyTripController extends BaseController
{
    public function index(Request $request)
    {
        // Ensure the user is authenticated
        $user = Auth::user();
        $user_id = $user->id;
    
        // Fetch active rides (not cancelled)
        $my_rides = RideRequests::whereHas('booking', function ($query) {
                $query->where('booking_status', '!=', 'cancelled');
            })
            ->with(['booking', 'users'])
            ->where('customer_id', $user_id)
            ->orderBy('id', 'desc')
            ->get();

        foreach($my_rides as $my_ride){
            $my_ride = checkSurgePrice($my_ride);
        }
    
        // Fetch cancelled rides
        $my_cancelled_rides = RideRequests::whereHas('booking', function ($query) {
                $query->where('booking_status', 'cancelled');
            })
            ->with(['booking', 'users'])
            ->where('customer_id', $user_id)
            ->orderBy('id', 'desc')
            ->get();

        foreach($my_cancelled_rides as $my_cancelled_ride){
            $my_cancelled_ride = checkSurgePrice($my_cancelled_ride);
        }
    
        // Return view with both ride types
        return view('Frontend.mytrip.index', compact('my_rides', 'my_cancelled_rides'));
    }
    

    public function viewTripDetails(Request $request, $ride_id=null)
    {   
        $user = Auth::user();
        $user_id = $user->id;

        $ride_details = RideRequests::with(['booking', 'users','review','cancelledRide.cancellationReasons'])
        ->where('customer_id', $user_id)
        ->orderBy('id', 'desc')
        ->find($ride_id);

        // Check if ride details exist
        if (!$ride_details) {
            // Redirect back with an error message if ride not found
            return redirect()->back()->with('error', 'Ride not found.');
        }
        
        $request->session()->put('ride_id',$ride_details->id);

        $driver_id = $ride_details->booking->driver_id ?? null;
        $admins = Admin::where('id',$driver_id)->first();
        $driver_completed_ride_count = 0;
        $average_rating = 0;
        $total_reviews = 0;

        if($admins){
            $admin_id = $admins->id ?? null;
            $driver_completed_ride_count = Booking::where('driver_id',$admin_id)->where('booking_status','Completed')->count();   
            $average_rating = Review::where('admin_id', $admin_id)->avg('rating');
            $average_rating = round($average_rating, 2);
            $total_reviews = Review::where('admin_id', $admin_id)->count();
        }

        $ride_details = checkSurgePrice($ride_details);

        return view('Frontend.mytrip.view_details',compact('average_rating','total_reviews','ride_details','driver_completed_ride_count'));
    }

    public function submitRating(Request $request)
    {
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
        ]);

        // Save the rating and review in the database
        Review::create([
            'user_id' => Auth::id(),
            'admin_id' => $request->driver_id,
            'ride_id' => $request->ride_id,
            'rating' => $request->rating,
            'feedback' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }

}
