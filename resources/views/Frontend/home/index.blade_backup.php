@extends('Frontend.layouts.master')
@section('title', 'Troy Rides Your Journey Our Passion')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
@section('content')
<div class="banner-sec">
     <img class="banner-rect" src="{{asset('assets/images/rect.svg')}}">
    <div class="container-fluid home-container">
    <div class="row mb-5 py-5">
        <div class="col-md-6 ms-auto text-end">
            <div class="banner-heading text-center">
                <h1 class="fs-50 fw-600"> Troy Rides
                <br>
                <span class="journey-txt"> Your Journey. Our Passion. </span></h1>
            </div>
        </div>
        <div class="row align-items-center">
        <div class="col-md-4">
            <div class="we-are-text">
            <h5>We Are</h5>
            <p style="margin-bottom: 5px;">Troy Rides—where drivers are valued, riders are prioritized, and innovation drives every journey. As a forward-thinking rideshare service, we believe in empowering our drivers with flexible earning options while ensuring riders benefit from a stable and reliable experience.</p>
            <p>Join us in revolutionizing the way you travel, one ride at a time.</p>
            <!-- <div class="learn-btn">
                <button class="btn btn-learn ps-0 fw-500" type="submit">Learn More <span class="ps-3"><img src="{{asset('assets/images/learn-btn.png')}}"></span></button>
            </div> -->
            <div class="book-ride-btn mt-4">
                <a href="{{route('request-ride')}}" class="btn btn-book  fw-500 bg-black text-white px-5">Book Ride</a>
            </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="car-img text-end position-absolute top-20">
            <img src="{{asset('assets/images/car.png')}}" alt="car" class=" mw-100">
            </div>
        </div>
        </div>
    </div>
    </div>
   
    <section>

    </section>

     <!-------------------- cal-sec start here------------------>
