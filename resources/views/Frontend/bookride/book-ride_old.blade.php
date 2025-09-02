@extends('Frontend.layouts.master')
@section('title', 'Book Ride')
@section('content')
<main>
    <div class="request-ride-for-cotent mb-5 pb-5">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="r-heading mt-lg-3 mt-5 mb-5">
                        <h1 class="fs-50 fw-600">Request a ride </h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cursus nibh mauris </p>
                    </div>
                </div>

                <div class="bookride-for-contnet ">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="book-bg h-auto">
                                <div class="book-your-ride p-4">
                                    <div class="bs-heading mb-lg-0 mb-4">
                                        <h5 class="text-white">Book Your Ride For </h5>
                                        <!-- <p class="fw-600 fs-14   text-white">09 May 2024, 12:15 </p> -->
                                        <p class="fw-600 fs-14 text-white">
                                            {{ \Carbon\Carbon::parse($ride_request['ride_date'])->format('d M Y, h:i A') }}
                                        </p>

                                        <p class="fw-400 fs-14   text-white">Passengers: <span class="ps-2">{{$ride_request['total_passenger']}}</span> </p>
                                    </div>
                                    <div class="row">
                                        <div class="col-8 col-md-6">
                                            <div class="pick-drop-line border-orange d-flex justify-content-between mt-4">
                                                <img src="{{asset('assets/images/location.png')}}" class="loc-icon">
                                                <img src="{{asset('assets/images/drop.png')}}" class="drop-icon">
                                            </div>
                                        </div>
                                        <div class="row mt-3 w-100 m-auto p-0">
                                            <div class="col-md-12">
                                                <div class="syndy-text row">
                                                    <div class="col-6 col-md-6">
                                                    <p class="text-white fw-400">{{$ride_request['pick_up_address']}}</p>
                                                </div>
                                                    <div class="edit-text col-6 col-md-6 pe-0">
                                                        <p class="text-white fw-400">{{$ride_request['drop_off_address']}}</p>
                                                        <!-- <a href="#" class="orange text-decoration-underline d-block text-end">Edit</a> -->
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @if(isset($ride_request['person_name']) && isset($ride_request['phone_number']))
                                    <div class="book-your-ride p-4 mt-4">
                                        <div class="bs-heading mb-lg-0 mb-4">
                                            <h5 class="text-white">Passenger details</h5>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <p class="text-white">{{$ride_request['person_name']}}</p>
                                                    <p class="text-white">Passenger Name</p>
                                                </div>
                                                <div class="col-sm-4 tex-white">
                                                    <p class="text-white">{{$ride_request['phone_number']}}</p>
                                                    <p class="text-white">Phone Number</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="book-your-ride estimated-price p-4 mt-4">
                                    <div class="bs-heading mb-lg-0 mb-4">
                                        <h5 class="text-white">Estimated Price </h5>
                                        <p class="fw-400 text-white">Price based on your information for
                                            selected route </p>
                                    </div>
                                    <div class="price-btn">
                                        <p class="orange py-3 rounded-15  fw-600">${{$ride_request['ride_amount']}}</p>
                                    </div>
                                </div>
                                
                                <div class="book-your-ride p-4 mt-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-8 col-12">
                                            <div class="bs-heading mb-lg-0 mb-4 d-flex align-items-center justify-content-between">
                                                <h5 class="text-white fw-400">Select Payment method </h5>

                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addCard">
                                                    <div class="add-card d-flex align-items-center gap-2">
                                                        <div class="plus-sign">
                                                            <sapn>+</sapn>
                                                        </div>
                                                        <div class="add-card-text">
                                                            <h6 class="orange mb-0">Add New Card</h6>
                                                            <p class="text-white fs-14">Save &amp; pay via cards</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="accordion mt-4" id="accordionExample">
                                               
                                                
                                                <!-- <div class="accordion-item mb-3 ">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed cash-icon " type="button" data-bs-toggle="collapse" data-bs-target="" aria-expanded="false" aria-controls="collapseThree">

                                                            <span class="strip-bg"><img src="{{asset('assets/images/as1.png')}}" class="strip"></span> Card
                                                            <div class="form-check ms-auto p-0">
                                                                <input class="form-check-input" type="radio" name="payment_method" id="payment_method" value="card">

                                                            </div>
                                                        </button>
                                                    </h2>
                                                </div> -->

                                                <div class="accordion-item mb-3 ">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed cash-icon" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                            <!-- <span class="strip-bg"><img src="{{asset('assets/images/as1.png')}}" class="strip"></span> Card -->
                                                            <span class="strip-bg"><img src="{{asset('assets/images/as1.png')}}" class="strip"></span> Card
                                                            <div class="form-check ms-auto p-0">
                                                                <input class="form-check-input" type="radio" name="payment_method" id="payment_method" value="card">
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample" style="">
                                                        <div class="accordion-body">
                                                            <!-- <strong>This is the second item's accordion
                                                                body.</strong> -->
                                                            @foreach($cards as $card)
                                                                <div class="form-check ms-auto p-0">
                                                                    <!-- <label>{{$card->brand}}</label> -->
                                                                    
                                                                    <label class="card-brand">{{$card->brand}} </label>
                                                                    
                                                                    <input class="form-check-input" type="radio" name="user_card" id="user_card" value="{{$card->id}}">
                                                                    <label class="card-nbr">{{$card->last_four}}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="accordion-item mb-3 ">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed cash-icon " type="button" data-bs-toggle="collapse" data-bs-target="" aria-expanded="false" aria-controls="collapseThree">

                                                            <span class="strip-bg"><img src="{{asset('assets/images/as1.png')}}" class="strip"></span> Cash
                                                            <div class="form-check ms-auto p-0">
                                                                <input class="form-check-input" type="radio" name="payment_method" id="payment_method" value="cash">
                                                            </div>
                                                        </button>
                                                    </h2>
                                                </div>
                                               
                                            </div>

                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="card-btn text-center">
                                                @if (Auth::check())
                                                    <button type="button" id="booked_ride" class="btn bg-black text-white w-75 rounded-10 py-2">Book Ride Now</button>
                                                @else
                                                    <a href="{{route('login')}}" class="btn btn-book  fw-500 bg-black text-white px-5">Book Ride Now</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="book-ride-map mt-lg-0 mt-4 h-100">
                                <!-- <img src="{{asset('assets/images/rb-map.png')}}" alt="map" class="img-fluid w-100 h-100"> -->
                                <div id="map" style="height: 700px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<div class="modal fade" id="addCard" aria-labelledby="addCardModalToggleLabel" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content book-bg ">
            <a href="#" class="btn-close ms-auto s-close" data-bs-dismiss="modal" aria-label="Close"><img src="{{asset('assets/images/close.png')}}"></a>

            <div class="modal-header border-0 ps-lg-0 pt-lg-0 pb-lg-0 pe-lg-3">
                
            </div>
            <div class="modal-body p-0 pe-3">
                
                <div class="row align-items-center">
                    <div class="h-auto">
                        <div class="book-your-ride p-4 mt-4">
                            <div class="row align-items-center">
                                <div class="col-md-12 col-12">
                                    <div class="bs-heading mb-lg-0 mb-4 d-flex align-items-center justify-content-between">
                                        <h5 class="text-white fw-400">Add New Card </h5>
                                    </div>
                                    <div class="add-card-input rr">
                                        <form id="payment-form">
                                            <div id="card-element">
                                                <!-- A Stripe Element will be inserted here. -->
                                            </div>

                                            <!-- Used to display form errors. -->
                                            <div id="card-errors" role="alert"></div>

                                            <!-- <button type="submit">Save Card</button> -->
                                            <div class="loader" id="loader" bis_skin_checked="1"></div>
                                            <div class="add-btn text-end mt-4">
                                                @if (Auth::check())
                                                    <button type="sumit" class="btn bg-black text-white w-50 rounded-10 py-2">Add</button>
                                                @else
                                                    <a href="{{route('login')}}" class="btn bg-black text-white w-50 rounded-10 py-2 ">Add</a>
                                                @endif

                                            </div>
                                        </form>
                                    </div>

                                </div>
                               <!--  <div class="col-md-4 col-12">
                                    <div class="card-img">
                                        <img src="{{asset('assets/images/card-img.png')}}" class="img-fluid w-100">
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="bookingRidePopup" aria-labelledby="exampleModalToggleLabel" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                
                <!-- <a href="#" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><img src="{{asset('assets/images/close.png')}}"></a> -->
            </div>
            <div class="modal-body text-center mb-2 pt-0 pb-5">
