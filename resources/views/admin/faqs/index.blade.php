@extends('admin.layouts.master')
@section('title', 'FAQ Management')
@section('content')
<div class="content-m-info">
    <div class="content-m-heading d-flex align-items-center justify-content-between mt-4 mb-3">
        <h4 class="color-2e fw-600 mb-0">FAQ Management</h4>
        <div class="add-plus-btn">
            <a href="{{route('faq-show')}}" class="btn bg-black text-white px-5 py-2 rounded-8 fw-500">
                <span class="pe-2">
                    <img src="{{asset('assets/icons/add-circle.png')}}">
                </span>Add New
            </a>
        </div>
    </div>
    <div class="content-table ">
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table shadow">
                <thead class="bg-e6 rounded-8">
                    <tr>
                        <th>Question</th>
                        <th></th>
                        <th>Answer</th>
                        <th></th>
                        <th></th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($faqs->count()>0)
                        @foreach($faqs as $faq)
                            <tr>
                                <td>
                                    {{$faq->question}}
                                </td>
                                <td></td>
                                <td>
                                {{$faq->answer}}
                                    <!-- <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                    </div> -->
                                </td>
                                <td></td>
                                <td></td>

                                <td>
                                    <div class="td-delete-icon d-flex gap-3">
                                        <a href="{{route('edit-faq',['faq_id'=>$faq->id])}}">
                                            <img src="{{asset('assets/images/td-eye.png')}}">
                                        </a>
                                        <a href="javascript:void(0);" class="delete-btn" data-faq-id="{{ $faq->id }}">
                                            <img src="{{ asset('assets/images/Delete Icon.png') }}" alt="Delete">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="7" class="text-center">No FAQ found</td>
                    </tr>
                    @endif
                  
                </tbody>
            </table>
        </div>

        <div class="total-drivers-box d-flex justify-content-end align-items-center mb-5 pb-5 mt-md-0 mt-3">
            @if ($faqs->hasPages())
                <div class="custom-pagination">
                    <div class="pagination-info">
                        {{ $faqs->firstItem() }}–{{ $faqs->lastItem() }} of {{ $faqs->total() }} items
                    </div>
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($faqs->onFirstPage())
                            <li class="page-item disabled"><span>&lt;</span></li>
                        @else
                            <li class="page-item">
                                <a href="{{ $faqs->previousPageUrl() }}" rel="prev">&lt;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($faqs->getUrlRange(1, $faqs->lastPage()) as $page => $url)
                            @if ($page == $faqs->currentPage())
                                <li class="page-item active"><span>{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($faqs->hasMorePages())
                            <li class="page-item">
                                <a href="{{ $faqs->nextPageUrl() }}" rel="next">&gt;</a>
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
@endsection

@section('scripts')
<script src="https://cdn.tiny.cloud/1/k6vov7xs3x5yy8qq6m6nl4qolwen4gg1kedvjbqk7cae33hv/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JavaScript to trigger SweetAlert and delete -->
<script>
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var faqId = this.getAttribute('data-faq-id');
            
            // SweetAlert confirmation popup
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this faq?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete route if confirmed
                    window.location.href = '{{ route("faq-delete", ["faq_id" => "__faq_id__"]) }}'.replace("__faq_id__", faqId);
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