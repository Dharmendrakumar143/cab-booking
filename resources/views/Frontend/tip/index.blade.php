@extends('Frontend.layouts.master')
@section('title', 'Tips')
@section('content')
<main>
    <div class="u-payment-for-cotent d-flex align-items-center justify-content-center mx-lg-0 mx-3">
        <div class="container-fluid my-4 px-lg-5 px-2">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-8 col-12 mx-auto ">
                    <div class="pay-tip my-3">
                        <div class="u-payment-info">
                            <div class="passe-heading mb-3 mt-2 ">
                                <h3 class="color-20 fw-600">Customer Tip</h3>
                            </div>
                        </div>
                        <form action="{{ route('create-tip', ['bookingId' => $booking_id]) }}" class="text-start w-75 mx-auto" method="POST">
                            @csrf
                            <label for="tip_amount" class="form-label fw-medium">Tip Amount (USD)</label>
                            <input type="number" name="tip_amount" min="1" class="tip-input form-control" placeholder="Enter Tip Amount" required>
                            <div class="text-center mb-3">
                                <button type="submit" class="tip-btn">Pay Tip</button>
                            </div>
                        </form>
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


@endsection