<div class="loader-box text-center mb-5">
    <div class="loader"></div>
</div>
                <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Processing booking ride</h1>
                <p>Please do not close this page</p>
            </div>
        </div>
    </div>
</div>

<div class="success-modal">
    <div class="modal fade" id="successbookingRidePopup" aria-labelledby="successModalToggle" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
           <a href="#" class="btn-close ms-auto s-close" data-bs-dismiss="modal" aria-label="Close"><img src="{{asset('assets/images/close.png')}}"></a>

            <div class="modal-header border-0 ps-lg-0 pt-lg-0 pb-lg-0 pe-lg-3">
                
            </div>
            <div class="modal-body p-0 pe-3">
                
                <div class="row align-items-center">
                    <div class="col-md-6 pe-lg-0">
                        <div class="success-img">
                            <img src="{{asset('assets/images/success.png')}}" class="img-fluid w-100 h-100">
                        </div>
                    </div>
                    <div class="col-md-6 ps-lg-0">
                        <div class="success-content mt-5 pt-4">
                            <h1>Sucess</h1>
                            <p class="fs-14">Congratulations! You have successfully completed your ride booking. Sit back, relax and wait for your driver to arrive. We will be in touch with any questions if necessary and please feel free to reach out to us as well. Cheers.</p>
                                    <div class="next-pay-btn mt-4">
                                    <a href="{{route('home')}}" class="btn bg-black text-white w-50 rounded-10 py-2">View</a>
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

