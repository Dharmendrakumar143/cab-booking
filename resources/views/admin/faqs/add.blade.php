@extends('admin.layouts.master')
@section('title', 'FAQ Management')
@section('content')
<div class="content-pm-info">
    <!-- <div class="content-m-heading d-flex align-items-center justify-content-between mt-4 mb-3">
        <h4 class="color-2e fw-600 mb-0">Content Management</h4>
    </div> -->
    <div class="content-page-box mt-4">
        <h4 class="color-23 fw-600 mb-3">FAQ</h4>
        <form action="{{ route('add-faq') }}" method="POST" id="faq_question">
            @csrf

            <div class="content-page-form shadow rounded-20 p-lg-5 p-3 col-lg-12">
                <div class="col-md-12">
                    <div class="c-title-input mb-3">
                        <label for="question" class="form-label fw-600">Question<span class="text-danger">*</span></label>
                        <textarea class="form-control rounded-30 bg-f6 @error('question') is-invalid @enderror" id="question" name="question" placeholder="question">
                        </textarea>
                    </div>
                    @error('question')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <div class="c-title-input">
                        <label for="answer" class="form-label fw-600">Answer<span class="text-danger">*</span></label>
                        <textarea class="form-control rounded-30 bg-f6 @error('answer') is-invalid @enderror" id="answer" name="answer" placeholder="answer">
                        </textarea>
                    </div>
                    @error('answer')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-md-12 mt-3">
                    <div class="c-title-input">
                        <label for="question_type" class="form-label fw-600">Question Type<span class="text-danger">*</span></label>
                        <select class="form-control rounded-30 bg-f6 @error('question_type') is-invalid @enderror" id="question_type" name="question_type">
                                <option value="">--choose--</option>
                                <option value="general questions">General Questions</option>
                                <option value="account and booking questions">Account and Booking Questions</option>
                                <option value="service and safety questions">Service and Safety Questions</option>
                                <option value="support questions">Support Questions</option>
                        </select>
                    </div>
                    @error('question_type')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
        
                <div class="text-editor-box">
                
                    <div class="submit-btn mt-4">
                        <button type="submit" class="btn bg-black text-white px-5 py-2 rounded-8 fw-500">Add</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>
    $("#faq_question").validate({
    rules: {
        question: {
        required: true,
        },
        answer: {
        required: true,
        },
    }
    });

    $(document).ready(function() {
        // Trim leading/trailing spaces for each field when the page loads
        $("#question").val($.trim($("#question").val()));
        $("#answer").val($.trim($("#answer").val()));
        $("#question_type").val($.trim($("#question_type").val()));
    });

    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>
@endsection