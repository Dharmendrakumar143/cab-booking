@extends('admin.layouts.master')
@section('title', 'Employees')
@section('content')
<div class="drivers-list custom-table mt-4">
    <div class="profile-heading d-flex align-items-center justify-content-between mt-4 mb-3">
        <h4 class="color-2e fw-600">Employee list</h4>
        <div class="add-plus-btn">
            <a href="{{route('create-employee')}}" class="btn bg-black text-white px-5 py-2 rounded-8 fw-500">
                <span class="pe-2">
                <img src="{{asset('assets/icons/add-circle.png')}}">
                </span>Add New
            </a>
        </div>
    </div> 
    <div class="pdf-btn-filter mt-5 mb-3">
        <a href="{{route('employee.export.excel')}}" class="ex-btn fw-600 text-black f7f8-bg rounded-30 py-1 px-5">Excel</a>
        <a href="{{route('employee.export')}}" class="cs-btn fw-600 text-black f7f8-bg rounded-30 py-1 px-5 mx-2">CSV</a>
        <a href="{{route('employee.export.pdf')}}" class="pd-btn fw-600 text-black f7f8-bg rounded-30 py-1 px-5">PDF</a>
    </div>
    <div class="table-responsive" style="overflow-x: auto;">
        <div class="d-flex justify-content-end">
            <div class="search-container pb-3 relative" bis_skin_checked="1">
                <input type="text" placeholder="Search..." class="search-input light-gray fs-14" name="search_by" id="search_by">
                <img src="{{asset('assets/images/search-icon(2).png')}}"  alt="search-icon" class="ride-search-icon">
            </div>
        </div>
        <table class="table" id="employee-details">
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
            <!-- <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td>
                        <div class="user-td d-flex gap-2 align-items-center">
                            <div class="u-td-img">
                                @if(isset($employee->profile_pic))
                                    <img src="{{asset('storage/profile')}}/{{$employee->profile_pic}}" alt="User Image">
                                @else
                                    <img src="{{asset('assets/images/place.png')}}" alt="User Image">
                                @endif

                            </div>
                            <div class="u-deails">
                            <p>{{ (isset($employee->name) && $employee->name != null) ? $employee->name : $employee->first_name . ' ' . $employee->last_name }}</p>
                            </div>
                        </div>
                    </td>
                    <td>{{$employee->email}}</td>
                    <td>{{$employee->phone_number}}</td>
                    <td>{{$employee->total_rides}}</td>
                    <td>{{$employee->completed_rides}}</td>
                    <td>{{$employee->verification_status}}</td>
                    
                    <td>
                        <div class="tde-delete-icon d-flex gap-2">
                            <a href="{{route('employee-edit',['employee_id'=>$employee->id])}}"><img src="{{asset('assets/images/td-eye.png')}}" class="img-fluid"></a> 
                            <a href="javascript:void(0);" class="delete-btn" data-employee-id="{{ $employee->id }}">
                                <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody> -->
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
        var table = $('#employee-details').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('employee-data') }}',
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
                        var url = '{{ route('employee-edit', ['employee_id' => '__employee_id__']) }}';
                        url = url.replace('__employee_id__', row.id); // Replace the placeholder with the actual row.id
                        return `<div class="tde-delete-icon d-flex gap-2">
                            <a href="${url}"><img src="{{asset('assets/images/td-eye.png')}}" class="img-fluid"></a> 
                            <a href="javascript:void(0);" class="delete-btn" data-employee-id=${row.id}>
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
        $('#employee-details').on('click', '.delete-btn', function(event) {
            var userId = this.getAttribute('data-employee-id');
            
            // SweetAlert confirmation popup
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this employee?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete route if confirmed
                    window.location.href = '{{ route("employee-delete", ["employee_id" => "__employee_id__"]) }}'.replace("__employee_id__", userId);
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