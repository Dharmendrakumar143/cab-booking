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
                        <div class="user-feedback-notification-content-box admin-help-content-box f8-bg rounded-20 p-4 border-e7 ">
                            <div class="notification-title-box">
                                <h5 class="fw-600 mb-0">Notifications Title</h5>
                                <p class="fw-600 fs-14 mb-3">{{$notification->title}}</p>
                                <h6 class="fw-600">Notifications Description</h6>
                                <p>{{$notification->message}}</p>
                            </div>
                            <div class="notify-btn-box d-flex gap-3 flex-lg-nowrap flex-wrap">
                                <form action="{{route('customer-mark-read')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="notification_id" value="{{$notification->id}}">
                                    <div class="submit-btn mt-4">
                                        <button type="submit" class="btn bg-black text-white rounded-8 fw-500">Mark as Read</button>
                                    </div>
                                </form>
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