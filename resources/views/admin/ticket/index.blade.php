@extends('admin.layouts.master')
@section('title', 'Help & Support')
@section('content')
<div class="passenger custom-table mt-4">
    <div class="help-info">
        <div class="help-heading mb-3 mt-4">
            <h3 class="p-h-color fs-clash fw-600">Help &amp; Support</h3>
        </div>
    </div>
    <div class="admin-help-content-box f8-bg rounded-20 p-4">
        <div class="search-filter-box">
            <div class="row align-items-center gy-lg-0 gy-3">
                <div class="col-md-7 col-12">
                    <div class="search-container">
                        <input type="text" placeholder="Search..." class="search-input light-gray fs-14" name="search_by" id="search_by">
                   <!--      <button class="search-icon ">
                            <img src="{{asset('assets/images/search-icon.png')}}">
                        </button> -->
                    </div>
                </div>
                <div class="col-md-5 col-12">
                    <div class="filters d-flex gap-2 justify-content-lg-end justify-content-center ">
                        <!-- <input type="text" class="form-control date-picker" id="date-picker"> -->
                        <input type="text" class="form-control date-picker" id="date-picker" placeholder="yy-mm-dd">
                        <div class="status-btn">
                            <select id="status" class="form-control  wm-content">
                                <option value="">Status</option>
                                <option value="closed" class="border-bottom pb-3">Closed</option>
                                <option value="open">Open</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="custom-table mt-3 mb-3">
            <div class="table-responsive" id="filter_result">
                <table class="table">
                    <thead class=" rounded-8">
                        <tr>
                            <th>Sr.No</th>
                            <th>Date</th>
                            <th>Sender</th>
                            <th>Subject</th>
                            <th>Discription</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i=1;
                        @endphp
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{ date('d M Y', strtotime($ticket->created_at)) }}</td>
                                <td>{{$ticket->name}}</td>
                                <td>{{$ticket->issue_subject}}</td>
                                <td>{{ \Illuminate\Support\Str::limit($ticket->issue_description, 20) }}</td>
                                <td>
                                    <div class="resolved-btn">
                                        <button type="button" class="btn bg-71 color-71 pe-4 fs-14 rounded-10 fw-500">{{$ticket->status}}</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="dots-box">
                                        <div class="dropdown">
                                            <a class=" dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="{{asset('assets/icons/dots.png')}}">
                                            </a>
                                            <ul class="dropdown-menu">
                                                @if($ticket->status=='closed')
                                                <li class="border-bottom"><a class="dropdown-item" href="{{route('ticket-details',['ticket_id'=>$ticket->id])}}">View</a></li>
                                                @else
                                                <li class=""><a class="dropdown-item" href="{{route('edit-ticket',['ticket_id'=>$ticket->id])}}">Edit</a></li>
                                                @endif
                                                <li class=""><a class="dropdown-item delete-btn" data-ticket-id="{{ $ticket->id }}" href="javascript:void(0);">Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="total-drivers-box d-flex justify-content-end align-items-center mb-5 pb-5 mt-md-0 mt-3">
                @if ($tickets->hasPages())
                    <div class="custom-pagination">
                        <div class="pagination-info">
                            {{ $tickets->firstItem() }}–{{ $tickets->lastItem() }} of {{ $tickets->total() }} items
                        </div>
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($tickets->onFirstPage())
                                <li class="page-item disabled"><span>&lt;</span></li>
                            @else
                                <li class="page-item">
                                    <a href="{{ $tickets->previousPageUrl() }}" rel="prev">&lt;</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($tickets->getUrlRange(1, $tickets->lastPage()) as $page => $url)
                                @if ($page == $tickets->currentPage())
                                    <li class="page-item active"><span>{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($tickets->hasMorePages())
                                <li class="page-item">
                                    <a href="{{ $tickets->nextPageUrl() }}" rel="next">&gt;</a>
                                </li>
                            @else
                                <li class="page-item disabled"><span>&gt;</span></li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery UI JS from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<!-- JavaScript to trigger SweetAlert and delete -->
<script>
    $(document).on('click', '.delete-btn', function() {
        var ticketId = $(this).data('ticket-id'); // Get ticket ID

        // SweetAlert confirmation popup
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to delete this ticket?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the delete route if confirmed
                window.location.href = '{{ route("delete-ticket", ["ticket_id" => "__ticket_id__"]) }}'.replace("__ticket_id__", ticketId);
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        var selectedDates = []; // Array to hold selected dates

        // Initialize the datepicker with multiple date selection
        $('#date-picker').datepicker({
            dateFormat: 'yy-mm-dd',  // Date format
        });
    });
</script>

<script>
    

    $(document).ready(function(){

        $("#search_by").keyup(function(){
            var search_by = $(this).val();
            ticketFilter(search_by);
        })

        $("select#status").change(function(){
            var search_by = $(this).val();
            ticketFilter(search_by);
        })

        // Trigger the filter when a date is selected
        $("input#date-picker").on('change', function() {
            var search_by = $(this).val();
          
            ticketFilter(search_by);
        });


        function ticketFilter(search_by){
            $.ajax({
                method: 'POST',
                url: '{{route("ticket-filter")}}',
                data: {
                    _token: "{{ csrf_token() }}", 
                    search_by: search_by
                },
                success: function (response) {
                    // console.log("Response received:", response);
                    $('#filter_result').html(response);
                },
                error: function (xhr, status, error) {
                    console.error('An error occurred:', error); // Log the error for debugging
                }
            });
        }

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