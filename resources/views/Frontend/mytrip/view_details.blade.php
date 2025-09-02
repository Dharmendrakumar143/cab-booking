@extends('Frontend.layouts.master')
@section('title', 'My Trip Details')
@section('content')
<main>
    <div class="my-trip-for-cotent  pb-3">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="r-heading mt-lg-5 mt-5 mb-3">
                        <h2 class=" fw-600">My Trip Details </h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class=" compelet-trip-deatils ride-detail-cancle rounded-10 my-3">
                        <div class="book-your-ride p-4 ">
                            <div class="r-d-flex d-flex justify-content-between flex-wrap">
                                <div class="bs-heading mb-lg-0 mb-4">
                                    <h5 class="fw-600 mb-0">Ride Details</h5>
                                    <p class="fw-600 fs-14 ">{{ date('d M Y', strtotime($ride_details->pick_up_date)) }}, {{$ride_details->pick_up_time}}</p>
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
                                        <p class="fw-500"><span class="pe-3">:</span> {{$ride_details->booking->duration}}
                                    </p>
                                    </div>
                                </div>
                                <div class="location-img-text">
                                    <div class="km d-flex align-items-end flex-lg-row flex-column">
                                        <img src="{{asset('assets/images/r-d-loc.png')}}">
                                        
                                        <span class="fs-12">
                                            <!-- {{$ride_details->booking->distance}} Km  -->
                                        {{$ride_details->booking->miles_distance}} Miles
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-md-7 col-7">
                                    <div class="pick-drop-line border-orange d-flex justify-content-between mt-4">
                                        <img src="{{asset('assets/images/location.png')}}" class="loc-icon">
                                        <img src="{{asset('assets/images/drop.png')}}" class="drop-icon">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6 col-md-6">
                                        <div class="syndy-text d-flex flex-wrap justify-content-between align-items-center">
                                            <p class="fw-400">{{$ride_details->pick_up_address}}</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <div class="edit-text">
                                            <p class=" fw-400">{{$ride_details->drop_off_address}}</p>
                                        </div>
                                    </div>
                                </div>
                                            <div class="compelete-btn text-end mt-4">
                                                <button type="button" class="btn bg-black px-5 orange  rounded-10 py-2">{{$ride_details->booking->booking_status}}</button>
                                                @if($ride_details->booking->booking_status!='Completed' && $ride_details->booking->booking_status!='Cancelled')
                                                    <button type="button" class="btn bg-black px-5 text-white  rounded-10 py-2" data-bs-target="#cancleModalToggle" data-bs-toggle="modal">Cancel</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="complete-trip-content mb-5">
                 <div class="container-fluid px-lg-5 px-2">
                <div class="row">
                    <div class="col-lg-5 col-md-10">
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="book-bg h-auto">
                                    <div class="drver-details text-white">
                                        <h4 class="border-bottom ">Driver Details</h4>
                                        <div class="driver-text-avtar d-flex pt-3 justify-content-between align-items-center">

                                            <div class="bt-modal">
                                                <!-- <div class="d-name">
                                                    <h5 class="m-0 fs-16">{{$ride_details->booking->admin->name ?? null}}</h5>
                                                </div> -->
                                                <div class="modal-flex d-flex gap-5">
                                                    <div class="car-modal">

                                                        <p>Name</p>
                                                        <p>Car Year, Make and Model</p>
                                                        <p>License Plate</p>
                                                    </div>
                                                    <div class="l-plate">
                                                        <p class="fw-500 fs-14"><span class="pe-3">:</span> {{$ride_details->booking->admin->name ?? null}}</p>
                                                        <p class="fw-500 fs-14"><span class="pe-3">:</span> {{$ride_details->booking->admin->car_model ?? null}}</p>
                                                        <p class="fw-500 fs-14"><span class="pe-3">:</span> {{$ride_details->booking->admin->car_number_plate ?? null}}</p>
                                                    </div>
                                                </div>
                                                <div class="dcm-btn d-flex gap-1 mt-3">
                                                    <!-- <img src="{{asset('assets/images/dcall.png')}}"> -->
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
 
                                                <!-- <img src="{{asset('assets/images/dr-start.png')}}"> -->
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

                        </div>
                        <div class="fare-details mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="bg-black rounded-20 pt-4">
                                        <div class="fr-detls gray-bg rounded-20 p-4">
                                            <div class="fare-header d-flex justify-content-between flex-wrap mb-3">
                                                <h4>Fare Details</h4>
                                                <div class="t-xl">
                                                    <a href="#" class="xl-btn orange-bg px-lg-5 px-2 py-2 text-black rounded-30">Troy
                                                        XL</a>
                                                </div>
                                            </div>
                                            <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                <p>Base Fare</p>
                                                <p class="fw-500"><span class="pe-3">:</span> ${{$ride_details->booking->ride_booking_amount}}</p>
                                            </div>
                                            <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                <p>Time and distance charges</p>
                                                <p class="fw-500"><span class="pe-3">:</span>${{config('global-constant.ride_per_km_charge.rate_per_km')}}/Miles + {{$ride_details->booking->miles_distance}} Miles</p>
                                            </div>
                                            <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                <p>Surge pricing (if applicable)</p>
                                                <p class="fw-500"><span class="pe-3">:</span>${{isset($ride_details->booking->surge_amount)?$ride_details->booking->surge_amount:'00.00'}}</p>
                                            </div>
                                            <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                <p>Tolls or fees</p>
                                                <p class="fw-500"><span class="pe-3">:</span> $00.00</p>
                                            </div>
                                            <div class="total-fee base-fare-box  d-flex justify-content-between rounded-8 p-2 mb-3">
                                                <p class="fs-20">Total fare</p>
                                                <p class="fw-500 orange fs-24">${{$ride_details->booking->subtotal}}</p>
                                            </div>
                                            <div class="pay-fee base-fare-box bg-light-gray  d-flex flex-lg-nowrap flex-wrap justify-content-between rounded-8 p-4 mb-3">
                                                <div class="pay-ment">
                                                    <p class="">Payment method</p>
                                                    <p class="fs-14 fw-600">
                                                    {{$ride_details->paymentStatus->payment_method}}
                                                    </p>
                                                </div>
                                                <!-- <div class="promo-code text-lg-end">
                                                    <p class="">Promo codes/Discounts</p>
                                                    <p class="fw-600  fs-14 "> GOODTOGO</p>
                                                </div> -->
                                            </div>
                                            @if(
                                                isset($ride_details->paymentStatus) && 
                                                isset($ride_details->booking) && 
                                                $ride_details->paymentStatus->payment_method === 'card' && 
                                                $ride_details->booking->booking_status === 'Completed' && 
                                                $ride_details->paymentStatus->status !== 'paid'
                                            )
                                                <!-- <div class="card-btn text-center">
                                                    <button type="button" class="btn bg-black text-white w-75 rounded-10 py-2" id="payNowButton">Pay Now</button>
                                                </div>                                                 -->
                                           @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-7 col-12">
                        <div class="r-d-map mb-3">
                            <!-- <img src="{{asset('assets/images/ride-details-map.png')}}" class="img-fluid h-100"> -->
                            <div id="map" style="height: 500px; width: 100%;"></div>
                        </div>
                            <div class="mb-3">
                                <a href="{{ url('/tip/driver-tip/'.encrypt($ride_details->booking->id)) }}" class="btn" style="padding: 10px 20px; background-color: #FB980F; color: #000000; text-decoration: none; border-radius: 5px;">Tip For Driver</a>
                            </div>
                            @if($ride_details->booking->booking_status=='Completed' && empty($ride_details->review))
                                <div class="write-review a-trip p-4 rounded-10">
                                    <div class="row">
                                        <form action="{{route('submit-rating')}}" method="post" id="give_rating">
                                            @csrf
                                            <div class="col-md-12">
                                                <h5 class="text-black">Write A Review</h5>
                                            </div>
                                            <input type="hidden" id="rating" name="rating">
                                            <input type="hidden" name="ride_id" value="{{$ride_details->id}}">
                                            <input type="hidden" name="driver_id" value="{{$ride_details->booking->driver_id}}">
                                            <div class="col-md-4">
                                                <div class="give-rating">
                                                    <h6 class="fw-600">Give Rating</h6>
                                                    <div class="g-star">
                                                        <div id="star-rating">
                                                            <span class="fa fa-star" data-value="1"></span>
                                                            <span class="fa fa-star" data-value="2"></span>
                                                            <span class="fa fa-star" data-value="3"></span>
                                                            <span class="fa fa-star" data-value="4"></span>
                                                            <span class="fa fa-star" data-value="5"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="review-some">
                                                    <h6 class="fw-600">Review</h6>
                                                    <div class="form-floating">
                                                        <textarea class="form-control pt-1" placeholder="Write something........." id="review" name="review" ></textarea>
                                                        </div>
                                                        
                                                </div>
                                                
                                            </div>
                                            <div class="col-md-4">
                                                <div class="review-submit-btn mt-3">
                                                    <button type="submit" id="submit_button" class="btn bg-black text-white w-100 rounded-10 py-2" disabled>Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            @if($ride_details->cancelledRide)
                                <div class="write-review a-trip p-4 rounded-10">
                                    <div class="row">    
                                        <div class="col-md-12">
                                            <div class="reason-cancle mt-4">
                                                <h6 class="fw-600">Reason to Cancel</h6>
                                                <div class="reson-input">
                                                    <!-- <input type="text" class="form-control" id="reason" placeholder="Reason"> -->
                                                    @if($ride_details->cancelledRide->cancellationReasons->reason=='Other reason')
                                                        <p>{{$ride_details->cancelledRide->cancellationReasons->reason}}</p>  
                                                        <p>{{$ride_details->cancelledRide->cancellation_reason}}</p>  
                                                    @else
                                                        <p>{{$ride_details->cancelledRide->cancellationReasons->reason}}</p>  
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        <!-- <div class="add-trip-driver mt-3 a-trip  rounded-10">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-12">
                                            <div class=" p-4">
                                                <h5 class="text-black">Add a Tip for Driver</h5>
                                                <div class="doller-num d-flex gap-4">
                                                    <span>$1</span>
                                                    <span class="s-active">$2</span>
                                                    <span>$3</span>
                                                    <span>$4</span>
                                                </div>
                                                <div class="m-p-btn mt-3">
                                                    <h6>Enter Custom Amount</h6>
                                                    <div class="quantity bg-white py-2 px-3 text-center rounded-6">
                                                        <button class="minus" aria-label="Decrease">−</button>
                                                        <input type="number" class="input-box" value="1" min="1" max="10" placeholder="$3">
                                                        <button class="plus" aria-label="Increase">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12 align-content-end">
                                            <div class="trip-pay-btn text-center  p-4">
                                                <button type="button" class="btn bg-black text-white w-100 rounded-10 py-2">Pay</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div> -->

                        <!-- <div class="card" id="chat3">
          <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h5 class="mb-0">Chat</h5>
            
          </div>
          <div class="card-body chat-box" data-mdb-perfect-scrollbar-init style="">

            <div class="d-flex flex-row justify-content-start">
              <img src="{{asset('assets/images/rider (2).png')}}"
                alt="avatar 1" class="rider-img">
              <div  class="chat-txt ms-3">
                <p class="small msg-txt p-2 mb-1 rounded-3 bg-body-tertiary">Hi</p>
                <p class="small  mb-3 rounded-3 text-muted">23:58</p>
              </div>
            </div>
            <div class="d-flex flex-row justify-content-end mb-4">
              <div  class="chat-txt me-3">
                <p class="small msg-txt p-2  mb-1 text-white rounded-3 bg-primary">Do you have pictures of Matley
                  Marriage?</p>
                <p class="small  mb-3 rounded-3 text-muted d-flex justify-content-end">00:11</p>
              </div>
              <img src="{{asset('assets/images/user (2).png')}}"
                alt="avatar 1" class="rider-img">
            </div>

            

            <div class="d-flex flex-row justify-content-start">
                <img src="{{asset('assets/images/rider (2).png')}}"
                alt="avatar 1" class="rider-img">
              <div class="chat-txt  ms-3">
                <p class="small  msg-txt p-2 mb-1 text-white rounded-3 bg-primary">Okay then see you on sunday!!
                </p>
                <p class="small mb-3 rounded-3 text-muted d-flex justify-content-start">00:15</p>
              </div>
              
            </div>

          </div>
          <div class="card-footer text-muted d-flex justify-content-start align-items-center p-2 chatboard-footer">
            <img src="{{asset('assets/images/rider (2).png')}}"
              alt="avatar 3" class="rider-img">
            <input type="text" class="form-control form-control-lg mx-3" id="exampleFormControlInput1"
              placeholder="Type message">
            <button class="btn send-msg ms-3" ><img src="{{asset('assets/images/send (2).png')}}" class="img-fluid "></button>
          </div>
        </div> -->
                    </div>
                </div>
            </div>
            </div>

        </div>
    </div>
