@extends('admin.layouts.master')
@section('content')
<div class="admin-forget admin-side admin-login mh-100vh bg-black p-md-5">
        <div class="login-abs">
        <img src="{{asset('assets/images/circle.png')}}" alt="logo">
    </div>
    <div class="container  ps-2">
        <div class="row align-items-center   login-height">
            <div class="col-lg-5 col-md-12 col-12">
                <div class="forget-form login-form ">
                    <div class="login-logo">
                        <img src="{{asset('assets/images/login-logo.png')}}" alt="logo">
                    </div>
                    <div class="login-content mt-4 mb-3">
                        <h1 class="color-23">Reset Password</h1>
                        <!-- <p class="color-96">Don't worry! It occurs. Please enter the email address linked with
                            your account.</p> -->
                    </div>
                    <form  class="form-md" action="{{route('admin-reset-password')}}" method="post">
                        @csrf
                        <input type="hidden" value="{{$token}}" name="token">
                        <div class="login-input form-group mt-4">
                            <div class="email mb-3">   <label for="exampleFormControlInput1" class="form-label es-label fw-500">Password</label>
                                <input type="password" name="password" placeholder="" class="form-control rounded-10 @error('password') is-invalid @enderror" id="inputPassword5" value="{{ old('password') }}">
                              
                                   <div class="pas-eye-icon" id="togglePassword">
                                        <img src="{{asset('assets/images/p-eye.png')}}" alt="eye"  id="eyeIcon">
                                    </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="email form-group mb-3">
                                <label for="exampleFormControlInput1" class="form-label es-label fw-500">Confirm Password</label>
                                <input type="password" placeholder=""  name="password_confirmation" class="form-control rounded-10 @error('password_confirmation') is-invalid @enderror" id="confirmPassword" value="{{ old('password_confirmation') }}">
                                
                                   <div class="pas-eye-icon" id="toggleConfirmPassword">
                                        <img src="{{asset('assets/images/p-eye.png')}}" alt="eye"  id="ConfirmeyeIcon">
                                    </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="submit-btn mt-5">
                                <button class="btn btn-book w-100 py-3  fw-500 bg-black text-white px-5 rounded-10" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-7  pe-0">
                <div class="login-img h-100">
                    <img src="{{asset('assets/images/admin-img.png')}}" class="img-fluid">
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#inputPassword5');
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


    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('#toggleConfirmPassword');
        const passwordField = document.querySelector('#confirmPassword');
        const eyeIcon = document.querySelector('#ConfirmeyeIcon');

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