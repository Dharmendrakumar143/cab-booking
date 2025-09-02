@extends('admin.layouts.master')
@section('content')
<div class="admin-login admin-side mh-100vh bg-black p-md-5">
     <div class="login-abs">
        <img src="{{asset('assets/images/circle.png')}}" alt="logo">
    </div>
    <div class="container  ps-2">
        <div class="row align-items-center login-height">
            <div class="col-lg-5 col-md-12 col-12">
                <div class="admin-login-form login-form ">
                    <div class="login-logo">
                        <img src="{{asset('assets/images/login-logo.png')}}" alt="logo">
                    </div>
                    <div class="login-content mt-4 mb-3">
                        <h1 class="color-23">Admin Log in</h1>
                        <p class="color-96">Please login to continue to your account.</p>
                    </div>
                    <form  class="form-md" action="{{route('admin-login')}}" method="post" id="admin_login">
                        @csrf
                        <div class="login-input mt-4">
                            <div class="email mb-3 form-group">   
                             <label for="exampleFormControlInput1" class="form-label es-label fw-500">Email</label>     
                                <input  type="email" name="email" class="form-control rounded-10 @error('email') is-invalid @enderror" id="exampleFormControlInput1" placeholder="name@example.com" value="{{ old('email') }}">
                                 
                                @error('email')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="password">
                                <div class="pass-input  form-group">
                                      <label for="" class="form-label es-label fw-500">Password</label>
                                    <input type="password" name="password"  id="inputPassword5" class="form-control rounded-10 @error('password') is-invalid @enderror" aria-describedby="passwordHelpBlock" placeholder="Password" value="{{ old('password') }}">
                                  
                                    
                                       <div class="pas-eye-icon" id="togglePassword">
                                        <img src="{{asset('assets/images/p-eye.png')}}" alt="eye"  id="eyeIcon">
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="forget-pas d-flex align-items-center justify-content-between mt-3 mb-2">
                               
                                <div class="keep-me form-check">
                                    <input class="form-check-input rounded-10" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label fw-500" for="flexCheckDefault">
                                        Keep me logged in
                                    </label>
                                </div>
                                 <a href="{{route('admin-forgot-password')}}" class="orange fw-500 text-decoration-none">Forgot Password?</a>
                            </div>
                            <div class="login-btn mt-4">
                                <button class="btn btn-book w-100 py-3  fw-500 bg-black text-white px-5 rounded-10" type="submit">Log in</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-7  pe-0">
                <div class="login-img h-100">
                    <img src="{{asset('assets/images/admin-img.png')}}" class="img-fluid   ">
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
$("#admin_login").validate({
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

</script>
@endsection