<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Frontend\MainPageController;
use App\Http\Controllers\Frontend\RegisterController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\ForgotPasswordController;
use App\Http\Controllers\Frontend\RequestRideController;
use App\Http\Controllers\Frontend\SomeoneRideRequestController;
use App\Http\Controllers\Frontend\MyTripController;
use App\Http\Controllers\Frontend\UserProfileController;
use App\Http\Controllers\Frontend\CancelRideController;
use App\Http\Controllers\Frontend\HelpSupportController;
use App\Http\Controllers\Frontend\PaymentHistoriesController;
use App\Http\Controllers\Frontend\PagesController;
use App\Http\Controllers\Frontend\CustomerNotificationController;
use App\Http\Controllers\Frontend\CustomerCardController;
use App\Http\Controllers\Frontend\TipController;

use App\Exports\UsersExport;
use App\Exports\EmployeeExport;
use App\Exports\PaymentExport;
use App\Exports\DriverExport;
use Maatwebsite\Excel\Facades\Excel;

//Admin 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminForgotPasswordController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminRidesController;
use App\Http\Controllers\Admin\PassengerController;
use App\Http\Controllers\Admin\SurgePriceController;
use App\Http\Controllers\Admin\AdminHelpSupportController;
use App\Http\Controllers\Admin\PaymentHistoryController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\FaqsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\DriverAuthController;
use App\Http\Controllers\Admin\DriverForgotPasswordController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\DriverStripeConnectController;
use App\Http\Controllers\Admin\DriverDuesController;
use App\Http\Controllers\Admin\EmployeeController;

use App\Events\AdminCancelRideNotification;

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::middleware('guest')->group(function () {
    Route::prefix('driver')->group(function (){
        Route::get('/register', [DriverAuthController::class, 'registerForm'])->name('driver-register');
        Route::post('/register', [DriverAuthController::class, 'registerDriver'])->name('driver-register');
        Route::get('/sent-otp', [DriverAuthController::class, 'driverSentOTPForm'])->name('driver-sent-otp');
        Route::post('/send-otp', [DriverAuthController::class, 'driverSentOTPOnMail'])->name('driver-send-otp');
        Route::get('/otp-verification', [DriverAuthController::class, 'driverOTPVerificationForm'])->name('driver-otp-verification');
        Route::post('/otp-verification', [DriverAuthController::class, 'driverOTPVerification'])->name('driver-otp-verification');
        Route::get('/otp-verified', [DriverAuthController::class, 'driverOTPVerifiedMessage'])->name('driver-otp-verified');
        Route::get('/resend-otp', [DriverAuthController::class, 'driverResendOTPOnMail'])->name('driver-resend-otp');

        Route::get('/login', [DriverAuthController::class, 'driverLoginForm'])->name('driver-login');
        Route::post('/login', [DriverAuthController::class, 'driverLoginUser'])->name('driver-login');
        Route::get('/driver-ride-disable', [DriverAuthController::class, 'driverRideDisable'])->name('driver-ride-disable');
        Route::get('/auto-reject-ride-enable', [DriverAuthController::class, 'autoRejectRideEnable'])->name('auto-reject-ride-enable');

        Route::get('/forgot-password', [DriverForgotPasswordController::class, 'driverForgotPasswordForm'])->name('driver-forgot-password');
        Route::post('/forgot-password', [DriverForgotPasswordController::class, 'driverForgotPassword'])->name('driver-forgot-password');
        Route::get('/reset-password/{token?}', [DriverForgotPasswordController::class, 'driverResetPasswordForm'])->name('driver-reset-password');
        Route::post('/reset-password', [DriverForgotPasswordController::class, 'driverResetPassword'])->name('driver-reset-password');

        Route::get('login/google', [DriverAuthController::class, 'driverRedirectToGoogle'])->name('driver-login-google');

    });
// });


    Route::prefix('employee')->group(function (){
        Route::get('/login', [DriverAuthController::class, 'driverLoginForm'])->name('employee-login');
        Route::post('/login', [DriverAuthController::class, 'EmployeeLoginUser'])->name('employee-login');
    });

// Register and OTP verification routes for guests only
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'registerForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'registerUser'])->name('register');
    Route::get('/sent-otp', [RegisterController::class, 'sentOTPForm'])->name('sent-otp');
    Route::post('/send-otp', [RegisterController::class, 'sendOTPOnMail'])->name('send-otp');
    Route::get('/otp-verification', [RegisterController::class, 'otpVerificationForm'])->name('otp-verification');
    Route::post('/otp-verification', [RegisterController::class, 'otpVerification'])->name('otp-verification');
    Route::get('/otp-verified', [RegisterController::class, 'otpVerifiedMessage'])->name('otp-verified');
    Route::get('/resend-otp', [RegisterController::class, 'resendOTPOnMail'])->name('resend-otp');
});

