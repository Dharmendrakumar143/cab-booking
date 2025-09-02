<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\AccountLink;
use Stripe\OAuth;
use App\Models\Admin;

use Illuminate\Support\Facades\Auth;

class DriverStripeConnectController extends AdminBaseController
{
   
    public function createConnectLink(Request $request)
    {
        $driver = Auth::guard('admin')->user(); // Get the logged-in driver

        // If the driver already has a Stripe account linked, allow them to proceed
        if ($driver->stripe_account_id) {
            return redirect()->route('dashboard')->with('success', 'Your Stripe account is already connected.');
        }

        // Step 1: Redirect to Stripe OAuth login
        $authorizeUrl = "https://connect.stripe.com/oauth/authorize?response_type=code&client_id=" . env('STRIPE_CLIENT_ID') . "&scope=read_write&redirect_uri=" . route('driver.stripe.callback');

        return redirect($authorizeUrl);
    }


    public function handleStripeCallback(Request $request)
    {
        if ($request->has('code')) {
            try {
                // Set Stripe API Key
                Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

                // Step 2: Exchange the authorization code for a Stripe account ID
                $response = OAuth::token([
                    'grant_type' => 'authorization_code',
                    'code' => $request->code,
                ]);

                // Retrieve the Stripe Account ID from the response
                $stripeAccountId = $response->stripe_user_id;

                // Step 3: Store it in the database
                $driver = Auth::guard('admin')->user();
                $driver->stripe_account_id = $stripeAccountId;
                $driver->save();

                return redirect()->route('dashboard')->with('success', 'Stripe account linked successfully.');
            } catch (\Exception $e) {
                return redirect()->route('dashboard')->with('error', 'Stripe account connection failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('dashboard')->with('error', 'Stripe authorization failed.');
    }
}
