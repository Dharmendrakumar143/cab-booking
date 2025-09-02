@extends('admin.layouts.master')
@section('title', 'Ride Details')
@section('content')
<div class="apporve-know-content">
    <div class="apporve-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="r-heading mt-4 mb-3">
                    <h5 class="a-h-color fw-600">Rides</h5>
                </div>
            </div>
            <div class="bookride-for-contnet">
                @if(isset($ride_details->booking->admin))
                    <div class="row">
                        <div class="col-md-6">
                            <div class="book-bg h-auto">
                                <div class="drver-details text-white">
                                    <h4 class="border-bottom ">Driver Details</h4>
                                    <div class="driver-text-avtar d-flex flex-wrap pt-3 justify-content-between align-items-center">
                                    
                                    <div class="bt-modal">
                                    
                                        <div class="modal-flex flex-wrap d-flex">
                                            <div class="car-modal">
                                                <p>Name </p>
                                            
                                            </div>
                                            <span class="plate-coln">:</span>
                                            <div class="l-plate">
                                                <p class="fw-500 fs-14">{{$ride_details->booking->admin->name ?? null}}</p>
                                            
                                            </div>
                                        </div>
                                        <div class="modal-flex flex-wrap d-flex">
                                            <div class="car-modal">
                                                <p>Car Year, Make and Model </p>
                                            
                                            </div>
                                            <span class="plate-coln">:</span>
                                            <div class="l-plate">
                                                <p class="fw-500 fs-14">{{$ride_details->booking->admin->car_model ?? null}}</p>
                                            
                                            </div>
                                        </div>
                                    <div class="modal-flex flex-wrap d-flex">
                                        <div class="car-modal">
                                            <p>License Plate</p>
                                        </div>
                                            <span class="plate-coln">:</span>
                                        <div class="l-plate">
                                            <p class="fw-500 fs-14"> {{$ride_details->booking->admin->car_number_plate ?? null}}</p>
                                        </div>
                                    </div>
                                    <div class="dcm-btn d-flex gap-1 mt-3">
                                        
                                        @if (!empty($ride_details->booking?->admin?->phone_number))
                                            <a href="tel:{{$ride_details->booking->admin->phone_number}}">
                                                <img src="{{asset('assets/images/dcall.png')}}" alt="Call Admin">
                                            </a>
                                        @else
                                            <img src="{{asset('assets/images/dcall.png')}}" alt="Call Admin">
                                        @endif
                                        <!-- <img src="{{asset('assets/images/dmsg.png')}}"> -->
                                    </div>
                                </div>
                                <div class="avtar-rate text-center">
                                    @if (!empty($ride_details->booking?->admin?->profile_pic))
                                        <img src="{{asset('storage/profile')}}/{{$ride_details->booking->admin->profile_pic}}">
                                    @else
                                        <img src="{{asset('assets/images/place.png')}}">
                                    @endif
                                    <p class="mt-2">{{isset($ride_details->booking->admin) ? $driver_completed_ride_count : 0}} Rides</p>
                                    @php
                                        $filledStars = floor($average_rating); // Number of fully filled stars
                                        $halfStar = $average_rating - $filledStars >= 0.5; // Determine if a half star is needed
                                        $emptyStars = 5 - $filledStars - ($halfStar ? 1 : 0); // Remaining empty stars
                                    @endphp

                                    <!-- Fully filled stars -->
                                    @for ($i = 0; $i < $filledStars; $i++)
                                        <i class="fa fa-star checked"></i>
                                    @endfor

                                    <!-- Half star -->
                                    @if ($halfStar)
                                        <i class="fa fa-star-half-o checked"></i>
                                    @endif

                                    <!-- Empty stars -->
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <i class="fa fa-star-o"></i>
                                    @endfor
                                    <span class="fs-12 ps-2">{{$average_rating}}</span>
                                </div>
                            </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $user = Auth::guard('admin')->user();
                        @endphp

                        @if($user->hasRole('super-admin')) 
                            @if($ride_details->booking->booking_status == 'Confirmed')
                                <div class="col-md-6">
                                    <a href="{{route('revert-ride',['booking_id'=>$ride_details->booking->id])}}" id="revert-back" class="btn bg-black px-md-5 text-white  rounded-10 py-2">Revert Ride</a>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
                
                <div class="ride-detail-cancle rounded-10 my-3">
                    <div class="book-your-ride p-4 ">
                        <div class="r-d-flex d-flex justify-content-between flex-wrap">
                        <div class="bs-heading mb-lg-0 mb-4">
                            <h5 class="fw-600 mb-0">Ride Details</h5>
                            <p class="fw-600 fs-14 ">{{ date('d M Y', strtotime($ride_details->pick_up_date)) }} {{$ride_details->pick_up_time}}</p>
                            <p class="fw-400 fs-14 ">Passengers: <span class="ps-2">{{$ride_details->total_passenger}}</span> </p>
                            <p class="fw-400 fs-14 ">Payment Status: <span class="ps-2">{{$ride_details->paymentStatus->status}}</span> </p>
                            
                        </div>
                        <div class="price-d-text d-flex justify-content-between">
                            <div class="p-d-modal">
                                <p>Price</p>
                                <p>Duration</p>
                            </div>
                            <div class="p-d-text">
                                <p class="fw-600"><span class="pe-3 ">:</span> ${{$ride_details->booking->ride_booking_amount}}</p>
                                <p class="fw-500"><span class="pe-3">:</span> {{$ride_details->booking->duration}}</p>
                            </div>
                        </div>
                        <div class="location-img-text">
                            <div class="km d-flex align-items-end flex-column">
                                <img src="{{asset('assets/images/r-d-loc.png')}}">
                                <!-- <span class="fs-12">{{$ride_details->booking->distance}} Km </span> -->
                                <span class="fs-12"> {{$ride_details->booking->miles_distance}} Miles</span>
                               
                            </div>
                            
                        </div>
                    </div>
                        <div class="row ">
                            <div class="col-md-4 col-6 pe-0">
                                <div class="pick-drop-line border-orange d-flex justify-content-between mt-4">
                                    <img src="{{asset('assets/images/location.png')}}" class="loc-icon">
                                    <img src="{{asset('assets/images/drop.png')}}" class="drop-icon">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 col-md-4">
                                    <div class="syndy-text">
                                        <p class="fw-400">{{$ride_details->pick_up_address}}</p>
                                    </div>
                                </div>
                                  <div class="col-6 col-md-4">
                                    <div class="syndy-text">
                                        <div class="edit-text">
                                            <p class=" fw-400">{{$ride_details->drop_off_address}}</p>
                                        </div>
                                      
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="pending-btn text-center">
                                        <button type="button" class="btn bg-black px-5 text-white  rounded-10 py-2">{{$ride_details->booking->booking_status}}</button>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="fare-details">
            <div class="row">
                <div class="col-md-5">
                    <div class="bg-black rounded-20 pt-4">
                        <div class="fr-detls gray-bg rounded-20 p-4">
                            <div class="fare-header d-flex justify-content-between flex-wrap mb-3">
                                <h4>Fare Details</h4>
                                <div class="t-xl">
                                    <a href="#" class="xl-btn orange-bg px-lg-5 px-2 py-2 text-black rounded-30">Troy XL</a>
                                </div>
                            </div>
                            <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                <p>Base Fare</p> <p class="fw-500"><span class="pe-3">:</span> $ {{$ride_details->booking->ride_booking_amount}}</p>
                            </div>
                            <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                <p>Time and distance charges</p> <p class="fw-500"><span class="pe-3">:</span> ${{config('global-constant.ride_per_km_charge.rate_per_km')}}/Miles + {{$ride_details->booking->miles_distance}} Miles</p>
                            </div>
                            <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                <p>Surge pricing (if applicable)</p> <p class="fw-500"><span class="pe-3">:</span> ${{isset($ride_details->booking->surge_amount)?$ride_details->booking->surge_amount:'00.00'}}</p>
                            </div>
                            <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                <p>Tolls or fees</p> <p class="fw-500"><span class="pe-3">:</span> $00.00</p>
                            </div>
                            <div class="total-fee base-fare-box  d-flex justify-content-between rounded-8 p-2 mb-3">
                                <p class="fs-20">Total fare</p> <p class="fw-500 orange fs-24"> ${{$ride_details->booking->subtotal}}</p>
                            </div>
                            <div class="pay-fee base-fare-box bg-light-gray  d-flex flex-lg-nowrap flex-wrap justify-content-between rounded-8 p-4 mb-3">
                                <div class="pay-ment">
                                    <p class="">Payment method</p>
                                    <p class="fs-14 fw-600">{{$ride_details->paymentStatus->payment_method}}</p>
                                </div>
                                <!-- <div class="promo-code text-lg-end">
                                    <p class="">Promo codes/Discounts</p>
                                    <p class="fw-600  fs-14 "> GOODTOGO</p>
                                </div> -->
                                    
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="r-d-map h-100">
                        <!-- <img src="{{asset('assets/images/ride-details-map.png')}}" class="img-fluid h-100"> -->
                        <div id="map" style="height: 500px; width: 100%;"></div>
                    </div>
                </div>
            </div>

            @if(isset($ride_details->booking->booking_status) && $ride_details->booking->booking_status=='In Progress' || $ride_details->booking->booking_status=='Paused')
                <div class="apporve-reject-btn d-flex mt-3 gap-2 flex-wrap">

                    @if(isset($ride_details->booking->payment_method) && $ride_details->booking->payment_method=='card')
                        <div class="card-btn text-center">
                            <button type="button" class="btn bg-black text-white  rounded-10 py-2" id="payNowButtonBtn" data-ride-id="{{$ride_details->booking->id}}">Complete Ride</button>
                        </div>  
                    @else  
                        <form action="{{route('accept-ride')}}" method="post" onsubmit="disableButton()"> 
                            @csrf
                            <input type="hidden" name="booking_id" value="{{$ride_details->booking->id}}">
                            <input type="hidden" name="booking_status" value="Completed">
                            <div class="apporve-btn">
                                <button type="submit" class="btn bg-black px-md-5 text-white  rounded-10 py-2" id="submitButton">Complete Ride</button>
                            </div>
                        </form>                     
                    @endif

                    @if(isset($ride_details->booking->booking_status) && $ride_details->booking->booking_status=='In Progress')
                        <form action="{{route('pause-ride')}}" method="post" onsubmit="disablePausedButton()"> 
                            @csrf
                            <input type="hidden" name="booking_id" value="{{$ride_details->booking->id}}">
                            <input type="hidden" name="booking_status" value="Paused">
                            <div class="apporve-btn">
                                <button type="submit" class="btn bg-black px-md-5 text-white rounded-10 py-2" id="submitPausedButton">Paused Ride</button>
                            </div>
                        </form>
                    @endif
                    <button type="button" class="btn  px-md-5  btn-outline-dark  rounded-10 py-2" data-bs-target="#addMilesModalToggle" data-bs-toggle="modal">Add Miles</button>
                </div
            @endif

            @if(isset($ride_details->booking->booking_status) && $ride_details->booking->booking_status=='Confirmed')
                <div class="apporve-reject-btn d-flex mt-3 gap-2 flex-wrap">
                    @php
                        $roles = Auth::guard('admin')->user()->roles->first()->name;
                    @endphp
                    @if($roles === 'admin' || $roles === 'super-admin')
                        <form action="{{route('start-ride')}}" method="post"> 
                            @csrf
                            <input type="hidden" name="booking_id" value="{{$ride_details->booking->id}}">
                            <input type="hidden" name="booking_status" value="In Progress">
                            <div class="apporve-btn">
                                <button type="submit" class="btn bg-black px-md-5 text-white  rounded-10 py-2">Ride Start</button>
                            </div>
                        </form>
                    @else
                        @if(isset($ride_details->booking->is_otp_verified) && $ride_details->booking->is_otp_verified)
                            <form action="{{route('start-ride')}}" method="post"> 
                                @csrf
                                <input type="hidden" name="booking_id" value="{{$ride_details->booking->id}}">
                                <input type="hidden" name="booking_status" value="In Progress">
                                <div class="apporve-btn">
                                    <button type="submit" class="btn bg-black px-md-5 text-white  rounded-10 py-2">Ride Start</button>
                                </div>
                            </form>
                        @else
                            <div class="apporve-reject-btn d-flex  gap-2 flex-wrap">
                                <button type="button" class="btn bg-black px-5 text-white  rounded-10 py-2" data-bs-target="#otpVerifyModalToggle" data-bs-toggle="modal">OTP verify</button>
                            </div>
                        @endif
                    @endif
                    <form action="{{route('reject-ride')}}" method="post" id="rejectForm">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{$ride_details->booking->id}}">
                        <input type="hidden" name="booking_status" value="Cancelled">
                        <div class="rejection-btn">
                            <button type="button" class="btn  px-md-5  btn-outline-dark  rounded-10 py-2" id="rejectBtn">Cancel</button>
                        </div>
                    </form>
                </div>
            @endif


            @if(isset($ride_details->booking->booking_status) && $ride_details->booking->booking_status=='Completed')
                @if($ride_details->paymentStatus->status=='unpaid')
                    <div class="apporve-reject-btn d-flex mt-3 gap-2 flex-wrap">
                        <button type="button" class="btn bg-black px-5 text-white  rounded-10 py-2" data-bs-target="#paymentModalToggle" data-bs-toggle="modal">Payment</button>
                    </div>
                @endif                
            @endif

            @if(isset($ride_details->booking->booking_status) && $ride_details->booking->booking_status=='Pending')
                <div class="apporve-reject-btn d-flex mt-3 gap-2 flex-wrap">
                    <form action="{{route('accept-ride')}}" method="post">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{$ride_details->booking->id}}">
                        <input type="hidden" name="booking_status" value="Confirmed">
                        <div class="apporve-btn">
                            <button type="submit" class="btn bg-black px-md-5 text-white  rounded-10 py-2">Approve</button>
                        </div>
                    </form>

                    @php
                        $roles =  Auth::guard('admin')->user()->roles->first()->name;
                    @endphp

                    @if($roles === 'admin' || $roles === 'super-admin')
                        @if($ride_details->booking->reject_by_super_admin==false)
                            <form action="{{route('reject-ride-by-admin')}}" method="post" id="rejectByAdminForm">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{$ride_details->booking->id}}">
                                <input type="hidden" name="booking_status" value="reject-ride">
                                <div class="rejection-btn">
                                    <button type="button" class="btn  px-md-5  btn-outline-dark  rounded-10 py-2" id="rejectByAdminBtn">Reject</button>
                                </div>
                            </form>
                        @endif
                        @if($ride_details->booking->reject_by_employee==false)
                            <form action="{{route('show-ride-request-drive')}}" method="post">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{$ride_details->booking->id}}">
                                <div class="rejection-btn">
                                    <button type="submit" class="btn  px-md-5  btn-outline-dark  rounded-10 py-2">Super Reject</button>
                                </div>
                            </form>
                        @endif
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>