// Login routes for guests only
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'loginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'loginUser'])->name('login');

    Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login-google');
    Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);
});

// Forgot password routes (these can be accessed by guests)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'forgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot-password');
    Route::get('/user/reset-password/{token?}', [ForgotPasswordController::class, 'resetPasswordForm'])->name('reset-password');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password');
});


// Publicly accessible routes
Route::get('/', [MainPageController::class, 'index'])->name('home');
Route::get('/request-ride', [RequestRideController::class, 'requestRideForm'])->name('request-ride');
Route::post('/request-ride', [RequestRideController::class, 'rideRequest'])->name('request-ride');
Route::get('/book-ride', [RequestRideController::class, 'bookingRideForm'])->name('book-ride');

Route::get('/request-ride-someone', [SomeoneRideRequestController::class, 'requestRideSomeoneForm'])->name('request-ride-someone');
Route::post('/request-ride-someone', [SomeoneRideRequestController::class, 'rideRequestSomeone'])->name('request-ride-someone');

Route::prefix('page')->group(function (){
    Route::get('/{slug}', [PagesController::class, 'pageContend'])->name('page-slug');
});

Route::prefix('tip')->group(function (){
    Route::get('/driver-tip/{bookingId}', [TipController::class, 'tipForm'])->name('driver-tip');
    Route::post('/create-tip/{bookingId}', [TipController::class, 'createTipPaymentLink'])->name('create-tip');
    Route::get('/tip-cancel/{bookingId}', [TipController::class, 'cancel'])->name('tip.cancel');
    Route::get('/tip-success/{bookingId}', [TipController::class, 'success'])->name('tip.success');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/update-card-details', [CustomerCardController::class, 'updateCardForm'])->name('update-card-details');
    Route::post('/create-payment-intent', [CustomerCardController::class, 'createPaymentIntent'])->name('create-payment-intent');
    Route::post('/store-card', [CustomerCardController::class, 'storeCard'])->name('store-card');
    Route::post('/process-payment', [CustomerCardController::class, 'processPayment'])->name('process-payment');

    Route::get('/logout', [LoginController::class, 'logoutUser'])->name('logout');
    
    Route::post('/book-ride', [RequestRideController::class, 'bookRide'])->name('book-ride');
    Route::get('/my-trips', [MyTripController::class, 'index'])->name('my-trip');
    Route::get('/view-trip-details/{ride_id}', [MyTripController::class, 'viewTripDetails'])->name('view-trip-details');
    Route::post('/submit-rating', [MyTripController::class, 'submitRating'])->name('submit-rating');

    Route::get('/my-profile', [UserProfileController::class, 'index'])->name('my-profile');
    Route::post('user-profile', [UserProfileController::class, 'updateProfile'])->name('user-profile');
    Route::post('change-password', [UserProfileController::class, 'changeUserPassword'])->name('user-change-password');
    Route::post('/upload-user-profile-image/{id}', [UserProfileController::class, 'uploadUserProfileImage'])->name('user-upload-profile-image');

    Route::get('/cancel-ride', [CancelRideController::class, 'index'])->name('cancel-ride');
    Route::post('/cancel-ride', [CancelRideController::class, 'cancelRide'])->name('cancel-ride');

    Route::get('/help-support', [HelpSupportController::class, 'index'])->name('help-support');
    Route::post('/send-ticket', [HelpSupportController::class, 'sendTicket'])->name('send-ticket');
    Route::get('/view-tickets', [HelpSupportController::class, 'viewTickets'])->name('view-tickets');
    Route::get('/view-ticket-details/{ticket_id}', [HelpSupportController::class, 'viewTicketDetails'])->name('view-ticket-details');


    Route::get('/payment-history', [PaymentHistoriesController::class, 'index'])->name('payment-history');
    Route::post('/payment-filter', [PaymentHistoriesController::class, 'paymentFilter'])->name('payment-filter');
    Route::get('/payment-history-details/{ride_id}', [PaymentHistoriesController::class, 'paymentHistoryDetails'])->name('payment-history-details');

    Route::prefix('notifications')->group(function (){
        Route::post('/customer-mark-as-read', [CustomerNotificationController::class, 'markAsRead'])->name('customer-mark-as-read');
        Route::get('/', [CustomerNotificationController::class, 'index'])->name('customer-notification-list');
        Route::get('/details/{notification_id}', [CustomerNotificationController::class, 'notificationDetails'])->name('customer-notification-detail');  
        Route::post('/mark-read', [CustomerNotificationController::class, 'markRead'])->name('customer-mark-read');
    });
    
});


