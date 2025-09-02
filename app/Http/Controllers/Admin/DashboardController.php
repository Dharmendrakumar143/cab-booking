<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RideRequests;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\TipAmount;
use DB;
use Auth;

class DashboardController extends AdminBaseController
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {   
        $admins = Auth::guard('admin')->user();
        $admin_id = $admins->id;
        $admin_role = $admins->roles->first()->name ?? null;

        // Count total users
        $user_count = User::count();

        $tip_amount_query = TipAmount::query();
        if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
            $tip_amount_query->where('driver_id', $admin_id);
        }
        $total_tip_amount = $tip_amount_query->sum('total_tip_amount');

        $total_paid_amounts = RideRequests::whereHas('paymentStatus', function ($query) use ($admin_role, $admin_id){
            $query->where('status', 'paid');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                $query->where('driver_id', $admin_id);
            }
        })
        ->with('paymentStatus')
        ->get();

        $paid_amount = 0;
        foreach($total_paid_amounts as $total_paid_amount){
            $paid_amount += $total_paid_amount->paymentStatus->amount;
        }

        // Count pending ride requests
        $total_request_rides_count = RideRequests::whereHas('booking', function ($query) use ($admin_role){
            $query->where('booking_status', 'Pending');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                if ($admin_role == 'employees') {
                    $query->where('reject_by_super_admin', true);
                }else{
                    $query->where('reject_by_super_admin', true)->where('reject_by_employee', true);
                }
            }
        })->count();

        // Count completed ride requests
        $total_completed_rides_count = RideRequests::whereHas('booking', function ($query) use ($admin_role, $admin_id){
            $query->where('booking_status', 'Completed');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                $query->where('driver_id', $admin_id);
            }
        })->count();

        // Fetch the latest 4 new ride requests
        $new_request_rides = RideRequests::whereHas('booking', function ($query) use ($admin_role){
            $query->where('booking_status', 'Pending');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                if ($admin_role == 'employees') {
                    $query->where('reject_by_super_admin', true);
                }else{
                    $query->where('reject_by_super_admin', true)->where('reject_by_employee', true);
                }
            }
        })->with(['booking','users'])->latest()->take(3)->get();

        foreach($new_request_rides as $new_request_ride){
            $new_request_ride = checkSurgePrice($new_request_ride);
        }

        // $admins = Admin::first();
       
        $driver_completed_ride_count = Booking::where('driver_id',$admin_id)->where('booking_status','Completed')->count();

        // Fetch Completed and Cancelled rides grouped by month, ensuring all months are included
        $monthly_data = RideRequests::select(
            DB::raw("MONTH(ride_requests.created_at) as month"),
            DB::raw("YEAR(ride_requests.created_at) as year"),
            DB::raw("COALESCE(SUM(CASE WHEN bookings.booking_status = 'Completed' THEN 1 ELSE 0 END), 0) as completed"),
            DB::raw("COALESCE(SUM(CASE WHEN bookings.booking_status = 'Cancelled' THEN 1 ELSE 0 END), 0) as cancelled")
        )
        ->join('bookings', function ($join) use ($admin_role, $admin_id) {
            $join->on('ride_requests.id', '=', 'bookings.ride_id');
    
            // Apply role-based filtering within the join
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                $join->where('bookings.driver_id', $admin_id);
            }
        })
        ->whereYear('ride_requests.created_at', date('Y')) // Filter for the current year
        ->groupBy(DB::raw("YEAR(ride_requests.created_at), MONTH(ride_requests.created_at)"))
        ->orderBy(DB::raw("MONTH(ride_requests.created_at)"))
        ->get();
    
        // Prepare labels and datasets for the frontend
        $labels = collect(range(1, 12))->map(function ($month) {
            return date("F", mktime(0, 0, 0, $month, 1)); // Convert month number to month name
        });

        // Initialize arrays for completed and cancelled rides, filled with 0
        $completed = array_fill(0, 12, 0);
        $cancelled = array_fill(0, 12, 0);

        // Map data to months
        foreach ($monthly_data as $data) {
            // Ensure that the month exists in the data (1-12)
            $completed[$data->month - 1] = $data->completed;
            $cancelled[$data->month - 1] = $data->cancelled;
        }
 
        // Return the dashboard view with data
        return view('admin.dashboard.index', compact(
            'user_count', 
            'total_request_rides_count', 
            'total_completed_rides_count', 
            'new_request_rides', 
            'admins',
            'driver_completed_ride_count',
            'labels',
            'completed',
            'cancelled',
            'paid_amount',
            'total_tip_amount'
        ));
    }

}
