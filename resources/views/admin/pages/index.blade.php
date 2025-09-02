@extends('admin.layouts.master')
@section('title', 'Content Management')
@section('content')
<div class="content-m-info">
    <div class="content-m-heading d-flex align-items-center justify-content-between mt-4 mb-3">
        <h4 class="color-2e fw-600 mb-0">Content Management</h4>
   
    </div>
    <div class="content-table ">
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table shadow">
                <thead class="bg-e6 rounded-8">
                    <tr>
                        <th>Title</th>
                        <th></th>
                        <th>Slug</th>
                        <th></th>
                        <th></th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($pages->count()>0)
                        @foreach($pages as $pages)
                            <tr>
                                <td>
                                    {{$pages->PageContent->name}}
                                </td>
                                <td></td>
                                <td>
                                {{$pages->slug}}
                                    <!-- <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                    </div> -->
                                </td>
                                <td></td>
                                <td></td>

                                <td>
                                    <div class="td-delete-icon d-flex gap-3">
                                        <a href="{{route('edit-page-content',['slug'=>$pages->slug])}}">
                                            <img src="{{asset('assets/images/td-eye.png')}}">
                                        </a>
                                     
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="7" class="text-center">No Page found</td>
                    </tr>
                    @endif
                  
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.tiny.cloud/1/k6vov7xs3x5yy8qq6m6nl4qolwen4gg1kedvjbqk7cae33hv/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>
@endsection