@extends('Frontend.layouts.master')
@section('title', 'Faqs')
@section('content')
<main>
    <div class="faq-for-cotent mb-3 pb-5 mx-lg-0 mx-3">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="fq-c-heading mt-4 mb-3">
                        <h2 class="fw-600">Frequently Asked Questions</h2>
                    </div>
                </div>
                <div class="row gy-lg-0 gy-2">

                    {{-- General Questions Section --}}
                    @if($faqs->where('question_type', 'general questions')->isNotEmpty())
                    <div class="col-lg-12 px-0 mb-3">
                        <div class="gen-qust fq-box rounded-30 bg-d1 px-3 pt-lg-5 pt-3 pb-3">
                            <div class="fq-heading">
                                <h5 class="fw-600">General Questions</h5>
                            </div>
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                @foreach($faqs as $faq)
                                    @if($faq->question_type == "general questions")
                                       <div class="accordion-item" id="item{{$faq->id}}"> 
                                            <h2 class="accordion-header">
                                                <button class="accordion-button fw-600 ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$faq->id}}" aria-expanded="true" aria-controls="collapse{{$faq->id}}">
                                                    {{$faq->question}}
                                                </button>
                                            </h2>
                                            <div id="collapse{{$faq->id}}" class="accordion-collapse collapse">
                                                <div class="accordion-body ps-0">
                                                    <p>{{$faq->answer}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Account and Booking Questions Section --}}
                    @if($faqs->where('question_type', 'account and booking questions')->isNotEmpty())
                    <div class="col-lg-12 px-0 mb-3">
                        <div class="ac-accordon fq-box rounded-30 bg-d1 px-3 pt-5 pb-3">
                            <div class="fq-heading">
                                <h5 class="fw-600">Account and Booking Questions</h5>
                            </div>
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                @foreach($faqs as $faq)
                                    @if($faq->question_type == "account and booking questions")
                                         <div class="accordion-item" id="item{{$faq->id}}"> 
                                            <h2 class="accordion-header">
                                                <button class="accordion-button fw-600 ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$faq->id}}" aria-expanded="true" aria-controls="collapse{{$faq->id}}">
                                                    {{$faq->question}}
                                                </button>
                                            </h2>
                                            <div id="collapse{{$faq->id}}" class="accordion-collapse collapse">
                                                <div class="accordion-body ps-0">
                                                    <p>{{$faq->answer}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif


                    {{-- Service and Safety Questions --}}
                    @if($faqs->where('question_type', 'service and safety questions')->isNotEmpty())
                    <div class="col-lg-12 px-0 mb-3">
                        <div class="ac-accordon fq-box rounded-30 bg-d1 px-3 pt-5 pb-3">
                            <div class="fq-heading">
                                <h5 class="fw-600">Service and Safety Questions</h5>
                            </div>
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                @foreach($faqs as $faq)
                                    @if($faq->question_type == "service and safety questions")
                                         <div class="accordion-item" id="item{{$faq->id}}"> 
                                            <h2 class="accordion-header">
                                                <button class="accordion-button fw-600 ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$faq->id}}" aria-expanded="true" aria-controls="collapse{{$faq->id}}">
                                                    {{$faq->question}}
                                                </button>
                                            </h2>
                                            <div id="collapse{{$faq->id}}" class="accordion-collapse collapse">
                                                <div class="accordion-body ps-0">
                                                    <p>{{$faq->answer}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- support questions --}}
                    @if($faqs->where('question_type', 'support questions')->isNotEmpty())
                    <div class="col-lg-12 mb-3">
                        <div class="ac-accordon fq-box rounded-30 bg-d1 px-3 pt-5 pb-3">
                            <div class="fq-heading">
                                <h5 class="fw-600">Support Questions</h5>
                            </div>
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                @foreach($faqs as $faq)
                                    @if($faq->question_type == "support questions")
                                       <div class="accordion-item" id="item{{$faq->id}}"> 
                                            <h2 class="accordion-header">
                                                <button class="accordion-button fw-600 ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$faq->id}}" aria-expanded="true" aria-controls="collapse{{$faq->id}}">
                                                    {{$faq->question}}
                                                </button>
                                            </h2>
                                            <div id="collapse{{$faq->id}}" class="accordion-collapse collapse">
                                                <div class="accordion-body ps-0">
                                                    <p>{{$faq->answer}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Default Section for Questions without a Type --}}
                    @if($faqs->where('question_type', '')->isNotEmpty() || $faqs->whereNull('question_type')->isNotEmpty())
                    <div class="col-lg-12 mb-3">
                        <div class="default-qust fq-box rounded-30 bg-d1 px-3 pt-5 pb-3">
                            <div class="fq-heading">
                                <h5 class="fw-600">Other Questions</h5>
                            </div>
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                @foreach($faqs as $faq)
                                    @if(!$faq->question_type)
                                         <div class="accordion-item" id="item{{$faq->id}}"> 
                                            <h2 class="accordion-header">
                                                <button class="accordion-button fw-600 ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$faq->id}}" aria-expanded="true" aria-controls="collapse{{$faq->id}}">
                                                    {{$faq->question}}
                                                </button>
                                            </h2>
                                            <div id="collapse{{$faq->id}}" class="accordion-collapse collapse">
                                                <div class="accordion-body ps-0">
                                                    <p>{{$faq->answer}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var accordionItems = document.querySelectorAll('.accordion-item');

        accordionItems.forEach(function(item) {
            item.querySelector('.accordion-button').addEventListener('click', function() {
                item.classList.toggle('open');
            });
        });
    });
</script>
@endsection