</main>

<div class="cancle-modal success-modal">
    <div class="modal fade" id="cancleModalToggle" aria-hidden="true" aria-labelledby="cancleModalToggle" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <a href="#" class="btn-close ms-auto s-close" data-bs-dismiss="modal" aria-label="Close">
                <img src="{{asset('assets/images/close.png')}}">
            </a>

            <div class="modal-header border-0 ps-lg-0 pt-lg-0 pb-lg-0 pe-lg-3">
            </div>
            <div class="modal-body p-0 pe-3">
                
                <div class="row align-items-center">
                    <div class="col-md-6 pe-lg-0">
                        <div class="success-img">
                            <img src="{{asset('assets/images/r-d-error.png')}}" class="img-fluid w-100 h-100">
                        </div>
                    </div>
                    <div class="col-md-6 ps-lg-0">
                        <div class="success-content mt-5 pt-4 text-center">
                            <h5>Are you sure you want to cancel your ride?</h5>
                            <p class="fs-14">Please do not close this page </p>
                                    <div class="yes-no-btn mt-4 d-flex gap-2 justify-content-center">
                                    <div class="y-btn">
                                        <a href="{{route('cancel-ride')}}" class="btn border border-black rounded-10 py-2">Yes</a>
                                    </div>
                                        <div class="n-btn">
                                            <button type="button" class="btn bg-black text-white  rounded-10 py-2" data-bs-dismiss="modal" aria-label="Close">No</button>
                                        </div>
                                </div>
                                <div class="hr">
                                    <hr class="text-black mt-4">
                                </div>
                                <div class="s-circle pt-2">
                                    <img src="{{asset('assets/images/s-circle.png')}}" class="img-fluid">
                                </div>
                        </div>
                    </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).on('click', "#payNowButton", function() {
    
    $("#loader").show();
  
    // const selectedCard = $("input[name='user_card']:checked").val();
    $.ajax({
        url: "{{route('process-payment')}}",
        type: "POST",
        data: {
            ride_id: "{{$ride_details->id}}",
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
                    window.location.href = "{{route('my-trip')}}";
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Failed!',
                    text: response.message,
                });
            }
        },
        error: function(error) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Something went wrong. Please try again.',
            });
        }
    });
});


