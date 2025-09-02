@extends('admin.layouts.master')
@section('title', 'Admin Dues')
@section('content')
<div class="payment-histroy-table-box custom-table mt-5">
    <div class="passe-heading mb-2 ">
        <h4 class="color-20 fw-600">Dues</h4>
    </div>
    <div class="payment-histroy d-flex justify-content-between align-items-center  mb-3 flex-lg-nowrap flex-wrap">
        <div class="pdf-btn-filter d-flex flex-lg-nowrap flex-wrap align-items-center">      
        </div>
        <div class="filters d-flex gap-2 justify-content-lg-end justify-content-center mt-lg-0 mt-3 ">
            <div class="sort-btn dropdown">
                    <div class="status-btn" bis_skin_checked="1">
                        <select id="sortby" name="sortby" class="form-control  wm-content">
                            <option value="">Sort</option>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="payment-histroy-table custom-table mt-3 mb-3">
    <div class="table-responsive">
        <table class="table" id="dues_filter_result">
            <thead class="rounded-8">
                <tr>
                    <th>Sr.No</th>
                    <th>Driver Name</th>
                    <th>Due Amount</th>
                    <th>status</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $i = 1;
                @endphp
                @foreach($driver_dues as $due)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$due->admin->first_name}} {{$due->admin->last_name}}</td>
                        <td>${{number_format($due->total_due,2)}}</td>
                     
                        <td>
                            <div class="success-btn" bis_skin_checked="1">
                                <button type="button" class="btn bg-28 color-28 px-4 fs-12 rounded-10 fw-600">{{$due->status}}</button>
                            </div>
                        </td>
                    </tr>
                    @php 
                        ++$i;
                    @endphp

                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total-drivers-box d-flex justify-content-end align-items-center mb-5 pb-5 mt-md-0 mt-3">
        @if ($driver_dues->hasPages())
            <div class="custom-pagination">
                <div class="pagination-info">
                    {{ $driver_dues->firstItem() }}–{{ $driver_dues->lastItem() }} of {{ $driver_dues->total() }} items
                </div>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($driver_dues->onFirstPage())
                        <li class="page-item disabled"><span>&lt;</span></li>
                    @else
                        <li class="page-item">
                            <a href="{{ $driver_dues->previousPageUrl() }}" rel="prev">&lt;</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($driver_dues->getUrlRange(1, $driver_dues->lastPage()) as $page => $url)
                        @if ($page == $driver_dues->currentPage())
                            <li class="page-item active"><span>{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($driver_dues->hasMorePages())
                        <li class="page-item">
                            <a href="{{ $driver_dues->nextPageUrl() }}" rel="next">&gt;</a>
                        </li>
                    @else
                        <li class="page-item disabled"><span>&gt;</span></li>
                    @endif
                </ul>
            </div>
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function(){
        $("select#sortby").change(function(){
            var search_by = $(this).val();
            driverDuesFilter(search_by);
        })


        function driverDuesFilter(search_by){
            $.ajax({
                method: 'GET',
                url: '{{route("driver-dues-filter")}}',
                data: {
                    _token: "{{ csrf_token() }}", 
                    search_by: search_by
                },
                success: function (response) {
                    // console.log("Response received:", response);
                    $('#dues_filter_result').html(response);
                },
                error: function (xhr, status, error) {
                    console.error('An error occurred:', error); // Log the error for debugging
                }
            });
        }

    });
</script>

@endsection