Route::prefix('admin')->group(function (){

    Route::middleware('guest_admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'loginForm'])->name('admin-login');
        Route::post('/login', [AdminLoginController::class, 'loginAdmin'])->name('admin-login');
        Route::get('/forgot-password', [AdminForgotPasswordController::class, 'forgotPasswordForm'])->name('admin-forgot-password');
        Route::post('/forgot-password', [AdminForgotPasswordController::class, 'forgotPassword'])->name('admin-forgot-password');
        Route::get('/reset-password/{token?}', [AdminForgotPasswordController::class, 'resetPasswordForm'])->name('admin-reset-password');
        Route::post('/reset-password', [AdminForgotPasswordController::class, 'resetPassword'])->name('admin-reset-password');
    });

    Route::middleware(['isAdmin'])->group(function () {
        Route::get('/admin-logout', [AdminLoginController::class, 'logoutAdmin'])->name('admin-logout');
        Route::get('profile', [ProfileController::class, 'Profile'])->name('admin-profile');
        Route::post('profile', [ProfileController::class, 'updateProfile'])->name('admin-profile');
        // Assuming you are using authenticated users
        Route::post('/upload-profile-image/{id}', [ProfileController::class, 'uploadProfileImage'])->name('admin-upload-profile-image');
        Route::post('/upload-document', [ProfileController::class, 'uploadDocuments'])->name('upload-document');

        Route::post('change-password', [ProfileController::class, 'changePassword'])->name('admin-change-password');
        
        Route::middleware('verify_driver')->group(function () {
            
            Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
            
            Route::prefix('rides')->group(function (){
                Route::get('/', [AdminRidesController::class, 'rides'])->name('admin-rides');
                Route::get('ride-details/{ride_id?}', [AdminRidesController::class, 'rideDetails'])->name('admin-ride-details');
                Route::post('accept-ride', [AdminRidesController::class, 'acceptRideRequest'])->name('accept-ride');
                Route::post('reject-ride', [AdminRidesController::class, 'acceptRideRequest'])->name('reject-ride');
                Route::post('pause-ride', [AdminRidesController::class, 'acceptRideRequest'])->name('pause-ride');
                Route::post('reject-ride-by-admin', [AdminRidesController::class, 'rejectRideRequestByAdmin'])->name('reject-ride-by-admin');
                Route::post('show-ride-request-driver', [AdminRidesController::class, 'showRideRequestDriver'])->name('show-ride-request-drive');
                Route::get('ride-delete/{ride_id?}', [AdminRidesController::class, 'rideDelete'])->name('admin-ride-delete');
                Route::post('start-ride', [AdminRidesController::class, 'startRide'])->name('start-ride');
                Route::post('mark-paid-payment', [AdminRidesController::class, 'markPaidPayment'])->name('mark-paid-payment');
                Route::post('add-miles', [AdminRidesController::class, 'addMiles'])->name('add-miles');
                Route::post('complete-ride-card-payment', [AdminRidesController::class, 'completeRideCardPayment'])->name('admin-process-payment');
                Route::post('verify-ride-otp', [AdminRidesController::class, 'verifyRideOTP'])->name('verify-ride-otp');
                Route::get('revert-ride/{booking_id}', [AdminRidesController::class, 'revertRide'])->name('revert-ride');
                Route::get('/pending-ride-data', [AdminRidesController::class, 'pendingRideData'])->name('pending-ride-data');
                Route::get('/completed-ride-data', [AdminRidesController::class, 'completedRideData'])->name('completed-ride-data');
                Route::get('/accepted-ride-data', [AdminRidesController::class, 'acceptedRideData'])->name('accepted-ride-data');
                Route::get('/paused-ride-data', [AdminRidesController::class, 'pausedRideData'])->name('paused-ride-data');
            });

            Route::prefix('payments')->group(function (){
                Route::get('/', [PaymentHistoryController::class, 'index'])->name('payment-listing');
                Route::post('/history-filter', [PaymentHistoryController::class, 'paymentHistoryFilter'])->name('payment-history-filter');
                Route::get('/export-payment-history-pdf', [PaymentHistoryController::class, 'exportPaymentHistoryPdf'])->name('export-payment-history-pdf');
                Route::get('/details/{ride_id}', [PaymentHistoryController::class, 'paymentDetails'])->name('payment-details');
                Route::get('/payment-delete/{ride_id}', [PaymentHistoryController::class, 'paymentDelete'])->name('payment-delete');
                
                Route::get('/export-payment-history', function () {
                    return Excel::download(new PaymentExport, 'payment-history.csv');
                })->name('payment-history.payment');
        
                Route::get('/export-payment-history-excel', function () {
                    return Excel::download(new PaymentExport, 'payment-history.xlsx');
                })->name('payment-history.export.excel');
            });

            Route::prefix('notifications')->group(function (){
                Route::post('/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
                Route::get('/', [NotificationController::class, 'index'])->name('notification-list');
                Route::get('/details/{notification_id}', [NotificationController::class, 'notificationDetails'])->name('notification-detail');  
                Route::post('/mark-read', [NotificationController::class, 'markRead'])->name('mark-read');
            });

            Route::get('/driver/connect', [DriverStripeConnectController::class, 'createConnectLink'])->name('create-connect-link');
            Route::get('/driver/stripe/callback', [DriverStripeConnectController::class, 'handleStripeCallback'])->name('driver.stripe.callback');

            Route::prefix('dues')->group(function (){
                Route::get('/', [DriverDuesController::class, 'driverDues'])->name('driver-dues');
                Route::get('/filter', [DriverDuesController::class, 'driverDuesFilters'])->name('driver-dues-filter');
            });

        });

        Route::middleware('prevent_role')->group(function () {
            Route::prefix('passengers')->group(function (){
                Route::get('/', [PassengerController::class, 'passengers'])->name('admin-passenger');
                Route::get('details/{ride_id?}', [PassengerController::class, 'passengerDetails'])->name('admin-passenger-detail');
                Route::get('user-delete/{user_id?}', [PassengerController::class, 'userDeleted'])->name('user-delete');
                Route::get('/export-users-pdf', [PassengerController::class, 'exportPDF'])->name('users.export.pdf');
                Route::get('/export-users', function () {
                    return Excel::download(new UsersExport, 'users.csv');
                })->name('users.export');

                Route::get('/export-users-excel', function () {
                    return Excel::download(new UsersExport, 'users.xlsx');
                })->name('users.export.excel');
            });

            Route::prefix('surge-price')->group(function (){
                Route::get('/',[SurgePriceController::class,'index'])->name('surge-price');
                Route::get('/add',[SurgePriceController::class,'addForm'])->name('add-price');
                Route::post('/add',[SurgePriceController::class,'addPrice'])->name('add-price');
                Route::get('price-delete/{price_id?}', [SurgePriceController::class, 'priceDeleted'])->name('price-delete');
                Route::get('edit/{price_id?}', [SurgePriceController::class, 'editPrice'])->name('edit-price');
                Route::post('update-price', [SurgePriceController::class, 'updatePrice'])->name('update-price');
            });

            Route::prefix('tickets')->group(function (){
                Route::get('/', [AdminHelpSupportController::class, 'index'])->name('tickets');
                Route::post('filter', [AdminHelpSupportController::class, 'ticketFilter'])->name('ticket-filter');
                Route::get('details/{ticket_id}', [AdminHelpSupportController::class, 'ticketDetails'])->name('ticket-details');
                Route::get('edit/{ticket_id}', [AdminHelpSupportController::class, 'editTicket'])->name('edit-ticket');
                Route::post('resolved', [AdminHelpSupportController::class, 'resolvedTicket'])->name('resolved-ticket');
                Route::get('delete/{ticket_id}', [AdminHelpSupportController::class, 'deleteTicket'])->name('delete-ticket');
            });

            Route::prefix('pages')->group(function (){
                Route::get('/', [PageContentController::class, 'index'])->name('page-content');
                // Route::get('/add', [PageContentController::class, 'addPageContent'])->name('add-page-content');
                Route::get('/edit/{slug}', [PageContentController::class, 'editPageContent'])->name('edit-page-content');
                Route::post('/update', [PageContentController::class, 'update'])->name('pages.update');

            });

            Route::prefix('faqs')->group(function (){
                Route::get('/', [FaqsController::class, 'index'])->name('faq-content');
                Route::get('/add', [FaqsController::class, 'show'])->name('faq-show');
                Route::post('/add', [FaqsController::class, 'addFaqs'])->name('add-faq');
                Route::get('/edit/{faq_id}', [FaqsController::class, 'edit'])->name('edit-faq');
                Route::post('/update', [FaqsController::class, 'updateFaqs'])->name('update-faq');
                Route::get('/delete', [FaqsController::class, 'deleteFaq'])->name('faq-delete'); 
            });

            Route::prefix('drivers')->group(function (){
                Route::get('/', [DriverController::class, 'drivers'])->name('admin-driver');
                Route::get('/driver-data', [DriverController::class, 'driverData'])->name('driver-data');
                Route::get('details/{driver_id}', [DriverController::class, 'driverDetails'])->name('driver-detail');
                Route::get('user-delete/{driver_id}', [DriverController::class, 'driverDeleted'])->name('driver-delete');
                Route::post('verify-document', [DriverController::class, 'verifyDocument'])->name('verify-document');
              
                Route::get('/export-driver-pdf', [DriverController::class, 'exportDriverPDF'])->name('driver.export.pdf');

                Route::get('/export-driver', function () {
                    return Excel::download(new DriverExport, 'drivers.csv');
                })->name('driver.export');

                Route::get('/export-driver-excel', function () {
                    return Excel::download(new DriverExport, 'drivers.xlsx');
                })->name('driver.export.excel');


            });

            Route::prefix('roles')->group(function (){ 
                Route::get('/', [RolePermissionController::class, 'index'])->name('roles');
                Route::get('/edit/{role_id}', [RolePermissionController::class, 'edit'])->name('role-edit');
                Route::post('/update', [RolePermissionController::class, 'update'])->name('role-update');
                // Route::resource('roles', RolePermissionController::class);
            });

            Route::prefix('employee')->group(function (){
                Route::get('/', [EmployeeController::class, 'index'])->name('employee-list');
                Route::get('/employee-data', [EmployeeController::class, 'employeeData'])->name('employee-data');
                Route::get('/add', [EmployeeController::class, 'addEmployeeForm'])->name('create-employee');
                Route::post('/add', [EmployeeController::class, 'addNewEmployee'])->name('store-employee');
                Route::get('employee-delete/{employee_id}', [EmployeeController::class, 'employeeDeleted'])->name('employee-delete');
                Route::get('employee-edit/{employee_id}', [EmployeeController::class, 'employeeEdit'])->name('employee-edit');
                Route::post('/update', [EmployeeController::class, 'updateEmployee'])->name('update-employee');
                Route::get('/export-employee-pdf', [EmployeeController::class, 'exportEmployeePDF'])->name('employee.export.pdf');

                Route::get('/export-employee', function () {
                    return Excel::download(new EmployeeExport, 'users.csv');
                })->name('employee.export');

                Route::get('/export-employees-excel', function () {
                    return Excel::download(new EmployeeExport, 'users.xlsx');
                })->name('employee.export.excel');

            });

        });
    });

});
