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
                                                    <table class="table" id="pending-ride">
                                                        <thead class="fc-bg rounded-8">
                                                            <tr>
                                                                <th>ID</th>
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
                                                        <!-- <tbody>
                                                            @if($pending_rides->isNotEmpty())
                                                                @foreach($pending_rides as $pending_ride)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="user-td d-flex gap-2">
                                                                                <div class="u-td-img">
                                                                                    @if(isset($pending_ride->users) && !empty($pending_ride->users->profile_pic))
                                                                                        <img src="{{asset('storage/profile')}}/{{$pending_ride->users->profile_pic}}" alt="User Image">
                                                                                    @else
                                                                                        <img src="{{asset('assets/images/place.png')}}" alt="User Image">
                                                                                    @endif
                                                                                </div>
                                                                                <div class="u-deails">
                                                                                    <p>{{$pending_ride->users->first_name}} {{$pending_ride->users->last_name}}</p>
                                                                                    <p class="color-90">{{$pending_ride->users->phone_number}}</p>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        
                                                                        <td>{{ date('m/d/Y', strtotime($pending_ride->pick_up_date)) }} {{$pending_ride->pick_up_time}}</td>
                                                                        <td>{{$pending_ride->pick_up_address}}</td>
                                                                        <td>{{$pending_ride->drop_off_address}}</td>
                                                                        <td>
                                                                            <a href="tel:{{ $pending_ride->booking->user_phone_number ?? $pending_ride->phone_number }}" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
                                                                                {{ $pending_ride->booking->user_phone_number ?? $pending_ride->phone_number }}
                                                                            </a>
                                                                        </td>

                                                                        <td>
                                                                            <div class="fare-btn">
                                                                                <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">${{$pending_ride->booking->subtotal}}</button>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="td-delete-icon d-flex gap-3">
                                                                                @if(Auth::guard('admin')->check()) 
                                                                                    @php
                                                                                        $user = Auth::guard('admin')->user();
                                                                                    @endphp

                                                                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                                                                        <a href="{{ route('admin-ride-details', ['ride_id' => $pending_ride->id]) }}">
                                                                                            <img src="{{ asset('assets/images/td-eye.png') }}">
                                                                                        </a>
                                                                                    @endif
                                                                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                                                                        <a href="javascript:void(0);" class="delete-btn" data-ride-id="{{ $pending_ride->id }}">
                                                                                            <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                                                                                        </a>
                                                                                    @endif
                                                                                @endif

                                                                                
                                                                            
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody> -->
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="total-drivers-box d-flex justify-content-end align-items-center mb-5 pb-5 mt-md-0 mt-3">
                                                @if($pending_rides->isNotEmpty())
                                                    @if ($pending_rides->hasPages())
                                                        <div class="custom-pagination">
                                                            <div class="pagination-info">
                                                                {{ $pending_rides->firstItem() }}–{{ $pending_rides->lastItem() }} of {{ $pending_rides->total() }} items
                                                            </div>
                                                            <ul class="pagination">
                                                                {{-- Previous Page Link --}}
                                                                @if ($pending_rides->onFirstPage())
                                                                    <li class="page-item disabled"><span>&lt;</span></li>
                                                                @else
                                                                    <li class="page-item">
                                                                        <a href="{{ $pending_rides->previousPageUrl() }}" rel="prev">&lt;</a>
                                                                    </li>
                                                                @endif

                                                                {{-- Pagination Elements --}}
                                                                @foreach ($pending_rides->getUrlRange(1, $pending_rides->lastPage()) as $page => $url)
                                                                    @if ($page == $pending_rides->currentPage())
                                                                        <li class="page-item active"><span>{{ $page }}</span></li>
                                                                    @else
                                                                        <li class="page-item"><a href="{{ $url }}">{{ $page }}</a></li>
                                                                    @endif
                                                                @endforeach

                                                                {{-- Next Page Link --}}
                                                                @if ($pending_rides->hasMorePages())
                                                                    <li class="page-item">
                                                                        <a href="{{ $pending_rides->nextPageUrl() }}" rel="next">&gt;</a>
                                                                    </li>
                                                                @else
                                                                    <li class="page-item disabled"><span>&gt;</span></li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="pills-confirm" role="tabpanel" aria-labelledby="pills-confirm-tab" tabindex="0">
                                            <div class="custom-table mt-3 mb-3">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead class="fc-bg rounded-8">
                                                                <tr>
                                                                    <th>User</th>
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

                                                            @foreach($confirmed_rides as $confirmed_ride)
                                                                <tr>
                                                                    <td>
                                                                        <div class="user-td d-flex gap-2">
                                                                            <div class="u-td-img">
                                                                                @if(isset($confirmed_ride->users) && !empty($confirmed_ride->users->profile_pic))
                                                                                    <img src="{{asset('storage/profile')}}/{{$confirmed_ride->users->profile_pic}}" alt="User Image">
                                                                                @else
                                                                                    <img src="{{asset('assets/images/place.png')}}" alt="User Image">
                                                                                @endif
                                                                            </div>
                                                                            <div class="u-deails">
                                                                                <p>{{$confirmed_ride->users->first_name}} {{$confirmed_ride->users->last_name}}</p>
                                                                                <p class="color-90">{{$confirmed_ride->users->phone_number}}</p>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <!-- <td>Troy Regular</td> -->
                                                                    <td>{{ date('m/d/Y', strtotime($confirmed_ride->pick_up_date)) }} {{$confirmed_ride->pick_up_time}}</td>
                                                                    <td>{{$confirmed_ride->pick_up_address}}</td>
                                                                    <td>{{$confirmed_ride->drop_off_address}}</td>
                                                                    <td>
                                                                        <a href="tel:{{ $confirmed_ride->booking->user_phone_number ?? $confirmed_ride->phone_number }}" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
                                                                            {{ $confirmed_ride->booking->user_phone_number ?? $confirmed_ride->phone_number }}
                                                                        </a>
                                                                    </td>
                                                                    <td>{{$confirmed_ride->booking->admin->name ?? null}}</td>
                                                                    <td>
                                                                        <div class="fare-btn">
                                                                            <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">${{$confirmed_ride->booking->subtotal}}</button>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="td-delete-icon d-flex gap-3">
                                                                        @if(Auth::guard('admin')->check()) 
                                                                            @php
                                                                                $user = Auth::guard('admin')->user();
                                                                            @endphp

                                                                            @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                                                            <a href="{{route('admin-ride-details',['ride_id'=>$confirmed_ride->id])}}">
                                                                                <img src="{{asset('assets/images/td-eye.png')}}">
                                                                            </a>                                                                         
                                                                            @endif
                                                                            @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                                                            <a href="javascript:void(0);" class="delete-btn" data-ride-id="{{ $confirmed_ride->id }}">
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
                                                                        <td>{{ date('m/d/Y', strtotime($in_progress_ride->pick_up_date)) }} {{$in_progress_ride->pick_up_time}}</td>
                                                                        <td>{{$in_progress_ride->pick_up_address}}</td>
                                                                        <td>{{$in_progress_ride->drop_off_address}}</td>
                                                                        <td>
                                                                            <a href="tel:{{ $in_progress_ride->booking->user_phone_number ?? $in_progress_ride->phone_number }}" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
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
                                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
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
                                                            <th>Payment status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    @foreach($completed_rides as $completed_ride)
                                                        <tr>
                                                            <td>
                                                                <div class="user-td d-flex gap-2">
                                                                    <div class="u-td-img">
                                                                        @if(isset($completed_ride->users) && !empty($completed_ride->users->profile_pic))
                                                                            <img src="{{asset('storage/profile')}}/{{$completed_ride->users->profile_pic}}" alt="User Image">
                                                                        @else
                                                                            <img src="{{asset('assets/images/place.png')}}" alt="User Image">
                                                                        @endif
                                                                    </div>
                                                                    <div class="u-deails">
                                                                        <p>{{$completed_ride->users->first_name}} {{$completed_ride->users->last_name}}</p>
                                                                        <p class="color-90">{{$completed_ride->users->phone_number}}</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <!-- <td>Troy Regular</td> -->
                                                            <td>{{ date('m/d/Y', strtotime($completed_ride->pick_up_date)) }} {{$completed_ride->pick_up_time}}</td>
                                                            <td>{{$completed_ride->pick_up_address}}</td>
                                                            <td>{{$completed_ride->drop_off_address}}</td>
                                                            <td>
                                                                <a href="tel:{{ $completed_ride->booking->user_phone_number ?? $completed_ride->phone_number }}" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
                                                                    {{ $completed_ride->booking->user_phone_number ?? $completed_ride->phone_number }}
                                                                </a>
                                                            </td>
                                                            <td>{{$completed_ride->booking->admin->name ?? null}}</td>
                                                            <td>
                                                                <div class="fare-btn">
                                                                    <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">${{$completed_ride->booking->subtotal}}</button>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="fare-btn">
                                                                    <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">{{$completed_ride->paymentStatus->status}}</button>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="td-delete-icon d-flex gap-3">
                                                                @if(Auth::guard('admin')->check()) 
                                                                    @php
                                                                        $user = Auth::guard('admin')->user();
                                                                    @endphp

                                                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                                                    <a href="{{route('admin-ride-details',['ride_id'=>$completed_ride->id])}}">
                                                                        <img src="{{asset('assets/images/td-eye.png')}}">
                                                                    </a>
                                                                                                                                                                                                                                                                    
                                                                    @endif
                                                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                                                        <a href="javascript:void(0);" class="delete-btn" data-ride-id="{{ $completed_ride->id }}">
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
                                                            <td>{{ date('m/d/Y', strtotime($upcoming_ride->pick_up_date)) }} {{$upcoming_ride->pick_up_time}}</td>
                                                            <td>{{$upcoming_ride->pick_up_address}}</td>
                                                            <td>{{$upcoming_ride->drop_off_address}}</td>
                                                            <td>
                                                                <a href="tel:{{ $upcoming_ride->booking->user_phone_number ?? $upcoming_ride->phone_number }}" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
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
                                                                <td>{{ date('m/d/Y', strtotime($pre_cancelled_ride->pick_up_date)) }} {{$pre_cancelled_ride->pick_up_time}}</td>
                                                                <td>{{$pre_cancelled_ride->pick_up_address}}</td>
                                                                <td>{{$pre_cancelled_ride->drop_off_address}}</td>
                                                                <td>
                                                                    <a href="tel:{{ $pre_cancelled_ride->booking->user_phone_number ?? $pre_cancelled_ride->phone_number }}" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
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
                                                                    <td>{{ date('m/d/Y', strtotime($cancelled_by_driver->pick_up_date)) }} {{$cancelled_by_driver->pick_up_time}}</td>
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


