<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

//stripe
use Stripe\Stripe;
use Stripe\PaymentMethod;
use Stripe\Customer;
use Stripe\SetupIntent;
use Stripe\Transfer;
use Stripe\Payout;
use Stripe\Account;

// models
use App\Models\Admin;
use App\Models\Booking;
use App\Models\RideRequests;
use App\Models\ExtraCharges;
use App\Models\Review;
use App\Models\Payment;
use App\Models\CancellationReasons;
use App\Models\RideCancellations;
use App\Models\DriverDues;
use App\Models\AdminComission;
use App\Models\DriverMarkPayment;
use App\Models\Transaction;
use App\Models\RideOTP;

//events
use App\Events\AdminConfirmRideNotification;
use App\Events\AdminCancelRideNotification;
use App\Events\AdminCompleteRideNotification;
use App\Events\AdminStartRideNotification;

// mail
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmBooking;
use App\Mail\DriverCancelRideAdminMail;
use App\Mail\DriverCancelRideCustomerMail;
use App\Mail\ConfirmBookingDriverMail;
use App\Mail\RideStartCustomerMail;
use App\Mail\RideStartDriverMail;
use App\Mail\CompleteRideCustomerMail;
use App\Mail\CompleteRideDriverMail;
use App\Mail\NewRideRequestDriverMail;
use App\Mail\InsufficientFundsNotification;
use App\Mail\deleteRideCustomerMail;
use App\Mail\deleteRideDriverMail;

class AdminRidesController extends AdminBaseController
{
    public function rides()
    {

        $admin = Auth::guard('admin')->user();
        $admin_id = $admin->id;
        $admin_role = $admin->roles->first()->name ?? null;

        // confirm rides
        $query = RideRequests::whereHas('booking', function ($query) use ($admin_role, $admin_id) {
            $query->where('booking_status', 'Confirmed');
            
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                $query->where('driver_id', $admin_id);
            }
        })->with(['booking.admin', 'users']);
        
        $confirmed_rides = $query->orderBy('id', 'desc')->get();
        $confirmed_rides_count = $query->count();

        foreach($confirmed_rides as $confirmed_ride){
            $confirmed_ride = checkSurgePrice($confirmed_ride);
        }


