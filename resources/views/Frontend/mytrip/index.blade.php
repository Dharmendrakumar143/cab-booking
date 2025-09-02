@extends('Frontend.layouts.master')
@section('title', 'My Trips')
@section('content')
<main>
    <div class="my-trip-for-cotent mb-5 pb-5">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="r-heading mt-lg-5 mt-5 mb-3">
                        <h2 class=" fw-600">My Trips </h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="past-tab-nav-menu">
                        <ul class="nav nav-pills mb-3 gap-2" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Past</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false" tabindex="-1">Cancelled</button>
                            </li>
                            
                                
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade active show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                
                                @foreach($my_rides as $my_ride) 
                                    <div class="col-lg-12">
                                        <div class="my-trip-details ride-detail-cancle rounded-10 my-3">
                                            <div class="book-your-ride p-4 ">
                                                <div class="r-d-flex d-flex justify-content-between flex-wrap">
                                                <div class="bs-heading mb-lg-0 mb-4">
                                                    <h5 class="fw-600 mb-0">Ride Details</h5>
                                                    <!-- <p class="fw-600 fs-14 ">09 May 2024, 12:15 </p> -->
                                                    <p class="fw-600 fs-14 ">{{ date('d M Y', strtotime($my_ride->pick_up_date)) }}, {{$my_ride->pick_up_time}}</p>
                                                    <p class="fw-400 fs-14 ">Passengers: <span class="ps-2">{{$my_ride->total_passenger}}</span> </p>
                                                    <p class="fw-400 fs-14 ">Ride Status: <span class="ps-2">{{$my_ride->booking->booking_status}}</span> </p>
                                                </div>
                                                <div class="price-d-text d-flex justify-content-between align-items-center">
                                                    <div class="p-d-modal">
                                                        <p>Price</p>
                                                    </div>
                                                    <div class="p-d-text"> 
                                                        <p class="fw-600"><span class="pe-3 "></span>: ${{$my_ride->booking->subtotal}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="row ">
                                                    <div class="col-md-7 col-12">
                                                        <div class="pick-drop-line border-orange d-flex justify-content-between mt-4">
                                                            <img src="{{asset('assets/images/location.png')}}" class="loc-icon">
                                                            <img src="{{asset('assets/images/drop.png')}}" class="drop-icon">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-6">
                                                            <div class="syndy-text d-flex flex-wrap justify-content-between align-items-center">
                                                                <p class="fw-400">{{$my_ride->pick_up_address}}</p>
                                                             
                                                            </div>
                                                        </div>
                                                         <div class="col-md-6">
                                                               <div class="edit-text">
                                                                    <p class=" fw-400">{{$my_ride->drop_off_address}}</p>
                                                                </div>
                                                           
                                                        </div>
                                                    </div>
                                                     <div class="view-btn mt-3">
                                                                <a href="{{route('view-trip-details',['ride_id'=>$my_ride->id])}}" class="btn bg-black px-5 text-white  rounded-10 py-2">view</a>
                                                            </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                    
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                @foreach($my_cancelled_rides as $cancelled_ride) 
                                    <div class="col-lg-12">
                                        <div class="my-trip-details ride-detail-cancle rounded-10 my-3">
                                            <div class="book-your-ride p-4 ">
                                                <div class="r-d-flex d-flex justify-content-between flex-wrap">
                                                <div class="bs-heading mb-lg-0 mb-4">
                                                    <h5 class="fw-600 mb-0">Ride Details</h5>
                                                    <!-- <p class="fw-600 fs-14 ">09 May 2024, 12:15 </p> -->
                                                    <p class="fw-600 fs-14 ">{{ date('d M Y', strtotime($cancelled_ride->pick_up_date)) }}, {{$cancelled_ride->pick_up_time}}</p>
                                                    <p class="fw-400 fs-14 ">Passengers: <span class="ps-2">{{$cancelled_ride->total_passenger}}</span> </p>
                                                    <p class="fw-400 fs-14 ">Ride Status: <span class="ps-2">{{$cancelled_ride->booking->booking_status}}</span> </p>
                                                </div>
                                                <div class="price-d-text d-flex justify-content-between align-items-center">
                                                    <div class="p-d-modal">
                                                        <p>Price</p>
                                                    </div>
                                                    <div class="p-d-text">
                                                        <p class="fw-600"><span class="pe-3 "></span>: ${{$cancelled_ride->booking->subtotal}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                                <div class="row ">
                                                    <div class="col-md-6 col-12">
                                                        <div class="pick-drop-line border-orange d-flex justify-content-between mt-4">
                                                            <img src="{{asset('assets/images/location.png')}}" class="loc-icon">
                                                            <img src="{{asset('assets/images/drop.png')}}" class="drop-icon">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-12 pe-lg-0">
                                                            <div class="syndy-text d-flex flex-wrap justify-content-between align-items-center">
                                                                <p class="fw-400">{{$cancelled_ride->pick_up_address}}</p>
                                                                <div class="edit-text">
                                                                    <p class=" fw-400">{{$cancelled_ride->drop_off_address}}</p>
                                                                </div>
                                                            </div>
                                                            <div class="view-btn mt-3">
                                                                <a href="{{route('view-trip-details',['ride_id'=>$cancelled_ride->id])}}" class="btn bg-black px-5 text-white  rounded-10 py-2">view</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>
    $(document).ready(function () {
        localStorage.removeItem('modal_shown');
        localStorage.removeItem('booking_in_progress');
    })

    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>
@endsection