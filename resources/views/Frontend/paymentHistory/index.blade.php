@extends('Frontend.layouts.master')
@section('title', 'Payment History')
@section('content')
<main>
    <div class="u-payment-for-cotent mb-3 pb-5 mx-lg-0 mx-3">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
                <div class="u-payment-info">
                    <div class="passe-heading mb-3 mt-4 ">
                        <h3 class="color-20 fw-600">Payment History</h3>
                    </div>
                </div>
                <div class="user-payment-histroy-box">
                    <div class="u-payment-histroy-table-box custom-table ">
                        
                        <div class="payment-histroy d-flex justify-content-end align-items-center  mb-3 flex-lg-nowrap flex-wrap">
                            
                            <div class="filters d-flex gap-2 justify-content-lg-end justify-content-center mt-lg-0 mt-3 ">
                                <input type="text" class="form-control" placeholder="yy-mm-dd" id="payment_date_picker" name="payment_date_picker">            
                                <div class="status-btn" bis_skin_checked="1">
                                    <select id="sortby" name="sortby" class="form-control  wm-content">
                                        <option value="">Sort</option>
                                        <option value="paid">Paid</option>
                                        <option value="unpaid">Unpaid</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="u-payment-histroy-table custom-table mt-3 mb-3">
                            <div class="table-responsive" id="payment_history_filter">
                                <table class="table">
                                    <thead class="rounded-8">
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Passanger Name</th>
                                            <th>Pickup  Address</th>
                                            <th>Drop  Address</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @foreach($payment_statuses as $payment_status)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$payment_status->users->first_name}} {{$payment_status->users->last_name}}</td>
                                            <td>{{$payment_status->pick_up_address}}</td>
                                            <td>{{$payment_status->drop_off_address}}</td>
                                            <td>{{ date('d M Y', strtotime($payment_status->pick_up_date)) }} </td>
                                            <td>${{$payment_status->booking->subtotal}}</td>
                                            <td>
                                                <div class="success-btn">
                                                    <button type="button" class="btn bg-28 color-28 px-4 fs-12 rounded-10 fw-600">{{$payment_status->paymentStatus->status}}</button>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td-delete-icon d-flex gap-3">
                                                    <a href="{{route('payment-history-details',['ride_id'=>$payment_status->id])}}">
                                                        <img src="{{asset('assets/images/td-eye.png')}}">
                                                    </a>
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
                        </div>
                        <div class="total-drivers-box d-flex justify-content-end align-items-center mb-5 pb-5 mt-md-0 mt-3">
                        @if ($payment_statuses->hasPages())
                            <div class="custom-pagination">
                                <div class="pagination-info">
                                    {{ $payment_statuses->firstItem() }}–{{ $payment_statuses->lastItem() }} of {{ $payment_statuses->total() }} items
                                </div>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($payment_statuses->onFirstPage())
                                        <li class="page-item disabled"><span>&lt;</span></li>
                                    @else
                                        <li class="page-item">
                                            <a href="{{ $payment_statuses->previousPageUrl() }}" rel="prev">&lt;</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($payment_statuses->getUrlRange(1, $payment_statuses->lastPage()) as $page => $url)
                                        @if ($page == $payment_statuses->currentPage())
                                            <li class="page-item active"><span>{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($payment_statuses->hasMorePages())
                                        <li class="page-item">
                                            <a href="{{ $payment_statuses->nextPageUrl() }}" rel="next">&gt;</a>
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
</main>
@endsection

@section('scripts')
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
    $(document).ready(function () {
        var selectedDates = []; // Array to hold selected dates

        // Initialize the datepicker with multiple date selection
        $('#payment_date_picker').datepicker({
            dateFormat: 'yy-mm-dd',  // Date format
        });
    });
</script>

<script>
    $(document).ready(function(){
        $("select#sortby").change(function(){
            var search_by = $(this).val();
            paymentHistoryFilter(search_by);
        })

        $("input#payment_date_picker").on('change', function() {
            var search_by = $(this).val();
            paymentHistoryFilter(search_by);
        });

        function paymentHistoryFilter(search_by){
            $.ajax({
                method: 'POST',
                url: '{{route("payment-filter")}}',
                data: {
                    _token: "{{ csrf_token() }}", 
                    search_by: search_by
                },
                success: function (response) {
                    // console.log("Response received:", response);
                    $('#payment_history_filter').html(response);
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