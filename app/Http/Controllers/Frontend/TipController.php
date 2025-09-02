<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\TipAmount;

class TipController extends Controller
{
    /**
     * Display the tip form for a specific driver.
     *
     * @param  int  $driver_id  The ID of the driver to tip.
     * @return \Illuminate\View\View  The tip form view.
     */
    public function tipForm($encryptedBookingId)
    {
        $booking_id = decrypt($encryptedBookingId);
        return view('Frontend.tip.index',compact('booking_id'));
    }

    /**
     * Create a Stripe Checkout session for the tip payment and redirect the user to the Stripe payment page.
     *
     * This method generates a Stripe payment link for tipping a specific driver. It validates the provided
     * tip amount and sets up a payment session with Stripe Checkout. The customer will be redirected to
     * Stripe's secure payment page.
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request containing the tip amount.
     * @param  int  $driverId  The ID of the driver to tip.
     * @return \Illuminate\Http\RedirectResponse  Redirects to Stripe Checkout page.
     */
    public function createTipPaymentLink(Request $request, $bookingId)
    {
        // Validate input
        $request->validate([
            'tip_amount' => 'required|numeric|min:1',
        ]);

        $booking = Booking::find($bookingId);
        $driverId = $booking->driver_id;
    
        $driver = Admin::findOrFail($driverId);

        // Set your Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $admin_role = $driver->roles->first()->name ?? null;

        if ($admin_role == 'super-admin') {
            $paymentIntentData = [];
        } else {
            $paymentIntentData = [
                'transfer_data' => [
                    'destination' => $driver->stripe_account_id,
                ],
            ];
        }

        $tip_amount = $request->tip_amount * 100;

        // Create a payment link via Stripe Checkout
        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Tip for ' . $driver->name,
                        ],
                        'unit_amount' => $tip_amount,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('tip.success', ['bookingId' => $bookingId]),
            'cancel_url' => route('tip.cancel', ['bookingId' => $bookingId]),
            'payment_intent_data' => $paymentIntentData
        ]);

        $request->session()->put('driver_tip_amount', $request->tip_amount);

        // Return the payment link (URL to Stripe Checkout)
        return redirect($checkoutSession->url);
    }

    /**
     * Show the cancel page after the customer cancels the tip payment.
     *
     * @param  int  $driverId  The ID of the driver to tip.
     * @return \Illuminate\View\View  The tip cancel view.
     */
    public function cancel(Request $request, $bookingId)
    {
        $request->session()->forget('driver_tip_amount');

        $booking = Booking::find($bookingId);
        $driverId = $booking->driver_id;

        $driver = Admin::findOrFail($driverId);
        return view('Frontend.tip.cancel',compact('driver'));
    }

    /**
     * Show the success page after the customer successfully completes the tip payment.
     *
     * @param  int  $driverId  The ID of the driver to tip.
     * @return \Illuminate\View\View  The tip success view.
     */
    public function success(Request $request, $bookingId)
    {
        $booking = Booking::find($bookingId);
        $driverId = $booking->driver_id;
        $rideId = $booking->ride_id;
        $customerId = $booking->customer_id;
        $amount = $request->session()->get('driver_tip_amount');

        TipAmount::create([
            'driver_id'=>$driverId,
            'customer_id'=>$customerId,
            'ride_id'=>$rideId,
            'total_tip_amount'=>$amount,
        ]);

        $request->session()->forget('driver_tip_amount');

        $driver = Admin::findOrFail($driverId);
        return view('Frontend.tip.success',compact('driver'));
    }

}
