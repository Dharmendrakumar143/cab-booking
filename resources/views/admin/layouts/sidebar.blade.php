<aside class="sidebar mt-4">
    <div class="sidebar-header">
        <a class="nav-link text-white" aria-current="page" href="{{route('dashboard')}}">
            <img src="{{asset('assets/images/dash-logo.png')}}" class="img-fluid">
        </a>
    </div>
    @php
        $roles =  Auth::guard('admin')->user()->roles->first()->name;
    @endphp
    <nav class="navbar navbar-expand-lg my-lg-5">
        <div class="container-fluid px-0">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-start bg-black" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasExampleLabel"><div class="sidebar-header">
                    <img src="{{asset('assets/images/dash-logo.png')}}" class="img-fluid">
                </div></h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav mb-2 mb-lg-0  side-bar w-100">
                        <li class="nav-item">
                            <a class="nav-link text-white" aria-current="page" href="{{route('dashboard')}}">
                                <img src="{{ asset(request()->routeIs('dashboard') ? 'assets/images/d-n-icon.png' : 'assets/icons/active_dashboard.png') }}">Dashboard
                            </a>
                        </li>

                        @if(Auth::guard('admin')->check()) 
                            @php
                                $user = Auth::guard('admin')->user();
                            @endphp

                            {{-- Super Admin: Show all items --}}
                            @if($user->hasRole('super-admin')) 
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('admin-rides') }}">
                                        <img src="{{ asset(request()->routeIs('admin-rides') || request()->routeIs('admin-ride-details') ? 'assets/icons/ride_active.png' : 'assets/images/dn-2.png') }}" alt="Rides">Rides
                                    </a>
                                </li>
                            
                            {{-- Other Admins: Check permission before showing --}}
                            @elseif($user->hasPermissionTo('view rides'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('admin-rides') }}">
                                        <img src="{{ asset(request()->routeIs('admin-rides') || request()->routeIs('admin-ride-details') ? 'assets/icons/ride_active.png' : 'assets/images/dn-2.png') }}" alt="Rides">Rides
                                    </a>
                                </li>
                            @endif
                        @endif


                        @if($roles === 'admin' || $roles === 'super-admin')
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('surge-price')}}">
                                <img src="{{ asset(request()->routeIs('surge-price') || request()->routeIs('add-price') || request()->routeIs('edit-price') ? 'assets/icons/cost-active.png' : 'assets/images/cost-img.png') }}" alt="Rides">Surge price
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('admin-passenger')}}">
                                <img src="{{ asset(request()->routeIs('admin-passenger') || request()->routeIs('admin-passenger-detail') ? 'assets/icons/passenger_active.jpg' : 'assets/images/dn-3.png') }}">Passengers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('admin-driver')}}"> 
                                <img src="{{ asset(request()->routeIs('admin-driver') || request()->routeIs('driver-detail') ? 'assets/icons/driver_active.png' : 'assets/images/dn-4.png') }}">    
                                Drivers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('employee-list')}}"> 
                                {{-- <img src="{{ asset(request()->routeIs('employee-list') || request()->routeIs('create-employee') || request()->routeIs('employee-edit') ? 'assets/icons/driver_active.png' : 'assets/images/dn-4.png') }}">     --}}
                                <img src="{{ asset(request()->routeIs('employee-list') || request()->routeIs('create-employee') || request()->routeIs('employee-edit') ? 'assets/icons/emp-active.png' : 'assets/images/emp-img.png') }}">
                                Employees
                            </a>
                        </li>
                        @endif

                        @if(Auth::guard('admin')->check()) 
                            @php
                                $user = Auth::guard('admin')->user();
                            @endphp

                            {{-- Super Admin: Show all items --}}
                            @if($user->hasRole('super-admin')) 
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{route('payment-listing')}}">
                                        <img src="{{ asset(request()->routeIs('payment-listing') || request()->routeIs('payment-details') ? 'assets/icons/payment.png' : 'assets/images/dn-5.png') }}">
                                        Payment History</a>
                                </li>
                            {{-- Other Admins: Check permission before showing --}}
                            @elseif($user->hasPermissionTo('view payments'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{route('payment-listing')}}">
                                        <img src="{{ asset(request()->routeIs('payment-listing') || request()->routeIs('payment-details') ? 'assets/icons/payment.png' : 'assets/images/dn-5.png') }}">
                                        Payment History</a>
                                </li>
                            @endif
                        @endif

                        @if($roles === 'admin' || $roles === 'super-admin')
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('page-content')}}">
                                <img src="{{ asset(request()->routeIs('page-content') || request()->routeIs('add-page-content') || request()->routeIs('edit-page-content')  ? 'assets/icons/content.png' : 'assets/images/dn-6.png') }}">
                                Content Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('faq-content')}}">
                                <img src="{{ asset(request()->routeIs('faq-content') || request()->routeIs('faq-show') || request()->routeIs('edit-faq')  ? 'assets/icons/content.png' : 'assets/images/dn-6.png') }}">
                                Faqs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('tickets')}}">
                                {{-- <img src="{{ asset(request()->routeIs('tickets') || request()->routeIs('ticket-details') || request()->routeIs('edit-ticket') ? 'assets/icons/icon.png' : 'assets/images/dn-7.png') }}"> --}}
                                <img src="{{ asset(request()->routeIs('tickets') || request()->routeIs('ticket-details') || request()->routeIs('edit-ticket') ? 'assets/icons/help-active.png' : 'assets/images/help-img.png') }}">
                                Help &amp; Support
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('roles')}}">
                                <img src="{{ asset(request()->routeIs('roles') || request()->routeIs('role-edit') ? 'assets/icons/icon.png' : 'assets/images/dn-7.png') }}">
                               Roles and Permissions
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('driver-dues')}}">
                                <img src="{{ asset(request()->routeIs('driver-dues') ? 'assets/icons/icon.png' : 'assets/images/dn-7.png') }}">
                               Dues
                            </a>
                        </li>
                        

                        <li class="nav-item mt-5 pt-5">
                            <a class="nav-link text-white" href="{{route('admin-profile')}}">
                                <img src="{{ asset(request()->routeIs('admin-profile') ? 'assets/icons/active_setting.png' : 'assets/images/dn-8.png') }}">Setting
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{route('admin-logout')}}"><img src="{{asset('assets/images/dn-9.png')}}">Logout</a>
                        </li>
                    </ul>
                </div>
                </div>
        </div>
    </nav>
</aside>
