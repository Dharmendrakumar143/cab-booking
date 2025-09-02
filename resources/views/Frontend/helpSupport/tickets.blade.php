@extends('Frontend.layouts.master')
@section('title', 'My Tickets')
@section('content')
<main>
    <div class="my-trip-for-cotent mb-5 pb-5">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="r-heading mt-lg-5 mt-5 mb-3">
                        <h2 class=" fw-600">My Tickets </h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="past-tab-nav-menu">
                        @foreach($support_tickets as $support_ticket) 
                            <div class="col-lg-6">
                                <div class="my-trip-details ride-detail-cancle rounded-10 my-3">
                                    <div class="book-your-ride p-4 ">
                                        <div class="r-d-flex d-flex justify-content-between flex-wrap">
                                        <div class="bs-heading mb-lg-0 mb-4">
                                            <!-- <h5 class="fw-600 mb-0">Ticket</h5> -->
                                            <p class="fs-14 ">{{$support_ticket->name}}</p>
                                            <p class="fs-14 ">{{$support_ticket->issue_subject}}</p>
                                            <p class="fs-14 ">{{$support_ticket->status}}</p>
                                        </div>
                                    </div>
                                        <div class="row ">
                                            <div class="row mt-3">
                                                <div class="col-md-12 pe-lg-0">
                                                    <div class="view-btn mt-3">
                                                        <a href="{{route('view-ticket-details',['ticket_id'=>$support_ticket->id])}}" class="btn bg-black px-5 text-white  rounded-10 py-2">view</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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