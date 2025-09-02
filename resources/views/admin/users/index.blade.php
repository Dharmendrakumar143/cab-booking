@extends('admin.layouts.master')
@section('title', 'Passengers')
@section('content')
<div class="passenger custom-table mt-4">
    <div class="passe-heading mb-2 ">
        <h4 class="color-2e fw-600">Passengers</h4>
    </div>
    <div class="pdf-btn-filter mt-5 mb-3">
        <a href="{{route('users.export.excel')}}" class="ex-btn fw-600 text-black f7f8-bg rounded-30 py-1 px-5">Excel</a>
        <a href="{{route('users.export')}}" class="cs-btn fw-600 text-black f7f8-bg rounded-30 py-1 px-5 mx-2">CSV</a>
        <a href="{{route('users.export.pdf')}}" class="pd-btn fw-600 text-black f7f8-bg rounded-30 py-1 px-5">PDF</a>
    </div>
    <div class="table-responsive" style="overflow-x: auto;">
        <table class="table">
            <thead class="fc-bg rounded-8">
                <tr>
                    <th>User</th>
                    <th>Mobile no</th>
                    <th>Email id</th>
                    <th></th>
                    <th></th>
                    
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($passengers as $user)
                <tr>
                    <td>
                        <div class="user-td d-flex gap-2 align-items-center">
                            <div class="u-td-img">
                                <img src="{{asset('assets/images/place.png')}}" alt="User Image">
                            </div>
                            <div class="u-deails">
                                <p>{{$user->first_name}} {{$user->last_name}}</p>
                            </div>
                        </div>
                    </td>
                    <td>{{$user->phone_number}}</td>
                    <td>{{$user->email}}</td>
                    <td></td>
                    <td></td>
                    
                    <td>
                        <div class="td-delete-icon d-flex gap-3">
                            <a href="{{route('admin-passenger-detail',['ride_id'=>$user->id])}}"><img src="{{asset('assets/images/td-eye.png')}}"></a>
                            <a href="javascript:void(0);" class="delete-btn" data-user-id="{{ $user->id }}">
                                <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                            </a>
                            <!-- <a href="#"><img src="{{asset('assets/images/Delete Icon.png')}}"></a> -->
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="total-drivers-box d-flex justify-content-end align-items-center mb-5 pb-5 mt-md-0 mt-3">
        @if ($passengers->hasPages())
        <div class="custom-pagination">
            <div class="pagination-info">
                {{ $passengers->firstItem() }}–{{ $passengers->lastItem() }} of {{ $passengers->total() }} items
            </div>
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($passengers->onFirstPage())
                    <li class="page-item disabled"><span>&lt;</span></li>
                @else
                    <li class="page-item">
                        <a href="{{ $passengers->previousPageUrl() }}" rel="prev">&lt;</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($passengers->getUrlRange(1, $passengers->lastPage()) as $page => $url)
                    @if ($page == $passengers->currentPage())
                        <li class="page-item active"><span>{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($passengers->hasMorePages())
                    <li class="page-item">
                        <a href="{{ $passengers->nextPageUrl() }}" rel="next">&gt;</a>
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
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Include Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JavaScript to trigger SweetAlert and delete -->
<script>
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var userId = this.getAttribute('data-user-id');
            
            // SweetAlert confirmation popup
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete route if confirmed
                    window.location.href = '{{ route("user-delete", ["user_id" => "__user_id__"]) }}'.replace("__user_id__", userId);
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