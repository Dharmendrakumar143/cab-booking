<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\RideRequests;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Review;
use App\Models\ExtraCharges;

class PaymentHistoriesController extends BaseController
{
    public function index()
    {
        // Ensure the user is authenticated
        $user = Auth::user();
        $user_id = $user->id;
        
        $payment_statuses = RideRequests::whereHas('booking',function($query){
            $query->where('booking_status','!=','Cancelled');
        })
        ->with(['booking','users','paymentStatus'])
        ->orderBy('id','desc')
        ->where('customer_id', $user_id)
        ->paginate(10);

        foreach($payment_statuses as $payment_status){
            $payment_status = checkSurgePrice($payment_status);
        }

        return view('Frontend.paymentHistory.index',compact('payment_statuses'));
    }

    public function paymentFilter(Request $request)
    {
        // Retrieve the search term from the request
        $search_by = $request->search_by;
    
        // Query for payment histories, filtering based on payment status and/or booking date
        if (!empty($search_by)) {
        $payment_statuses = RideRequests::whereHas('booking', function($query) {
                $query->where('booking_status', '!=', 'Cancelled');
            })
            ->with(['booking', 'users', 'paymentStatus'])
            ->whereHas('paymentStatus', function($query) use ($search_by) {
                $query->where('status', $search_by);
            })
            ->orWhereHas('booking', function($query) use ($search_by) {
                $query->where('booking_date', $search_by);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        }else{
            $payment_statuses = RideRequests::whereHas('booking',function($query){
                $query->where('booking_status','!=','Cancelled');
            })->with(['booking','users','paymentStatus'])->orderBy('id','desc')->paginate(10);    
        }
        // Pass the filtered payment histories to the view
        return view('Frontend.paymentHistory.filter_payment', compact('payment_statuses'));
    }

    public function paymentHistoryDetails($ride_id)
    {
        $payment_detail = RideRequests::whereHas('booking', function($query) {
            $query->where('booking_status', '!=', 'Cancelled');
        })
        ->with(['booking', 'users', 'paymentStatus'])->find($ride_id);

        $driver_id = $payment_detail->booking->driver_id ?? null;
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

        $payment_detail = checkSurgePrice($payment_detail);
        
        return view('Frontend.paymentHistory.payment_detail',compact('average_rating','total_reviews','payment_detail','driver_completed_ride_count'));
    }
}
