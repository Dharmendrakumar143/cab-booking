@extends('Frontend.layouts.master')
@section('title', 'Login')
@section('content')
<div class="login-page admin-side">
    <div class="container-fluid ps-md-5 ps-2">
        <div class="row ">
            <div class="col-md-5 col-12">
                <div class="login-form web-page ">
                    <div class="login-logo">
                        <a href="{{route('home')}}">
                            <img src="{{asset('assets/images/login-logo.png')}}" alt="logo">
                        </a>
                    </div>
                    <div class="login-content mt-4 mb-3">
                        @if(Request::segment(1) == 'employee')
                            <h1 class="color-23">Employee Login</h1>
                            <p class="color-96">Please login to continue to your employee account.</p>
                        @else
                            <h1 class="color-23">Log in</h1>
                            <p class="color-96">Please login to continue to your account.</p>
                        @endif
                    </div>
                    <form class="form-md" action="{{ Request::segment(1) === 'driver' ? route('driver-login') : (Request::segment(1) === 'employee' ? route('employee-login') : route('login')) }}" method="post" id="user_login">
                        @csrf
                        <div class="login-input mt-4">
                            <div class="email mb-3">    
                             <label for="exampleFormControlInput1" class="form-label es-label fw-500">Email</label>                   
                                <input type="email" name="email" class="form-control rounded-10 @error('email') is-invalid @enderror" placeholder="" id="email" value="{{ old('email') }}">
                               
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="password">
                                <div class="pass-input">
                                      <label for="" class="form-label es-label fw-500">Password</label>
                                    <input type="password" name="password" id="password" placeholder="" class="form-control rounded-10 @error('password') is-invalid @enderror" aria-describedby="passwordHelpBlock" value="{{ old('password') }}">
                                    
                                    <div class="pas-eye-icon" id="togglePassword" style="cursor: pointer;">
                                        <img src="{{asset('assets/images/p-eye.png')}}" alt="eye" id="eyeIcon">
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                
                                @if(Request::segment(1) != 'employee')
                                <div class="forget-pas">
                                    <a href="{{ Request::segment(1) === 'driver' ? route('driver-forgot-password') : route('forgot-password')}}" class="orange fw-500 text-decoration-none">Forgot Password?</a>
                                </div>
                                @endif
                                <div class="keep-me form-check">
                                    <input class="form-check-input rounded-10" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label fw-500" for="flexCheckDefault">
                                        Keep me logged in
                                    </label>
                                </div>
                            </div>
                            <div class="login-btn mt-4">
                                <button class="btn btn-book w-100 py-2  fw-500 bg-black text-white px-5 rounded-10" type="submit">Log in</button>
                            </div>

                            @if(Request::segment(1) != 'employee')
                                <div class="or-text text-center my-3">
                                    <p class="orange fw-500">Or</p>
                                </div>
                                <div class="login-google bg-white rounded-10 p-2 text-center">
                                    <a href="{{ Request::segment(1) === 'driver' ? route('driver-login-google') : route('login-google')}}" class="text-black text-decoration-none color-23">
                                        <h5 class="fw-600">Log in with Google <span class="ps-2"><img src="{{asset('assets/images/google.png')}}"></span></h5>
                                    </a>
                                </div>
                                <div class="need-login d-flex justify-content-center gap-1 mt-4">
                                    <p class="color-96">Need an account?</p>
                                    <div class="creat">
                                        <a href="{{ Request::segment(1) === 'driver' ? route('driver-register') : route('register')}}" class="color-23 fw-600 text-decoration-underline">Create one</a>
                                    </div>
                                </div>
                                <div class="need-login d-flex justify-content-center gap-1 mt-2">
                                    <div class="creat">
                                        <a href="{{ Request::segment(1) === 'driver' ? route('driver-sent-otp') : route('sent-otp')}}" class="color-23 fw-400 text-decoration-underline">Didn't receive your verification email?</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-7 col-12 pe-0">
                <div class="login-img h-100">
                    <img src="{{asset('assets/images/circle-img.png')}}" class="img-fluid w-100 h-100 object-fit-cover ">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>
    $("#user_login").validate({
        rules: {
            email: {
                required: true,
            },
            password: {
                required: true,
            },
        },
        messages: {
            email: {
                required: "Email is required.",
            },
            password: {
                required: "Password is required.",
            }
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the eye icon (change the image if necessary)
            if (type === 'text') {
                eyeIcon.src = '{{asset("assets/icons/eye-open.png")}}'; // Update to your "eye-slash" icon path
            } else {
                eyeIcon.src = '{{asset("assets/images/p-eye.png")}}'; // Update to your "eye" icon path
            }
        });
    });

</script>
@endsection