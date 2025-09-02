@extends('admin.layouts.master')
@section('title', 'Dashboard')
@section('content')

@php
    $user =  Auth::guard('admin')->user();
    $roles =  $user->roles->first()->name;

@endphp
<div class="know-know-content">
    <div class="know-content">
        <div class="row">
            <div class="col-md-6">
                <div class="konwlege-base mt-3 mt-4 mb-3">
                    @if($roles == 'admin' || $roles == 'super-admin')
                        <div class="col-md-10 mb-3">  
                            <p>Auto Reject</P>             
                            <label class="switch">
                                <input type="checkbox" class="user_auto_reject_ride" {{ !empty($user->auto_reject_ride) ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>   
                        </div>
                    @endif
                    @if($roles != 'admin' && $roles != 'super-admin')
                        <div class="col-md-10 mb-3">  
                            <p>Enable/Disable Ride Requests</P>             
                            <label class="switch">
                                <input type="checkbox" class="status_user" {{ !empty($user->login_check) ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>   
                        </div>
                    @endif
                    <div class="row gy-3">
                    <h5 class="a-h-color fw-600">Knowledge base</h5>
                    @if($roles === 'admin' || $roles === 'super-admin')
                        <div class="col-md-4">
                            <a href="{{route('admin-passenger')}}">
                                <div class="total-info-user rounded-8 blue-light p-2">
                                    <div class="file-info d-flex justify-content-between ">
                                        <div class="t-user">
                                            <img src="{{asset('assets/images/folder-open1.png')}}">
                                            <span class="fw-600 fs-20 a-m-color">{{$user_count}}</span>

                                        </div>
                                        <div class="opn-arrow">
                                            <img src="{{asset('assets/images/opn-1.png')}}">
                                        </div>
                                    </div>
                                    <p class="a-m-color fs-10">Total Registered Users</p>
                                </div>
                            </a>
                        </div>
                    @endif

                        <div class="col-md-4">
                            <a href="{{route('admin-rides')}}">
                                <div class="total-info-ride rounded-8 blue-light p-2 t-r-box">
                                    <div class="file-info d-flex justify-content-between">
                                        <div class="t-user">
                                            <img src="{{asset('assets/images/folder-open1.png')}}">
                                            <span class="fw-600 fs-20 ">{{$total_request_rides_count}}</span>
                                        </div>
                                        <div class="opn-arrow">
                                            <img src="{{asset('assets/images/opn-2.png')}}">
                                        </div>
                                    </div>

                                    <p class=" fs-10">Total Rides Requested</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('admin-rides')}}">
                                <div class="total-info-complete rounded-8 blue-light p-2 t-c-r-box">
                                    <div class="file-info d-flex justify-content-between">
                                        <div class="t-user">
                                            <img src="{{asset('assets/images/folder-open1.png')}}">
                                            <span class="fw-600 fs-20 ">{{$total_completed_rides_count}}</span>
                                        </div>
                                        <div class="opn-arrow">
                                            <img src="{{asset('assets/images/open-3.png')}}">
                                        </div>
                                    </div>
                                    <p class=" fs-10">Total Completed Rides</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="{{route('admin-rides')}}">
                                <div class="total-info-user rounded-8 blue-light p-2">
                                    <div class="file-info d-flex justify-content-between ">
                                        <div class="t-user">
                                            <img src="{{asset('assets/images/folder-open1.png')}}">
                                            <span class="fw-600 fs-20 a-m-color">${{number_format($paid_amount,2)}}</span>

                                        </div>
                                        <div class="opn-arrow">
                                            <img src="{{asset('assets/images/opn-1.png')}}">
                                        </div>
                                    </div>
                                    <p class="a-m-color fs-10">Ride Amount</p>
                                </div>
                            </a>
                        </div>

                         <div class="col-md-4">
                            <a href="{{route('admin-rides')}}">
                                <div class="total-info-user rounded-8 blue-light p-2">
                                    <div class="file-info d-flex justify-content-between ">
                                        <div class="t-user">
                                            <img src="{{asset('assets/images/folder-open1.png')}}">
                                            <span class="fw-600 fs-20 a-m-color">${{number_format($total_tip_amount,2)}}</span>
                                        </div>
                                        <div class="opn-arrow">
                                            <img src="{{asset('assets/images/opn-1.png')}}">
                                        </div>
                                    </div>
                                    <p class="a-m-color fs-10">Tip Amount</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="satic-sec">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="graph-img mt-3">
                                <!-- <img src="{{asset('assets/images/graph-img.png')}}" class="img-fluid"> -->
                                <canvas id="rideStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="top-drivers-sec mt-4 mb-3">
                    <div class="drivr-heading d-flex justify-content-between">
                        <h5 class="a-h-color fw-600">Top Drivers </h5>
                        <!-- <a href="#" class="fs-14 text-decoration-underline">View</a> -->
                    </div>

                    <div class="driver-info-box rounded-8 fc-bg p-3 d-flex justify-content-between align-items-center">
                        <div class="d-n-box d-flex gap-2 w-100">
                            <div class="dri-avtar">
                                @if($admins->profile_pic)
                                    <img src="{{asset('storage/profile/')}}/{{$admins->profile_pic}}" class="img-fluid">
                                @else
                                    <img src="{{asset('assets/images/place.png')}}" class="img-fluid">
                                @endif

                            </div>

                            <div class="d-p">
                                <p class="color-2e fw-600 fs-14">{{$admins->name}}</p>
                                <p class="fs-12">{{$admins->phone_number}}</p>
                            </div>

                        </div>
                        <div class="d-income">
                            <div class="rides d-flex justify-content-between border-bottom">
                                <p>Rides:</p>
                                <p class="color-2e fw-600">{{$driver_completed_ride_count}}</p>
                            </div>
                            <!-- <div class="income-text d-flex gap-5">
                                <p>income:</p>
                                <p class="color-2e fw-600">$98</p>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="ride-box">
                    <div class="drivr-heading d-flex justify-content-between">
                        <h5 class="a-h-color fw-600">Rides </h5>
                        <a href="{{route('admin-rides')}}" class="fs-14 text-decoration-underline">View</a>
                    </div>
                    <div class="row gy-3">
                        @foreach($new_request_rides as $new_request_ride)
                        <div class="col-md-6 ">
                            <div class="ride-box-1 border rounded-8 p-2">
                                <div class="img-dots d-flex justify-content-between align-items-center">
                                    <div class="d-bg-img">
                                        <img src="{{asset('assets/images/d-car.png')}}">
                                    </div>

                                    <!-- <div class="t-dots">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div> -->
                                </div>
                                <div class="d-info-box mt-2">
                                    <p class="fw-600 fs-14">{{ date('d M Y', strtotime($new_request_ride->pick_up_date)) }} , {{$new_request_ride->pick_up_time}}</p>
                                    <p>Passengers : {{$new_request_ride->total_passenger}}</p>
                                </div>
                                <div class="sydney-box">
                                    <div class="sy-flex d-flex align-items-start pb-3 gap-2">
                                        <img src="{{asset('assets/images/location.png')}}">
                                        <p class="fs-14">{{$new_request_ride->pick_up_address}}</p>
                                    </div>
                                    <div class="park-flex d-flex align-items-start gap-2">
                                        <img src="{{asset('assets/images/drop.png')}}">
                                        <p class="fs-14">{{$new_request_ride->drop_off_address}}</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    
                        <!-- <div class="change-sec">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="avail-balnce fc-bg rounded-8 d-flex justify-content-between align-items-center p-3 h-100">
                                        <div class="ab-text">
                                            <p>Available Balance</p>
                                            <h4>$14,235.34 </h4>
                                        </div>
                                        <div class="change-btn">
                                            <a href="#" class="ca-bg text-black px-md-4 px-2 py-1 rounded-30"><img src="{{asset('assets/images/Refresh.png')}}">Change</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="custom-table mt-3">
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table">
                <thead class="fc-bg rounded-8">
                    <tr>
                        <th>User</th>
                        <!-- <th>Car Category</th> -->
                        <th>Pickup Time</th>
                        <th>Pickup Location</th>
                        <th>Drop Location</th>
                        <th>Fare</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($new_request_rides as $new_request_ride)
                    <tr>
                        <td>
                            <div class="user-td d-flex gap-2">
                                <!-- <div class="u-td-img">
                                    <img src="{{asset('assets/images/td-pro.png')}}" alt="User Image">
                                </div> -->
                                <div class="u-td-img">
                                    @if(isset($pending_ride->users) && !empty($pending_ride->users->profile_pic))
                                        <img src="{{asset('storage/profile')}}/{{$pending_ride->users->profile_pic}}" alt="User Image">
                                    @else
                                        <img src="{{asset('assets/images/place.png')}}" alt="User Image">
                                    @endif
                                </div>
                                <div class="u-deails">
                                    <p>{{$new_request_ride->users->first_name}}{{$new_request_ride->users->last_name}}</p>
                                    <p class="color-90">{{$new_request_ride->users->phone_number}}</p>
                                </div>
                            </div>
                        </td>
                        <!-- <td>Troy Regular</td> -->
                        <td>{{ date('m/d/Y', strtotime($new_request_ride->pick_up_date)) }} {{$new_request_ride->pick_up_time}}</td>
                        <td>{{$new_request_ride->pick_up_address}}</td>
                        <td>{{$new_request_ride->drop_off_address}}</td>
                        <td>
                            <div class="fare-btn">
                                <button type="button" class="btn f-bg pe-4 fs-10 rounded-30 fw-500">${{$new_request_ride->booking->subtotal}}</button>
                            </div>
                        </td>
                        <td>
                            <div class="td-delete-icon d-flex gap-3">
                                @if(Auth::guard('admin')->check()) 
                                    @php
                                        $user = Auth::guard('admin')->user();
                                    @endphp

                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('view ride details'))
                                        <a href="{{route('admin-ride-details',['ride_id'=>$new_request_ride->id])}}">
                                            <img src="{{asset('assets/images/td-eye.png')}}">
                                        </a>                                                                                                                                                                                                                                                                                                           
                                    @endif
                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('delete rides'))
                                        <a href="javascript:void(0);" class="delete-btn" data-ride-id="{{ $new_request_ride->id }}">
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
@endsection

