<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Admin\LoginService;
use App\Services\Admin\ForgotPasswordService;
use App\Services\Admin\ProfileService;
use App\Services\Admin\RidesService;
use App\Services\Admin\SurgePriceService;
use App\Services\Admin\SupportTicketService;
use App\Services\Admin\DriverAuthService;
use App\Services\Admin\DriverForgotPasswordService;
use App\Services\Admin\DriverService;
use App\Services\Admin\EmployeeService;

class AdminBaseController extends Controller
{
    protected $login_service;
    protected $forgot_password_service;
    protected $profile_service;
    protected $rides_service;
    protected $surge_price_service;
    protected $support_ticket_service;
    protected $driver_auth_service;
    protected $driver_forgot_password_service;
    protected $driver_service;
    protected $employee_service;

    public function __construct(
        LoginService $login_service,
        ForgotPasswordService $forgot_password_service,
        ProfileService $profile_service,
        RidesService $rides_service,
        SurgePriceService $surge_price_service,
        SupportTicketService $support_ticket_service,
        DriverAuthService $driver_auth_service,
        DriverForgotPasswordService $driver_forgot_password_service,
        DriverService $driver_service,
        EmployeeService $employee_service
    ){
        $this->login_service = $login_service;
        $this->forgot_password_service = $forgot_password_service;
        $this->profile_service = $profile_service;
        $this->rides_service = $rides_service;
        $this->surge_price_service = $surge_price_service;
        $this->support_ticket_service = $support_ticket_service;
        $this->driver_auth_service = $driver_auth_service;
        $this->driver_forgot_password_service = $driver_forgot_password_service;
        $this->driver_service = $driver_service;
        $this->employee_service = $employee_service;
    }
}