<div class="cancle-modal success-modal">
    <div class="modal fade" id="paymentModalToggle" aria-labelledby="paymentModalToggle" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 ps-lg-0 pt-lg-0 pb-lg-0 pe-lg-3">
                </div>
                <div class="modal-body p-0 pe-3">
                    <div class="row align-items-center">
                        <!-- <div class="col-md-6 ps-lg-0"> -->
                            <div class="success-content mt-5 pt-4 text-center">
                            <form action="{{route('mark-paid-payment')}}" method="post" id="markPayment" class="p-3 shadow-sm rounded bg-light">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{$ride_details->booking->id}}">
                                <div class="form-group mb-3">
                                    <label for="payment_method" class="form-label font-weight-bold">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-control">
                                        <option value="card" {{isset($ride_details->booking->payment_method) && ($ride_details->booking->payment_method=='card') ? 'selected' : ''}}>Card</option>
                                        <option value="cash" {{isset($ride_details->booking->payment_method) && ($ride_details->booking->payment_method=='cash') ? 'selected' : ''}}>Cash</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="final_amount" class="form-label font-weight-bold">Payment Amount</label>
                                    <input 
                                        type="text" 
                                        name="final_amount" 
                                        id="final_amount" 
                                        class="form-control" 
                                        value="{{$ride_details->booking->subtotal}}" 
                                        placeholder="Enter total price" 
                                        readonly
                                    >
                                </div>

                                <div class="form-group mb-3">
                                    <label class="d-block font-weight-bold">Payment Status</label>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="paid" name="payment_status" value="paid" class="form-check-input">
                                        <label for="paid" class="form-check-label">Paid</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="unpaid" name="payment_status" value="unpaid" class="form-check-input" checked>
                                        <label for="unpaid" class="form-check-label">Unpaid</label>
                                    </div>
                                </div>

                                <div class="form-group text-end">
                                    <button type="submit" class="btn bg-black px-5 orange  rounded-10 py-2">Mark Paid</button>
                                </div>
                            </form>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="cancle-modal success-modal">
    <div class="modal fade" id="otpVerifyModalToggle" aria-labelledby="otpVerifyModalToggle" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 ps-lg-0 pt-lg-0 pb-lg-0 pe-lg-3">
                </div>
                <div class="modal-body p-4">
                    <div class="row align-items-center">
                        <!-- <div class="col-md-6 ps-lg-0"> -->
                            <div class="success-content  pt-4 text-center">
                            <div class="login-logo">
                                <img src="{{asset('assets/images/login-logo.png')}}" alt="logo">
                            </div>
                            <div class="login-content mt-4 mb-3">
                                <h1 class="color-23 otp_title">OTP Verification</h1>
                                <p class="color-96">Enter the ride verification code shared by the customer<br> when picking them up.</p>
                            </div>
                            <form action="{{route('verify-ride-otp')}}" method="post">
                                      @csrf
                                      <input type="hidden" name="ride_id" id="ride_id" value="{{$ride_details->id}}">                              
                                    <div class="login-input mt-4">                            
                                        <div class="d-flex justify-content-center gap-2">
                                            <input type="text" name="otp1" maxlength="1" class="form-control otp-input " id="otp1" oninput="moveFocus(1)" value="">
                                            <input type="text" name="otp2" maxlength="1" class="form-control otp-input " id="otp2" oninput="moveFocus(2)" value="">
                                            <input type="text" name="otp3" maxlength="1" class="form-control otp-input " id="otp3" oninput="moveFocus(3)" value="">
                                            <input type="text" name="otp4" maxlength="1" class="form-control otp-input " id="otp4" oninput="moveFocus(4)" value="">
                                        </div>                     
                                        <div class="submit-btn mt-4">
                                        <button class="btn btn-book w-50 py-2  fw-500 bg-black text-white px-5 rounded-10" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="add-miles-modal success-modal">
    <div class="modal fade" id="addMilesModalToggle" aria-labelledby="addMilesModalToggle" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h2>Add More Miles</h2>
                </div>
                <div class="modal-body p-0 pe-3">
                    <div class="row align-items-center">
                        <div class="success-content mt-5 pt-4 text-center">
                            <form action="{{route('add-miles')}}" method="post" id="addMiles" class="p-3 shadow-sm rounded bg-light">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{$ride_details->booking->id}}">
                                <div class="form-group mb-3">
                                    <label for="payment_method" class="form-label font-weight-bold">Miles</label>
                                    <input type="text" name="add_miles" id="add_miles" class="form-control">
                                </div>
                                <div class="form-group text-end">
                                    <button type="submit" class="btn bg-black px-5 orange rounded-10 py-2">Add</button>
                                </div>
                            </form>
                        </div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Include Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function disableButton() {
    var submitButton = document.getElementById('submitButton');
    submitButton.disabled = true; // Disable the submit button to prevent multiple clicks
    submitButton.innerHTML = 'Complete Ride...'; // Change the button text to indicate the form is being submitted
}

