<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Frontend\RegisterService;
use App\Services\Frontend\LoginService;
use App\Services\Frontend\ForgotPasswordService;
use App\Services\Frontend\RideRequestService;
use App\Services\Frontend\SomeoneRideRequestService;
use App\Services\Frontend\ProfileService;
use App\Services\Frontend\RideCancelService;
use App\Services\Frontend\HelpSupportService;


class BaseController extends Controller
{
    //Services
    protected $auth_service;
    protected $login_service;
    protected $forgot_password_service;
    protected $ride_request_service;
    protected $someone_ride_request_service;
    protected $profile_service;
    protected $ride_cancel_service;
    protected $help_support_service;

    public function __construct(
        RegisterService $auth_service,
        LoginService $login_service,
        ForgotPasswordService $forgot_password_service,
        RideRequestService $ride_request_service,
        SomeoneRideRequestService $someone_ride_request_service,
        ProfileService $profile_service,
        RideCancelService $ride_cancel_service,
        HelpSupportService $help_support_service
    )
    {
        // services
        $this->auth_service = $auth_service;
        $this->login_service = $login_service;
        $this->forgot_password_service = $forgot_password_service;
        $this->ride_request_service = $ride_request_service;
        $this->someone_ride_request_service = $someone_ride_request_service;
        $this->profile_service = $profile_service;
        $this->ride_cancel_service = $ride_cancel_service;
        $this->help_support_service = $help_support_service;
    }
}