</div>
    <div class="calculate-sec ">
        <div class="container-fluid home-container">
        <div class="cal-heading">
            <h2 class="fw-600">Calculate your Fare</h2>
        </div>
        <div class="bg-map relative mt-3">
            <div class="row relative py-5 px-5 align-items-center" style="z-index: 99">
            <div class="col-md-5 col-12">
                <div class="left-map h-100">
                <!-- <img src="{{asset('assets/images/left-map.png')}}" class="w-100"> -->
                <div id="map" style="height: 370px; width: 100%;"></div>    
            </div>
            </div>
            <div class="col-md-7 col-12">
                <div class="book-your-ride p-4">
                    <div class="b-heading">
                        <h5 class="text-white mb-3">Book Your Ride</h5>
                        <form action="{{route('request-ride')}}" method="post" id="book_ride">
                            @csrf
                            <div class="form">
                                <div class="location-input d-flex gap-3">
                                    <div class="pick-up-input mb-3 w-50">
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
                                    <div class="pick-up-input w-50 mb-3">
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
                                </div>
                                <div class="date-input d-flex gap-3 mb-3">
                                <div class="date w-50">
                                    <label for="date" class="form-label text-white mb-0">Date &amp; time</label>
                                    <div class="d-input">
                                        <input type="text" id="date_time" name="ride_date" class="form-control bg-trans rounded-15 @error('ride_date') is-invalid @enderror" placeholder="Select pickup date" aria-label="date" aria-describedby="addon-wrapping" autocomplete="off" value="{{ old('ride_date') }}">
                                        <div class="d-icon">
                                            <img src="{{asset('assets/images/date (1).png')}}">
                                        </div>
                                        @error('ride_date')
                                            <div class='invalid-feedback'>{{$message}}</div>
                                        @enderror
                                    </div>

                                </div>
                                <div class="number w-50">
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
                                <div class="pick-up-input mb-4">
                                    <label for="user_phone_number" class="form-label text-white mb-0">Phone Number</label>
                                    <div class="input-group flex-nowrap">
                                        <input type="text" id="user_phone_number" name="user_phone_number" placeholder="Enter your Phone Number" class="form-control bg-trans  rounded-15 @error('user_phone_number') is-invalid @enderror" value="{{ old('user_phone_number') }}">
                                        <span class="input-group-text bg-trans border-start-0 rounded-15" id="addon-wrapping"><img src="{{asset('assets/images/location.png')}}"></span>
                                    </div>
                                    @error('user_phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            
                                <div class="cal-fare-btn mt-4">
                                <button class="btn btn-book w-100 py-3  fw-500 bg-black text-white px-5 rounded-15" type="submit">Calculate Fare</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    <!-------------------- cal-sec end here------------------>
<main>
    <!-------------------- about-sec start here------------------>
    <section>
    <div class="about-us-sec mt-3" id="about-us">
        <div class="container-fluid home-container">
        <div class="row">
            <div class="col-md-12">
            <div class="about-heading">
                <h2 class="fw-600">About Us</h2>
            </div>
            </div>
            <div class="col-md-4 pe-0">
            <div class="high-time p-4 redefining h-100">
                <div class="company-top-txt">
                    <h6>1 /</h6>
                    <p class="mb-5 company-first-text pb-5">Troy Rides is dedicated to redefining the traditional ridesharing experience by merging cutting-edge technology with personalized service. Through our innovative platform, we aim to create a safe, comfortable, and efficient journey for both drivers and passengers. From seamless booking processes to tailored driving preferences, we prioritize user satisfaction and strive to make each ride a memorable experience.</p>
                </div>
                <div class="tp-sapce">
                    <h2 class="h-img mt-5 pt-5">Unique Driving<span class="ps-2"><img src="{{asset('assets/images/circle-arrow.png')}}"></span><br>
                 Experience </h2>
                </div>
                
            </div>
            </div>
            <div class="col-md-8 pe-0">
            <div class="row">
                <div class="col-md-12">
                <div class="row ">
                    <div class="col-md-7">
                    <div class="real-text-box second d-flex stya-inform">
                        <div class="real-time-box high-time comfort-box  p-4" style="width: 60%">
                            <div class="empower-box d-flex justify-content-between flex-column">
                                <div>
                                    <h6>2 /</h6>
                                    <p class="pb-3 stay-informed fs-14">Empowering our drivers with flexible options to optimize their earnings and experience, tailored to individual preferences.</p>
                                </div>
                                <div class="real-i-text  d-flex  align-items-center justify-content-between">
                          
                                    <h5 class="h-img fw-400">  Simple Earning <br> Opportunities</h5>
                                    <span class="width-50"><img src="{{asset('assets/images/circle-arrow.png')}}"></span>
                                </div>
                            </div>
                        </div>
                        <div class="real-right-img bg-black rounded-15 px-3 pt-2 pb-0">
                        <img src="{{asset('assets/images/real-img.png')}}" class="h-100">
                        </div>
                    </div>
                    </div>
                    <div class="col-md-5 ps-0">
                    <div class="Availability second high-time comfort-box  p-4 h-100">
                        <div class="empower-box d-flex justify-content-between flex-column">
                            <div>
                                <h6>3 /</h6>
                                <p class="fs-14 community-txt">Promoting inclusivity by encouraging drivers to serve diverse neighborhoods, fostering a sense of belonging and equitable service.</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-end pt-4">
                                 <h5 class="h-mar fw-400 mb-0">Community-<br>Centric Focus</h5>
                                <div class="crcl-arrow text-end">
                                    <span class="width-50 text-end"><img src="{{asset('assets/images/circle-arrow.png')}}"></span>
                                </div>                     
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="row mt-2 pt-1 pe-0 row-apceing">
                    <div class="col-md-5 ">
                        <div class="Availability second high-time comfort-box   p-4 h-100">
                        <h6>4 /</h6>
                        <div>
                            <img src="{{asset('assets/images/chekr.png')}}" class="img-fluid chekr-logo">
                            {{-- <p class="fs-14 safety-content">Seamlessly integrating cutting-edge technology to enhance safety and efficiency, ensuring a premium experience for all passengers.</p> --}}
                            <p class="fs-14 safety-content">Seamlessly integrating advanced tools like Checkr background checks and autonomous driving technology to boost safety, efficiency, and the passenger experience.</p>
                        </div>
                            <div class="crcl-arrow pt-5 mt-2">
                            <h5 class="h-mar fw-400 mb-0 d-flex justify-content-between align-items-center"> <span> Innovative Safety <br> Measures </span><span class="ps-1 width-50 text-end"><img src="{{asset('assets/images/circle-arrow.png')}}"></span></h5>
                            </div>
                            
                            
                        </div>
                    </div>
                    <div class="col-md-7 px-0">
                        <div class="real-text-box flex-pay w-100 h-100">
                        <div class="real-time-box second p-4">
                            <!-- <div class="wifi mb-2">
                            <img src="{{asset('assets/images/Union.png')}}">
                            </div> -->
                            <h6>5 /</h6>
                            <p class="fs-14">Optimizing route planning and investing in sustainable technologies, we strive to minimize environmental impact. Join us as we drive towards a more sustainable tomorrow!</p>
                            <div class="real-i-text mt-2">
                            <span class="width-50"><img src="{{asset('assets/images/circle-arrow.png')}}"></span>
                            <h5 class="h-img fw-400 pt-4 mb-0"> Sustainability & <br> Environmental Responsibility</h5>
                            </div>

                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
        </div>
        <div class="commute-sec mt-5">
            <div class="container-fluid pe-5">
            <div class="row align-items-center">
                <div class="col-md-6 ps-0">
                <div class="w-car">
                    <img src="{{asset('assets/images/w-car.png')}}" alt="w-car" class="img-fluid">
                </div>
                </div>
                <div class="col-md-6 ps-0">
                <div class="Commute-text text-end">
                    <h2 class="fs-40 fw-600">Revolutionizing<br> Commutes for SoCal</h2>
                    <p>Troy Rides combines cutting-edge vehicle technology with the personal touch of skilled drivers to create a superior ride experience. This approach guarantees safety, efficiency, and the agility to handle real-world traffic while offering personalized support along the way. Troy Rides, the home for optimal commutes with a personal touch.</p>
                </div>
                <div class="plus-text text-end mt-4">
                    <div class="row">
                    <div class="col-md-4">
                        <div class="dummy-text text-end">
                        <h2>24</h2>
                        <p>Hours a Day</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dummy-text">
                        <h2>7</h2>
                        <p>Days a Week</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dummy-text  text-end">
                        <h2>365</h2>
                        <p>Days a Year</p>
                    </div>
                    </div>
                </div>
                
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
    <!-------------------- about-sec end here------------------>

    <!-------------------- our-clients-sec start here------------------>
<section>
    <div class="our-clients-sec">
    <div class="container-fluid home-container">
        <div class="row">
        <div class="clients-heading">
            <h2 class="fs-40 fw-600">What Our Clients Say About Us</h2>
        </div>
        <div id="carouselExampleIndicators" class="carousel slide">

            <div class="carousel-indicators mb-4">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <!-- <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button> -->
            </div>
            <!-- <div class="pr-next-btn text-end">
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div> -->
            <div class="carousel-inner">
            <div class="carousel-i">
              <div class="slick-slider">
                    @if($reviews->count()>3)
                        @foreach($reviews as $review)
                            <div class="slide-box-1 text-center first  ">
                                <div class="avtar-img">
                                    @if($review->users->profile_pic)
                                        <img src="{{asset('storage/profile')}}/{{$review->users->profile_pic}}" class="b-radius">
                                    @else
                                        <img src="{{asset('assets/images/avtar.jpg')}}" class="b-radius">
                                    @endif                                
                                </div>
                                <h5 class="mt-2">
                                {{$review->users->first_name}} {{$review->users->last_name}}
                                </h5>
                                <p>Customer</p>
                                <div class="quote-img">
                                    <img src="{{asset('assets/images/quote-line.png')}}">
                                </div>
                                <p  class="fs-14">
                                {{$review->feedback}}
                                
                                </p>
                            </div>
                        @endforeach
                    @else
                     

                        <div class="slide-box-1 text-center first">
                            <div class="avtar-img">
                            <img src="{{asset('assets/icons/Debb.jpg')}}" class="b-radius">
                            </div>
                            <h5 class="mt-2">Debb S</h5>
                            <p>Customer</p>
                            <div class="quote-img">
                                <img src="{{asset('assets/images/quote-line.png')}}">
                            </div>
                            <p  class="fs-14">
                            Very Nice Gentleman, friendly, professional has his Own Business etc.. went and tried his Troy Rides and it was nice (Just like Lyft), but it felt personal! You can pay cash or you can pay with Debit/ Credit cards (I chose cash)
                            I will be using his business again
                            Thanks Troy Rides
                            </p>
                        </div>

                        <div class="slide-box-1 text-center center">
                            <div class="avtar-img">
                            <img src="{{asset('assets/icons/chada.jpg')}}" class="b-radius">
                            </div>
                            <h5 class="mt-2">Chada G</h5>
                            <p>Customer</p>
                            <div class="quote-img">
                                <img src="{{asset('assets/images/quote-line.png')}}">
                            </div>
                            <p class="fs-14">
                            This was one of the best ride-share rides I have had in a long time! Troy himself picked me up in a Tesla last weekend and drove me to Beverly Hills. He was kind, courteous, efficient and above all ..: honest! I unknowingly left my wallet in the car.
                            About 40 minutes later I got a text from Troy letting me know that he had found the wallet. Later that day Troy brought my wallet back to my home!!! WHO DOES THAT??? He came from about 30 miles away to return my wallet! Nothing missing!! l will use Troy rides again!!! You should too!
                            </p>
                        </div>

                        <div class="slide-box-1  text-center right ">
                            <div class="avtar-img">
                            <img src="{{asset('assets/icons/Andrea P.jpeg')}}" class="b-radius">
                            </div>
                            <h5 class="mt-2">Andrea P. RoundTree</h5>
                            <p>Customer</p>
                            <div class="quote-img">
                                <img src="{{asset('assets/images/quote-line.png')}}">
                            </div>
                            <p  class="fs-14">
                            On time pickup, comfortable & safe ride, with a pleasant and skilled driver, all at a great price! This was our first ride but it definitely will not be our last. Thank you, Troy Rides!
                            </p>
                        </div> 

                        <div class="slide-box-1 text-center first">
                            <div class="avtar-img">
                            <img src="{{asset('assets/icons/jone.jpg')}}" class="b-radius">
                            </div>
                            <h5 class="mt-2">iloveelvinjones</h5>
                            <p>Customer</p>
                            <div class="quote-img">
                                <img src="{{asset('assets/images/quote-line.png')}}">
                            </div>
                            <p  class="fs-14">
                            Great service. Ride came exactly as requested. This will be my first choice for rides from now on!
                            </p>
                        </div>

                        <div class="slide-box-1 text-center center">
                            <div class="avtar-img">
                            <img src="{{asset('assets/images/avtar.jpg')}}" class="b-radius">
                            </div>
                            <h5 class="mt-2">Tracey P</h5>
                            <p>Customer</p>
                            <div class="quote-img">
                                <img src="{{asset('assets/images/quote-line.png')}}">
                            </div>
                            <p class="fs-14">
                              Deone’s ride was amazing. He was friendly, courteous, opened the door for us, made sure we were comfortable, told us to have a good day! I LOVE that! We had a great experience!!!! Just wanted to share.
                            </p>
                        </div>

                    @endif
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
    <div class="fb text-center py-3 bcm-drive " id="driver-link">
        <a href="{{route('request-ride')}}" class="nav-link d-inline-block  bg-black btn btn-book text-white py-2 px-5">Book Ride</a>
    </div>
</section>
    <!-------------------- our-clients-sec end here------------------>

</main>
@endsection

@section('scripts')
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.slick/1.4.1/slick.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/luxon@3/build/global/luxon.min.js"></script>

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
        required: true
    },
    total_passenger:{
        required: true,
        number: true,
        min: 1,
        max: 4
    },
    user_phone_number: {
        required: true,
        minlength: 10,
    },
    dropoff_location:{
        required: true
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
    user_phone_number: {
        required: "Phone number is required.",
    },
  },
});