<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#pending-ride').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('pending-ride-data') }}',
                data: function(d) {
                    d.custom_search = $('#search_by').val();
                }
            },
            searching: false,
            lengthChange: false, 
            order: [[0, 'desc']],
                columns: [
                {
                    data: null,
                    name: 'id',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: null, // User information column
                    name: 'user',
                    render: function(data, type, row) {
                        return `
                            <div class="user-td d-flex gap-2">
                                <div class="u-td-img">
                                    ${row.users && row.users.profile_pic
                                        ? `<img src="{{asset('storage/profile')}}/${row.users.profile_pic}" alt="User Image">`
                                        : `<img src="{{asset('assets/images/place.png')}}" alt="User Image">`}
                                </div>
                                <div class="u-deails">
                                    <p>${row.users ? row.users.first_name + ' ' + row.users.last_name : ''}</p>
                                    <p class="color-90">${row.users ? row.users.phone_number : ''}</p>
                                </div>
                            </div>`;
                    }
                },
                {
                    data: null, // Pick-up date and time column
                    name: 'pick_up_date_time',
                    render: function(data, type, row) {
                        const formattedDate = new Date(row.pick_up_date);
                        const formattedDateStr = `${formattedDate.getMonth() + 1}/${formattedDate.getDate()}/${formattedDate.getFullYear()}`;
                        return `${formattedDateStr} ${row.pick_up_time}`;
                    }
                },
                { data: 'pick_up_address', name: 'pick_up_address' },
                { data: 'drop_off_address', name: 'drop_off_address' },
                {
                    data: null, // Phone number column with a call button
                    name: 'phone_number',
                    render: function(data, type, row) {
                        const phone = row.booking ? row.booking.user_phone_number : row.phone_number;
                        return `
                            <a href="tel:${phone}" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
                                ${phone}
                            </a>`;
                    }
                },
                {
                    data: null, // Fare column
                    name: 'fare',
                    render: function(data, type, row) {
                        return `
                            <div class="fare-btn">
                                <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">
                                    $${row.booking ? row.booking.subtotal : ''}
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
                        return null;
                    }
                }
            ]
        });

        // Custom search input (for Name, Email, Phone)
        $('#search_by').on('keyup', function() {
            table.ajax.reload();
        });

        // Filter by Active/Inactive users
        $('#status_filter').on('change', function() {
            table.ajax.reload();
        });

    });

</script>

<!-- JavaScript to trigger SweetAlert and delete -->
<script>
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
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