@section('scripts')
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Include Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
    const ctx = document.getElementById('rideStatusChart').getContext('2d');

    const rideStatusChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels), // Month labels from the backend
            datasets: [
                {
                    label: 'Completed',
                    data: @json($completed), // Completed rides data
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 0, 255, 0.1)',
                    fill: true,
                    tension: 0.4, // Adjust tension for smoothness, or use 0.2 for more angular lines
                    pointRadius: 4, // Increase point size to make data points clearer
                    pointBackgroundColor: 'blue', // Points' color
                    pointBorderColor: 'blue', // Border color of points
                    borderWidth: 2 // Line width for the completed dataset
                },
                {
                    label: 'Cancelled',
                    data: @json($cancelled), // Cancelled rides data
                    borderColor: 'red', // Changed color to red for cancelled
                    backgroundColor: 'rgba(255, 0, 0, 0.1)', // Light red fill for cancelled rides
                    fill: true,
                    tension: 0.4, // Adjust tension for smoothness
                    pointRadius: 4,
                    pointBackgroundColor: 'red',
                    pointBorderColor: 'red',
                    borderWidth: 2 // Line width for the cancelled dataset
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month',
                        font: {
                            size: 14
                        }
                    },
                    ticks: {
                        autoSkip: false, // Show all months in the x-axis
                        maxRotation: 45, // Rotate labels to avoid overlap
                        minRotation: 45
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Number of Rides',
                        font: {
                            size: 14
                        }
                    },
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 // Ensure y-axis ticks step by 1
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw} Rides`;
                        }
                    }
                },
                legend: {
                    position: 'top', // Positioning of the legend (optional)
                    labels: {
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
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
    $(document).on('change','input.status_user',function(){ 
        $.ajax({
            method: 'GET',
            url: '{{route("driver-ride-disable")}}',
            data: {
                _token: "{{ csrf_token() }}", 
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('An error occurred:', error); // Log the error for debugging
            }
        });
    })

    $(document).on('change','input.user_auto_reject_ride',function(){ 
        $.ajax({
            method: 'GET',
            url: '{{route("auto-reject-ride-enable")}}',
            data: {
                _token: "{{ csrf_token() }}", 
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('An error occurred:', error); // Log the error for debugging
            }
        });
    })

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