<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class PassengerController extends AdminBaseController
{
    public function passengers()
    {
        // Retrieve passengers with pagination
        $passengers = User::paginate(10);

        // Return the view with paginated passenger data
        return view('admin.users.index',compact('passengers'));
    }

    public function passengerDetails($user_id = null)
    {
        // Find the passenger by user_id
        $passenger = User::find($user_id);
    
        // Check if the passenger exists
        if (!$passenger) {
            // Flash an error message and redirect if the passenger is not found
            session()->flash('error', 'Passenger not found');
            return redirect()->route('admin.users.index');  // Redirect to a relevant route (change if necessary)
        }
    
        // Pass the passenger data to the view
        return view('admin.users.passenger_details', compact('passenger'));
    }

    public function userDeleted(Request $request, $user_id = null)
    {
        // Find the passenger by user_id
        $passenger = User::find($user_id);
    
        // Check if the passenger exists
        if (!$passenger) {
            // Flash an error message and redirect if the passenger is not found
            session()->flash('error', 'Passenger not found');
            return redirect()->back();
            //return redirect()->route('admin.users.index');  // Redirect to a relevant route (change if necessary)
        }
        
        $passenger->delete();

        // Flash success message
        $request->session()->flash('success', 'User deleted successfully');

        // Redirect to the rides page
        return redirect()->route('admin-passenger');
        
    }


    public function exportPDF()
    {
        $users = User::all(['id', 'first_name', 'last_name', 'email', 'created_at']);

        // Pass data to the PDF view
        $pdf = Pdf::loadView('admin.users.exports.users_pdf', compact('users'));

        // Stream the PDF as a response
        return $pdf->download('users.pdf');
    }
    
    
}
