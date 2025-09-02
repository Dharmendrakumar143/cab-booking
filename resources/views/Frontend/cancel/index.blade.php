@extends('Frontend.layouts.master')
@section('title', 'Cancel Rides')
@section('content')
<main>
    <div class=" cancle-ride-page ride-details mb-5 pb-5">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="r-heading mt-lg-3 mt-5 mb-5">
                        <h1 class="fs-50 fw-600">Cancel Ride </h1>
                    </div>
                </div>
                <div class="bookride-for-contnet ">
                    
                    <div class="row">
                        <div class="col-md-7">
                            <!-- <div class="ride-detail-cancle rounded-10 ">
                                <div class="book-your-ride p-4 ">
                                    <div class="r-d-flex d-flex justify-content-between">
                                    <div class="bs-heading mb-lg-0 mb-4">
                                        <h5 class="fw-600 mb-0">Ride Details</h5>
                                        <p class="fw-600 fs-14 ">09 May 2024, 12:15 </p>
                                        <p class="fw-400 fs-14 ">Passengers: <span class="ps-2">03</span> </p>
                                    </div>
                                    <div class="price-d-text d-flex justify-content-between">
                                        <div class="p-d-modal">
                                            <p>Price</p>
                                            <p>Duration</p>
                                        </div>
                                        <div class="p-d-text">
                                            <p class="fw-600"><span class="pe-3 ">:</span> $54</p>
                                            <p class="fw-500"><span class="pe-3">:</span> 30 min</p>
                                        </div>
                                    </div>
                                </div>
                                    <div class="row ">
                                        <div class="col-md-4 col-12">
                                            <div class="pick-drop-line border-orange d-flex justify-content-between mt-4">
                                                <img src="images/location.png" class="loc-icon">
                                                <img src="images/drop.png" class="drop-icon">
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12 pe-lg-0">
                                                <div class="syndy-text d-flex gap-5 align-items-center">
                                                    <p class="fw-400">Sydney NSW, Australia</p>
                                                    <div class="edit-text">
                                                        <p class=" fw-400">Sydney Olympic Park NSW,
                                                            Australia</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="select-reason-cancle mt-3">
                                <div class="sr-heading">
                                    <h4>Please select reason to cancel ride:</h4>
                                </div>
                                <form action="{{route('cancel-ride')}}" method="post" id="cancellationForm">
                                    @csrf
                                    <div class="reason-box">
                                        <div class="cride-input">
                                            @foreach($cancellation_reasons as $cancellation_reason)
                                                @if($cancellation_reason->reason != 'Other reason')
                                                    <div class="form-check bg-color-d7 rounded-5p mb-3">
                                                        <input class="form-check-input" type="radio" name="cancellation_reason" id="exampleRadios{{$cancellation_reason->id}}" value="{{$cancellation_reason->id}}" checked>
                                                        <label class="form-check-label" for="exampleRadios{{$cancellation_reason->id}}">
                                                            {{$cancellation_reason->reason}}
                                                        </label>
                                                    </div>
                                                @endif
                                                @if($cancellation_reason->reason == 'Other reason')
                                                    <div class="other-reason-accordian mb-3">
                                                        <div class="accordion" id="accordionExample" bis_skin_checked="1">
                                                            <div class="accordion-item" bis_skin_checked="1">
                                                                <h2 class="accordion-header bg-color-d7 rounded-5p">
                                                                    <button class="accordion-button rounded-10 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOtherReason" aria-expanded="false" aria-controls="collapseOtherReason">
                                                                    <input class="form-check-input" type="radio" name="cancellation_reason" id="exampleRadios{{$cancellation_reason->id}}" value="{{$cancellation_reason->id}}">
                                                                    <label class="form-check-label" for="exampleRadios{{$cancellation_reason->id}}">
                                                                        {{$cancellation_reason->reason}}
                                                                    </label>
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseOtherReason" class="accordion-collapse collapse" data-bs-parent="#accordionExample" bis_skin_checked="1">
                                                                    <div class="accordion-body p-0 mt-3" bis_skin_checked="1">
                                                                        <div class="mb-3" bis_skin_checked="1">
                                                                            <textarea class="form-control bg-color-d7 fs-14" id="otherReasonTextarea" name="otherReasonTextarea" rows="3" placeholder="Please write your reason for cancelling the ride here..."></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach    
                                            <div class="other-reason-accordian mb-3">
                                                <div class="yes-im">
                                                    <p class="mt-3">Are you sure you want to cancel this ride? Cancellation may incur a fee if it’s after the driver has been assigned. Please check our cancellation policy for details.</p>
                                                    <div class="form-check gap-2">
                                                        <input class="form-check-input" type="checkbox" value="1" id="mark_cancel" name="mark_cancel" require>
                                                        <label class="form-check-label fs-14" for="mark_cancel">
                                                            Yes I am sure
                                                        </label>
                                                        </div>
                                                        <div class="c-next-pay-btn mt-4">
                                                        <button type="submit" class="btn bg-black text-white w-25 rounded-10 py-2">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- <div class="col-md-5">
                            <div class="book-bg h-auto rounded-10">
                                <div class="drver-details text-white">
                                    <h4 class="border-bottom ">Driver Details</h4>
                                    <div class="driver-text-avtar d-flex pt-3 justify-content-between align-items-center">
                                    
                                    <div class="bt-modal">
                                    <div class="modal-flex d-flex gap-5">
                                    <div class="car-modal">
                                        <div class="d-name">
                                            <h5 class="m-0">Allan Smith</h5>
                                        </div>
                                        <p>Car Year</p>
                                        <p>License Plate</p>
                                    </div>
                                    <div class="l-plate">
                                        <p class="fw-500"><span class="pe-3">:</span> 2020 Toyota Camry</p>
                                        <p class="fw-500"><span class="pe-3">:</span> XYZ 1234</p>
                                    </div>
                                </div>
                                <div class="dcm-btn d-flex gap-1 mt-3">
                                    <img src="images/dcall.png">
                                    <img src="images/dmsg.png">
                                </div>
                            </div>
                                <div class="avtar-rate text-center">
                                    <img src="images/d-avtar.png">
                                    <p class="mt-2">124 Rides</p>
                                    <img src="images/dr-start.png"><span class="fs-12 ps-2">4.1</span>
                                </div>
                            </div>
                                </div>
                            </div>
                            <div class="fare-details mt-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="bg-black rounded-20 pt-4">
                                            <div class="fr-detls gray-bg rounded-20 p-4">
                                                <div class="fare-header d-flex justify-content-between mb-3">
                                                    <h4>Fare Details</h4>
                                                    <div class="t-xl">
                                                        <a href="#" class="xl-btn orange-bg px-5 py-2 text-black rounded-30">Troy XL</a>
                                                    </div>
                                                </div>
                                                <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                    <p>Base Fare</p> <p class="fw-500"><span class="pe-3">:</span> $ 50.00</p>
                                                </div>
                                                <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                    <p>Time and distance charges</p> <p class="fw-500"><span class="pe-3">:</span> $ 05.00</p>
                                                </div>
                                                <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                    <p>Surge pricing (if applicable)</p> <p class="fw-500"><span class="pe-3">:</span> $ 00.05</p>
                                                </div>
                                                <div class="base-fare-box bg-light-gray d-flex justify-content-between rounded-8 p-2 mb-3">
                                                    <p>Tolls or fees</p> <p class="fw-500"><span class="pe-3">:</span> $ 00.05</p>
                                                </div>
                                                <div class="total-fee base-fare-box  d-flex justify-content-between rounded-8 p-2 mb-3">
                                                    <p class="fs-20">Total fare</p> <p class="fw-500 orange fs-24"> $ 55.10</p>
                                                </div>
                                                <div class="pay-fee base-fare-box bg-light-gray  d-flex justify-content-between rounded-8 p-4 mb-3">
                                                    <div class="pay-ment">
                                                        <p class="">Payment method</p>
                                                        <p class="fs-14 fw-600">Cash on Delivery</p>
                                                    </div>
                                                    <div class="promo-code text-end">
                                                        <p class="">Promo codes/Discounts</p>
                                                        <p class="fw-600  fs-14 "> GOODTOGO</p>
                                                    </div>
                                                        
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
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
    $(document).ready(function() {
        // Initially hide the "Other reason" textarea if it's not selected
        $('#otherReasonSection').hide();

        // When a radio button is clicked
        $('input[name="cancellation_reason"]').on('change', function() {
            var selectedValue = $(this).val();
            // If "Other reason" is selected
            if (selectedValue == '7') {
                $('div#collapseOtherReason').addClass('show');  // Show the textarea
            } else {

                $('div#collapseOtherReason').removeClass('show');  // Hide the textarea
            }
        });
    });

    $('#cancellationForm').validate({
        rules: {
            otherReasonTextarea: {
                required: true,
            },
            mark_cancel: {
                required: true,
            },
        },
        messages: {
            otherReasonTextarea: {
                required: 'Please provide a reason for cancelling the ride.',
            },
            mark_cancel: {
                required: 'Please confirm your cancellation by checking this box.',
            },
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