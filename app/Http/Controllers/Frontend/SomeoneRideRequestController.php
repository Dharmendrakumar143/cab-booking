<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SomeoneRideRequestController extends BaseController
{

    /**
     * Display the form to request a ride for someone else.
     * 
     * 
     * @return \Illuminate\View\View
     */
    public function requestRideSomeoneForm()
    {
        // Returns the view for the "request a ride for someone" form.
        return view('Frontend.bookride.request-ride-someone');
    }

    /**
     * Handle the submission of the ride request for someone else.
     * 
     * @param \Illuminate\Http\Request $request The HTTP request containing the form data.
     * @return \Illuminate\Http\RedirectResponse Redirects to the appropriate route based on the success or failure of the request.
     */
    public function rideRequestSomeone(Request $request)
    {
        // Validate user input data from the request
        $request->validate([
            'pickup_address' => 'required|string',
            'ride_date' => 'required|date|after_or_equal:today',
            'total_passenger' => 'required|integer|min:1|max:4',
            'dropoff_location' => 'required|min:8',
            'person_name' => 'required|string',
            'phone_number' => ['required', 'string', 'regex:/^[0-9]+$/'], // Only digits allowed
        ]);

        // Call a service to process the ride request
        $result = $this->someone_ride_request_service->someoneRideRequest($request);

        // Check if the request was successful
        if ($result['success']) {
            $request->session()->flash('success', 'Ride request for fare calulcation send successfull');
            return redirect()->route('book-ride');
        }

        // Flash an error message and redirect back to the previous page if the request failed
        $request->session()->flash('error', $result['message']);
        return redirect()->back();

    }
}
