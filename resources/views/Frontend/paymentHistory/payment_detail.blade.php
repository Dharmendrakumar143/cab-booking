@extends('Frontend.layouts.master')
@section('title', 'Payment History Details')
@section('content')
<main>
    <div class="u-s-payment-for-cotent mb-3 pb-5 mx-lg-0 mx-3">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
                <div class="u-s-payment-info">
                    <div class="passe-heading mb-3 mt-4 ">
                        <h3 class="color-20 fw-600">Payment History Details</h3>
                    </div>
                </div>
                <div class="user-payment-histroy-box">
                        <div class="payment-success-box">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="row gy-lg-0 gy-3">
                                    <div class="col-md-5">
                                        <div class="payment-fare-details">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="bg-black rounded-20 pt-4">
                                                    <div class="fr-detls gray-bg rounded-20 p-4">
                                                        <div class="fare-header d-flex justify-content-between flex-wrap mb-3">
                                                            <h4>Fare Details</h4>
                                                            <div class="t-xl">
                                                                <a href="#" class="xl-btn orange-bg px-lg-5 px-2 py-2 text-black rounded-30">Troy XL</a>
                                                            </div>
                                                        </div>
                                                        <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                            <p>Base Fare</p> <p class="fw-500"><span class="pe-3">:</span> ${{$payment_detail->booking->ride_booking_amount}}</p>
                                                        </div>
                                                        <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                            <p>Time and distance charges</p> <p class="fw-500"><span class="pe-3">:</span> ${{config('global-constant.ride_per_km_charge.rate_per_km')}}/Miles + {{$payment_detail->booking->miles_distance}} Miles</p>
                                                        </div>
                                                        <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                            <p>Surge pricing (if applicable)</p> <p class="fw-500"><span class="pe-3">:</span> ${{isset($payment_detail->booking->surge_amount)?$payment_detail->booking->surge_amount:'00.00'}}</p>
                                                        </div>
                                                        <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                            <p>Tolls or fees</p> <p class="fw-500"><span class="pe-3">:</span> $00.00</p>
                                                        </div>
                                                        <div class="total-fee base-fare-box  d-flex justify-content-between rounded-8 p-2 mb-3">
                                                            <p class="fs-20">Total fare</p> <p class="fw-500 orange fs-24"> ${{$payment_detail->booking->final_booking_amount ?? '00.00'}}</p>
                                                        </div>
                                                        <div class="pay-fee base-fare-box bg-light-gray  d-flex flex-lg-nowrap flex-wrap justify-content-between rounded-8 p-4 mb-3">
                                                            <div class="pay-ment">
                                                                <p class="">Payment method</p>
                                                                <p class="fs-14 fw-600">{{$payment_detail->booking->payment_method}}</p>
                                                            </div>
                                                                
                                                        </div>
                                                        <div class="success-btn">
                                                            <button type="button" class="btn bg-28 color-28 w-100  rounded-10 fw-600">{{$payment_detail->paymentStatus->status}}</button>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    
                                    <div class="book-bg h-auto">
                                        <div class="drver-details text-white">
                                            <h4 class="border-bottom ">Driver Details</h4>
                                            <div class="driver-text-avtar d-flex pt-3 justify-content-between align-items-center">
                                            
                                            <div class="bt-modal">
                                                <div class="d-name">
                                                    <h5 class="m-0 fs-16">{{$payment_detail->booking->admin->name ?? null}}</h5>
                                                </div>
                                            <div class="modal-flex d-flex gap-2">
                                            <div class="car-modal">
                                                
                                                <p>Car Year, Make and Model</p>
                                                <p>License Plate</p>
                                            </div>
                                            <div class="l-plate">
                                                <p class="fw-500 fs-14"><span class="pe-3">:</span> {{$payment_detail->booking->admin->car_model ?? null}}</p>
                                                <p class="fw-500 fs-14"><span class="pe-3">:</span> {{$payment_detail->booking->admin->car_number_plate ?? null}}</p>
                                            </div>
                                        </div>
                                        <div class="dcm-btn d-flex gap-1 mt-3">
                                            @if (!empty($payment_detail->booking?->admin?->phone_number))
                                                <a href="tel:{{$payment_detail->booking->admin->phone_number}}">
                                                    <img src="{{asset('assets/images/dcall.png')}}" alt="Call Admin">
                                                </a>
                                            @else
                                                <img src="{{asset('assets/images/dcall.png')}}" alt="Call Admin">
                                            @endif
                                            <!-- <img src="{{asset('assets/images/dmsg.png')}}"> -->
                                        </div>
                                    </div>
                                    <div class="avtar-rate text-center">
                                        @if (!empty($payment_detail->booking?->admin?->profile_pic))
                                            <img src="{{asset('storage/profile')}}/{{$payment_detail->booking->admin->profile_pic}}">
                                        @else
                                            <img src="{{asset('assets/images/d-avtar.png')}}">
                                        @endif
                                        <p class="mt-2">{{isset($payment_detail->booking->admin) ? $driver_completed_ride_count : 0}} Rides</p>
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
                                <div class="ride-detail-cancle rounded-10 my-3">
                                    <div class="book-your-ride p-4 ">
                                        <div class="r-d-flex d-flex justify-content-between flex-wrap">
                                        <div class="bs-heading mb-lg-0 mb-4">
                                            <h5 class="fw-600 mb-0">Ride Details</h5>
                                            <p class="fw-600 fs-14 ">{{ date('d M Y', strtotime($payment_detail->pick_up_date)) }} {{$payment_detail->pick_up_time}}</p>
                                            <p class="fw-400 fs-14 ">Passengers: <span class="ps-2">{{$payment_detail->total_passenger}}</span> </p>
                                        </div>
                                    </div>
                                        <div class="sydney-box">
                                            <div class="loca-box pb-4">
                                                <img src="{{asset('assets/images/location.png')}}"><span>{{$payment_detail->pick_up_address}}</span>
                                            </div>
                                            <div class="drop-box">
                                                <img src="{{asset('assets/images/drop.png')}}"><span>{{$payment_detail->drop_off_address}}</span>
                                            </div>
                                        <div class="price-d-text d-flex gap-4 mt-3">
                                            <div class="p-d-modal">
                                                <p>Duration</p>
                                            </div>
                                            <div class="p-d-text pb-5">
                                                <p class="fw-500"><span class="pe-3">:</span> {{$payment_detail->booking->duration}}</p>
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
        </div>
    </div>
</main>
@endsection

@section('scripts')
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
    $(document).ready(function () {
        var selectedDates = []; // Array to hold selected dates

        // Initialize the datepicker with multiple date selection
        $('#payment_date_picker').datepicker({
            dateFormat: 'yy-mm-dd',  // Date format
        });
    });
</script>

<script>
    $(document).ready(function(){
        $("select#sortby").change(function(){
            var search_by = $(this).val();
            paymentHistoryFilter(search_by);
        })

        $("input#payment_date_picker").on('change', function() {
            var search_by = $(this).val();
            paymentHistoryFilter(search_by);
        });

        function paymentHistoryFilter(search_by){
            $.ajax({
                method: 'POST',
                url: '{{route("payment-filter")}}',
                data: {
                    _token: "{{ csrf_token() }}", 
                    search_by: search_by
                },
                success: function (response) {
                    // console.log("Response received:", response);
                    $('#payment_history_filter').html(response);
                },
                error: function (xhr, status, error) {
                    console.error('An error occurred:', error); // Log the error for debugging
                }
            });
        }
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


@endsection