function disablePausedButton() {
    var submitButton = document.getElementById('submitPausedButton');
    submitButton.disabled = true; // Disable the submit button to prevent multiple clicks
    submitButton.innerHTML = 'Paused Ride...'; // Change the button text to indicate the form is being submitted
}
</script>

<script>

function moveFocus(index) {
    if (index < 4) {
        document.getElementById('otp' + (index + 1)).focus();
    }
}

$(document).on('click', "#payNowButtonBtn", function() {
    
    $("#loader").show();
    var submitButton = document.getElementById('payNowButtonBtn');
    submitButton.disabled = true; 
    submitButton.innerHTML = 'Complete Ride...';
  
    // const selectedCard = $("input[name='user_card']:checked").val();
    $.ajax({
        url: "{{route('admin-process-payment')}}",
        type: "POST",
        data: {
            booking_id: "{{$ride_details->booking->id}}",
            _token: "{{ csrf_token() }}" // Ensure CSRF protection
        },
        success: function(response) {
            $("#loader").hide();
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful!',
                    text: response.message,
                }).then(() => {
                    window.location.href = "{{route('admin-rides')}}";
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Failed!',
                    text: response.message,
                }).then(() => {
                     window.location.reload();
                });
            }
        },
        error: function(xhr, status, error) {
            $("#loader").hide();
            console.log('Error:', xhr.responseJSON); // Log the error response

            // Handle specific errors based on status code
            if (xhr.status === 400) {
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Failed!',
                    text: 'There was an issue with your payment request.',
                }).then(() => {
                     window.location.reload();
                });
            } else if (xhr.status === 401) {
                Swal.fire({
                    icon: 'error',
                    title: 'Authentication Failed!',
                    text: 'Payment authentication failed. Please contact support.',
                }).then(() => {
                     window.location.reload();
                });
            } else if (xhr.status === 429) {
                Swal.fire({
                    icon: 'error',
                    title: 'Too Many Requests!',
                    text: 'Too many payment attempts. Please try again later.',
                }).then(() => {
                     window.location.reload();
                });
            } else if (xhr.status === 502) {
                Swal.fire({
                    icon: 'error',
                    title: 'Network Issue!',
                    text: 'Network issue, please check your internet connection.',
                }).then(() => {
                     window.location.reload();
                });
            } else if (xhr.status === 500) {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error!',
                    text: xhr.responseJSON.message,
                }).then(() => {
                     window.location.reload();
                });
            } else {
                // General error handler
                Swal.fire({
                    icon: 'error',
                    title: 'An Unexpected Error Occurred!',
                    text: 'Please try again later.',
                }).then(() => {
                     window.location.reload();
                });
            }
        }
    });
});
</script>

