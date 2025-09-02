@extends('Frontend.layouts.master')
@section('title', 'OTP Verification')
@section('content')
<div class=" sign-up-verification login-page">
    <div class="container-fluid ps-5">
        <div class="row ">
            <div class="col-md-5">
                <div class="otp-form login-form ">
                    <div class="login-logo">
                        <img src="{{asset('assets/images/login-logo.png')}}" alt="logo">
                    </div>
                    <div class="login-content mt-4 mb-3">
                        <h1 class="color-23">OTP Verification</h1>
                        <p class="color-96">Enter the verification code we just sent on your email address</p>
                    </div>
                    <form action="{{ Request::segment(1) === 'driver' ? route('driver-otp-verification') : route('otp-verification')}}" method="post">
                        @csrf
                        <div class="login-input mt-4">                            
                                <div class="d-flex justify-content-between">
                                    <input type="text" name="otp1" maxlength="1" class="form-control otp-input @error('otp1') is-invalid @enderror" id="otp1" oninput="moveFocus(1)" value="{{ old('otp1') }}">
                                    <input type="text" name="otp2" maxlength="1" class="form-control otp-input @error('otp2') is-invalid @enderror" id="otp2" oninput="moveFocus(2)" value="{{ old('otp2') }}">
                                    <input type="text" name="otp3" maxlength="1" class="form-control otp-input @error('otp3') is-invalid @enderror" id="otp3" oninput="moveFocus(3)" value="{{ old('otp3') }}">
                                    <input type="text" name="otp4" maxlength="1" class="form-control otp-input @error('otp4') is-invalid @enderror" id="otp4" oninput="moveFocus(4)" value="{{ old('otp4') }}">
                                </div>
                                @foreach (['otp1', 'otp2', 'otp3', 'otp4'] as $field)
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @endforeach
                            <div class="submit-btn mt-5">
                                <button class="btn btn-book w-100 py-2  fw-500 bg-black text-white px-5 rounded-10" type="submit">Submit</button>
                            </div>
                            <div class="resend-login d-flex justify-content-center gap-1 mt-4">
                                <p class="color-96">Didn’t received code? </p>
                                <div class="creat">
                                    <a href="{{ Request::segment(1) === 'driver' ? route('driver-resend-otp') : route('resend-otp')}}" class="color-23 fw-600">Resend</a>
                                </div>
                            </div>
                        </div></form>
                    
                </div>
            </div>
            <div class="col-md-7 pe-0">
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
    function moveFocus(index) {
        if (index < 4) {
            document.getElementById('otp' + (index + 1)).focus();
        }
    }
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>
@endsection