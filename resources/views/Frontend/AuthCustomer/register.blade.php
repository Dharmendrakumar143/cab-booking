@extends('Frontend.layouts.master')
@section('title', 'Sign up')
@section('content')
<div class="sign-up admin-side login-page">
    <div class="container-fluid ps-5">
        <div class="row ">
            <div class="col-md-5 col-12">
                <div class="sign-up login-form web-page ">
                    <div class="login-logo">
                    <a href="{{route('home')}}">
                        <img src="{{asset('assets/images/login-logo.png')}}" alt="logo">
                    </a>
                    </div>
                    <div class=" sign-up-content login-content mt-4 mb-3">
                        <h1 class="color-23">Sign up</h1>
                        <p class="color-96">Sign up to enjoy the feature of Troy Rides</p>
                    </div>
                    <form class="form-md" action="{{ Request::segment(1) === 'driver' ? route('driver-register') : route('register') }}" method="POST" id="register_user">  
                    @csrf    
                    <div class="sign-up login-input mt-4">
                        
                            <div class="name mb-3">
                              <label for="exampleFormControlInput1" class="form-label es-label fw-500">First Name</label>
                                <input type="text" name="first_name" class="form-control rounded-10 @error('first_name') is-invalid @enderror" id="first_name" placeholder="Jonas Khanwald"  value="{{ old('first_name') }}">
                                  
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="name mb-3">
                            <label for="exampleFormControlInput1" class="form-label es-label fw-500">Last Name</label>
                                <input type="text" name="last_name" class="form-control rounded-10 @error('last_name') is-invalid @enderror" id="last_name" placeholder="Jonas Khanwald" value="{{ old('last_name') }}">
                                
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="email mb-3">
                               <label for="exampleFormControlInput1" class="form-label es-label fw-500">Email</label>
                                <input type="email" name="email" class="form-control rounded-10 @error('email') is-invalid @enderror" id="email" placeholder="name@example.com" value="{{ old('email') }}">
                              
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="password">
                                <div class="pass-input">
                                       <label for="exampleFormControlInput1" class="form-label es-label fw-500">Password</label>
                                    <input type="password" name="password" id="password" class="form-control rounded-10  @error('password') is-invalid @enderror" aria-describedby="passwordHelpBlock" placeholder="Password" value="{{ old('password') }}">
                                  
                                    <div class="pas-eye-icon" id="togglePassword" style="cursor: pointer;">
                                        <img src="{{asset('assets/images/p-eye.png')}}" alt="eye" id="eyeIcon">
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="g-recaptcha mt-4" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>

                            <div class="sign-up-btn mt-4">
                                <button class="btn btn-book w-100 py-2  fw-500 bg-black text-white px-5 rounded-10" type="submit">Sign-up</button>
                            </div>
                            <div class="or-text text-center my-3">
                                <p class="orange fw-500">Or</p>
                            </div>
                            <div class="login-google bg-white rounded-10 p-2 text-center">
                                <a href="{{ Request::segment(1) === 'driver' ? route('driver-login-google') : route('login-google')}}" class="text-black text-decoration-none color-23">
                                    <h5 class="fw-600">Continue with Google <span class="ps-2"><img src="{{asset('assets/images/google.png')}}"></span></h5>
                                </a>
                            </div>
                            <div class="need-login d-flex justify-content-center gap-1 mt-2">
                                <p class="color-96">Already have an account?</p>
                                <div class="creat">
                                    <a href="{{ Request::segment(1) === 'driver' ? route('driver-login') : route('login')}}" class="color-23 fw-400 text-decoration-underline">Login</a>
                                </div>
                            </div>
                            <div class="need-login d-flex justify-content-center gap-1 mt-2">

                                <div class="creat">
                                    <a href="{{ Request::segment(1) === 'driver' ? route('driver-sent-otp') : route('sent-otp')}}" class="color-23 fw-400 text-decoration-underline">Didn't receive your verification email?</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-7 col-12 pe-0">
                <div class="sign-up login-img h-100">
                    <img src="{{asset('assets/images/circle-img.png')}}" class="img-fluid h-100 object-fit-cover ">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<!-- Include jQuery -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

    // Adding a custom validation method for alphanumeric characters
    jQuery.validator.addMethod("strongPassword", function(value, element) {
        var regex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&])[A-Za-z\\d@$!%*?&]{8,}$");
        return this.optional(element) || regex.test(value); // Validates alphanumeric + special characters
    }, "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.");

    // Define custom regex method
    jQuery.validator.addMethod('regex', function(value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, 'Please enter a valid value.');

    $("#register_user").validate({
        rules: {
            first_name: {
                required: true,
                regex: /^[A-Za-z]+$/
            },
            last_name: {
                required: true,
                regex: /^[A-Za-z]+$/
            },
            email: {
                required: true,
                email: true,
                regex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/
            },
            password: {
                required: true,
                minlength: 8,
                strongPassword: true
            },
        },
        messages: {
            first_name: {
                required: "First name is required.",
                 regex: "First name can only contain letters."
            },
            last_name: {
                required: "Last name is required.",
                regex: "Last name can only contain letters."
            },
            email: {
                required: "Email is required.",
                email: "Please enter a valid email address.",
                regex: "Please enter a valid email address with a domain extension (e.g., .com, .org)."
            },
            password: {
                required: "Password is required.",
                minlength: "Password must be at least 8 characters long.",
            }
        }
    });

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

<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>

@endsection