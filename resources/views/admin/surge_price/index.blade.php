@extends('admin.layouts.master')
@section('title', 'Surge Price')
@section('content')
<div class="passenger custom-table mt-4" bis_skin_checked="1">
    <div class="passe-heading mb-2 " bis_skin_checked="1">
    
        <a href="{{route('add-price')}}" class="btn bg-black text-white px-5 py-2 rounded-8 fw-500">
            <span class="pe-2">
                <img src="{{asset('assets/icons/add-circle.png')}}">
            </span>Add New
        </a>
    </div>
    
    <div class="table-responsive" style="overflow-x: auto;" bis_skin_checked="1">
        <table class="table">
            <thead class="fc-bg rounded-8">
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($extra_charges as $extra_charge)
                    <tr>
                        <td>
                            <div class="user-td d-flex gap-2 align-items-center" bis_skin_checked="1">
                               
                                <div class="u-deails" bis_skin_checked="1">
                                    <p>{{$extra_charge->name}}</p>
                                </div>
                            </div>
                        </td>
                        <td>${{$extra_charge->value}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="td-delete-icon d-flex gap-3" bis_skin_checked="1">
                                <a href="{{route('edit-price',['price_id'=>$extra_charge->id])}}"><img src="{{asset('/assets/images/td-eye.png')}}"></a>
                                <a href="javascript:void(0);" class="delete-btn" data-surge-price-id="{{ $extra_charge->id }}">
                                    <img src="{{asset('/assets/images/Delete Icon.png')}}" alt="Delete">
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="total-drivers-box d-flex justify-content-end align-items-center mb-5 pb-5 mt-md-0 mt-3" bis_skin_checked="1">
        
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