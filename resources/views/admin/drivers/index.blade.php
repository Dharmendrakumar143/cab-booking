@extends('admin.layouts.master')
@section('title', 'drivers')
@section('content') 
<div class="drivers-list custom-table mt-4">
    <div class="profile-heading mb-2 ">
        <h4 class="color-2e fw-600">Drivers list</h4>
    </div>
    <div class="d-flex justify-content-between my-4 align-items-center">
        <div class="pdf-btn-filter mb-3">
            <a href="{{route('driver.export.excel')}}" class="ex-btn fw-600 text-black f7f8-bg rounded-30 py-3 px-5">Excel</a>
            <a href="{{route('driver.export')}}" class="cs-btn fw-600 text-black f7f8-bg rounded-30 py-3 px-5 mx-2">CSV</a>
            <a href="{{route('driver.export.pdf')}}" class="pd-btn fw-600 text-black f7f8-bg rounded-30 py-3 px-5">PDF</a>
        </div>
        <div class="d-flex justify-content-end">
            <div class="search-container pb-3 relative" bis_skin_checked="1">
                <input type="text" placeholder="Search..." class="search-input light-gray fs-14" name="search_by" id="search_by">
                <img src="{{asset('assets/images/search-icon(2).png')}}"  alt="search-icon" class="ride-search-icon">
            </div>
        </div>
    </div>
    
    <div class="table-responsive" style="overflow-x: auto;">
        
        <table class="table" id="driver-detail">
            <thead class="fc-bg rounded-8">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total rides</th>
                    <th>Total finished</th>    
                    <th>Verification Status</th>                    
                    <th>Action</th>
                </tr>
            </thead>
        </table>
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
        var table = $('#driver-detail').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('driver-data') }}',
                data: function(d) {
                    d.custom_search = $('#search_by').val();
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
                                    <p>${row ? row.first_name + ' ' + row.last_name : ''}</p>
                                </div>
                            </div>`;
                    }
                },
                { data: 'email', name: 'email' },
                { data: 'phone_number', name: 'phone_number' },
                {
                    data: null,
                    name: 'total_rides',
                    render: function (data, type, row, meta) {
                        return row.total_rides;
                    },
                    searchable: false,
                    orderable: false,
                },
                {
                    data: null,
                    name: 'completed_rides',
                    render: function (data, type, row, meta) {
                        return row.completed_rides;
                    },
                    searchable: false,
                    orderable: false,
                },
                { data: 'verification_status', name: 'verification_status' },
                {
                    data: 'action', // Action column
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var url = '{{ route('driver-detail', ['driver_id' => '__driver_id__']) }}';
                        url = url.replace('__driver_id__', row.id); // Replace the placeholder with the actual row.id
                        return `<div class="tde-delete-icon d-flex gap-2">
                            <a href="${url}"><img src="{{asset('assets/images/td-eye.png')}}" class="img-fluid"></a> 
                            <a href="javascript:void(0);" class="delete-btn" data-driver-id=${row.id}>
                                <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                            </a>
                        </div>`;
                    }
                }
            ]
        });

        // Custom search input (for Name, Email, Phone)
        $('#search_by').on('keyup', function() {
            table.ajax.reload();
        });
    });
</script>

<!-- JavaScript to trigger SweetAlert and delete -->
<script>

     $('#driver-detail').on('click', '.delete-btn', function(event) {
        var userId = $(this).data('driver-id');

        // SweetAlert confirmation popup
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to delete this driver?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the delete route if confirmed
                window.location.href = '{{ route("driver-delete", ["driver_id" => "__driver_id__"]) }}'.replace("__driver_id__", userId);
            }
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