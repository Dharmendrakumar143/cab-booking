@extends('Frontend.layouts.master')
@section('title', 'OTP Verification')
<style type="text/css">
    @media(min-width: 1024px) and (max-width: 1400px){
    .back-to-login-btn a.btn.btn-book {
    border-radius: 14px;
    padding: 10px 30px !important;
}
}

</style>
@section('content')
<div class="login-page">
    <div class="container-fluid ps-lg-5 ps-2">
        <div class="row ">
            <div class="col-md-5 col-12">
                <div class="otp-verified form login-form d-flex gap-3">
                    <div class="verifed-mox">
                        <div class="login-logo">
                            <img src="{{asset('assets/images/login-logo.png')}}" alt="logo">
                        </div>
                        <div class="login-content mt-4 mb-3">
                            <h1 class="color-23">OTP Verified</h1>
                            <p class="color-96">Your OTP has been verified.</p>
                        </div>
                        <div class="back-to-login-btn mt-5">
                            <a href="{{ Request::segment(1) === 'driver' ? route('driver-login') : route('login')}}" class="btn btn-book w-100 py-2  fw-500 bg-black text-white px-lg-5 rounded-10" type="submit">Back to Log in</a>
                        </div>
                    </div>
                    <div class="verified-img">
                        <img src="{{asset('assets/images/verified.png')}}" class="img-fluid h-100 object-fit-cover">
                    </div>

                </div>
            </div>
            <div class="col-md-7 col-12 pe-0">
                <div class="login-img h-100">
                    <img src="{{asset('assets/images/circle-img.png')}}" class="img-fluid h-100 object-fit-cover ">
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

<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>
@endsection