<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Barryvdh\DomPDF\Facade\Pdf;

class DriverController extends AdminBaseController
{
    public function drivers()
    { 
        $drivers = Admin::with('bookingMore')
        ->whereHas('roles', function($query){
            $query->whereIn('name',['admin', 'independent-contractors']);
        }) 
        ->orderBy('id','desc')
        ->paginate(10);

        // Iterate through each driver and calculate total rides and completed rides
        $drivers->getCollection()->transform(function($driver) {
            // Calculate the total rides
            $totalRides = $driver->bookingMore->count();

            // Calculate the total completed rides (assuming "completed" rides are marked by a specific status)
            $completedRides = $driver->bookingMore->where('booking_status', 'Completed')->count();

            // Add the counts to the driver object
            $driver->total_rides = $totalRides;
            $driver->completed_rides = $completedRides;

            return $driver;
        });

        return view('admin.drivers.index',compact('drivers'));
    }


    public function driverData(Request $request)
    {
        $query = Admin::with('bookingMore')
        ->whereHas('roles', function($query){
            $query->whereIn('name',['admin', 'independent-contractors']);
        });

        // Apply custom search if provided
        if ($request->has('custom_search') && $request->custom_search) {
            $searchTerm = $request->custom_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('first_name', 'like', "%{$searchTerm}%")
                    ->orWhere('last_name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Sorting logic based on the column and direction from the request
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir');
        $columns = $request->input('columns');
        $orderColumn = $columns[$orderColumnIndex]['name'];

        if($orderColumn=='user'){
            $query->orderBy('first_name', $orderDirection);
        }else{
            $query->orderBy($orderColumn, $orderDirection);
        }

        // Pagination logic
        $totalRecords = $query->count();
        $drivers = $query->skip($request->start)
            ->take($request->length)
            ->orderBy('id','desc')
            ->get();

        foreach($drivers as $driver){
            // Calculate the total rides
            $totalRides = $driver->bookingMore->count();

            // Calculate the total completed rides (assuming "completed" rides are marked by a specific status)
            $completedRides = $driver->bookingMore->where('booking_status', 'Completed')->count();

            // Add the counts to the driver object
            $driver->total_rides = $totalRides;
            $driver->completed_rides = $completedRides;
        }
        
        // Return data formatted for DataTables
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $drivers,
        ]);
    }


    public function driverDeleted(Request $request, $driver_id)
    {
        // Fetch the driver along with their bookings
        $driver = Admin::with('bookingMore')->find($driver_id);
    
        // If driver does not exist
        if (!$driver) {
            session()->flash('error', 'Driver not found');
            return redirect()->back();
        }
    
        // Check if the driver has any assigned bookings
        if ($driver->bookingMore->count() > 0) {
            session()->flash('error', 'This driver cannot be deleted because they have assigned bookings.');
            return redirect()->back();
        }
    
        // If no bookings are assigned, proceed with deletion
        $driver->delete();
    
        session()->flash('success', 'Driver deleted successfully.');
        return redirect()->back();
    }

    public function driverDetails($driver_id)
    {
        // Fetch the driver along with their bookings
        $driver = Admin::withCount([
            'bookingMore as total_rides' => function ($query) {
                $query->where('booking_status', 'Completed');
            }, 
            'bookingMore as canceled_rides' => function ($query) {
                $query->where('booking_status', 'Cancelled');
            }
        ])->with('driverDocuments')->find($driver_id);
        // If driver does not exist
        if (!$driver) {
            session()->flash('error', 'Driver not found');
            return redirect()->back();
        }

        return view('admin.drivers.details', compact('driver'));
    }


    public function verifyDocument(Request $request)
    {
        // Validate user input data
        $request->validate([
            'verification_status' => 'required',
            'reason' => 'required'
        ]);

        $result = $this->driver_service->verifyDriverDocument($request);

        if ($result['success']) {
            $request->session()->flash('success', 'Driver status update successfully.');
            return redirect()->route('admin-driver');
        }

        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }


    public function exportDriverPDF()
    {
        $drivers = Admin::whereHas('roles', function ($query) {
                        $query->where('name', 'independent-contractors');
                    })
                    ->get(['id', 'first_name', 'last_name', 'email', 'phone_number', 'created_at']);

        // Pass data to the PDF view
        $pdf = Pdf::loadView('admin.drivers.exports.users_pdf', compact('drivers'));

        // Stream the PDF as a response
        return $pdf->download('drivers.pdf');
    }

    
}