<!-- Include Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>
    // Set your publishable key
    const stripe = Stripe('{{ env('STRIPE_PUBLISHABLE_KEY') }}');
    const elements = stripe.elements();
    
    // Create a card element
    const card = elements.create('card', {
        style: {
            base: {
                color: "#333",
                fontSize: "16px",
                fontFamily: "'Arial', sans-serif",
                fontSmoothing: "antialiased",
                border: "1px solid #ddd",
                borderRadius: "4px",
                padding: "12px",
                backgroundColor: "#f9f9f9",
            },
            focus: {
                borderColor: "#4CAF50",
            },
        }
    });
    card.mount('#card-element');

</script>

<script>
    $(document).ready(function () {
        // Handle form submission
        $('#payment-form').on('submit', async function (event) {
            event.preventDefault(); // Prevent form from submitting the traditional way
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Step 1: Create a SetupIntent to get the client secret
            $.ajax({
                url: '{{route("create-payment-intent")}}', // Endpoint for creating payment intent
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (setupIntent) {
                    const { clientSecret } = setupIntent;

                    // Step 2: Confirm the card setup using the client secret
                    stripe.confirmCardSetup(clientSecret, {
                        payment_method: {
                            card: card, // Your Stripe card element
                        },
                    }).then(function (result) {

                        console.log(result);
                        
                        if (result.error) {
                            // Handle error (e.g., insufficient funds)
                            $('#card-errors').text(result.error.message);
                        } else {
                            
                            // Step 3: Send the payment method ID to your backend to save the card
                            $.ajax({
                                url: '{{route("store-card")}}', // Backend endpoint to store the card info
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    _token: csrfToken,  
                                    payment_method_id: result.setupIntent.payment_method,
                                },
                                success: function (response) {
                                    // console.log('response');
                                    // console.log(response);
                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Card Added Successful!',
                                            text: response.message,
                                            confirmButtonColor: '#000000',
                                        }).then(() => {
                                            window.location.href = "{{route('book-ride')}}";
                                        });
                                    } else {
                                        // Swal.fire({
                                        //     icon: 'error',
                                        //     title: 'Card Failed!',
                                        //     text: response.message,
                                        // });
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Card Failed!',
                                            text: response.message,
                                            confirmButtonColor: '#000000',
                                        }).then(() => {
                                            window.location.href = "{{route('book-ride')}}";
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    // Handle errors if the request fails
                                    console.log('Error saving card:', error);
                                }
                            });
                        }
                    });
                },
                error: function (xhr, status, error) {
                    // Handle error if the SetupIntent request fails
                    console.log('Error creating setup intent:', error);
                }
            });
        });
    });
</script>


