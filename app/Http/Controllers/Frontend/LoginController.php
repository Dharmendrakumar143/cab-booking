<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

use App\Models\User;
use App\Models\Admin;

class LoginController extends BaseController
{
    /**
     * Display the user login form.
     *
     * @return \Illuminate\View\View
     */
    public function loginForm()
    {
        return view('Frontend.AuthCustomer.login');
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->login_service->login($request);

        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            $ride_request = $request->session()->get('user_ride_request_data');
            
            if(!empty($ride_request)){
                return redirect()->route('book-ride');                
            }else{
                return redirect()->route('home');
            }

        }

        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    public function logoutUser()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    // Redirect the user to the Google authentication page
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

     // Handle the Google callback
    public function handleGoogleCallback(Request $request)
    {
         
       $loginType = session('login_type');

        try {
   
            // Get the user's information from Google
            $googleUser = Socialite::driver('google')->user();
        
            if ($loginType === 'driver') {

                $driverRole = Role::where('name', 'independent-contractors')->first();
            
                if (!$driverRole) {
                    return [
                        'success' => false,
                        'message' => 'Driver role does not exist.',
                    ];
                }
                
                // Admin login logic
                $admin = Admin::where('google_id', $googleUser->getId())->first();

                if (!$admin) {
                    $checkMail = Admin::where('email', $googleUser->getEmail())->first();
                    if ($checkMail) {
                        return redirect()->route('driver-login')->with('error', 'The email is already associated with another driver account.');
                    }

                    $admin = Admin::create([
                        'name' => $googleUser->getName(),
                        'first_name' => $googleUser->getName(),
                        'last_name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password' => bcrypt(Str::random(16)),
                    ]);

                    // Assign the 'independent-contractors' role to the driver
                    if (!$admin->hasRole('independent-contractors')) {
                        $admin->assignRole($driverRole);
                    }
                }

                $admin->update([
                    'login_check'=>true
                ]);
                
                // Log the admin in
                Auth::guard('admin')->login($admin);
                $request->session()->forget('login_type');
                return redirect()->route('dashboard');

            }else{

                $userRole = Role::where('name', 'customer')->first();
            
                if (!$userRole) {
                    return [
                        'success' => false,
                        'message' => 'User role does not exist.',
                    ];
                }
            
                // Check if the user already exists in the database
                $user = User::where('google_id', $googleUser->id)->first();

                // If user does not exist, create a new user
                if (!$user) {
                    $checkmail = User::where('email', $googleUser->getEmail())->first();
                    if($checkmail){
                        return redirect('/login')->with('error', 'The email already exists.');
                    }

                    $user = User::create([
                        'first_name' => $googleUser->getName(),
                        'last_name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password' => bcrypt(Str::random(16)),
                    ]);

                    if (!$user->hasRole('customer')) {
                        $user->assignRole($userRole);
                    }
                }

                // Log the user in
                Auth::login($user);

                return redirect()->route('book-ride');  
            }

            
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Google Login Error: ' . $e->getMessage());

            $loginRoute = ($loginType === 'driver') ? 'driver-login' : 'login';
            // Handle the error (e.g., user denied access)
            return redirect()->route($loginRoute)->with('error', 'Unable to login with Google.');
        }
    }

}
