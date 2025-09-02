@extends('admin.layouts.master')
@section('title', 'Rides')
@section('content')
<div class="rides-know-content">
        <div class="rides-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="rides-base mt-3 mt-4 mb-3">
                        <h5 class="a-h-color fw-600">Rides</h5>
                        <div class="row gy-3">
                            <div class="col-md-12">
                            <div class="tab-nav-menu mt-4">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Pending ({{$pending_rides_count}})</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-confirm-tab" data-bs-toggle="pill" data-bs-target="#pills-confirm" type="button" role="tab" aria-controls="pills-confirm" aria-selected="true">Accept ({{$confirmed_rides_count}})</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false" tabindex="-1">In progress ({{$count_in_progress_ride}})</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-paused-tab" data-bs-toggle="pill" data-bs-target="#pills-paused" type="button" role="tab" aria-controls="pills-paused" aria-selected="false" tabindex="-1">Paused ({{$paused_rides_count}})</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false" tabindex="-1">Completed ({{$completed_rides_count}})</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-upcoming-tab" data-bs-toggle="pill" data-bs-target="#pills-upcoming" type="button" role="tab" aria-controls="pills-upcoming" aria-selected="false" tabindex="-1">Upcoming ({{$upcoming_rides->count()}})</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-pre-tab" data-bs-toggle="pill" data-bs-target="#pills-pre" type="button" role="tab" aria-controls="pills-pre" aria-selected="false" tabindex="-1">Cancelled by customer ({{$pre_cancelled_rides->count()}})</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-driver-tab" data-bs-toggle="pill" data-bs-target="#pills-driver" type="button" role="tab" aria-controls="pills-driver" aria-selected="false" tabindex="-1">Cancelled by driver ({{$cancelled_by_drivers->count()}})</button>
                                    </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <div class="custom-table mt-3 mb-3">
                                                <div class="table-responsive">
                                                    <div class="d-flex justify-content-end gap-3">
                                                        <div class="date-img mt-2 relative" bis_skin_checked="1">
                                                            <img src="{{asset('assets/images/calendar.png')}}" alt="">
                                                            <input type="text" name="date-picker-pending-ride" id="date-picker-pending-ride" class="form-control light-gray fs-14" placeholder="YYYY-MM-DD">
                                                        </div>
                                                        <div class="search-container pb-3 relative" bis_skin_checked="1">
                                                            <input type="text" placeholder="Search..." class="search-input light-gray fs-14" name="search_by" id="search_by">
                                                            <img src="{{asset('assets/images/search-icon(2).png')}}"  alt="search-icon" class="ride-search-icon">
                                                        </div>
                                                    </div>
                                                    <table class="table w-100" id="pending-ride">
                                                        <thead class="fc-bg rounded-8">
                                                            <tr>
                                                                <th class="px-4">ID </th>
                                                                <th class="px-4">User</th>
                                                                <th class="px-4">Pickup Date</th>
                                                                <th class="px-4">Pickup Time</th>
                                                                <th class="px-4">Pickup Location</th>
                                                                <th class="px-4">Drop Location</th>
                                                                <th class="px-4">Phone Number</th>
                                                                <th class="px-4">Fare</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="pills-confirm" role="tabpanel" aria-labelledby="pills-confirm-tab" tabindex="0">
                                            <div class="custom-table mt-3 mb-3">
                                                    <div class="table-responsive">
                                                        <div class="d-flex justify-content-end gap-3">
                                                            <div class="date-img mt-2 relative" bis_skin_checked="1">
                                                                <img src="{{asset('assets/images/calendar.png')}}" alt="">
                                                                <input type="text" name="date-picker-confirm-ride" id="date-picker-confirm-ride" class="form-control light-gray fs-14" placeholder="YYYY-MM-DD">
                                                            </div>
                                                            <div class="search-container pb-3 relative" bis_skin_checked="1">
                                                                <input type="text" placeholder="Search..." class="search-input light-gray fs-14" name="confirm_search_by" id="confirm_search_by">
                                                                <img src="{{asset('assets/images/search-icon(2).png')}}"  alt="search-icon" class="ride-search-icon">
                                                            </div>
                                                        </div>
                                                            
                                                        <table class="table w-100" id="confirm-ride">
                                                            <thead class="fc-bg rounded-8">
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>User</th>
                                                                    <th>Pickup Date</th>
                                                                    <th>Pickup Time</th>
                                                                    <th>Pickup Location</th>
                                                                    <th>Drop Location</th>
                                                                    <th>Phone Number</th>
                                                                    <th>Driver</th>
                                                                    <th>Fare</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                           
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                            <div class="custom-table mt-3 mb-3">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead class="fc-bg rounded-8">
                                                                <tr>
                                                                    <th>User</th>
                                                                    <!-- <th>Car Category</th> -->
                                                                    <th>Pickup Time</th>
                                                                    <th>Pickup Location</th>
                                                                    <th>Drop Location</th>
                                                                    <th>Phone Number</th>
                                                                    <th>Driver</th>
                                                                    <th>Fare</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            @if($in_progress_rides->isNotEmpty())
                                                                @foreach($in_progress_rides as $in_progress_ride)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="user-td d-flex gap-2">
                                                                                <div class="u-td-img">
                                                                                    @if(isset($in_progress_ride->users) && !empty($in_progress_ride->users->profile_pic))
                                                                                        <img src="{{asset('storage/profile')}}/{{$in_progress_ride->users->profile_pic}}" alt="User Image">
                                                                                    @else
                                                                                        <img src="{{asset('assets/images/place.png')}}" alt="User Image">
                                                                                    @endif
                                                                                </div>
                                                                                <div class="u-deails">
                                                                                    <p>{{$in_progress_ride->users->first_name}} {{$in_progress_ride->users->last_name}}</p>
                                                                                    <p class="color-90">{{$in_progress_ride->users->phone_number}}</p>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <!-- <td>Troy Regular</td> -->
                                                                        <!-- <td>{{ date('m/d/Y', strtotime($in_progress_ride->pick_up_date)) }} {{$in_progress_ride->pick_up_time}}</td> -->
                                                                        <td>{{ date('m/d/Y', strtotime($in_progress_ride->pick_up_date)) }} {{ \Carbon\Carbon::parse($in_progress_ride->pick_up_time)->format('H:i') }}</td>
                                                                        <td>{{$in_progress_ride->pick_up_address}}</td>
                                                                        <td>{{$in_progress_ride->drop_off_address}}</td>
                                                                        <td>
                                                                            <a href="tel:{{ $in_progress_ride->booking->user_phone_number ?? $in_progress_ride->phone_number }}" class="btn f-bg pe-4  rounded-30 fw-400">
                                                                                {{ $in_progress_ride->booking->user_phone_number ?? $in_progress_ride->phone_number }}
                                                                            </a>
                                                                        </td>
                                                                        <td>{{$in_progress_ride->booking->admin->name ?? null}}</td>

                                                                        <td>
                                                                            <div class="fare-btn">
                                                                                <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">${{$in_progress_ride->booking->subtotal}}</button>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="td-delete-icon d-flex gap-3">
                                                                            @if(Auth::guard('admin')->check()) 
                                                                                @php
                                                                                    $user = Auth::guard('admin')->user();
                                                                                @endphp

                                                                                @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                                                                    <a href="{{route('admin-ride-details',['ride_id'=>$in_progress_ride->id])}}">
                                                                                        <img src="{{asset('assets/images/td-eye.png')}}">
                                                                                    </a>                                                                                                                                          
                                                                                @endif
                                                                                @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                                                                    <a href="javascript:void(0);" class="delete-btn" data-ride-id="{{ $in_progress_ride->id }}">
                                                                                        <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                                                                                    </a>
                                                                                @endif
                                                                            @endif
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="tab-pane fade" id="pills-paused" role="tabpanel" aria-labelledby="pills-paused-tab" tabindex="0">
                                            <div class="custom-table mt-3 mb-3">
                                                    <div class="table-responsive">
                                                        <div class="d-flex justify-content-end gap-3">
                                                            <div class="date-img mt-2 relative" bis_skin_checked="1">
                                                                <img src="{{asset('assets/images/calendar.png')}}" alt="">
                                                                <input  type="text" name="date-picker-paused-ride" id="date-picker-paused-ride" class="form-control  light-gray fs-14" placeholder="YYYY-MM-DD">
                                                            </div>
                                                            <div class="search-container pb-3 relative" bis_skin_checked="1">
                                                                <input type="text" placeholder="Search..." class="search-input light-gray fs-14" name="paused_search_by" id="paused_search_by">    
                                                                <img src="{{asset('assets/images/search-icon(2).png')}}"  alt="search-icon" class="ride-search-icon">
                                                            </div>
                                                        </div>
                                                            
                                                        <table class="table w-100" id="paused-ride">
                                                            <thead class="fc-bg rounded-8">
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>User</th>
                                                                    <th>Pickup Date</th>
                                                                    <th>Pickup Time</th>
                                                                    <th>Pickup Location</th>
                                                                    <th>Drop Location</th>
                                                                    <th>Phone Number</th>
                                                                    <th>Driver</th>
                                                                    <th>Fare</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                           
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                                        <div class="custom-table mt-3 mb-3">
                                            <div class="table-responsive">
                                                <div class="d-flex justify-content-end gap-3">
                                                    <div class="date-img mt-2 relative" bis_skin_checked="1">
                                                        <img src="{{asset('assets/images/calendar.png')}}" alt="">
                                                        <input type="text" name="date-picker-complete-ride" id="date-picker-complete-ride" class="form-control light-gray fs-14" placeholder="YYYY-MM-DD">
                                                    </div>
                                                    <div class="search-container pb-3 relative" bis_skin_checked="1">
                                                        <input type="text" placeholder="Search..." class="search-input light-gray fs-14" name="complete_ride_search" id="complete_ride_search">
                                                        <img src="{{asset('assets/images/search-icon(2).png')}}" alt="search-icon" class="ride-search-icon">
                                                    </div>
                                                </div>
                                                <table class="table w-100" id="completed-rides">
                                                    <thead class="fc-bg rounded-8">
                                                        <tr>
                                                            <th class="px-4">ID</th>
                                                            <th class="px-4">User</th>
                                                            <th class="px-4">Pickup Date</th>
                                                            <th class="px-4">Pickup Time</th>
                                                            <th class="px-4">Pickup Location</th>
                                                            <th class="px-4">Drop Location</th>
                                                            <th class="px-4">Phone Number</th>
                                                            <th class="px-4">Driver</th>
                                                            <th class="px-4">Fare</th>
                                                            <th class="px-4">Payment status</th>
                                                            <th class="px-4">Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-upcoming" role="tabpanel" aria-labelledby="pills-ucoming-tab" tabindex="0">
                                            <div class="custom-table mt-3 mb-3">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead class="fc-bg rounded-8">
                                                        <tr>
                                                            <th>User</th>
                                                            <!-- <th>Car Category</th> -->
                                                            <th>Pickup Time</th>
                                                            <th>Pickup Location</th>
                                                            <th>Drop Location</th>
                                                            <th>Phone Number</th>
                                                            <th>Fare</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    @foreach($upcoming_rides as $upcoming_ride)
                                                        <tr>
                                                            <td>
                                                                <div class="user-td d-flex gap-2">
                                                                    <div class="u-td-img">
                                                                        @if(isset($upcoming_ride->users) && !empty($upcoming_ride->users->profile_pic))
                                                                            <img src="{{asset('storage/profile')}}/{{$upcoming_ride->users->profile_pic}}" alt="User Image">
                                                                        @else
                                                                            <img src="{{asset('assets/images/place.png')}}" alt="User Image">
                                                                        @endif
                                                                    </div>
                                                                    <div class="u-deails">
                                                                        <p>{{$upcoming_ride->users->first_name}} {{$upcoming_ride->users->last_name}}</p>
                                                                        <p class="color-90">{{$upcoming_ride->users->phone_number}}</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <!-- <td>Troy Regular</td> -->
                                                            <!-- <td>{{ date('m/d/Y', strtotime($upcoming_ride->pick_up_date)) }} {{$upcoming_ride->pick_up_time}}</td> -->
                                                             <td>{{ date('m/d/Y', strtotime($upcoming_ride->pick_up_date)) }} {{ \Carbon\Carbon::parse($upcoming_ride->pick_up_time)->format('H:i') }}</td>
                                                            <td>{{$upcoming_ride->pick_up_address}}</td>
                                                            <td>{{$upcoming_ride->drop_off_address}}</td>
                                                            <td>
                                                                <a href="tel:{{ $upcoming_ride->booking->user_phone_number ?? $upcoming_ride->phone_number }}" class="btn f-bg pe-4 rounded-30 fw-400">
                                                                    {{ $upcoming_ride->booking->user_phone_number ?? $upcoming_ride->phone_number }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <div class="fare-btn">
                                                                    <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">${{$upcoming_ride->booking->subtotal ?? 00}}</button>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="td-delete-icon d-flex gap-3">
                                                                @if(Auth::guard('admin')->check()) 
                                                                    @php
                                                                        $user = Auth::guard('admin')->user();
                                                                    @endphp

                                                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                                                        <a href="{{route('admin-ride-details',['ride_id'=>$upcoming_ride->id])}}">
                                                                            <img src="{{asset('assets/images/td-eye.png')}}">
                                                                        </a>                                                                                                                                                                                                  
                                                                    @endif
                                                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                                                        <a href="javascript:void(0);" class="delete-btn" data-ride-id="{{ $upcoming_ride->id }}">
                                                                            <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-pre" role="tabpanel" aria-labelledby="pills-pre-tab" tabindex="0">
                                            <div class="custom-table mt-3 mb-3">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead class="fc-bg rounded-8">
                                                            <tr>
                                                                <th>User</th>
                                                                <!-- <th>Car Category</th> -->
                                                                <th>Pickup Time</th>
                                                                <th>Pickup Location</th>
                                                                <th>Drop Location</th>
                                                                <th>Phone Number</th>
                                                                <th>Fare</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach($pre_cancelled_rides as $pre_cancelled_ride)
                                                            <tr>
                                                                <td>
                                                                    <div class="user-td d-flex gap-2">
                                                                        <div class="u-td-img">
                                                                            @if(isset($pre_cancelled_ride->users) && !empty($pre_cancelled_ride->users->profile_pic))
                                                                                <img src="{{asset('storage/profile')}}/{{$pre_cancelled_ride->users->profile_pic}}" alt="User Image">
                                                                            @else
                                                                                <img src="{{asset('assets/images/place.png')}}" alt="User Image">
                                                                            @endif
                                                                        </div>
                                                                        <div class="u-deails">
                                                                            <p>{{$pre_cancelled_ride->users->first_name}} {{$pre_cancelled_ride->users->last_name}}</p>
                                                                            <p class="color-90">{{$pre_cancelled_ride->users->phone_number}}</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <!-- <td>Troy Regular</td> -->
                                                                <!-- <td>{{ date('m/d/Y', strtotime($pre_cancelled_ride->pick_up_date)) }} {{$pre_cancelled_ride->pick_up_time}}</td> -->
                                                                <td>{{ date('m/d/Y', strtotime($pre_cancelled_ride->pick_up_date)) }} {{ \Carbon\Carbon::parse($pre_cancelled_ride->pick_up_time)->format('H:i') }}</td>

                                                                <td>{{$pre_cancelled_ride->pick_up_address}}</td>
                                                                <td>{{$pre_cancelled_ride->drop_off_address}}</td>
                                                                <td>
                                                                    <a href="tel:{{ $pre_cancelled_ride->booking->user_phone_number ?? $pre_cancelled_ride->phone_number }}" class="btn f-bg pe-4  rounded-30 fw-400">
                                                                        {{ $pre_cancelled_ride->booking->user_phone_number ?? $pre_cancelled_ride->phone_number }}
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <div class="fare-btn">
                                                                        <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">${{$pre_cancelled_ride->booking->subtotal}}</button>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="td-delete-icon d-flex gap-3">
                                                                    @if(Auth::guard('admin')->check()) 
                                                                        @php
                                                                            $user = Auth::guard('admin')->user();
                                                                        @endphp

                                                                        @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                                                            <a href="{{route('admin-ride-details',['ride_id'=>$pre_cancelled_ride->id])}}">
                                                                                <img src="{{asset('assets/images/td-eye.png')}}">
                                                                            </a>                                                                                                                                                                                                                                                                
                                                                        @endif
                                                                        @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                                                            <a href="javascript:void(0);" class="delete-btn" data-ride-id="{{ $pre_cancelled_ride->id }}">
                                                                                <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                        </div>
                                        <div class="tab-pane fade" id="pills-driver" role="tabpanel" aria-labelledby="pills-driver-tab" tabindex="0">
                                            <div class="custom-table mt-3 mb-3">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead class="fc-bg rounded-8">
                                                            <tr>
                                                                <th>User</th>
                                                                <!-- <th>Car Category</th> -->
                                                                <th>Pickup Time</th>
                                                                <th>Pickup Location</th>
                                                                <th>Drop Location</th>
                                                                <th>Phone Number</th>
                                                                <th>Driver</th>
                                                                <th>Fare</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($cancelled_by_drivers as $cancelled_by_driver)
                                                                <tr>
                                                                    <td>
                                                                        <div class="user-td d-flex gap-2">
                                                                            <div class="u-td-img">
                                                                                @if(isset($cancelled_by_driver->users) && !empty($cancelled_by_driver->users->profile_pic))
                                                                                    <img src="{{asset('storage/profile')}}/{{$cancelled_by_driver->users->profile_pic}}" alt="User Image">
                                                                                @else
                                                                                    <img src="{{asset('assets/images/place.png')}}" alt="User Image">
                                                                                @endif
                                                                            </div>
                                                                            <div class="u-deails">
                                                                                <p>{{$cancelled_by_driver->users->first_name}} {{$cancelled_by_driver->users->last_name}}</p>
                                                                                <p class="color-90">{{$cancelled_by_driver->users->phone_number}}</p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <!-- <td>Troy Regular</td> -->
                                                                    <!-- <td>{{ date('m/d/Y', strtotime($cancelled_by_driver->pick_up_date)) }} {{$cancelled_by_driver->pick_up_time}}</td> -->
                                                                    <td>{{ date('m/d/Y', strtotime($cancelled_by_driver->pick_up_date)) }} {{ \Carbon\Carbon::parse($cancelled_by_driver->pick_up_time)->format('H:i') }}</td>
                                                                    <td>{{$cancelled_by_driver->pick_up_address}}</td>
                                                                    <td>{{$cancelled_by_driver->drop_off_address}}</td>
                                                                    <td>{{$cancelled_by_driver->booking->user_phone_number ?? $cancelled_by_driver->phone_number}}</td>
                                                                    <td>{{$cancelled_by_driver->booking->admin->name ?? null}}</td>
                                                                    <td>
                                                                        <div class="fare-btn">
                                                                            <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">${{$cancelled_by_driver->booking->subtotal}}</button>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="td-delete-icon d-flex gap-3">
                                                                        @if(Auth::guard('admin')->check()) 
                                                                            @php
                                                                                $user = Auth::guard('admin')->user();
                                                                            @endphp

                                                                            @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                                                                <a href="{{route('admin-ride-details',['ride_id'=>$cancelled_by_driver->id])}}">
                                                                                    <img src="{{asset('assets/images/td-eye.png')}}">
                                                                                </a>                                                                                                                                                                                                                                                                                                                     
                                                                            @endif
                                                                            @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                                                                <a href="javascript:void(0);" class="delete-btn" data-ride-id="{{ $cancelled_by_driver->id }}">
                                                                                    <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
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
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr("#date-picker-paused-ride, #date-picker-complete-ride, #date-picker-pending-ride, #date-picker-confirm-ride", {
        dateFormat: "Y-m-d",
    });

    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#pending-ride').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('pending-ride-data') }}',
                data: function(d) {
                    d.custom_search = $('#search_by').val();
                    d.date_filter = $('#date-picker-pending-ride').val();
                }
            },
            searching: false,
            lengthChange: false, 
            order: [[0, 'desc']],
                columns: [
                {
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return row.id;
                    },
                    searchable: false
                },
                {
                    data: null, // User information column
                    name: 'user',
                    render: function(data, type, row) {
                        return `
                            <div class="user-td d-flex gap-2">
                                <div class="u-deails">
                                    <p>${row.users ? row.users.first_name + ' ' + row.users.last_name : ''}</p>
                                    <p class="color-90">${row.users && row.users.phone_number != null ? row.users.phone_number : ''}</p>
                                </div>
                            </div>`;
                    }
                },
                {
                    data: null,
                    name: 'pick_up_date',
                    render: function(data, type, row) {
                         return `${row.pick_up_date}`;
                    }
                },
                {
                    data: null,
                    name: 'pick_up_time',
                    render: function(data, type, row) {
                         return `${row.pick_up_time}`;
                    }
                },
                { data: 'pick_up_address', name: 'pick_up_address' },
                { data: 'drop_off_address', name: 'drop_off_address' },
                {
                    data: null,
                    name: 'phone_number',
                    render: function(data, type, row) {
                        const phone = row.booking?.user_phone_number ?? row.phone_number ?? '';
                        return `
                            <a href="tel:${phone}" class="btn f-bg pe-4 rounded-30 fw-400">
                                ${phone}
                            </a>`;
                    }
                },
                {
                    data: null,
                    name: 'fare',
                    orderable: false,
                    searchable: false, 
                    render: function(data, type, row) {
                        let formattedSubtotal = row.booking ? row.booking.subtotal : '';
                        
                        // Format the subtotal value as currency
                        if (formattedSubtotal !== '') {
                            formattedSubtotal = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(formattedSubtotal);
                        }
                        
                        return `
                            <div class="fare-btn">
                                <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
                                    ${formattedSubtotal}
                                </button>
                            </div>`;
                    }
                },
                {
                    data: 'action', // Action column
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var url = '{{ route('admin-ride-details', ['ride_id' => '__ride_id__']) }}';
                        url = url.replace('__ride_id__', row.id); // Replace the placeholder with the actual row.id
                        return `
                            <div class="td-delete-icon d-flex gap-3">
                                @if(Auth::guard('admin')->check()) 
                                    @php
                                        $user = Auth::guard('admin')->user();
                                    @endphp

                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                          <a href="${url}">
                                            <img src="{{ asset('assets/images/td-eye.png') }}">
                                        </a>
                                    @endif
                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                        <a href="javascript:void(0);" class="delete-btn" data-ride-id=${row.id}>
                                            <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                                        </a>
                                    @endif
                                @endif
                            </div>`;
                    }
                }
            ]
        });

        // Custom search input (for Name, Email, Phone)
        $('#search_by').on('keyup', function() {
            table.ajax.reload();
        });

        $('#date-picker-pending-ride').on('change', function() {
            table.ajax.reload();
        });

    });

