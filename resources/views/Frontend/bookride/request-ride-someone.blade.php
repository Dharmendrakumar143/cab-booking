@extends('Frontend.layouts.master')
@section('title', 'Request Ride For Someone')
@section('content')
<main>
    <div class="request-ride-cotent mb-5 pb-5">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
            <div class="col-lg-12">
                <div class="r-heading mt-lg-3 mt-5 mb-5">
                    <h1 class="fs-50 fw-600">Request a ride </h1>
                    <p>Here is where you can book a ride for someone other than yourself.</p>
                </div>
            </div>
            <div class="bookride-contnet  pb-5">
                <div class="row">
                  
                    <div class="col-md-8">
                        <form action="{{route('request-ride-someone')}}" method="post" id="book_ride">
                            @csrf
                            <div class="book-bg h-100">
                            <div class="book-your-ride h-100 p-4">
                                <div class="bs-heading mb-lg-0 mb-4 d-flex align-items-center justify-content-between">
                                <h5 class="text-white">Book Your Ride</h5>
                                <!-- <p class="fw-600 fs-14   text-white">Book For Someone Else!</p> -->
                                </div>
                                <div class="input-row d-flex justify-content-between gap-lg-0 gap-4">
                                    <div class="pick-up-location">
                                    
                                    <div class="pick-up-input mb-4">
                                        <label for="location" class="form-label text-white mb-0">Pick Up Location</label>
                                        <div class="input-group flex-nowrap">
                                            <input type="text" id="pickup_address" name="pickup_address" class="form-control bg-trans  rounded-15 @error('pickup_address') is-invalid @enderror" aria-label="Username" aria-describedby="addon-wrapping" value="{{ old('pickup_address') }}">
                                            <span class="input-group-text bg-trans border-start-0 rounded-15" id="addon-wrapping"><img src="{{asset('assets/images/location.png')}}"></span>
                                        </div>
                                        @error('pickup_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <input type="hidden" id="pickup_city" name="pickup_city">
                                        <input type="hidden" id="pickup_state" name="pickup_state">
                                        <input type="hidden" id="pickup_country" name="pickup_country">
                                        <input type="hidden" id="pickup_latitude" name="pickup_latitude">
                                        <input type="hidden" id="pickup_longitude" name="pickup_longitude">

                                    </div>
                                
                                <div class="date-input d-flex gap-3 mb-3">
                                    <div class="date w-100">
                                    <label for="date" class="form-label text-white mb-0">Date &amp; time</label>
                                    <div class="d-input" aria-label="date" aria-describedby="addon-wrapping">
                                        <input type="text" id="date_time" name="ride_date" class="form-control bg-trans rounded-15 @error('ride_date') is-invalid @enderror" aria-label="date" aria-describedby="addon-wrapping" value="{{ old('date_time') }}" autocomplete="off" placeholder="Date &amp; time">
                                        <div class="d-icon">
                                        <img src="{{asset('assets/images/date (1).png')}}">
                                        </div>
                                        @error('ride_date')
                                            <div class='invalid-feedback'>{{$message}}</div>
                                        @enderror
                                    </div>

                                    </div>
                                </div>
                                </div>
                                <div class="dropp-location">
                                    <div class="pick-up-input mb-4">
                                        <label for="location" class="form-label text-white mb-0">Drop Off Location</label>
                                        <div class="input-group flex-nowrap">
                                        <input type="text" id="dropoff_location" name="dropoff_location" class="form-control bg-trans rounded-15 @error('dropoff_location') is-invalid @enderror" aria-label="Username" aria-describedby="addon-wrapping" value="{{ old('dropoff_location') }}">
                                        <span class="input-group-text bg-trans border-start-0 rounded-15" id="addon-wrapping"><img src="{{asset('assets/images/drop.png')}}"></span>
                                        </div>
                                        @error('dropoff_location')
                                            <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                        <input type="hidden" id="dropoff_city" name="dropoff_city">
                                        <input type="hidden" id="dropoff_state" name="dropoff_state">
                                        <input type="hidden" id="dropoff_country" name="dropoff_country">
                                        <input type="hidden" id="dropoff_latitude" name="dropoff_latitude">
                                        <input type="hidden" id="dropoff_longitude" name="dropoff_longitude">
                                    </div>

                                    <div class="number">
                                        <label for="number" class="form-label text-white mb-0">Number of Passengers</label>
                                        <div class="d-input">
                                            <select name="total_passenger" id="total_passenger" class="form-control bg-trans rounded-15 @error('total_passenger') is-invalid @enderror" aria-label="date" aria-describedby="addon-wrapping">
                                                <option value="">Number of Passengers</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                            <!-- <input type="text" name="total_passenger" id="total_passenger" class="form-control bg-trans rounded-15 @error('total_passenger') is-invalid @enderror" aria-label="date" aria-describedby="addon-wrapping" value="{{ old('total_passenger') }}"> -->
                                            <div class="d-icon">
                                                <img src="{{asset('assets/images/passenger.png')}}">
                                            </div>
                                            @error('total_passenger')
                                                <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <div class="input-row d-flex justify-content-between gap-lg-0 gap-4">
                                <div class="pick-up-location">
                                    <h4 class="contact_info text-white">Add Contact information</h4>                                    
                                    <div class="pick-up-input mb-4">
                                        <label for="location" class="form-label text-white mb-0">Name of person</label>
                                        <div class="flex-nowrap">
                                            <input type="text" id="person_name" name="person_name" class="form-control bg-trans rounded-15 pac-target-input @error('person_name') is-invalid @enderror" aria-label="Username" aria-describedby="addon-wrapping" autocomplete="off" value="{{ old('person_name') }}" placeholder="Name of person">
                                            @error('person_name')
                                                <div class="invalid-feedback">{{$message}}</div>
                                            @enderror  
                                        </div>
                                    </div>
                                </div>
                                <div class="dropp-location">
                                    <div class="pick-up-input" id="someone_phone_number">
                                        <label for="location" class="form-label text-white mb-0">Phone number</label>
                                        <div class="flex-nowrap">
                                            <input type="text" id="phone_number" name="phone_number" class="form-control bg-trans rounded-15 pac-target-input @error('phone_number') is-invalid @enderror" aria-label="Username" aria-describedby="addon-wrapping" autocomplete="off" value="{{ old('phone_number') }}" placeholder="Phone number">
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{$message}}</div>
                                            @enderror  
                                        </div>
                                    </div>
                                </div>

                                </div>
                                   <div class="bk-rd-fare-btn mt-5">
                                    <button class="btn btn-book py-3  fw-500 bg-black text-white px-lg-5 rounded-15" type="submit">Book Ride Now</button>
                                </div>
                            </div>
                            </div>
                        </form>
                    </div>
    
                    <div class="col-md-4">
                        <div class="book-ride-map mt-lg-0 mt-4">
                            <!-- <img src="{{asset('assets/images/b-map.png')}}" alt="map" class="img-fluid h-75"> -->
                            <div id="map" style="height: 500px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')

