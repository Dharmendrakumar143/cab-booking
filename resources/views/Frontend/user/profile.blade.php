@extends('Frontend.layouts.master')
@section('title', 'Profile Settings')
@section('content')
<main>
    <div class="user-profile-setting">
        <div class="container-fluid px-lg-5 px-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-heading mt-4 mb-3">
                        <h3 class="p-h-color fw-600">Profile Settings</h3>
                    </div>
                    <div class="profile-avtar d-flex gap-3 align-items-center fc2-bg rounded-16 px-3">
                        <div class="profile-pic-container my-3">
                            <input type="file" id="profile-picture" accept="image/*" hidden="" onchange="uploadProfileImage()">
                            <div class="profile-pic" onclick="document.getElementById('profile-picture').click();">
                                <div class="avtar-cemra">
                                    @if(Auth::user()->profile_pic)
                                        <img id="profile-image" src="{{asset('storage/profile')}}/{{Auth::user()->profile_pic}}" alt="Profile Picture">                            
                                    @else
                                        <img id="profile-image" src="{{asset('assets/images/place.png')}}">
                                    @endif
                                    <!-- <img id="profile-image" class="profile-ico" src="{{asset('storage/profile')}}/{{$user->profile_pic}}" alt="Profile Picture"> -->
                                    <img id="profile-image" class="p-cemera" src="{{asset('assets/images/p-cancle.png')}}" alt="Profile Picture">

                                </div>

                            </div>
                        </div>
                        <div class="profile-info">
                            <h5 class="mb-0">
                                {{ Auth::check() ? Auth::user()->first_name . ' ' . Auth::user()->last_name : '' }}
                            </h5>
                            <p>Your account is ready, you can now book rides.</p>
                        </div>
                    </div>
                    <div class="personal-details-info fc2-bg rounded-10 mt-md-5 mt-3 py-4 px-3 mb-5">
                        <div class=" pass-tab d-flex align-items-start">
                            <div class="nav flex-column nav-pills me-3  " id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <button class="nav-link active text-black fw-500  " id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Personal
                                    Details</button>
                                <button class="nav-link mt-2 fw-500 text-black " id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false" tabindex="-1">Password &amp;
                                    Security</button>

                            </div>
                            <div class="tab-content w-75 border-start ps-4 mt-md-0 mt-4" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
                                    <div class="personal-conent">
                                        <h5>Personal Information</h5>
                                    </div>
                                    <div class="form">
                                        <form action="{{route('user-profile')}}" method="post" class="personal-form mt-4" id="update_profile">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{$user->id}}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    <input type="text" class="form-control rounded-10 @error('first_name') {{$message}} @enderror" id="first_name" name="first_name" value="{{$user->first_name}}">
                                                    @error('first_name') 
                                                        <div class="invalid-feedback">{{$message}}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control rounded-10 @error('last_name') {{$message}} @enderror" id="last_name" name="last_name" value="{{$user->last_name}}">
                                                    @error('last_name') 
                                                    <div class="invalid-feedback">{{$message}}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 mt-4">
                                                    <label for="inputEmail4" class="form-label">Email</label>
                                                    <input type="email" class="form-control rounded-10" id="inputEmail4" name="email" value="{{$user->email}}" disabled>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="phone-input mt-4">
                                                        <label for="name" class="form-label">Phone
                                                            Number</label>
                                                        <div class="phone-input-container">
                                                            <!-- <span class="country-code">+91</span> -->
                                                            <input type="text" name="phone_number" id="phone_number" class="phone-input form-control rounded-10 @error('phone_number') is-invalid @enderror" placeholder="Enter phone number" value="{{$user->phone_number}}">
                                                            @error('phone_number') 
                                                                <div class="invalid-feedback">{{$message}}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                            
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="male-input mt-4">
                                                        <label for="name" class="form-label">Gender</label>
                                                        <select class="form-select rounded-10   @error('gender') {{$message}} @enderror" aria-label="Default select example" name="gender">
                                                            <option value="">--Select--</option>
                                                            <option value="male" {{ isset($user->gender) && $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                            <option value="female" {{ isset($user->gender) && $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                            <option value="other" {{ isset($user->gender) && $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                        @error('gender') 
                                                            <div class="invalid-feedback">{{$message}}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="update-btn mt-5">
                                                        <button type="submit" class="btn bg-black text-white px-4 rounded-8 fs-14">Update</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                                    <div class="change-password-tab">
                                        <div class="personal-conent">
                                            <h5>Change Password</h5>
                                        </div>
                                        <div class="change-pass-form mt-4">
                                            <form class="personal-form" action="{{route('user-change-password')}}" method="post" id="change_password">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="password old-pass mb-4">
                                                            <label for="inputPassword4" class="form-label text-black">Old
                                                                Password</label>
                                                            <div class="pass-input">
                                                                <input type="password" name="current_password" id="current_password" class="form-control rounded-10 @error('current_password') is-invalid @enderror" aria-describedby="passwordHelpBlock" placeholder="Old Password" value="{{ old('current_password') }}">
                                                                @error('current_password') 
                                                                    <div class="invalid-feedback">{{$message}}</div>
                                                                @enderror
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="password new-pass mb-4">
                                                            <label for="inputPassword4" class="form-label text-black">New
                                                                Password</label>
                                                            <div class="pass-input">
                                                                <input type="password" name="password" id="password" class="form-control rounded-10 @error('password') is-invalid @enderror" placeholder="New Password" value="{{ old('password') }}">
                                                                @error('password') 
                                                                    <div class="invalid-feedback">{{$message}}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="password con-pass">
                                                            <label for="inputPassword4" class="form-label text-black">Confirm
                                                                Password</label>
                                                            <div class="pass-input">
                                                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-10 @error('password_confirmation') is-invalid @enderror" placeholder="Confirm Password" value="{{ old('password_confirmation') }}">
                                                                @error('password_confirmation') 
                                                                    <div class="invalid-feedback">{{$message}}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="save-btn mt-4">
                                                            <button type="submit" class="btn bg-black text-white px-4 rounded-8 fs-14">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script>
    function uploadProfileImage() {
        var formData = new FormData();
        var fileInput = document.getElementById('profile-picture');
        var file = fileInput.files[0];
        var admin_id = {{ Auth::user()->id }};  // Dynamically get the admin ID

        if (file) {
            formData.append('profile_picture', file);
            formData.append('_token', '{{ csrf_token() }}'); // CSRF token for security

            // Make AJAX request to upload the image for the specific user
            $.ajax({
                url: '{{ route("user-upload-profile-image", ["id" => Auth::user()->id]) }}', // Pass user ID dynamically using route()
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Update the profile image on success
                        document.getElementById('profile-image').src = response.image_url;
                        document.getElementById('profile1-image').src = response.image_url;

                        
                    } else {
                        alert('Error uploading image. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred. Please try again.');
                    console.log(error);
                }
            });
        } else {
            alert('No file selected.');
        }
    }
</script>

<script>

$(document).ready(function() {
    $('input#phone_number').on('input', function() {
        var numbers = $(this).val();
        // Allow numbers, plus (+) and minus (-) signs only
        $(this).val(numbers.replace(/[^0-9\+\-]/g, ''));
    });

    // Prevent Enter key from being pressed
    $('input#phone_number').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent Enter key from submitting or focusing out
        }
    });
});

$("#update_profile").validate({
  rules: {
    phone_number: {
      required: true,
      digits: true,
      maxlength: 12,
      minlength: 10,
    },
    gender: {
      required: true,
    },
    first_name: {
      required: true,
    },
    last_name: {
      required: true,
    }
  },
  messages: {
    phone_number: {
      required: "Phone number is required.",
      digits: "Phone number must contain only numbers.",
    },
    gender: {
      required: "Please select your gender.",
    },
    first_name: {
      required: "First Name is required.",
    },
    last_name: {
      required: "Last Name is required.",
    }
  },
  highlight: function (element) {
    $(element).addClass("is-invalid");
  },
  unhighlight: function (element) {
    $(element).removeClass("is-invalid");
  }
});

// Adding a custom validation method for alphanumeric characters
jQuery.validator.addMethod("strongPassword", function(value, element) {
    var regex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&])[A-Za-z\\d@$!%*?&]{8,}$");
    return this.optional(element) || regex.test(value); // Validates alphanumeric + special characters
}, "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.");


$("#change_password").validate({
  rules: {
    current_password: {
      required: true,
    },
    password: {
      required: true,
      minlength: 8,
      strongPassword: true
    },
    password_confirmation: {
      required: true,
      equalTo: "#password" // Ensures the confirmation matches the password field
    }
  },
  messages: {
    current_password: {
      required: "Old password is required.",
    },
    password: {
      required: "Password is required.",
      minlength: "Password must be at least 8 characters long.",
    },
    password_confirmation: {
      required: "Password confirmation is required.",
      equalTo: "Password confirmation must match the password."
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