        // pending rides
        $query = RideRequests::whereHas('booking', function ($query) use ($admin_role){
            $query->where('booking_status', 'Pending');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                if ($admin_role == 'employees') {
                    $query->where('reject_by_super_admin', true);
                }else{
                    $query->where('reject_by_super_admin', true)->where('reject_by_employee', true);
                }
            }
        })->with(['booking', 'users']);
        
        $pending_rides = $query->orderBy('id', 'desc')->paginate(10);
        $pending_rides_count = $query->count();    
    
        foreach($pending_rides as $pending_ride){
            $pending_ride = checkSurgePrice($pending_ride);
        }
         
     
        // in progress rides
        $query = RideRequests::whereHas('booking', function ($query) use ($admin_role, $admin_id) {
            $query->where('booking_status', 'In Progress');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                $query->where('driver_id', $admin_id);
            }
        })->with(['booking.admin', 'users']);
        
        $in_progress_rides = $query->orderBy('id', 'desc')->get();
        $count_in_progress_ride = $query->count();

        foreach($in_progress_rides as $in_progress_ride){
            $in_progress_ride = checkSurgePrice($in_progress_ride);
        }
        
        // Completed rides
        $query = RideRequests::whereHas('booking', function ($query) use ($admin_role, $admin_id) {
            $query->where('booking_status', 'Completed');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                $query->where('driver_id', $admin_id);
            }
        })->with(['booking.admin', 'users','paymentStatus']);
        
        $completed_rides = $query->orderBy('id', 'desc')->get();
        $completed_rides_count = $query->count();

        foreach($completed_rides as $completed_ride){
            $completed_ride = checkSurgePrice($completed_ride);
        }

        // upcoming riders
        $upcoming_rides = RideRequests::whereHas('booking',function($query) use ($admin_role, $admin_id){
            $query->where(function($query) use ($admin_role, $admin_id){
                $query->where('booking_status', '=', 'Pending');
                if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                    $query->where('driver_id', $admin_id);
                }
            });
        })
        ->where('pick_up_date', '>=', now()->toDateString())
        ->where(function($query) {
            $query->where('pick_up_time', '>=', Carbon::now()->format('g:i A')); // Current time in 12-hour format
        })
        ->with(['booking','users'])
        ->get();

        foreach($upcoming_rides as $upcoming_ride){
            $upcoming_ride = checkSurgePrice($upcoming_ride);
        }

        // cancelled ride by customer
        $pre_cancelled_rides = RideRequests::whereHas('booking',function($query) use ($admin_role, $admin_id){
            $query->where('booking_status','Cancelled');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                $query->where('driver_id', $admin_id);
            }
        })
        ->whereHas('cancelledRide',function($query){
            $query->whereNotNull('customer_id');
        })
        ->with(['booking','cancelledRide','users'])
        ->orderBy('id','desc')
        ->get();

        foreach($pre_cancelled_rides as $pre_cancelled_ride){
            $pre_cancelled_ride = checkSurgePrice($pre_cancelled_ride);
        }

        // cancelled ride by driver
        $cancelled_by_drivers = RideRequests::whereHas('booking',function($query) use ($admin_role, $admin_id){
            $query->where('booking_status','Cancelled');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                $query->where('driver_id', $admin_id);
            }
        })
        ->whereHas('cancelledRide',function($query){
            $query->whereNotNull('driver_id');
        })
        ->with(['booking.admin','cancelledRide','users'])
        ->orderBy('id','desc')
        ->get();

        foreach($cancelled_by_drivers as $cancelled_by_driver){
            $cancelled_by_driver = checkSurgePrice($cancelled_by_driver);
        }

        return view('admin.rides.index',compact('confirmed_rides','confirmed_rides_count','pending_rides_count','pre_cancelled_rides','upcoming_rides','completed_rides','completed_rides_count','pending_rides','in_progress_rides','count_in_progress_ride','cancelled_by_drivers'));
    }
   

    public function rideDetails($ride_id = null)
    {
        // Retrieve ride details with related models
        $ride_details = RideRequests::with(['booking.admin', 'users','paymentStatus'])->find($ride_id);
        // Check if ride details exist
        if (!$ride_details) {
            // Redirect back with an error message if ride not found
            return redirect()->back()->with('error', 'Ride not found.');
        }

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

        // Return the view with ride details
        return view('admin.rides.ride_details', compact('average_rating','total_reviews','ride_details','driver_completed_ride_count'));
    }

    
    public function acceptRideRequest(Request $request)
    {
        $driver_role = config('global-constant.driver_role');
        $employee_role = config('global-constant.employee_role');
        $booking_id = $request->booking_id;
        $admin_user = Auth::guard('admin')->user();
        $admin_id = $admin_user->id;
        $driver_email = $admin_user->email;

        $admin_role = $admin_user->roles->first()->name;
        $verification_status = $admin_user->verification_status;

       // Check if the admin role is "independent-contractors" and validate Stripe account ID
        if (($admin_role === $driver_role || $admin_role === $employee_role) && empty($admin_user->stripe_account_id)) {
            $request->session()->flash('error', 'Invalid or missing Stripe account. Please link your Stripe account to proceed.');
            return redirect()->back();
        }


        if ($admin_role === $driver_role && $verification_status=='suspended') {
            $request->session()->flash('error', 'Your account is suspended. You are not allowed to accept new rides. Please contact our support team.');
            return redirect()->back();
        }

        // Check if ride_id is provided
        if (!$booking_id) {
            $request->session()->flash('error', 'Ride not exist for update status');
            return redirect()->back();
        }

        // Find the booking using the provided ride_id
        $ride_detail = Booking::find($booking_id);
        $booking_status = $request->booking_status;


        // Check if ride detail exists
        if (!$ride_detail) {
            $request->session()->flash('error', 'Ride not found');
            return redirect()->back();
        }

        $payment_method = $ride_detail->payment_method;
          
        if ($admin_role === $driver_role) {
            // Count the number of pending dues for the driver
            $due_count = DriverDues::where('driver_id', $admin_id)
                ->where('status', 'pending')
                ->count();
       
            // If the driver has more than 3 pending dues and the payment method is 'cash'
            if ($due_count > 3 && $payment_method == 'cash') {
                $request->session()->flash('error', 'You cannot accept more cash rides because you have over three pending cash rides with admin commission due. Please accept only card payment rides.');
                return redirect()->back();
            }
            
        }

        // Update the booking status if the ride exists
        $ride_detail->update(['booking_status' => $booking_status,'driver_id'=>$admin_id]);
        
        $ride_id = $ride_detail->ride_id;
        $payment = Payment::where('ride_id',$ride_id)->first();

        $payment->update([
           'driver_id'=>$admin_id
        ]);

        $booking_ride = Booking::with(['rideRequests','admin','user'])->find($booking_id);
        $customer_mail = $booking_ride->user->email ?? null;

        $ride = RideRequests::with(['paymentStatus','booking', 'booking.card'])->find($ride_id);
        // get surge amount
        $ride_surge_amount = checkSurgePrice($ride);
        $total_fare =  $booking_ride->ride_booking_amount + $ride_surge_amount->booking->surge_amount;
           
        $message = "Booking status updated successfully";

        if ($booking_status == 'Completed') {

            if($payment){

                DriverMarkPayment::create([
                    'driver_id'=>$admin_id,
                    'ride_id'=>$ride_id,
                    'payment_method' => 'cash',
                    'amount' => $total_fare,
                    'status'=>'paid',
                    'payment_date' => now()->format('Y-m-d'),
                    'payment_time' => now()->format('H:i:s'),                 
                ]);

                $payment->update([
                    'driver_id'=>$admin_id,
                    'payment_method' => 'cash',
                    'amount' => $total_fare,
                    'status'=>'paid',
                    'payment_date' => now()->format('Y-m-d'),
                    'payment_time' => now()->format('H:i:s'), 
                ]);
            }
            
            $booking_ride->update(['final_booking_amount' => $total_fare]);

            if ($admin_role === $driver_role) {
                
                $admin_comission = AdminComission::first();
                $comission = 0;
                if($admin_comission->type === "percentage"){
                    $commissionPercentage = $admin_comission->commission;
                    $comission = ($total_fare * $commissionPercentage) / 100;
                }

                DriverDues::create([
                    'ride_id'=>$ride_id,
                    'driver_id'=>$admin_id,
                    'total_due'=>round($comission,2),
                ]);
            
                $booking_ride->update([
                    'admin_commission'=>round($comission,2),
                    'driver_earning'=>$total_fare - round($comission,2),
                ]);
                
            }

            event(new AdminCompleteRideNotification([
                'title' => 'Your Ride Completed',
                'notification_type' => 'ride_completed',
                'type' => 'admin',
                'message' => 'Your ride has been completed.',
                'admin_id' => $ride_detail->driver_id,
                'user_id' => $ride_detail->customer_id,
            ]));

            $booking_ride = addSurgePriceOnMail($booking_ride);

            if($customer_mail){
                Mail::to($customer_mail)->send(new CompleteRideCustomerMail($booking_ride));
            }

            if($driver_email){
                Mail::to($driver_email)->send(new CompleteRideDriverMail($booking_ride));
            }

            $message = "Ride completed and payment paid successfully.";
             
        }
        
        $booking_ride = addSurgePriceOnMail($booking_ride);

        if ($booking_status == 'Confirmed') {

            event(new AdminConfirmRideNotification([
                'title' => 'Driver Confirmed Ride',
                'notification_type' => 'ride_confirmed',
                'type'=>'admin',
                'message' => "The driver has Confirmed the ride request.",
                'admin_id' => $ride_detail->driver_id,
                'user_id' => $ride_detail->customer_id,
            ])); 
         
            if($customer_mail){
                Mail::to($customer_mail)->send(new ConfirmBooking($booking_ride));
            }

            if($driver_email){
                Mail::to($driver_email)->send(new ConfirmBookingDriverMail($booking_ride));
            }

            $message = "You have confirmed the ride request.";

        }
 
        if ($booking_status == 'Cancelled') {

            $cancellation_reason = CancellationReasons::first();
            RideCancellations::create([
                'driver_id' => $ride_detail->driver_id,
                'cancel_id' => $cancellation_reason->id,
                'ride_id' => $ride_id,
            ]);

            event(new AdminCancelRideNotification([
                'title' => 'Driver Cancelled Ride',
                'notification_type' => 'ride_cancelled',
                'type'=>'admin',
                'message' => "The driver has Cancelled the ride request.",
                'admin_id' => $ride_detail->driver_id,
                'user_id' => $ride_detail->customer_id,
            ]));
            
            if($driver_email){
                Mail::to($driver_email)->send(new DriverCancelRideAdminMail($booking_ride));
            }

            if($customer_mail){
                Mail::to($customer_mail)->send(new DriverCancelRideCustomerMail($booking_ride));
            }
        }

        // Check if the booking status is "rejected" and set the appropriate message
        if ($booking_status == 'Cancelled') {
            $request->session()->flash('error', 'Booking Cancelled successfully');
        } else {
            $request->session()->flash('success', $message);
        }

        // Redirect to the rides page
        return redirect()->route('admin-rides');
    }


    public function completeRideCardPayment(Request $request)
    {
        try {
            
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $driver_role = config('global-constant.driver_role');
            $employee_role = config('global-constant.employee_role');
            $booking_id = $request->booking_id;
            $admin_user = Auth::guard('admin')->user();
            $admin_id = $admin_user->id;
            $driver_email = $admin_user->email;

            $admin_role = $admin_user->roles->first()->name;
    
            // Check if the admin role is "independent-contractors" and validate Stripe account ID
            if (($admin_role === $driver_role || $admin_role === $employee_role) && empty($admin_user->stripe_account_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or missing Stripe account. Please link your Stripe account to proceed.',
                ], 400);
            }

            // Find the booking using the provided ride_id
            $booking_ride = Booking::with(['rideRequests','admin','user','card'])->find($booking_id);

            if (!$booking_ride || !$booking_ride->card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid ride or payment details!',
                ], 400);
            }

            $payment_method = $booking_ride->payment_method;
            
            if ($admin_role === $driver_role || $admin_role === $employee_role) {
                // Count the number of pending dues for the driver
                $due_count = DriverDues::where('driver_id', $admin_id)
                    ->where('status', 'pending')
                    ->count();
        
                // If the driver has more than 3 pending dues and the payment method is 'cash'
                if ($due_count > 3 && $payment_method == 'cash') {
                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot accept more cash rides because you have over three pending cash rides with admin commission due. Please accept only card payment rides.',
                    ], 400);
                }   
            }

            $ride_id = $booking_ride->rideRequests->id;
            $ride = RideRequests::with(['paymentStatus','booking', 'booking.card'])->find($ride_id);
            
            if (!$ride || !$ride->booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid ride!',
                ], 400);
            }

            // get surge amount
            $ride_surge_amount = checkSurgePrice($ride);
            
            $total_fare = $ride->booking->ride_booking_amount + $ride_surge_amount->booking->surge_amount;
            $amount = $total_fare * 100; // Convert to cents
            $paymentMethodId = $ride->booking->card->payment_method_id;
            $stripeCustomerId = $ride->booking->card->stripe_customer_id;
            $driver_id = $ride->booking->driver_id;

            $driver = Admin::find($driver_id);
            $admin_comission = AdminComission::first();
            $comission = 1;

            if($admin_comission->type === "percentage"){
                $commissionPercentage = $admin_comission->commission;
                $comission = ($total_fare * $commissionPercentage) / 100;
            }

            // Driver's earnings after admin deduction
            $driver_earning = $total_fare - round($comission,2);

            if (!$paymentMethodId || !$stripeCustomerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing payment method or Stripe customer ID!',
                ], 400);
            }

            $paymentIntent = [
                'amount' => $amount,
                'currency' => 'usd',
                'customer' => $stripeCustomerId,
                'payment_method' => $paymentMethodId,
                'off_session' => true, // For one-click payments without user interaction
                'confirm' => true,
            ];

            if(isset($driver->stripe_account_id) && !empty($driver->stripe_account_id)){
                $paymentIntent['transfer_group'] = "ride_{$ride->id}";
            }

           
            if(isset($driver->stripe_account_id) && !empty($driver->stripe_account_id)){

                try {

                    $pendingDues = DriverDues::where('driver_id', $driver_id)
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'asc')
                    ->get();

                    $totalDue = $pendingDues->sum('total_due');
                    $deductibleAmount = min($totalDue, $driver_earning);
                    $driverFinalEarnings = round($driver_earning - $deductibleAmount,2);         
                    $remainingDeduction = $deductibleAmount;

                    foreach ($pendingDues as $due) {
                        if ($remainingDeduction <= 0) break;

                        if ($due->total_due <= $remainingDeduction) {
                            // Fully Paid
                            $remainingDeduction -= $due->total_due;
                            $due->update(['status' => 'paid', 'last_payment' => now()]);
                        } else {
                            // Partially Deducted
                            $due->update(['total_due' => $due->total_due - $remainingDeduction]);
                            $remainingDeduction = 0;
                        }
                    }

                    if($driverFinalEarnings>0){
                        // Transfer money to the driver after successful payment
                        $transfer = \Stripe\Transfer::create([
                            "amount" => $driverFinalEarnings * 100, 
                            "currency" => "usd",
                            "destination" => $driver->stripe_account_id, // Connected driver's Stripe account
                            "transfer_group" => "ride_{$ride->id}",
                            "description" => "Driver payout for Ride ID: {$ride->id}"
                        ]);
                    }
                
                    if($booking_ride){

                        $adminCommission = round((float)$total_fare - (float)$driver_earning, 2);
                        $driverEarning = round((float)$driverFinalEarnings, 2);
                        $booking_ride->update([
                            'admin_commission'=>$adminCommission,
                            'driver_earning'=>$driverEarning,
                        ]);

                    }

                } catch (\Exception $e) {
                    
                    $friendlyMessage = '';
                    if (strpos(strtolower($e->getMessage()), 'insufficient') !== false) {
                        $friendlyMessage .= 'Admin Stripe account has insufficient available funds. Please contact support for assistance.';
                    } else {
                        $friendlyMessage .= 'Please verify your Stripe account details or contact support for further help.';
                    }
                    
                    return response()->json([
                        'success' => false,
                        'message' => $friendlyMessage,
                        'details' => 'Payment failed, could not process the transfer: ' . $e->getMessage(),
                    ], 500);
                    
                }

            }

            // Create a PaymentIntent using the saved payment method
            $paymentIntent = \Stripe\PaymentIntent::create($paymentIntent);

            if ($paymentIntent->status === 'succeeded') {

                // Update the booking status if the ride exists
                $booking_ride->update([
                    'booking_status' => 'Completed',
                    'final_booking_amount'=>$total_fare,
                ]);

                $payment = Payment::where('ride_id',$ride_id)->first();

                if($payment){

                    $user_id = $booking_ride->customer_id;

                    Transaction::create([
                        'driver_id'=>$admin_id,
                        'customer_id'=>$user_id,
                        'ride_id'=>$ride_id,
                        'payment_method' => 'card',
                        'amount' => $total_fare,
                        'status'=>'paid',
                        'payment_date' => now()->format('Y-m-d'),
                        'payment_time' => now()->format('H:i:s'),                 
                    ]);

                    $payment->update([
                        'driver_id'=>$admin_id,
                        'payment_method' => 'card',
                        'amount' => $total_fare,
                        'status'=>'paid',
                        'payment_date' => now()->format('Y-m-d'),
                        'payment_time' => now()->format('H:i:s'), 
                    ]);
                }

                $customer_mail = $booking_ride->user->email ?? null; 
             
                event(new AdminCompleteRideNotification([
                    'title' => 'Your Ride Completed',
                    'notification_type' => 'ride_completed',
                    'type' => 'admin',
                    'message' => 'Your ride has been completed.',
                    'admin_id' => $booking_ride->driver_id,
                    'user_id' => $booking_ride->customer_id,
                ]));

                $booking_ride = addSurgePriceOnMail($booking_ride);

                if($customer_mail){
                    Mail::to($customer_mail)->send(new CompleteRideCustomerMail($booking_ride));
                }

                if($driver_email){
                    Mail::to($driver_email)->send(new CompleteRideDriverMail($booking_ride));
                }
                    
                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful!',
                    'paymentIntent' => $paymentIntent,
                ], 200);
            }

        } catch (\Stripe\Exception\CardException $e) {
            // Payment declined (e.g., insufficient funds, expired card, incorrect CVC)
            \Log::error("Card payment failed for Ride ID: {$ride_id}", ['error' => $e->getMessage()]);

             // Handle insufficient funds error
            if ($error->code === 'card_declined' && $error->decline_code === 'insufficient_funds') {
                
                $booking_ride = addSurgePriceOnMail($booking_ride);

                // Send an email to the customer notifying them of insufficient funds
                Mail::to($customer_mail)->send(new InsufficientFundsNotification($booking_ride));

                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed: Insufficient funds on the card.',
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getError()->message, // Detailed error message
            ], 400);

        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests to the API
            return response()->json([
                'success' => false,
                'message' => 'Too many payment attempts, please try again later.',
            ], 429);

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters (e.g., incorrect amount, invalid customer ID)
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment request, please check your details.' . $e->getError()->message,
            ], 400);

        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Invalid Stripe API key
            return response()->json([
                'success' => false,
                'message' => 'Payment authentication failed, please contact support.',
            ], 401);

        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network error
            return response()->json([
                'success' => false,
                'message' => 'Network issue, please check your internet connection.',
            ], 502);

        } catch (\Stripe\Exception\ApiErrorException $e) {

            // Other Stripe API errors
            return response()->json([
                'success' => false,
                'message' => 'Payment could not be processed, please try again later.',
            ], 500);

        } catch (\Exception $e) {
            // General server error
            \Log::error("General error in payment for Ride ID: {$ride_id}", ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred, please try again.'.$e->getMessage(),
            ], 500);
        }

    }

    

    public function rejectRideRequestByAdmin(Request $request)
    {
        $booking_id = $request->booking_id;
        // $driver_role = config('global-constant.driver_role');
        $employee_role = config('global-constant.employee_role');
        
        $employees = Admin::where('status', true)
                        ->where('is_email_verified', true)
                        ->where('login_check', true)
                        ->whereHas('roles', function($query) use ($employee_role) {
                            $query->where('name', $employee_role);
                        })
                        ->get();           
    
        // Check if ride_id is provided
        if (!$booking_id) {
            $request->session()->flash('error', 'Ride does not exist for updating status');
            return redirect()->back();
        }
    
        // Find the booking using the provided booking_id
        $ride_detail = Booking::find($booking_id);
        
        // Check if the booking exists
        if (!$ride_detail) {
            $request->session()->flash('error', 'Ride not found');
            return redirect()->back();
        }
    
        // Fetch the detailed booking along with related data
        $booking_ride = Booking::with(['rideRequests','user'])->find($booking_id);
        $booking_status = $request->booking_status;
    
        // Check if the booking status is to reject the ride
        if ($booking_status === 'reject-ride') {
            // Update the booking status to rejected by the admin
            $booking_ride->update([
                'reject_by_super_admin' => 1
            ]);
    
            $booking_ride = addSurgePriceOnMail($booking_ride);

            // Send email notifications to the employee
            foreach ($employees as $employee) {
                $employee_email = $employee->email;
    
                if ($employee_email) {
                    Mail::to($employee_email)->send(new NewRideRequestDriverMail($booking_ride));
                }
            }
    
            $request->session()->flash('success', 'Booking rejected successfully');
        } else {
            $request->session()->flash('error', 'Invalid booking status');
        }
    
        // Redirect to the rides page
        return redirect()->route('admin-rides');
    }    


    public function showRideRequestDriver(Request $request)
    {
        $booking_id = $request->booking_id;
        $driver_role = config('global-constant.driver_role');
        
        $drivers = Admin::where('status', true)
                    ->where('is_email_verified', true)
                    ->where('login_check', true)
                    ->where('verification_status', 'verified')
                    ->whereHas('roles', function($query) use ($driver_role) {
                        $query->where('name', $driver_role);
                    })
                    ->get();    
    
        // Check if ride_id is provided
        if (!$booking_id) {
            $request->session()->flash('error', 'Ride does not exist for updating status');
            return redirect()->back();
        }
    
        // Find the booking using the provided booking_id
        $ride_detail = Booking::find($booking_id);
        
        // Check if the booking exists
        if (!$ride_detail) {
            $request->session()->flash('error', 'Ride not found');
            return redirect()->back();
        }
    
        // Fetch the detailed booking along with related data
        $booking_ride = Booking::with(['rideRequests','user'])->find($booking_id);
        $booking_status = $request->booking_status;
        $reject_by_super_admin = $booking_ride->reject_by_super_admin;

        if (!$reject_by_super_admin) {
            $request->session()->flash('error', 'The driver cannot view this ride until it is first shown to the employees.');
            return redirect()->back();
        }

        // Update the booking status to rejected by the admin
        $booking_ride->update([
            'reject_by_employee' => 1
        ]);

        $booking_ride = addSurgePriceOnMail($booking_ride);

        // Send email notifications to the employee
        foreach ($drivers as $driver) {
            $driver_email = $driver->email;

            if ($driver_email) {
                Mail::to($driver_email)->send(new NewRideRequestDriverMail($booking_ride));
            }
        }

        $request->session()->flash('success', 'Ride has been successfully sent to all drivers.');
    
        // Redirect to the rides page
        return redirect()->route('admin-rides');
    }    


    public function startRide(Request $request)
    {
        $booking_id = $request->booking_id;
        $admin_user = Auth::guard('admin')->user();
        $admin_id = $admin_user->id;
        $driver_email = $admin_user->email;
        
        // Check if booking_id is provided
        if (!$booking_id) {
            $request->session()->flash('error', 'Ride not exists for update status.');
            return redirect()->back();
        }
    
        // Check if there is any ongoing ride for this admin
        $ongoingRide = Booking::where('driver_id', $admin_id)
            ->where('booking_status', 'In Progress')
            ->first();
        
        if ($ongoingRide) {
            $request->session()->flash('error', 'You cannot start a new ride until the current ride is completed.');
            return redirect()->back();
        }

        $completeRidePayment = Booking::where('driver_id', $admin_id)
        ->where('booking_status', 'Completed')
        ->whereHas('rideRequests.paymentStatus', function($query) {
            $query->where('status', 'unpaid');
        })
        ->get();

        if ($completeRidePayment->isNotEmpty()) {
            // If there's an ongoing unpaid ride, show an error message and redirect
            $request->session()->flash('error', 'Please mark the payment as paid before accepting the next ride for the completed ride.');
            return redirect()->back();
        }

    
        // Find the booking using the provided booking_id
        $ride_detail = Booking::find($booking_id);
    
        // Check if ride detail exists
        if (!$ride_detail) {
            $request->session()->flash('error', 'Ride not found.');
            return redirect()->back();
        }

    
        // Update the booking status and assign the driver
        $booking_status = $request->booking_status;
        $ride_detail->update(['booking_status' => $booking_status, 'driver_id' => $admin_id]);

        event(new AdminStartRideNotification([
            'title' => 'Ride Started',
            'notification_type' => 'ride_start',
            'type' => 'admin',
            'message' => 'The driver has started the ride.',
            'admin_id' => $ride_detail->driver_id,
            'user_id' => $ride_detail->customer_id,
        ]));

        $booking_ride = Booking::with(['rideRequests','admin','user'])->find($booking_id);
        $customer_mail = $booking_ride->user->email ?? null;

        $booking_ride = addSurgePriceOnMail($booking_ride);

        if($driver_email){
            Mail::to($driver_email)->send(new RideStartDriverMail($booking_ride));
        }

        if($customer_mail){
            Mail::to($customer_mail)->send(new RideStartCustomerMail($booking_ride));
        }
    
        // Update payment details with the driver's ID
        $ride_id = $ride_detail->ride_id;
        $payment = Payment::where('ride_id', $ride_id)->first();
    
        if ($payment) {
            $payment->update(['driver_id' => $admin_id]);
        }
    
        // Set the appropriate flash message based on the booking status
        $request->session()->flash('success', 'Booking status updated successfully.');
  
        // Redirect to the rides page
        return redirect()->route('admin-rides');
    }    


    public function rideDelete(Request $request, $ride_id = null)
    {
        // Find the ride by ID
        $ride = RideRequests::find($ride_id);
    
        // Check if ride detail exists
        if (!$ride) {
            $request->session()->flash('error', 'Ride not found');
            return redirect()->back();
        }

        $booking_id = $ride->booking->id ?? null;
        $booking_ride = Booking::with(['rideRequests','admin','user'])->find($booking_id);
        $customer_mail = $booking_ride->user->email ?? null;
        $driver_email = $booking_ride->admin->email ?? null;
        $booking_ride = addSurgePriceOnMail($booking_ride);

        // Check if there's an associated booking and delete it
        if ($ride->booking) {
            $ride->booking->delete();  // Delete the associated booking record
        }
        
        if ($ride->cancelledRide) {
            $ride->cancelledRide->delete();  // Delete the associated booking record
        }

        // Delete the ride record
        $ride->delete();
    
        if($driver_email){
            Mail::to($driver_email)->send(new deleteRideDriverMail($booking_ride));
        }

        if($customer_mail){
            Mail::to($customer_mail)->send(new deleteRideCustomerMail($booking_ride));
        }

        // Flash success message
        $request->session()->flash('success', 'Ride deleted successfully');
    
        // Redirect to the rides page
        return redirect()->route('admin-rides');
    }


    public function markPaidPayment(Request $request)
    {
        // Authenticate the admin user and retrieve their ID
        $admin_user = Auth::guard('admin')->user();
        $admin_id = $admin_user->id;
        $user_role = $admin_user->roles->first()->name;

        // Validate that booking_id is provided
        $booking_id = $request->booking_id;
        if (!$booking_id) {
            $request->session()->flash('error', 'Ride does not exist for updating payment status.');
            return redirect()->back();
        }
    
        // Retrieve the booking details using the booking_id
        $ride_detail = Booking::find($booking_id);
    
        // Check if booking details are found
        if (!$ride_detail) {
            $request->session()->flash('error', 'Ride not found.');
            return redirect()->back();
        }
    
        // Retrieve input values
        $payment_method = $request->payment_method;
        $payment_status = $request->payment_status;
        $final_amount = $request->final_amount;
    
        // Update the final booking amount in the booking record
        $ride_detail->update(['final_booking_amount' => $final_amount]);
    
        // Retrieve payment details associated with the ride
        $ride_id = $ride_detail->ride_id;
        $payment = Payment::where('ride_id', $ride_id)->first();
        
        // Update payment details if they exist
        if ($payment) {
            
            DriverMarkPayment::create([
                'driver_id'=>$admin_id,
                'ride_id'=>$ride_id,
                'payment_method' => $payment_method,
                'amount' => $final_amount,
                'status'=>$payment_status,
                'payment_date' => now()->format('Y-m-d'),
                'payment_time' => now()->format('H:i:s'),                 
            ]);

            $payment->update([
                'payment_method' => $payment_method,
                'amount' => $final_amount,
                'status'=>$payment_status,
                'payment_date' => now()->format('Y-m-d'),
                'payment_time' => now()->format('H:i:s'), 
            ]);
        } else {
            // Optionally, handle the case where payment details are not found
            $request->session()->flash('error', 'Payment record not found.');
            return redirect()->back();
        }

        if($user_role === 'independent-contractors' || $user_role === 'employees'){
            
            $admin_comission = AdminComission::first();
            $comission = 0;
            if($admin_comission->type === "percentage"){
                $commissionPercentage = $admin_comission->commission;
                $comission = ($final_amount * $commissionPercentage) / 100;
            }

            DriverDues::create([
                'ride_id'=>$ride_id,
                'driver_id'=>$admin_id,
                'total_due'=>round($comission,2),
            ]);
          
            $ride_detail->update([
                'admin_commission'=>round($comission,2),
                'driver_earning'=>$final_amount - round($comission,2),
            ]);
            
        }

        // Flash a success message for the admin
        $request->session()->flash('success', 'Payment marked as paid successfully.');
    
        // Redirect the admin to the rides page
        return redirect()->route('admin-rides');
    }


    public function addMiles(Request $request)
    {
        // Authenticate the admin user and retrieve their ID
        $admin_user = Auth::guard('admin')->user();
        $admin_id = $admin_user->id;
    
        // Validate that booking_id is provided
        $booking_id = $request->booking_id;
        if (!$booking_id) {
            $request->session()->flash('error', 'Ride does not exist for add more miles.');
            return redirect()->back();
        }
    
        // Retrieve the booking details using the booking_id
        $ride_detail = Booking::find($booking_id);
    
        // Check if booking details are found
        if (!$ride_detail) {
            $request->session()->flash('error', 'Ride not found.');
            return redirect()->back();
        }
    
        // Retrieve input values
        $miles = $request->add_miles;
        $miles_distance = $miles + $ride_detail->miles_distance;
        $kilometers = round($miles * 1.60934, 2);
        $distance = $kilometers + $ride_detail->distance;

        $rate_per_miles = config('global-constant.ride_per_km_charge.rate_per_km');
        $ride_booking_amount = $miles_distance*$rate_per_miles;
    
        // Update the final booking amount in the booking record
        $ride_detail->update([
            'distance' => $distance,
            'miles_distance' => $miles_distance,
            'ride_booking_amount' => $ride_booking_amount,
            'extend_distance' => $kilometers + $ride_detail->extend_distance ?? 0,
            'extend_miles_distance' => $miles + $ride_detail->extend_miles_distance ?? 0,
        ]);
        
        // Flash a success message for the admin
        $request->session()->flash('success', 'The miles have been successfully extended.');
    
        // Redirect the admin to the rides page
        return redirect()->route('admin-ride-details',['ride_id'=>$ride_detail->ride_id]);
    }


    public function verifyRideOTP(Request $request)
    {
        // Concatenate the OTP digits from the request
        $otp = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4;
        $ride_id = $request->ride_id;

        // Retrieve the OTP entry for the user
        $otpEntry = RideOTP::where('ride_id', $ride_id)
                        ->where('otp', $otp)
                        ->first();

        // Check if the OTP entry exists
        if (!$otpEntry) {
            $request->session()->flash('error', 'Invalid OTP.');
            // Redirect the admin to the rides page
            return redirect()->route('admin-ride-details', ['ride_id' => $ride_id]);
        }

        // Retrieve the ride and booking details
        $ride = RideRequests::with(['booking'])->find($ride_id);
        if (!$ride || !$ride->booking) {
            $request->session()->flash('error', 'Ride or booking not found.');
            return redirect()->route('admin-ride-details', ['ride_id' => $ride_id]);
        }

        // Get the booking ID and update the booking status
        $booking_id = $ride->booking->id;
        $booking = Booking::find($booking_id);
        if ($booking) {
            $booking->is_otp_verified = true;
            $booking->save();
        }

        // Flash a success message
        $request->session()->flash('success', 'OTP verified successfully.');

        // Redirect the admin to the rides page
        return redirect()->route('admin-ride-details', ['ride_id' => $ride_id]);
    }


    /**
     * Reverts the ride booking status to "Pending" and clears the assigned driver.
     * 
     * This function finds a booking by its ID, checks if the ride exists, 
     * and updates the ride status to "Pending" while clearing the assigned driver.
     * It also provides appropriate flash messages to notify the user of the result.
     * 
     * @param Request $request The incoming HTTP request.
     * @param int $booking_id The ID of the booking to revert.
     * @return \Illuminate\Http\RedirectResponse A redirect response to the admin rides page with flash messages.
     */
    public function revertRide(Request $request, $booking_id)
    {
        // Find the booking record by its ID
        $ride_detail = Booking::find($booking_id);

        // If no ride found, display an error message and redirect back
        if (!$ride_detail) {
            $request->session()->flash('error', 'Ride not found.');
            return redirect()->back();
        }

        // Update the booking status to "Pending" and clear the assigned driver
        $ride_detail->update([
            'booking_status' => 'Pending',
            'driver_id' => null
        ]);

        // Flash a success message indicating the ride was successfully reverted
        $request->session()->flash('success', 'Ride Reverted Successfully.');

        // Redirect to the admin rides page
        return redirect()->route('admin-rides');
    }


    public function pendingRideData(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $admin_id = $admin->id;
        $admin_role = $admin->roles->first()->name ?? null;

        // pending rides
        $query = RideRequests::whereHas('booking', function ($query) use ($admin_role){
            $query->where('booking_status', 'Pending');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                if ($admin_role == 'employees') {
                    $query->where('reject_by_super_admin', true);
                }else{
                    $query->where('reject_by_super_admin', true)->where('reject_by_employee', true);
                }
            }
        })->with(['booking', 'users']);

        // Apply custom search if provided
        if ($request->has('custom_search') && $request->custom_search) {
            $searchTerm = $request->custom_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('pick_up_address', 'like', "%{$searchTerm}%")
                    ->orWhere('drop_off_address', 'like', "%{$searchTerm}%")
                    ->orWhere('phone_number', 'like', "%{$searchTerm}%")
                    // Filter on user name (first_name and last_name)
                    ->orWhereHas('users', function ($query) use ($searchTerm) {
                        $query->where('first_name', 'like', "%{$searchTerm}%")
                            ->orWhere('last_name', 'like', "%{$searchTerm}%");
                    })->orWhereHas('booking', function ($query) use ($searchTerm) {
                        $query->where('user_phone_number', 'like', "%{$searchTerm}%")
                        ->orWhere('booking_number', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Sorting logic based on the column and direction from the request
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir');
        $columns = $request->input('columns');
        $orderColumn = $columns[$orderColumnIndex]['name'];

        if($orderColumn=='user'){
            $query->with(['users' => function ($query) use ($orderColumn) {
                $query->orderBy('first_name', $request->input('orderDirection', 'asc'));
            }]);
        }elseif($orderColumn=='booking_number'){
            $query->with(['booking' => function ($query) use ($orderColumn) {
                $query->orderBy('booking_number', $request->input('orderDirection', 'asc'));
            }]);
        }else{
            $query->orderBy($orderColumn, $orderDirection);
        }

        // Pagination logic
        $totalRecords = $query->count();
        $pending_rides = $query->skip($request->start)
            ->take($request->length)
            ->with(['booking', 'users'])
            ->orderBy('created_at','desc')
            ->get();


        foreach($pending_rides as $pending_ride){
            $pending_ride = checkSurgePrice($pending_ride);

             // Format pick_up_date if available
            if ($pending_ride->pick_up_date) {
                $pending_ride->pick_up_date = Carbon::parse($pending_ride->pick_up_date)->format('m/d/Y');
            }
        }

        
        // Return data formatted for DataTables
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $pending_rides,
        ]);
    }


    public function completedRideData(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $admin_id = $admin->id;
        $admin_role = $admin->roles->first()->name ?? null;

        // Completed rides
        $query = RideRequests::whereHas('booking', function ($query) use ($admin_role){
            $query->where('booking_status', 'Completed');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                if ($admin_role == 'employees') {
                    $query->where('reject_by_super_admin', true);
                }else{
                    $query->where('reject_by_super_admin', true)->where('reject_by_employee', true);
                }
            }
        });

        // Apply custom search if provided
        if ($request->has('custom_search') && $request->custom_search) {
            $searchTerm = $request->custom_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('pick_up_address', 'like', "%{$searchTerm}%")
                    ->orWhere('drop_off_address', 'like', "%{$searchTerm}%")
                    ->orWhere('phone_number', 'like', "%{$searchTerm}%")
                    // Filter on user name (first_name and last_name)
                    ->orWhereHas('users', function ($query) use ($searchTerm) {
                        $query->where('first_name', 'like', "%{$searchTerm}%")
                            ->orWhere('last_name', 'like', "%{$searchTerm}%");
                    })->orWhereHas('booking', function ($query) use ($searchTerm) {
                        $query->where('user_phone_number', 'like', "%{$searchTerm}%")
                        ->orWhere('booking_number', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Sorting logic based on the column and direction from the request
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir');
        $columns = $request->input('columns');
        $orderColumn = $columns[$orderColumnIndex]['name'];

        if($orderColumn=='user'){
            $query->with(['users' => function ($query) use ($orderDirection) {
                $query->orderBy('first_name', $orderDirection);
            }]);
        }elseif($orderColumn=='booking_number'){
            $query->with(['booking' => function ($query) use ($orderDirection) {
                $query->orderBy('booking_number', $orderDirection);
            }]);
        }elseif($orderColumn=='admin_name'){
            $query->with(['booking.admin' => function ($query) use ($orderDirection) {
                $query->orderBy('name', $orderDirection);
            }]);
        }elseif($orderColumn=='payment_status'){
            $query->with(['paymentStatus' => function ($query) use ($orderDirection) {
                $query->orderBy('status', $orderDirection);
            }]);
        }else{
            $query->orderBy($orderColumn, $orderDirection);
        }

        // Pagination logic
        $totalRecords = $query->count();
        $complete_rides = $query->skip($request->start)
            ->take($request->length)
            ->with(['booking.admin', 'users','paymentStatus'])
            ->orderBy('created_at','desc')
            ->get();

        foreach($complete_rides as $complete_ride){
            $complete_ride = checkSurgePrice($complete_ride);
            
            // Format pick_up_date if available
            if ($complete_ride->pick_up_date) {
                $complete_ride->pick_up_date = Carbon::parse($complete_ride->pick_up_date)->format('m/d/Y');
            }
        }
        
        // Return data formatted for DataTables
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $complete_rides,
        ]);
    }


    public function acceptedRideData(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $admin_id = $admin->id;
        $admin_role = $admin->roles->first()->name ?? null;

        // Confirmed rides
        $query = RideRequests::whereHas('booking', function ($query) use ($admin_role){
            $query->where('booking_status', 'Confirmed');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                if ($admin_role == 'employees') {
                    $query->where('reject_by_super_admin', true);
                }else{
                    $query->where('reject_by_super_admin', true)->where('reject_by_employee', true);
                }
            }
        });

        // Apply custom search if provided
        if ($request->has('custom_search') && $request->custom_search) {
            $searchTerm = $request->custom_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('pick_up_address', 'like', "%{$searchTerm}%")
                    ->orWhere('drop_off_address', 'like', "%{$searchTerm}%")
                    ->orWhere('phone_number', 'like', "%{$searchTerm}%")
                    // Filter on user name (first_name and last_name)
                    ->orWhereHas('users', function ($query) use ($searchTerm) {
                        $query->where('first_name', 'like', "%{$searchTerm}%")
                            ->orWhere('last_name', 'like', "%{$searchTerm}%");
                    })->orWhereHas('booking', function ($query) use ($searchTerm) {
                        $query->where('user_phone_number', 'like', "%{$searchTerm}%")
                        ->orWhere('booking_number', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Sorting logic based on the column and direction from the request
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir');
        $columns = $request->input('columns');
        $orderColumn = $columns[$orderColumnIndex]['name'];

        if($orderColumn=='user'){
            $query->with(['users' => function ($query) use ($orderDirection) {
                $query->orderBy('first_name', $orderDirection);
            }]);
        }elseif($orderColumn=='booking_number'){
            $query->with(['booking' => function ($query) use ($orderDirection) {
                $query->orderBy('booking_number', $orderDirection);
            }]);
        }elseif($orderColumn=='admin_name'){
            $query->with(['booking.admin' => function ($query) use ($orderDirection) {
                $query->orderBy('name', $orderDirection);
            }]);
        }elseif($orderColumn=='payment_status'){
            $query->with(['paymentStatus' => function ($query) use ($orderDirection) {
                $query->orderBy('status', $orderDirection);
            }]);
        }else{
            $query->orderBy($orderColumn, $orderDirection);
        }

        // Pagination logic
        $totalRecords = $query->count();
        $confirm_rides = $query->skip($request->start)
            ->take($request->length)
            ->with(['booking.admin', 'users','paymentStatus'])
            ->orderBy('created_at','desc')
            ->get();

        foreach($confirm_rides as $confirm_ride){
            $confirm_ride = checkSurgePrice($confirm_ride);
            
            // Format pick_up_date if available
            if ($confirm_ride->pick_up_date) {
                $confirm_ride->pick_up_date = Carbon::parse($confirm_ride->pick_up_date)->format('m/d/Y');
            }
        }
        
        // Return data formatted for DataTables
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $confirm_rides,
        ]);
    }


}
