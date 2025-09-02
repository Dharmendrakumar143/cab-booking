@extends('Frontend.layouts.master')
@section('content')
<main>
    <div class="u-notify-for-cotent mb-3 pb-5 mx-lg-0 mx-3">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
                <div class="u-notify-info">
                    <div class="help-heading mb-3 mt-4">
                        <h3 class="p-h-color fs-clash fw-600">Notifications</h3>
                    </div>
                </div>
                <div class="user-notification-content-box admin-help-content-box f8-bg rounded-20 p-4 border-e7 ">
                    
                    <div class="notify-box">
                        <div class="row">
                                
                            @foreach($notifications as $notification)
                            <div class="col-md-6 mb-3">
                                <div class="1st-box d-flex gap-2 align-items-center shadow rounded-10 p-2">
                                    <div class="n-img">
                                        <img src="{{asset('assets/images/place.png')}}" class="position-img">
                                        <span class="n-circle"></span>
                                    </div>
                                    <div class="n-content-box">
                                        <p class="fs-14">
                                            <a href="{{route('customer-notification-detail',['notification_id'=>$notification->id])}}">    
                                                {{$notification->title}}
                                            </a>
                                        </p>
                                        <p class="fs-12 light-gray">
                                        @php
                                            $created_at = \Carbon\Carbon::parse($notification->created_at);
                                            $time_ago = $created_at->diffForHumans();
                                        @endphp
                                        {{ $time_ago }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
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