</script>


<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#completed-rides').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('completed-ride-data') }}',
                data: function(d) {
                    d.custom_search = $('#complete_ride_search').val();
                    d.date_search = $('#date-picker-complete-ride').val();
                }
            },
            searching: false,
            lengthChange: false, 
            order: [[0, 'desc']],
                columns: [
                {
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return row.id;
                    },
                    searchable: false
                },
                {
                    data: null, // User information column
                    name: 'user',
                    render: function(data, type, row) {
                        return `
                            <div class="user-td d-flex gap-2">
                                <div class="u-deails">
                                    <p>${row.users ? row.users.first_name + ' ' + row.users.last_name : ''}</p>
                                    <p class="color-90">${row.users && row.users.phone_number != null ? row.users.phone_number : ''}</p>
                                </div>
                            </div>`;
                    }
                },
                {
                    data: null,
                    name: 'pick_up_date',
                    render: function(data, type, row) {
                        return `${row.pick_up_date}`;
                    }
                },
                {
                    data: null,
                    name: 'pick_up_time',
                    render: function(data, type, row) {
                        return `${row.pick_up_time}`;
                    }
                },
                { data: 'pick_up_address', name: 'pick_up_address' },
                { data: 'drop_off_address', name: 'drop_off_address' },
                {
                    data: null,
                    name: 'phone_number',
                    render: function(data, type, row) {
                        const phone = row.booking?.user_phone_number ?? row.phone_number ?? '';
                        return `
                            <a href="tel:${phone}" class="btn f-bg pe-4 rounded-30 fw-400">
                                ${phone}
                            </a>`;
                    }
                },
                {
                    data: null,
                    name: 'admin_name',
                    render: function(data, type, row) {
                        const name = row.booking.admin ? row.booking.admin.name :'';
                        return name;
                    }
                },
                {
                    data: null,
                    name: 'fare',
                    orderable: false,
                    searchable: false, 
                    render: function(data, type, row) {
                        let formattedSubtotal = row.booking ? row.booking.subtotal : '';
                        
                        // Format the subtotal value as currency
                        if (formattedSubtotal !== '') {
                            formattedSubtotal = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(formattedSubtotal);
                        }
                        
                        return `
                            <div class="fare-btn">
                                <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
                                    ${formattedSubtotal}
                                </button>
                            </div>`;
                    }
                },
                {
                    data: null,
                    name: 'payment_status',
                    render: function(data, type, row) {
                        const paymentStatus = row.payment_status ? row.payment_status.status :'';
                        return paymentStatus;
                    }
                },
                {
                    data: 'action', // Action column
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var url = '{{ route('admin-ride-details', ['ride_id' => '__ride_id__']) }}';
                        url = url.replace('__ride_id__', row.id); // Replace the placeholder with the actual row.id
                        return `
                            <div class="td-delete-icon d-flex gap-3">
                                @if(Auth::guard('admin')->check()) 
                                    @php
                                        $user = Auth::guard('admin')->user();
                                    @endphp

                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                          <a href="${url}">
                                            <img src="{{ asset('assets/images/td-eye.png') }}">
                                        </a>
                                    @endif
                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                        <a href="javascript:void(0);" class="delete-btn" data-ride-id=${row.id}>
                                            <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                                        </a>
                                    @endif
                                @endif
                            </div>`;
                    }
                }
            ]
        });

        // Custom search input (for Name, Email, Phone)
        $('#complete_ride_search').on('keyup', function() {
            table.ajax.reload();
        });

        // Custom search input (for Name, Email, Phone)
        $('#date-picker-complete-ride').on('change', function() {
            table.ajax.reload();
        });

    });

