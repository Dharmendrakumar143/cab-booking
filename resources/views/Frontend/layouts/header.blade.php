<header>
        <div class="container-fluid px-5" bis_skin_checked="1">
          <div class="row" bis_skin_checked="1">
            <nav class="navbar navbar-expand-lg">
              <div class="container-fluid" bis_skin_checked="1">
                <a class="navbar-brand" href="{{route('home')}}"><img src="{{asset('assets/images/logo.png')}}" alt="logo"> </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent" bis_skin_checked="1">
                  <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                      <a class="nav-link active text-white" aria-current="page" href="{{route('home')}}">Home</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-white" href="#about-us" bis_skin_checked="1">About</a>
                    </li>
                  </ul>
                  @if (Auth::check())
                  @php
                    // Get the count of unread notifications for the logged-in user
                    $notification_count = DB::table('notifications')
                        ->where('read_status', false)
                        ->where('type', 'admin')
                        ->where('user_id', auth()->id())
                        ->count();

                    // Get the most recent 5 unread notifications for the logged-in user
                    $notifications = DB::table('notifications')
                        ->where('read_status', false)
                        ->where('type', 'admin')
                        ->where('user_id', auth()->id())
                        ->orderBy('id', 'desc')
                        ->take(5) // Limit to showing 5 notifications
                        ->get();
                  @endphp

                  <div class="bell-icon pe-3" bis_skin_checked="1">
                      <!-- <i class="bi bi-bell text-white"></i> -->
                        <a class="btn btn-secondary dropdown-toggle roy-btn text-black" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="bi bi-bell text-white"></i> 
                          @if($notification_count > 0)
                              <span class="badge bg-danger">{{ $notification_count }}</span>
                          @endif
                        </a>
                        <ul class="dropdown-menu" id="header-notification-list">
                          @if($notifications->isNotEmpty())
                              @foreach($notifications as $notification)
                                  <li class="border-bottom py-2">
                                      <a class="dropdown-item customer-mark-as-read" href="javascript:void(0)" data-id="{{ $notification->id }}">
                                          <span class="pe-2">
                                              <img src="{{asset('assets/images/drrop1.png')}}">
                                          </span>
                                          {{$notification->title}}
                                      </a>
                                  </li>
                              @endforeach

                              <li class="py-2">
                                  <a class="dropdown-item text-center text-primary" href="{{route('customer-notification-list')}}" id="view-all-notifications-btn">View all notifications</a>
                              </li>
                          @else
                              <li class="py-2 text-center" id="no-notifications-message">
                                  <span class="text-muted">No new notifications</span>
                              </li>
                          @endif
                        </ul>
                  </div>
                  @endif
                  
                  @if (Auth::check())
                      <div class="dropdown">
                        <a class="btn btn-secondary dropdown-toggle roy-btn show" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                        <span>
                        @if(Auth::user()->profile_pic)
                          <img id="profile1-image" src="{{asset('storage/profile')}}/{{Auth::user()->profile_pic}}" alt="Profile Picture">                            
                        @else
                          <img id="profile1-image" src="{{asset('assets/images/place.png')}}">
                        @endif
                        </span> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </a>
                      
                        <ul class="dropdown-menu" data-bs-popper="static">
                            <li><a class="dropdown-item" href="{{route('my-profile')}}">Profile</a></li>
                            <li><a class="dropdown-item" href="{{route('my-trip')}}">My Trip</a></li>
                            <li><a class="dropdown-item" href="{{route('payment-history')}}">Payment Histroy</a></li>
                            <li><a class="dropdown-item" href="{{route('help-support')}}">Help & Support</a></li>
                            <li><a class="dropdown-item" href="{{route('logout')}}">Logout</a></li>
                        </ul>

                      </div>
                  @else
                    <div class="login-btn-s-up" bis_skin_checked="1">
                        <a href="{{route('login')}}" class="text-white" bis_skin_checked="1">Login</a> <span class="text-white">/</span> <a href="{{route('register')}}" class="text-white" bis_skin_checked="1">Sign Up</a>
                    </div>
                  @endif
                </div>
            </div></nav>
          </div>
        </div>
      </header>