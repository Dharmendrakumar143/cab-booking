@extends('admin.layouts.master')
@section('title', 'Profile Settings')
@section('content')
<div class="profile-content mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="profile-heading mb-4">
                <h4 class="p-h-color fw-600">Profile Settings</h4>
            </div>
            <div class="profile-avtar d-flex gap-3 align-items-center fc-bg rounded-16 px-3">
                <div class="profile-pic-container my-3">
                    <input type="file" id="profile-picture" accept="image/*" hidden="" onchange="uploadProfileImage()">
                    <div class="profile-pic" onclick="document.getElementById('profile-picture').click();">
                        <div class="avtar-cemra">
                            @if(Auth::guard('admin')->user()->profile_pic)
                                <img id="profile-image" src="{{asset('storage/profile')}}/{{$admin_user->profile_pic}}" alt="Profile Picture">
                            @else
                                <img id="profile-image" src="{{asset('assets/images/place.png')}}">
                            @endif
                            <!-- <img id="profile-image" src="{{asset('storage/profile')}}/{{$admin_user->profile_pic}}" alt="Profile Picture"> -->
                            <img id="profile-image" class="p-cemera" src="{{asset('assets/images/p-cancle.png')}}" alt="Profile Picture">
                        </div>
                    </div>
                </div>
                <div class="profile-info">
                <h5 class="mb-0">{{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->name : '' }}</h5>

                    <!-- <p>Your account is ready, you can now book rides.</p> -->
                </div>
            </div>
            
            @php
                $roles =  Auth::guard('admin')->user()->roles->first()->name;
            @endphp
            @if($roles !== 'admin' && $roles !== 'super-admin' && $roles !== 'employees')
                <p class="py-3 text-center">Driver, please complete your personal details and upload your documents for eligibility to the Troy Rides platform.</p>
            @endif
            <div class="personal-details-info fc-bg rounded-10  py-4 px-3">
                <div class=" pass-tab d-flex align-items-start">
                    <div class="nav flex-column nav-pills me-3  " id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active text-black fw-500  " id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Personal
                            Details</button>
                        <button class="nav-link mt-2 fw-500 text-black " id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false" tabindex="-1">Password &amp;
                            Security</button>
                      
                            @if($roles !== 'admin' && $roles !== 'super-admin' && $roles !== 'employees')
                                <button class="nav-link mt-2 fw-500 text-black" id="v-pills-upload-tab" data-bs-toggle="pill" data-bs-target="#v-pills-upload" type="button" role="tab" aria-controls="v-pills-upload" aria-selected="true">Upload Documents</button>
                            @endif
                    </div>
                    <div class="tab-content w-75 border-start ps-4 mt-md-0 mt-4" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
                            <div class="personal-conent">
                                <h5>Personal Information</h5>
                            </div>
                            <div class="form">
                                <form action="{{route('admin-profile')}}" method="post" class="personal-form mt-4" id="update_profile">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{$admin_user->id}}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control rounded-10 @error('name') {{$message}} @enderror" id="name" name="name" value="{{$admin_user->name}}">
                                            @error('name') 
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputEmail4" class="form-label">Email</label>
                                            <input type="email" class="form-control rounded-10" id="inputEmail4" name="email" value="{{$admin_user->email}}" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="phone-input mt-4">
                                                <label for="name" class="form-label">Phone
                                                    Number</label>
                                                <div class="phone-input-container">
                                                    <!-- <span class="country-code">+91</span> -->
                                                    <input type="text" name="phone_number" id="phone_number" class="phone-input form-control rounded-10 @error('phone_number') is-invalid @enderror" placeholder="Enter phone number" value="{{$admin_user->phone_number}}">
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
                                                    <option value="male" {{ isset($admin_user->gender) && $admin_user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ isset($admin_user->gender) && $admin_user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="other" {{ isset($admin_user->gender) && $admin_user->gender == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('gender') 
                                                    <div class="invalid-feedback">{{$message}}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="phone-input mt-4">
                                                <label for="name" class="form-label">Car Year, Make and Model</label>
                                                <div class="phone-input-container">
                                                    <input type="text" name="car_model" id="car_model" class="phone-input form-control rounded-10" placeholder="Enter car year, make and model" value="{{$admin_user->car_model}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="phone-input mt-4">
                                                <label for="name" class="form-label">Car Number Plate</label>
                                                <div class="phone-input-container">
                                                    <input type="text" name="car_number_plate" id="car_number_plate" class="phone-input form-control" placeholder="Enter car number plate" value="{{$admin_user->car_number_plate}}">
                                                </div>
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
                                    <form class="personal-form" action="{{route('admin-change-password')}}" method="post" id="change_password">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="password old-pass mb-4">
                                                    <label for="current_password" class="form-label text-black">Old
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
                                                    <label for="password" class="form-label text-black">New
                                                        Password</label>
                                                    <div class="pass-input">
                                                        <input type="password" name="password" id="password" class="form-control rounded-10 @error('password') is-invalid @enderror" aria-describedby="passwordHelpBlock" placeholder="New Password" value="{{ old('password') }}">
                                                        @error('password') 
                                                            <div class="invalid-feedback">{{$message}}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="password con-pass">
                                                    <label for="password_confirmation" class="form-label text-black">Confirm
                                                        Password</label>
                                                    <div class="pass-input">
                                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-10 @error('password_confirmation') is-invalid @enderror" aria-describedby="passwordHelpBlock" placeholder="Confirm Password" value="{{ old('password_confirmation') }}">
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
                        
                        @if($roles !== 'admin' && $roles !== 'super-admin')
                            <div class="tab-pane fade" id="v-pills-upload" role="tabpanel" aria-labelledby="v-pills-upload-tab" tabindex="0">
                                <div class="change-password-tab">
                                    <div class="personal-conent">
                                        <h5>Upload Documents</h5>
                                    </div>
                                    <div class="change-pass-form mt-4">
                                        <form class="personal-form" action="{{route('upload-document')}}" id="upload-documents" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="uplaod old-pass mb-4">
                                                        @if(isset($admin_user->driverDocuments->license))
                                                            <img src="{{asset('storage/documents')}}/{{$admin_user->driverDocuments->license}}" width="50" height="50">
                                                        @endif
                                                        <label for="licences" class="form-label text-black">Proof of license</label>
                                                        <div class="up-input">
                                                            <div class="up-inpt-file">
                                                                <label for="licences" class="custom-file-label">Choose a File</label>
                                                                <span class="file-name">No file chosen</span>
                                                                <input type="file" id="licences" class="file-input @error('license') is-invalid @enderror" name="license">
                                                                @error('license') 
                                                                    <div class="invalid-feedback">{{$message}}</div>
                                                                @enderror
                                                            </div>
                                                            {{-- <input class="form-control" type="file" id="licences" name="license"> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="uplaod old-pass mb-4">
                                                        @if(isset($admin_user->driverDocuments->registration))
                                                            <img src="{{asset('storage/documents')}}/{{$admin_user->driverDocuments->registration}}" width="50" height="50">
                                                        @endif
                                                        <label for="registration" class="form-label text-black">Proof of Registration</label>
                                                        <div class="up-input">
                                                            <div class="up-inpt-file">
                                                                <label for="registration" class="custom-file-label">Choose a File</label>
                                                                <span class="file-name">No file chosen</span>
                                                                <input type="file" id="registration" class="file-input @error('registration') is-invalid @enderror" name="registration">
                                                                @error('registration') 
                                                                    <div class="invalid-feedback">{{$message}}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="uplaod old-pass mb-4">
                                                        @if(isset($admin_user->driverDocuments->insurance))
                                                            <img src="{{asset('storage/documents')}}/{{$admin_user->driverDocuments->insurance}}" width="50" height="50">
                                                        @endif
                                                        <label for="insurance" class="form-label text-black">Proof of Insurance</label>
                                                        <div class="up-input">
                                                            <div class="up-inpt-file">
                                                                <label for="insurance" class="custom-file-label">Choose a File</label>
                                                                <span class="file-name">No file chosen</span>
                                                                <input type="file" id="insurance" class="file-input @error('insurance') is-invalid @enderror" name="insurance">
                                                                @error('insurance') 
                                                                    <div class="invalid-feedback">{{$message}}</div>
                                                                @enderror
                                                            </div>
                                                            {{-- <input class="form-control" type="file" id="insurance" name="insurance"> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="uplaod old-pass mb-4">
                                                        @if(isset($admin_user->driverDocuments->ownership_tesla_model))
                                                            <img src="{{asset('storage/documents')}}/{{$admin_user->driverDocuments->ownership_tesla_model}}" width="50" height="50">
                                                        @endif
                                                        <label for="ownership_tesla_model" class="form-label text-black">Picture of your vehicle</label>
                                                        <div class="up-input">
                                                            <div class="up-inpt-file">
                                                                <label for="ownership_tesla_model" class="custom-file-label">Choose a File</label>
                                                                <span class="file-name">No file chosen</span>
                                                                <input type="file" id="ownership_tesla_model" class="file-input @error('ownership_tesla_model') is-invalid @enderror" name="ownership_tesla_model">
                                                                @error('ownership_tesla_model') 
                                                                    <div class="invalid-feedback">{{$message}}</div>
                                                                @enderror
                                                            </div>
                                                            {{-- <input class="form-control" type="file" id="ownership_tesla_model" name="ownership_tesla_model"> --}}
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
                        @endif
                    </div>
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
<!-- <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script>
    function uploadProfileImage() {
        var formData = new FormData();
        var fileInput = document.getElementById('profile-picture');
        var file = fileInput.files[0];
        var admin_id = {{ Auth::guard('admin')->user()->id }};  // Dynamically get the admin ID

        if (file) {
            formData.append('profile_picture', file);
            formData.append('_token', '{{ csrf_token() }}'); // CSRF token for security

            // Make AJAX request to upload the image for the specific user
            $.ajax({
                url: '{{ route("admin-upload-profile-image", ["id" => Auth::guard("admin")->user()->id]) }}', // Pass user ID dynamically using route()
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Update the profile image on success
                        document.getElementById('profile-image').src = response.image_url;
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

$.validator.addMethod("imageOnly", function (value, element) {
    if (element.files.length === 0) return true; // Let "required" handle empty

    const file = element.files[0];
    const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
    return allowedTypes.includes(file.type);
}, "Only image files (JPG, JPEG, PNG, GIF) are allowed.");

$("#upload-documents").validate({
    rules: {
        license: {
            required: true,
            imageOnly: true
        },
        registration: {
            required: true,
            imageOnly: true
        },
        insurance: {
            required: true,
            imageOnly: true
        },
        ownership_tesla_model: {
            required: true,
            imageOnly: true
        }
    }
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
    name: {
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
    name: {
      required: "Name is required.",
    }
  },
  highlight: function (element) {
    $(element).addClass("is-invalid");
  },
  unhighlight: function (element) {
    $(element).removeClass("is-invalid");
  }
});

// Restrict phone number input to numbers only
$('#phone_number').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
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
    document.querySelectorAll(".file-input").forEach((input) => {
    input.addEventListener("change", function () {
        let fileName = this.files.length > 0 ? this.files[0].name : "No file chosen";
        this.closest(".up-inpt-file").querySelector(".file-name").textContent = fileName;
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