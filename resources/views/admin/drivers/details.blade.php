@extends('admin.layouts.master')
@section('title', 'Driver Detafils')
@section('content')
<div class="drivers-details custom-table mt-4">
    <div class="profile-heading mb-2 ">
        <h4 class="color-2e fw-600">Driver Details</h4>
    </div>
    <div class="driver-d-box shadow bg-white p-3 rounded-16">
        <div class="row align-items-center">
            <div class="col-md-2">
                <div class="profile-pic-container my-3">
                        @if(isset($driver->profile_pic))
                            <img id="profile-pics" src="{{asset('storage/profile')}}/{{$driver->profile_pic}}" alt="Profile Picture">
                        @else
                            <img id="profile-imaege" src="{{asset('assets/images/place.png')}}" alt="Profile Picture">
                        @endif
                  </div>
            </div>
            <div class="col-md-3">
                <div class="driver-name pe-3">
                    <div class="d-n d-flex justify-content-between mb-2">
                        <p>Name:</p><p class="color-83">{{$driver->name}}</p>
                    </div>
                    <div class="d-n d-flex justify-content-between mb-2">
                        <p>Email:</p><p class="color-83">{{$driver->email}}</p>
                    </div>
                    <div class="d-n d-flex justify-content-between">
                        <p>Status:</p><p class="color-83">{{$driver->verification_status}}</p>
                    </div>
                </div>
            </div>
          
        </div>
    </div>
    <div class="rides-info d-flex gap-2 mt-5 mb-5 pb-5">
            
        <div class="accept-ride ac-bg rounded-8 p-2 d-flex gap-2">
            <div class="a-c-img">
                <img src="{{asset('assets/images/ar.png')}}">
            </div>
            <div class="a-r-text">
                <p class="color-33">Accepted Rides</p>
                <p class="fs-14">{{$driver->total_rides}}</p>
            </div>
        </div>
        <div class="accept-ride cr-bg rounded-8 p-2 d-flex gap-2">
            <div class="a-c-img">
                <img src="{{asset('assets/images/cr.png')}}">
            </div>
            <div class="a-r-text">
                <p class="cr-color">Cancelled Rides </p>
                <p class="fs-14">{{$driver->canceled_rides}}</p>
            </div>
        </div>
    </div>
    <div class="update-doc-table">
        <div class="row" id="animated-thumbnails-gallery" lg-uid="lg0">
            <!-- Gallery images inside anchor tags -->
            <div class="col-md-3">
                <div class="id-proof-box">
                    <h6>Proof of license</h6>
                    <div class="proof-img-box">

                        @if(isset($driver->driverDocuments->license))
                            <a href="{{asset('storage/documents')}}/{{$driver->driverDocuments->license}}">
                                <img src="{{asset('storage/documents')}}/{{$driver->driverDocuments->license}}">
                            </a>
                        @else
                            <p>No document uploaded</p>
                        @endif 
                        
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="id-proof-box">
                    <h6>Proof of Insurance</h6>
                    <div class="proof-img-box">
                    @if(isset($driver->driverDocuments->insurance))
                        <a href="{{asset('storage/documents')}}/{{$driver->driverDocuments->insurance}}">
                            <img src="{{asset('storage/documents')}}/{{$driver->driverDocuments->insurance}}">
                        </a>
                    @else
                       <p>No document uploaded</p>
                    @endif

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="id-proof-box">
                    <h6>Proof of RC</h6>
                    <div class="proof-img-box">
                        @if(isset($driver->driverDocuments->registration))
                            <a href="{{asset('storage/documents')}}/{{$driver->driverDocuments->registration}}">
                                <img src="{{asset('storage/documents')}}/{{$driver->driverDocuments->registration}}">
                            </a>
                        @else
                            <p>No document uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="id-proof-box">
                    <h6>Upload Pictures of Vehicle</h6>
                    <div class="proof-img-box">
                        @if(isset($driver->driverDocuments->ownership_tesla_model))
                            <a href="{{asset('storage/documents')}}/{{$driver->driverDocuments->ownership_tesla_model}}">
                                <img src="{{asset('storage/documents')}}/{{$driver->driverDocuments->ownership_tesla_model}}">
                            </a>
                        @else
                            <p>No document uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('verify-document')}}" method="post" id="driver-document-status">
                    @csrf
                    <input type="hidden" name="driver_id" value="{{$driver->id}}">
                    <div class="update-form mt-4">
                        <div class="form-group">
                            <label>Approved Status</label>
                            <select name="verification_status" id="verification_status" class="form-control">
                                <option value="">--Select--</option>
                                <option value="requested" 
                                    {{ isset($driver->driverDocuments) && $driver->driverDocuments->verification_status == 'requested' ? 'selected' : '' }} 
                                    disabled>Requested</option>

                                <option value="approved" 
                                    {{ isset($driver->driverDocuments) && $driver->driverDocuments->verification_status == 'approved' ? 'selected' : '' }}>
                                    Approved</option>

                                <option value="rejected" 
                                    {{ isset($driver->driverDocuments) && $driver->driverDocuments->verification_status == 'rejected' ? 'selected' : '' }}>
                                    Rejected</option>

                                <option value="suspended" 
                                    {{ isset($driver->driverDocuments) && $driver->driverDocuments->verification_status == 'suspended' ? 'selected' : '' }}>
                                    Suspended</option>

                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label>Reason</label>
                            <textarea class="form-control" id="reason" name="reason" rows="2">{{$driver->driverDocuments->message ?? null}}</textarea>
                        </div>
                        <div class="submit-btn mt-4 text-end">
                            <button type="submit" class="btn bg-black text-white px-5 py-2 rounded-8 fw-500">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery/lightgallery.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery/plugins/thumbnail/lg-thumbnail.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lightgallery/plugins/fullscreen/lg-fullscreen.umd.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $("#driver-document-status").validate({
        rules: {
            verification_status: {
                required: true,
            },
            reason: {
                required: true,
            },
        },
        messages: {
            verification_status: {
                required: "Please select a verification status.",
            },
            reason: {
                required: "Please enter a reason.",
            },
        }
    });

    // Initialize LightGallery on the correct container
    lightGallery(document.getElementById('animated-thumbnails-gallery'), {
        thumbnail: true,
        selector: '.col-md-3 a' // Ensure it's selecting images wrapped in anchor tags
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