// Restrict phone number input to numbers only
$('#user_phone_number').on('input', function() {
    this.value = this.value.replace(/[^0-9+\-]/g, '');
});
</script>

<script type="text/javascript">
$(".slick-slider").slick({
    slidesToShow: 3,
    infinite: false,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    dots: true, // Set to true if you want dots
    arrows: false, // Set to true if you want navigation arrows
    responsive: [
        {
            breakpoint: 1024, // Adjust settings for screen widths below 1024px
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
               
            }
        },
        {
            breakpoint: 768, // Adjust settings for screen widths below 768px
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
               
            }
        }
    ]
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

    const { DateTime } = luxon;

    flatpickr("#date_time", {
        enableTime: true,
        dateFormat: "Y-m-d h:i K",
        minuteIncrement: 15,
        minDate: "today",  
    });

    // $(document).ready(function () {
    //     // Initialize the datetime picker
    //     $('#date_time').datetimepicker({
    //         format: 'Y-m-d H:i',
    //         step: 15,            
    //         minDate: 0,    
    //         onShow: function (currentDateTime) {
    //             let currentDate = new Date(); 
    //             this.setOptions({
    //                 minDate: 0,
    //                 minTime: currentDate.getDate() === currentDateTime.getDate() 
    //                     ? currentDate.getHours() + ':' + (currentDate.getMinutes()) 
    //                     : false 
    //             });
    //         },
    //         // Additional option to use AM/PM
    //         // formatTime: 'g:i A' // Ensure time is displayed in AM/PM format
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