<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // $(document).ready(function() {
    //     $('input#date_time').on('input', function() {
    //         var inputVal = $(this).val();

    //         // Regular expression for date format YYYY-MM-DD
    //         var validDateFormat = /^\d{4}-\d{2}-\d{2}$/;

    //         // Split the input value by commas for multiple dates
    //         var dates = inputVal.split(',');

    //         // Validate each date and remove any invalid dates or those with '00'
    //         dates = dates.map(function(date) {
    //             date = date.trim(); // Trim spaces around dates
    //             if (validDateFormat.test(date) && !date.includes('00')) {
    //                 return date; // Return the valid date
    //             } else {
    //                 return ''; // Return empty string for invalid date
    //             }
    //         });

    //         // Join the valid dates back with commas
    //         $(this).val(dates.filter(function(date) { return date !== ''; }).join(', '));
    //     });
    // });

    $(document).ready(function() {
        $('input#phone_number').on('input', function() {
            var numbers = $(this).val();
            // Allow numbers, plus (+) and minus (-) signs only
            $(this).val(numbers.replace(/[^0-9\+\-]/g, ''));
        });

        // Prevent Enter key from being pressed
        $('input#phone_number').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent Enter key from submitting or focusing out
            }
        });
    });


    $(document).ready(function() {
        $('input#total_passenger').on('input', function() {
            var numbers = $(this).val();

            $(this).val(numbers.replace(/[^0-9]/g, ''));
        });

        // Prevent Enter key from being pressed
        $('input#total_passenger').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent Enter key from submitting or focusing out
            }
        });
    });


    $("#book_ride").validate({
        rules: {
            pickup_address: {
                required: true,
            },
            ride_date:{
                required: true,
            },
            total_passenger:{
                required: true,
                number: true,
                min: 1,
                max: 4
            },
            dropoff_location:{
                required: true
            },
            person_name:{
                required: true,
                normalizer: function(value) {
                    return $.trim(value);
                }
            },
            phone_number:{
                required: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
                maxlength: 12,
                minlength: 10,
                digits: true,
            }
        },
        messages: {
            pickup_address: {
                required: "Pickup address is required.",
            },
            ride_date:{
                required: "Pickup date is required.",
            },
            total_passenger:{
                required: "This field is required.",
                number: "Please enter a valid number.",
                min: "The value must be at least 1.",
                max: "The value cannot be greater than 4."
            },
            dropoff_location:{
                required: "Drop off location is required.",
            },
            person_name:{
                required: "Person name is required.",
            },
            phone_number:{
                required: "Phone number is required.",
                digits: "Phone number must contain only numbers.",
            }
        },
    });
