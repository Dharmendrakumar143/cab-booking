@extends('admin.layouts.master')
@section('title', 'Notifications')
@section('content')
<div class="help-info">
    <div class="help-heading mb-3 mt-4">
        <h3 class="p-h-color fs-clash fw-600">Notifications</h3>
    </div>
</div>
<div class="admin-notification-content-box admin-help-content-box f8-bg rounded-20 p-4 border-e7 ">
    <!-- <div class="col-md-12 col-12">
        <div class="filters d-flex gap-2 justify-content-end  mb-3">
            <input type="date" class="form-control " placeholder="8 Jan 2024 - 12 Jan 2024" id="date-picker">
            <div class="status-btn">
                <select id="status" class="form-control  wm-content">
                    <option value="all">Status</option>
                    <option value="pending" class="border-bottom pb-3">Resolved</option>
                    <option value="approved">Pending</option>
                </select>
            </div>
        </div>
    </div> -->
    <div class="notify-box">
        <div class="row">
            @foreach($notifications as $notification)
            <div class="col-md-6 mb-3">
                <div class="1st-box d-flex gap-2 shadow rounded-10 p-2">
                    <div class="n-img">
                        <img src="{{asset('assets/images/place.png')}}" class="position-img">
                        <span class="n-circle"></span>
                    </div>
                    <div class="n-content-box">
                        <p class="fs-14"><a href="{{route('notification-detail',['notification_id'=>$notification->id])}}">{{$notification->title}}</a></p>
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
@endsection

@section('scripts')

@endsection