@extends('admin.layouts.master')
@section('title', 'Passengers')
@section('content')
<div class="profile-content mt-5">
    <div class="row">
        <div class="col-md-12">
         
            <div class="profile-avtar d-flex gap-3 align-items-center fc-bg rounded-16 px-3">
                <div class="profile-pic-container my-3">
                    <input type="file" id="profile-picture" accept="image/*" hidden="">
                    <div class="profile-pic" onclick="document.getElementById('profile-picture').click();">
                        <div class="avtar-cemra">
                            <img id="profile-image" src="{{asset('assets/images/place.png')}}" alt="Profile Picture">
                            <!-- <img id="profile-image" class="p-cemera" src="simages/p-cancle.png" alt="Profile Picture"> -->

                        </div>

                    </div>
                </div>
                <div class="profile-info">
                    <h5 class="mb-0">{{$passenger->first_name}} {{$passenger->last_name}}</h5>
                </div>
            </div>
            <div class="personal-details-info fc-bg rounded-10 mt-md-5 mt-3 py-4 px-3">
                <div class=" pass-tab d-flex align-items-start">
                
                    <div class="tab-content w-75 border-start ps-4 mt-md-0 mt-4" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
                            <div class="personal-conent">
                                <h5>Personal Information</h5>
                            </div>
                            <div class="form">
                                <form class="personal-form mt-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control rounded-10" id="name" value="{{$passenger->first_name}} {{$passenger->last_name}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputEmail4" class="form-label">Email</label>
                                            <input type="email" class="form-control rounded-10" id="inputEmail4" value="{{$passenger->email}}">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="phone-input mt-4">
                                                <label for="name" class="form-label">Phone
                                                    Number</label>
                                                <div class="phone-input-container">
                                                    <!-- <span class="country-code">+91</span> -->
                                                    <input type="tel" class="phone-input form-control rounded-10" value="{{$passenger->phone_number}}" pattern="[0-9]{10}" maxlength="10" required="" title="Please enter a valid 10-digit phone number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="male-input mt-4">
                                                <label for="name" class="form-label">Gender</label>
                                                <select class="form-select rounded-10" aria-label="Default select example">
                                            
                                                    <option value="male" {{ isset($passenger->gender) && $passenger->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ isset($passenger->gender) && $passenger->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="other" {{ isset($passenger->gender) && $passenger->gender == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-12">
                                            <div class="update-btn mt-5">
                                                <button type="button" class="btn bg-black text-white px-4 rounded-8 fs-14">Update</button>
                                            </div>
                                        </div> -->
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
@endsection

@section('scripts')
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Include Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JavaScript to trigger SweetAlert and submit form -->
<script>
    document.getElementById('rejectBtn').addEventListener('click', function() {
        // Show SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to reject this ride?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Reject it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user confirms, submit the form
                document.getElementById('rejectForm').submit();
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