</script>


<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#paused-ride').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('paused-ride-data') }}',
                data: function(d) {
                    d.custom_search = $('#paused_search_by').val();
                    d.date_filter = $('#date-picker-paused-ride').val();
                }
            },
            searching: false,
            lengthChange: false, 
            order: [[0, 'desc']],
                columns: [
                {
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return row.id;
                    },
                    searchable: false
                },
                {
                    data: null, // User information column
                    name: 'user',
                    render: function(data, type, row) {
                        return `
                            <div class="user-td d-flex gap-2">
                                <div class="u-deails">
                                    <p>${row.users ? row.users.first_name + ' ' + row.users.last_name : ''}</p>
                                    <p class="color-90">${row.users && row.users.phone_number != null ? row.users.phone_number : ''}</p>
                                </div>
                            </div>`;
                    }
                },
                {
                    data: null,
                    name: 'pick_up_date',
                    render: function(data, type, row) {
                        return `${row.pick_up_date}`;
                    }
                },
                {
                    data: null,
                    name: 'pick_up_time',
                    render: function(data, type, row) {
                        return `${row.pick_up_time}`;
                    }
                },
                { data: 'pick_up_address', name: 'pick_up_address' },
                { data: 'drop_off_address', name: 'drop_off_address' },
                {
                    data: null,
                    name: 'phone_number',
                    render: function(data, type, row) {
                        const phone = row.booking?.user_phone_number ?? row.phone_number ?? '';
                        return `
                            <a href="tel:${phone}" class="btn f-bg pe-4  rounded-30 fw-400">
                                ${phone}
                            </a>`;
                    }
                },
                {
                    data: null,
                    name: 'admin_name',
                    render: function(data, type, row) {
                        const name = row.booking.admin ? row.booking.admin.name :'';
                        return name;
                    }
                },
                {
                    data: null,
                    name: 'fare',
                    orderable: false,
                    searchable: false, 
                    render: function(data, type, row) {
                        let formattedSubtotal = row.booking ? row.booking.subtotal : '';
                        
                        // Format the subtotal value as currency
                        if (formattedSubtotal !== '') {
                            formattedSubtotal = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(formattedSubtotal);
                        }
                        
                        return `
                            <div class="fare-btn">
                                <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
                                    ${formattedSubtotal}
                                </button>
                            </div>`;
                    }
                },
                {
                    data: 'action', // Action column
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var url = '{{ route('admin-ride-details', ['ride_id' => '__ride_id__']) }}';
                        url = url.replace('__ride_id__', row.id); // Replace the placeholder with the actual row.id
                        return `
                            <div class="td-delete-icon d-flex gap-3">
                                @if(Auth::guard('admin')->check()) 
                                    @php
                                        $user = Auth::guard('admin')->user();
                                    @endphp

                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                          <a href="${url}">
                                            <img src="{{ asset('assets/images/td-eye.png') }}">
                                        </a>
                                    @endif
                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                        <a href="javascript:void(0);" class="delete-btn" data-ride-id=${row.id}>
                                            <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                                        </a>
                                    @endif
                                @endif
                            </div>`;
                    }
                }
            ]
        });

        // Custom search input (for Name, Email, Phone)
        $('#paused_search_by').on('keyup', function() {
            table.ajax.reload();
        });

         $('#date-picker-paused-ride').on('change', function() {
            table.ajax.reload();
        });

    });
