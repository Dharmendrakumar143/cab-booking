@extends('Frontend.layouts.master')
@section('title', 'Help & Support')
@section('content')
<main>
    <div class="user-help-for-cotent mb-3 pb-5 mx-lg-0 mx-3">
        <div class="container-fluid px-lg-5 px-2">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between">
                    <div class="u-help-heading mt-4 mb-3">
                        <h3 class="fw-600">Help &amp; Support</h3>
                    </div>
                    <div class="submit-btn mt-4">
                        <a href="{{route('view-tickets')}}" class="btn bg-black text-white px-5 py-2 rounded-8 fw-500">View Tickets</a>
                    </div>
                </div>
                <div class="help-box f8-bg rounded-30 p-5 ">

                    <form action="{{route('send-ticket')}}" method="post" id="sendTicket">
                        <div class="row">
                            @csrf
                            <div class="col-md-4">
                                <div class="h-name-input">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control rounded-10 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="e-name-input">
                                    <label for="inputEmail4" class="form-label">Email</label>
                                    <input type="email" class="form-control rounded-10 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="h-name-input">
                                    <label for="name" class="form-label">Subject</label>
                                    <input type="text" class="form-control rounded-10 @error('email') is-invalid @enderror" id="subject" name="subject" value="{{ old('email') }}">
                                    @error('subject')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="h-testarea mt-4">
                                    <label for="name" class="form-label">Discription</label>
                                    <div class="form-floating">
                                        <textarea class="form-control rounded-10 @error('comment') is-invalid @enderror" name="comment" id="comment" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px">
                                        {{ old('comment') }}
                                        </textarea>
                                        <!-- <label for="floatingTextarea2">Comments</label> -->
                                    </div>
                                    @error('comment')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="submit-btn mt-4">
                                    <button type="submit" class="btn bg-black text-white px-5 py-2 rounded-8 fw-500">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection

@section('scripts')
<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>
    $(document).ready(function() {
        $("textarea#comment").val($.trim($("textarea#comment").val()));
    });
    
    $('#sendTicket').validate({
        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
            },
            subject:{
                required: true
            },
            comment:{
                required: true
            }
        }
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