@extends('Frontend.layouts.master')
@section('title', 'Terms and Conditions')
@section('content')
<main>
    <div class="term-for-cotent mb-3 pb-5 mx-lg-0 mx-3">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="term-c-heading mt-4 mb-3">
                        <h2 class="fw-600">  {{ $page->PageContent->name }} </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="term-intro-box">
                        {!! $page->PageContent->page_content !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="term-img">
                            <img src="{{asset('assets/icons/term.png')}}" class="img-fluid">
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
</main>
@endsection

@section('scripts')

@endsection