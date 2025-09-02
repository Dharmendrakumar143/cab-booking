<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\CancellationReasons;
use App\Models\RideCancellations;
use App\Models\RideRequests;
use App\Models\Booking;

class CancelRideController extends BaseController
{
    public function index(Request $request)
    { 
        $cancellation_reasons = CancellationReasons::all();
        return view('Frontend.cancel.index',compact('cancellation_reasons'));
    }

    public function cancelRide(Request $request){

        $ride_id = $request->session()->get('ride_id');

        $result = $this->ride_cancel_service->userRideCancel($request);

        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return redirect()->route('view-trip-details',['ride_id'=>$ride_id]);
        }

        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();

    }
}