</script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#confirm-ride').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('accepted-ride-data') }}',
                data: function(d) {
                    d.custom_search = $('#confirm_search_by').val();
                    d.date_filter = $('#date-picker-confirm-ride').val();
                }
            },
            searching: false,
            lengthChange: false, 
            order: [[0, 'desc']],
                columns: [
                {
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return row.id;
                    },
                    searchable: false
                },
                {
                    data: null, // User information column
                    name: 'user',
                    render: function(data, type, row) {
                        return `
                            <div class="user-td d-flex gap-2">
                                <div class="u-deails">
                                    <p>${row.users ? row.users.first_name + ' ' + row.users.last_name : ''}</p>
                                    <p class="color-90">${row.users && row.users.phone_number != null ? row.users.phone_number : ''}</p>
                                </div>
                            </div>`;
                    }
                },
                {
                    data: null,
                    name: 'pick_up_date',
                    render: function(data, type, row) {
                        return `${row.pick_up_date}`;
                    }
                },
                {
                    data: null,
                    name: 'pick_up_time',
                    render: function(data, type, row) {
                        return `${row.pick_up_time}`;
                    }
                },
                { data: 'pick_up_address', name: 'pick_up_address' },
                { data: 'drop_off_address', name: 'drop_off_address' },
                {
                    data: null,
                    name: 'phone_number',
                    render: function(data, type, row) {
                        const phone = row.booking?.user_phone_number ?? row.phone_number ?? '';
                        return `
                            <a href="tel:${phone}" class="btn f-bg pe-4  rounded-30 fw-400">
                                ${phone}
                            </a>`;
                    }
                },
                {
                    data: null,
                    name: 'admin_name',
                    render: function(data, type, row) {
                        const name = row.booking.admin ? row.booking.admin.name :'';
                        return name;
                    }
                },
                {
                    data: null,
                    name: 'fare',
                    orderable: false,
                    searchable: false, 
                    render: function(data, type, row) {
                        let formattedSubtotal = row.booking ? row.booking.subtotal : '';
                        
                        // Format the subtotal value as currency
                        if (formattedSubtotal !== '') {
                            formattedSubtotal = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(formattedSubtotal);
                        }
                        
                        return `
                            <div class="fare-btn">
                                <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
                                    ${formattedSubtotal}
                                </button>
                            </div>`;
                    }
                },
                {
                    data: 'action', // Action column
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var url = '{{ route('admin-ride-details', ['ride_id' => '__ride_id__']) }}';
                        url = url.replace('__ride_id__', row.id); // Replace the placeholder with the actual row.id
                        return `
                            <div class="td-delete-icon d-flex gap-3">
                                @if(Auth::guard('admin')->check()) 
                                    @php
                                        $user = Auth::guard('admin')->user();
                                    @endphp

                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                          <a href="${url}">
                                            <img src="{{ asset('assets/images/td-eye.png') }}">
                                        </a>
                                    @endif
                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                        <a href="javascript:void(0);" class="delete-btn" data-ride-id=${row.id}>
                                            <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                                        </a>
                                    @endif
                                @endif
                            </div>`;
                    }
                }
            ]
        });

        // Custom search input (for Name, Email, Phone)
        $('#confirm_search_by').on('keyup', function() {
            table.ajax.reload();
        });

        $('#date-picker-confirm-ride').on('change', function() {
            table.ajax.reload();
        });

    });

</script>


<!-- JavaScript to trigger SweetAlert and delete -->
<script>

    $('#pending-ride, #completed-rides, #confirm-ride, #paused-ride').on('click', '.delete-btn', function(event) {
        var rideId = $(this).data('ride-id');
        // SweetAlert confirmation popup
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to delete this ride?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the delete route if confirmed
                window.location.href = '{{ route("admin-ride-delete", ["ride_id" => "__ride_id__"]) }}'.replace("__ride_id__", rideId);
            }
        });
    });

    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function(event) {
            var rideId = this.getAttribute('data-ride-id');
            // SweetAlert confirmation popup
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this ride?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete route if confirmed
                    window.location.href = '{{ route("admin-ride-delete", ["ride_id" => "__ride_id__"]) }}'.replace("__ride_id__", rideId);
                }
            });
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