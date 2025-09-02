<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\RideRequests;
use App\Models\ExtraCharges;
use App\Models\Review;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Auth;

class PaymentHistoryController extends AdminBaseController
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $admin_id = $admin->id;
        $admin_role = $admin->roles->first()->name ?? null;

        $payment_statuses = RideRequests::whereHas('booking',function($query) use ($admin_role, $admin_id){
            $query->where('booking_status','!=','Cancelled');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                $query->where('driver_id', $admin_id);
            }
        })->with(['booking','users','paymentStatus'])->orderBy('id','desc')->paginate(10);

        foreach($payment_statuses as $payment_status){
            $payment_status = checkSurgePrice($payment_status);
        }

        return view('admin.paymentHistory.index',compact('payment_statuses'));
    }

    public function paymentHistoryFilter(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $admin_id = $admin->id;
        $admin_role = $admin->roles->first()->name ?? null;

        // Retrieve the search term from the request
        $search_by = $request->search_by;
    
        // Query for payment histories, filtering based on payment status and/or booking date
        if (!empty($search_by)) {

            $query = RideRequests::whereHas('booking', function($query) use ($admin_role, $admin_id){
                $query->where('booking_status', '!=', 'Cancelled');
                if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                    $query->where('driver_id', $admin_id);
                }
            })
            ->with(['booking', 'users', 'paymentStatus'])
            ->whereHas('paymentStatus', function($query) use ($search_by) {
                $query->where('status', $search_by);
            });

            // Check if $search_by is a valid date before applying it to booking_date
            if (strtotime($search_by)) {
                $query->orWhereHas('booking', function ($query) use ($search_by, $admin_role, $admin_id) {
                    if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                        $query->where('booking_date', $search_by)->where('driver_id', $admin_id);
                    } else {
                        $query->where('booking_date', $search_by);
                    }
                });
            }

            $payment_statuses = $query->orderBy('id', 'desc')
            ->paginate(10);
            
        }else{

            $payment_statuses = RideRequests::whereHas('booking',function($query) use ($admin_role, $admin_id){
                $query->where('booking_status','!=','Cancelled');
                if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                    $query->where('driver_id', $admin_id);
                }
            })->with(['booking','users','paymentStatus'])->orderBy('id','desc')->paginate(10);    
        
        }
        // Pass the filtered payment histories to the view
        return view('admin.paymentHistory.filter_payment', compact('payment_statuses'));
    }


    public function exportPaymentHistoryPdf()
    {
        $admin = Auth::guard('admin')->user();
        $admin_id = $admin->id;
        $admin_role = $admin->roles->first()->name ?? null;

        // Retrieve the filtered RideRequests data
        $rideRequests = RideRequests::whereHas('booking', function ($query) use ($admin_role, $admin_id){
                $query->where('booking_status', '!=', 'Cancelled');
                if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                    $query->where('driver_id', $admin_id);
                }
            })
            ->with(['booking', 'users', 'paymentStatus'])
            ->get()
            ->map(function ($rideRequest) {
                return [
                    'id'            => $rideRequest->id,
                    'user_name'     => $rideRequest->users->first_name . ' ' . $rideRequest->users->last_name,
                    'email'         => $rideRequest->users->email,
                    'booking_status'=> $rideRequest->booking->booking_status,
                    'payment_status'=> $rideRequest->paymentStatus->status ?? 'N/A',
                    'created_at'    => $rideRequest->created_at->toDateTimeString(),
                ];
            });

        // Pass data to the PDF view
        $pdf = Pdf::loadView('admin.paymentHistory.payment_history_pdf', compact('rideRequests'));

        // Stream the PDF as a response
        return $pdf->download('payment_history.pdf');
    }

    
    public function paymentDetails($ride_id)
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
 
        return view('admin.paymentHistory.payment_detail',compact('average_rating','total_reviews','payment_detail','driver_completed_ride_count'));
    }

    public function paymentDelete(Request $request, $ride_id = null)
    {
        // Find the ride by ID
        $ride = RideRequests::find($ride_id);
    
        // Check if ride detail exists
        if (!$ride) {
            $request->session()->flash('error', 'Ride not found');
            return redirect()->back();
        }

        // Check if there's an associated booking and delete it
        if ($ride->booking) {
            $ride->booking->delete();  // Delete the associated booking record
        }

        if ($ride->paymentStatus) {
            $ride->paymentStatus->delete(); 
        }

        // Delete the ride record
        $ride->delete();
    
        // Flash success message
        $request->session()->flash('success', 'Payment history deleted with ride successfully');
    
        // Redirect to the rides page
        return redirect()->route('payment-listing');
    }

    
}