<script>
    $("#addMiles").validate({
        rules: {
            add_miles: {
                required: true,
                digits: true
            }
        }
    });
</script>


<script>
    var map, directionsService, directionsRenderer;

    function initMap() {
        // Create a map centered at a default location
        map = new google.maps.Map(document.getElementById('map'), {
            center: { 
                lat: {{$ride_details->pick_up_latitude}}, 
                lng: {{$ride_details->pick_up_longitude}} 
            },
            zoom: 10
        });

        // Initialize the DirectionsService and DirectionsRenderer
        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map
        });

        // Set up the directions request
        var request = {
            origin: { 
                lat: {{$ride_details->pick_up_latitude}}, 
                lng: {{$ride_details->pick_up_longitude}} 
            },  // Pickup location
            destination: { 
                lat: {{$ride_details->drop_off_latitude}}, 
                lng: {{$ride_details->drop_off_longitude}} 
            },  // Dropoff location
            travelMode: 'DRIVING'  // Use 'DRIVING' mode
        };

        // Request the directions and render the route on the map
        directionsService.route(request, function(result, status) {
            if (status === 'OK') {
            directionsRenderer.setDirections(result);
            } else {
            alert('Directions request failed due to ' + status);
            }
        });
    }
</script>

<!-- JavaScript to trigger SweetAlert and submit form -->
<script>
    document.getElementById('rejectBtn').addEventListener('click', function() {
        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to Cancel this ride?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Cancel it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user confirms, submit the form
                document.getElementById('rejectForm').submit();
            }
        });
    });
</script>

<script>
    document.getElementById('rejectByAdminBtn').addEventListener('click', function() {
        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to reject this ride?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Reject it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user confirms, submit the form
                document.getElementById('rejectByAdminForm').submit();
            }
        });
    });

    
</script>



<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{config('global-constant.google_map_api_key')}}&callback=initMap" 
    async defer></script>

@endsection