$(document).ready(function () {
    const stars = $('#star-rating .fa');
    const submitButton = $('#submit_button');

    // Function to check if rating is selected
    function checkRating() {
    
        const selectedRating = $('#rating').val();
   
        if (selectedRating) {
            submitButton.prop('disabled', false); // Enable button if rating is selected
        } else {
            submitButton.prop('disabled', true); // Disable button if no rating
        }
    }

    // Handle star click event
    stars.on('click', function () {
        const ratingValue = $(this).data('value');
        $('#rating').val(ratingValue); // Set the hidden input value

        // Highlight the selected stars
        stars.each(function () {
            if ($(this).data('value') <= ratingValue) {
                $(this).addClass('checked');
            } else {
                $(this).removeClass('checked');
            }
        });

        checkRating(); // Check rating after click
    });

    // Form validation
    $('#give_rating').validate({
        rules: {
            review: {
                required: true,
            },
            rating: {
                required: true,
                min: 1, // Ensure at least one star is selected
            },
        },
        messages: {
            review: {
                required: 'Please write something.',
            },
            rating: {
                required: 'Please select a rating.',
                min: 'Please select a rating.',
            },
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') === 'rating') {
                error.insertAfter('#star-rating'); // Place rating error after stars
            } else {
                error.insertAfter(element);
            }
        },
    });

    // Initial check to disable the button
    checkRating();
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