</script>

<script>
    
    $(document).ready(function () {
        localStorage.removeItem('modal_shown');
        localStorage.removeItem('booking_in_progress');
    })

    function initApp() {
        // Call both functions from here
        initAutocomplete();
        initMap();
    }

    var map, directionsService, directionsRenderer;

    function initMap() {
        // Create a map centered at a default location
        map = new google.maps.Map(document.getElementById('map'), {
            center: { 
                lat: 34.052235, 
                lng: -118.243683 
            },
            zoom: 8
        });

        // Initialize the DirectionsService and DirectionsRenderer
        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            preserveViewport: true 
        });

        // Set up the directions request
        var request = {
            origin: { 
                lat: 34.052235, 
                lng: -118.243683 
            },  // Pickup location
            destination: { 
                lat: 34.052235, 
                lng: -118.243683 
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
    flatpickr("#date_time", {
        enableTime: true,
        dateFormat: "Y-m-d h:i K",  // 12-hour format with AM/PM
        minuteIncrement: 15,
        minDate: "today",  
    });

    // $(document).ready(function () {
    //     // Initialize the datetime picker
    //     $('#date_time').datetimepicker({
    //         format: 'Y-m-d H:i',
    //         step: 15,            
    //         minDate: 0,    
    //     });
    // });
</script>


<script>
    let pickupAutocomplete;
    let dropoffAutocomplete;

    /* Initialize Autocomplete */
    function initAutocomplete() {
        // Pickup Address Autocomplete
        const pickupInput = document.getElementById("pickup_address");
        if (pickupInput) {
            pickupAutocomplete = new google.maps.places.Autocomplete(pickupInput);
            pickupAutocomplete.addListener("place_changed", () => onPlaceChange(pickupAutocomplete, 'pickup'));
        } else {
            console.error("Pickup Address input field not found");
        }

        // Dropoff Location Autocomplete
        const dropoffInput = document.getElementById("dropoff_location");
        if (dropoffInput) {
            dropoffAutocomplete = new google.maps.places.Autocomplete(dropoffInput);
            dropoffAutocomplete.addListener("place_changed", () => onPlaceChange(dropoffAutocomplete, 'dropoff'));
        } else {
            console.error("Dropoff Location input field not found");
        }
    }

    function onPlaceChange(autocompleteInstance, type) {
        const place = autocompleteInstance.getPlace();
        if (!place.geometry || !place.address_components) {
            alert(`No details available for the selected ${type} location.`);
            return;
        }

        let city = "";
        let state = "";
        let country = "";
        let latitude = "";
        let longitude = "";

        // Extract details from the address components
        for (const component of place.address_components) {
            const componentType = component.types[0];

            switch (componentType) {
                case "locality":
                    city = component.long_name;
                    break;
                case "administrative_area_level_1":
                    state = component.long_name;
                    break;
                case "country":
                    country = component.long_name;
                    break;
            }
        }

        // Populate fields based on the type (pickup or dropoff)
        if (type === "pickup") {
            document.getElementById("pickup_city").value = city;
            document.getElementById("pickup_state").value = state;
            document.getElementById("pickup_country").value = country;

            latitude = place.geometry.location.lat();
            longitude = place.geometry.location.lng();

            document.getElementById("pickup_latitude").value = latitude;
            document.getElementById("pickup_longitude").value = longitude;
        } else if (type === "dropoff") {
            document.getElementById("dropoff_city").value = city;
            document.getElementById("dropoff_state").value = state;
            document.getElementById("dropoff_country").value = country;

            latitude = place.geometry.location.lat();
            longitude = place.geometry.location.lng();

            document.getElementById("dropoff_latitude").value = latitude;
            document.getElementById("dropoff_longitude").value = longitude;
        }
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

<script src="https://maps.googleapis.com/maps/api/js?key={{config('global-constant.google_map_api_key')}}&libraries=places&callback=initApp" async defer></script>

@endsection