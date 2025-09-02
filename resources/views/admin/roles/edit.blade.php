@extends('admin.layouts.master')
@section('title', 'Roles and permission')
@section('content')
<div class="container mt-5">
    <div class="table-responsive" style="overflow-x: auto;">
        <h2>Edit Role Permissions</h2>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="text-danger">{{ $error }}</div>
            @endforeach
        @endif
        <form action="{{route('role-update')}}" method="POST">
            @csrf
            <input type="hidden" name="role_id" value="{{$role->id}}">
            <div class="form-group">
                <label>Role Name</label>
                <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
            </div>
            <div class="form-group mt-3">
                <label>Permissions</label>
                @foreach ($permissions as $permission)
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                    <label class="form-check-label" for="monday"> {{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>
            <div class="submit-btn mt-4">
                <button type="submit" class="btn bg-black text-white px-5 py-2 rounded-8 fw-500">Update Role</button>
            </div>
        </form>
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