<script>
    $(document).ready(function () {

        // Check if the modal was already shown in this session
        if(localStorage.getItem('modal_shown') === 'true') {
            $("#successbookingRidePopup").modal({
                backdrop: 'static',
                keyboard: false 
            }).modal('show');
        }

        $('button.accordion-button.cash-icon').on('click',function(){
            const selected_value = $("input[name='payment_method']:checked").val();
            if(selected_value=='cash'){
                $('div#collapseTwo').removeClass('show');
                $("input[name='user_card']").prop("checked", false);
            }
        });


        $("#booked_ride").on('click', function () {

            // Check if booking is already in progress
            // if(localStorage.getItem('booking_in_progress') === 'true') {
            //     alert('Booking is already in progress. Please wait.');
            //     return false; // Prevent submitting the request again
            // }

            if (localStorage.getItem('booking_in_progress') === 'true') {
                Swal.fire({
                    icon: 'warning', // You can change this to 'error' or 'info' based on the context
                    title: 'Booking In Progress',
                    text: 'Booking is already in progress. Please wait.',
                    confirmButtonColor: '#000000', // Blue button color
                    confirmButtonText: 'Okay'
                });
                return false; // Prevent submitting the request again
            }

            // Get the selected radio button value
            const selected_value = $("input[name='payment_method']:checked").val();
            const user_card = $("input[name='user_card']:checked").val();
            
            if(selected_value=='card'){
                if (!user_card) {
                    Swal.fire({
                        icon: 'error', // Error icon
                        title: 'Oops...',
                        text: 'Please select a card or add a new one!',
                        confirmButtonColor: '#d33', // Red color button
                        confirmButtonText: 'OK'
                    });
                    return false; // Stop further execution if no selection is made
                }
            }else{
                $('div#collapseTwo').removeClass('show');
                $("input[name='user_card']").prop("checked", false);
            }

            // if (!selected_value) {
            //     alert('Please select a payment method!');
            //     return false; // Stop further execution if no selection is made
            // }

            if (!selected_value) {
                Swal.fire({
                    icon: 'error', // Error icon
                    title: 'Oops...',
                    text: 'Please select a payment method!',
                    confirmButtonColor: '#d33', // Red color button
                    confirmButtonText: 'OK'
                });
                return false; // Stop further execution if no selection is made
            }

            
            var encrypted_ride_data = @json($encrypted_ride_request);

            $("#bookingRidePopup").modal('show');

            // Mark that booking is in progress
            localStorage.setItem('booking_in_progress', 'true');

            // Perform the AJAX request
            $.ajax({
                method: 'POST',
                url: '{{route("book-ride")}}',
                data: {
                    _token: "{{ csrf_token() }}", 
                    ride_request: encrypted_ride_data,
                    payment_method: selected_value,
                    user_selected_card: user_card,
                },
                success: function (response) {
                    console.log("Response received:", response); // Log the response to see what it contains
                    // Hide the bookingRidePopup modal

                    if(response.status){
                        setTimeout(function() { 
                            $("#bookingRidePopup").modal('hide');
                            // $("#successbookingRidePopup").modal('show');
                            $("#successbookingRidePopup").modal({
                                backdrop: 'static',
                                keyboard: false
                            }).modal('show');

                            // Store a flag to indicate the modal was shown
                            localStorage.setItem('modal_shown', 'true');

                        }, 500);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('An error occurred:', error); // Log the error for debugging
                    // Clear the booking in progress flag on error as well
                    localStorage.removeItem('booking_in_progress');
                }
            });

            return false; // Prevent form submission or other default actions
        });
    });

  
    document.addEventListener('DOMContentLoaded', function () {
        // Select all the buttons
        const buttons = document.querySelectorAll('.accordion-button');

        buttons.forEach(button => {
            button.addEventListener('click', function () {
                // Find the radio button within the clicked button's parent
                const radioButton = this.querySelector('.form-check-input');

                // Check the radio button
                if (radioButton) {
                    radioButton.checked = true;
                }
            });
        });
    });


    var map, directionsService, directionsRenderer;

    function initMap() {
    // Create a map centered at a default location
    map = new google.maps.Map(document.getElementById('map'), {
        center: { 
            lat: {{$ride_request['pick_up_latitude']}}, 
            lng: {{$ride_request['pick_up_longitude']}} 
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
            lat: {{$ride_request['pick_up_latitude']}}, 
            lng: {{$ride_request['pick_up_longitude']}} 
        },  // Pickup location
        destination: { 
            lat: {{$ride_request['drop_off_latitude']}}, 
            lng: {{$ride_request['drop_off_longitude']}} 
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