@extends('admin.layouts.master')
@section('title', 'Roles and permission')
@section('content')
<div class="role-content-m-info">
    <div class="content-m-heading d-flex align-items-center justify-content-between mt-4 mb-3">
        <h4 class="color-2e fw-600 mb-0">Role and permission</h4>
        <!-- <div class="add-plus-btn">
            <button type="button" class="btn bg-black text-white px-lg-5 px-1 py-2 rounded-8 fw-500"><span class="pe-2"><img src="images/add-circle.png"></span>Add New</button>
        </div> -->
    </div>
    <div class="role-table content-table ">
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table shadow">
                <thead class="bg-e6 rounded-8">
                    <tr>
                        <th>Name</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ implode(', ', $role->permissions->pluck('name')->toArray()) }}</td>
                            <td>
                                <div class="td-delete-icon d-flex gap-3">
                                    <a href="{{ route('role-edit', ['role_id'=>$role->id]) }}">
                                        <img src="{{asset('assets/images/td-eye.png')}}">
                                    </a>
                                    <!-- <img src="{{asset('assets/images/Delete Icon.png')}}"> -->
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


<!-- JavaScript to trigger SweetAlert and delete -->
<script>
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var priceId = this.getAttribute('data-surge-price-id');
            
            // SweetAlert confirmation popup
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this price?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete route if confirmed
                    window.location.href = '{{ route("price-delete", ["price_id" => "__price_id__"]) }}'.replace("__price_id__", priceId);
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