@extends('Frontend.layouts.master')
@section('title', 'Tips')
@section('content')
<main>
    <div class="u-payment-for-cotent d-flex align-items-center justify-content-center mx-lg-0 mx-3">
        <div class="container-fluid my-3 px-lg-5 px-2">
            <div class="row">
                <div class="col-xxl-4 col-lg-5 col-md-6 mx-auto">
                    <div class="alert alert-danger text-center py-sm-4 px-sm-2 py-2 px-2  my-2">
                        <img src="{{ asset('assets/images/cancel.png') }}" class="img-fluid mb-3" width="100">
                        <h2>Payment Cancelled</h2>
                        <p>It seems like you have cancelled the payment for the tip.</p>
                        <p>If you have any questions or need assistance, feel free to reach out to us.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary  fw-500 bg-black text-white mt-3 ">Return to Homepage</a>
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