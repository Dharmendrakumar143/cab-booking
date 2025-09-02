@extends('Frontend.layouts.master')
@section('title', 'Tips')
@section('content')
<main>
    <div class="u-payment-for-cotent d-flex align-items-center justify-content-center mx-lg-0 mx-3">
        <div class="container-fluid my-3 px-lg-5 px-2">
            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-6 mx-auto">
            <div class="alert alert-success text-center py-sm-3 px-sm-3 py-2 px-2 my-2">
                <img src="{{ asset('assets/images/success.gif') }}">    
                <h2>Thank you for your generous tip!</h2>
                <p>Your tip has been successfully processed and sent to {{ $driver->name }}.</p>
                <p>We appreciate your support!</p>
                <a href="{{ route('home') }}" class="btn btn-primary fw-500 bg-black text-white mt-3">Return to Homepage</a>
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