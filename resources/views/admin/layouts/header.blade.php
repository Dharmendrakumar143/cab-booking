<div class="search-card shadow rounded-20 bg-white p-3 d-flex justify-content-between">
   
    @php

        $user = Auth::guard('admin')->user();
        $role = $user->roles->first()->name;

        if($role=='super-admin' || $role=='admin'){

            $notification_count = DB::table('notifications')
            ->where('read_status', false)
            ->where('type', 'customer')
            ->count();

            $notifications = DB::table('notifications')
            ->where('read_status', false)
            ->where('type', 'customer')
            ->orderBy('id', 'desc')
            ->take(5) // Limit to showing 2 notifications
            ->get();

        }else{

            $notification_count = DB::table('notifications')
                ->where('read_status', false)
                ->where('type', 'driver')
                ->where('admin_id',$user->id)
                ->count();

            $notifications = DB::table('notifications')
                ->where('read_status', false)
                ->where('type', 'driver')
                ->orderBy('id', 'desc')
                ->where('admin_id',$user->id)
                ->take(5) // Limit to showing 2 notifications
                ->get();
        }

    @endphp

    @php
        $roles =  Auth::guard('admin')->user()->roles->first()->name;
    @endphp
    <div class="search-container">
        @if(($roles === 'independent-contractors' || $roles === 'employees') && empty($user->stripe_account_id))
            <div class="notification-payment=link">
                <span><strong>Link your Stripe account to receive payments</strong>
                    <a href="{{route('create-connect-link')}}" target="_blank">Click here</a>
                </span>
            </div>
        @endif
    </div>
   
    <div class="admin-dropdown dropdown d-flex align-items-center">
        <div class="a-notify-icon pe-3">
            <a class="btn btn-secondary dropdown-toggle roy-btn note-count text-black" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="notes-admin">
                    <img src="{{asset('assets/images/a-notify.png')}}">
                    @if($notification_count > 0)
                        <span class="badge bg-danger">{{ $notification_count }}</span>
                    @endif
                </div>
            </a>
            <ul class="dropdown-menu" id="header-notification-list">
              
                @if($notifications->isNotEmpty())
                    @foreach($notifications as $notification)
                        <li class="border-bottom py-2">
                            <a class="dropdown-item mark-as-read" href="javascript:void(0)" data-id="{{ $notification->id }}">
                                <span class="pe-2">
                                    <img src="{{asset('assets/images/drrop1.png')}}">
                                </span>
                                {{$notification->title}}
                            </a>
                        </li>
                    @endforeach

                    <li class="py-2">
                        <a class="dropdown-item text-center text-primary" href="{{route('notification-list')}}" id="view-all-notifications-btn">View all notifications</a>
                    </li>
                @else
                    <li class="py-2 text-center" id="no-notifications-message">
                        <span class="text-white">No new notifications</span>
                    </li>
                @endif
            </ul>
        </div>

        <a class="btn btn-secondary dropdown-toggle roy-btn ac-btn text-black" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span>
                @if(Auth::guard('admin')->user()->profile_pic)
                    <img id="admin-head-profile-image" src="{{asset('storage/profile')}}/{{Auth::guard('admin')->user()->profile_pic}}" alt="Profile Picture">
                @else
                    <img id="admin-head-profile-image" src="{{asset('assets/images/place.png')}}">
                @endif
            </span>  {{Auth::guard('admin')->user()->name}}
        </a>
     
        <ul class="dropdown-menu">
            <li class="border-bottom py-2"><a class="dropdown-item" href="{{route('admin-profile')}}"><span class="pe-2"><img src="{{asset('assets/images/drrop1.png')}}"></span>Profile</a></li>
            <li class="border-bottom py-2"><a class="dropdown-item" href="{{route('admin-rides')}}"><span class="pe-2"><img src="{{asset('assets/images/drop2.png')}}"></span>My Trips</a></li>
               
            @if($roles === 'independent-contractors')
                <li class="border-bottom py-2">
                    <a class="dropdown-item" href="{{route('create-connect-link')}}">
                        <span class="pe-2">
                            <img src="{{asset('assets/images/drop3.png')}}">
                        </span>Add Stripe Account
                    </a>
                </li>
            @endif
            <li class="border-bottom py-2"><a class="dropdown-item" href="{{route('payment-listing')}}"><span class="pe-2"><img src="{{asset('assets/images/drop3.png')}}"></span>Payment History</a></li>
            @if($roles === 'admin' || $roles === 'super-admin')
                <li class="border-bottom py-2"><a class="dropdown-item" href="{{route('tickets')}}"><span class="pe-2"><img src="{{asset('assets/images/drop4.png')}}"></span>Help &amp; Support</a></li>
            @endif
         
            <li><a class="dropdown-item py-2" href="{{route('admin-logout')}}"><span class="pe-2"><img src="{{asset('assets/images/logout.png')}}"></span>Logout</a></li>
        </ul>
    </div>
</div>