@extends('Frontend.layouts.master')
@section('title', 'My Ticket Details')
@section('content')
<main>
    <div class="my-trip-for-cotent mb-5 pb-5">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="r-heading mt-lg-5 mt-5 mb-3">
                        <h2 class=" fw-600">My Ticket Details</h2>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class=" compelet-trip-deatils ride-detail-cancle rounded-10 my-3">
                        <div class="book-your-ride p-4 ">
                            <div class="r-d-flex d-flex justify-content-between flex-wrap">
                                <div class="bs-heading mb-lg-0 mb-4">
                                    <p class="fs-14 ">Status: <span class="ps-2">{{$ticket_details->status}}</span> </p>
                                    <p class="fs-14 ">Subject: <span class="ps-2">{{$ticket_details->issue_subject}}</span> </p>
                                    <p class="fs-14 ">Name: <span class="ps-2">{{$ticket_details->name}}</span> </p>
                                    <p class="fs-14 ">Email: <span class="ps-2">{{$ticket_details->email}}</span> </p>
                                    <p class="fs-14 ">Discription: <span class="ps-2">{{$ticket_details->issue_description}}</span> </p>
                                    <p class="fs-14 ">Solution: <span class="ps-2">{{$ticket_details->solutions}}</span> </p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>


@endsection