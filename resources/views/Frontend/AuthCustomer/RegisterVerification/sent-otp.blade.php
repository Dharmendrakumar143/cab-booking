@extends('Frontend.layouts.master')
@section('title', 'OTP Verification')
@section('content')
<div class="sign-up-otp login-page">
    <div class="container-fluid ps-5">
        <div class="row ">
            <div class="col-md-5">
                <div class="otp-form login-form ">
                    <div class="login-logo">
                        <img src="{{asset('assets/images/login-logo.png')}}" alt="logo">
                    </div>
                    <div class="login-content mt-4 mb-3">
                        <h1 class="color-23">OTP Verification</h1>
                        <p class="color-96">Please enter the email address linked with your account.</p>
                    </div>
                    <form class="form-md" action="{{ Request::segment(1) === 'driver' ? route('driver-send-otp') : route('send-otp')}}" method="POST">
                        @csrf
                          <div class="login-input mt-4">
                        <div class="email mb-3">
                          
                            <input type="email" name="email" placeholder="" class="form-control rounded-10 @error('email') is-invalid @enderror" id="exampleFormControlInput1" value="{{ old('email') }}">
                              <label for="exampleFormControlInput1" class="form-label e-label fw-500">Email
                                </label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="submit-btn mt-5">
                            <button class="btn btn-book w-100 py-2  fw-500 bg-black text-white px-5 rounded-10" type="submit">Submit</button>
                        </div>
                    </div>
                    </form>
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
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>
@endsection