@extends('Frontend.layouts.master')
@section('title', 'Forgot Password')
@section('content')
<div class="login-page admin-side">
    <div class="container-fluid ps-5">
        <div class="row ">
            <div class="col-md-5">
                <div class="forget-form login-form ">
                    <div class="login-logo">
                        <a href="{{route('home')}}">
                            <img src="{{asset('assets/images/login-logo.png')}}" alt="logo">
                        </a>
                    </div>
                    <div class="login-content mt-4 mb-3">
                        <h1 class="color-23">Forgot Password</h1>
                        <p class="color-96">Don't worry! It occurs. Please enter the email address linked with your account.</p>
                    </div>
                    <form class="form-md" action="{{ Request::segment(1) === 'driver' ? route('driver-forgot-password') : route('forgot-password')}}" method="post" id="forgot_password">
                        @csrf
                        <div class="login-input mt-4">
                            <div class="email mb-3">          
                             <label for="exampleFormControlInput1" class="form-label es-label fw-500">Email</label>                     
                                <input type="email" name="email" class="form-control rounded-10 @error('email') is-invalid @enderror" id="email" placeholder="" value="{{ old('email') }}">
                               
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="submit-btn mt-5">
                                <button class="btn btn-book w-100 py-2  fw-500 bg-black text-white px-5 rounded-10" type="submit">Submit</button>
                            </div>
                            <div class="or-text text-center my-3">
                                <p class="orange fw-500">Or</p>
                            </div>
                            <div class="f-login-btn text-center">
                                <!-- <a href="{{route('login')}}" class="color-96">Login</a> -->
                                <a href="{{ Request::segment(1) === 'driver' ? route('driver-login') : route('login')}}" class="color-96">Login</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>
    $("#forgot_password").validate({
    rules: {
        email: {
        required: true,
        }
    },
    messages: {
        email: {
        required: "Email is required.",
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

@endsection