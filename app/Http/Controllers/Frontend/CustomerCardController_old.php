<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//stripe
use Stripe\Stripe;
use Stripe\PaymentMethod;
use Stripe\Customer;
use Stripe\SetupIntent;
use Stripe\Transfer;
use Stripe\Payout;
use Stripe\Account;

//Auth
use Illuminate\Support\Facades\Auth;

//Models
use App\Models\Card;
use App\Models\RideRequests;
use App\Models\Payment;
use App\Models\Admin;
use App\Models\AdminComission;
use App\Models\Booking;
use App\Models\DriverDues;

class CustomerCardController extends BaseController
{
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // Create a SetupIntent to get the client secret
        $setupIntent = SetupIntent::create([
            'payment_method_types' => ['card'],
        ]);

        return response()->json(['clientSecret' => $setupIntent->client_secret]);
    }


    public function storeCard(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // Get the logged-in user
        $user = Auth::user();
        $user_id = $user->id;
        
        // Create a customer
        $customer = Customer::create([
            'name' => $user->first_name.' '.$user->first_name,
            'email' => $user->email,
            'payment_method' => $request->payment_method_id,
            'invoice_settings' => [
                'default_payment_method' => $request->payment_method_id,
            ],
        ]);

        // Attach Payment Method to the Customer for future use
        $paymentMethod = PaymentMethod::retrieve($request->payment_method_id);
        $paymentMethod->attach([
            'customer' => $customer->id,
        ]);

        $card = Card::where('user_id',$user_id)
        ->where('last_four',$paymentMethod->card->last4)
        ->first();
        
        if ($card) {
            return response()->json([
                'success' => false,
                'message' => 'The card already exists.',
            ], 200);  // HTTP status code 400 indicates a bad request
        }        

        // Save the card details in the local database
        $card = new Card();
        $card->user_id = $user->id;
        $card->payment_method_id = $request->payment_method_id;
        $card->brand = $paymentMethod->card->brand;
        $card->last_four = $paymentMethod->card->last4;
        $card->exp_month = $paymentMethod->card->exp_month;
        $card->exp_year = $paymentMethod->card->exp_year;
        $card->stripe_customer_id = $customer->id;
        $card->save();

        return response()->json([
            'success' => true,
            'message' => 'Card Added Successful!',
        ], 200);

    }

    public function processPayment(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $user = Auth::user();
            $ride_id = $request->ride_id;
            $ride = RideRequests::with(['paymentStatus','booking', 'booking.card'])->find($ride_id);

            if (!$ride || !$ride->booking || !$ride->booking->card) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid ride or payment details!',
                ], 400);
            }

            $total_fare = $ride->booking->ride_booking_amount;
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

            // echo "<pre>driver_earning==";
            // print_r($driver_earning);
            // die;

            if (!$paymentMethodId || !$stripeCustomerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing payment method or Stripe customer ID!',
                ], 400);
            }

            // $connectedAccount = Account::retrieve($driver->stripe_account_id);
            // $balance = \Stripe\Balance::retrieve();

            // echo "<pre>balance==";
            // print_r($balance);
            // // echo "<pre>driver_earning==";
            // // print_r($driver_earning);
            // die;

            $paymentIntent = [
                'amount' => $amount,
                'currency' => 'usd',
                'customer' => $stripeCustomerId,
                'payment_method' => $paymentMethodId,
                'off_session' => true, // For one-click payments without user interaction
                'confirm' => true,
            ];

            if($driver->stripe_account_id){
                $paymentIntent['transfer_group'] = "ride_{$ride->id}";
            }

            // echo "<pre>driver_earning==";
            // print_r($driver_earning);
            // die;

            // Create a PaymentIntent using the saved payment method
            $paymentIntent = \Stripe\PaymentIntent::create($paymentIntent);

            if($driver->stripe_account_id){

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
                
                $booking = Booking::find($ride->booking->id);

                if($booking){

                    $adminCommission = round((float)$total_fare - (float)$driver_earning, 2);
                    $driverEarning = round((float)$driverFinalEarnings, 2);
                    $booking->update([
                        'admin_commission'=>$adminCommission,
                        'driver_earning'=>$driverEarning,
                    ]);

                }

            }

            $payment = Payment::where('ride_id',$ride_id)->first();

            if($payment){

                $payment->update([
                    'payment_method' => 'card',
                    'amount' => $total_fare,
                    'status'=>'paid',
                    'payment_date' => now()->format('Y-m-d'),
                    'payment_time' => now()->format('H:i:s'), 
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'paymentIntent' => $paymentIntent,
            ], 200);

        } catch (\Stripe\Exception\CardException $e) {
            // Payment declined (e.g., insufficient funds, expired card, incorrect CVC)
            \Log::error("Card payment failed for Ride ID: {$ride_id}", ['error' => $e->getMessage()]);

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

}
