@extends('admin.layouts.master')
@section('title', 'Employee')
@section('content')
<div class="content-pm-info">
    <div class="content-page-box mt-4">
        <h4 class="color-23 fw-600 mb-3">Update Employee</h4>
        <form action="{{route('update-employee')}}" method="POST" id="add-employee">
            @csrf
            <div class="content-page-form shadow rounded-20 p-lg-5 p-3 col-lg-12">
                <input type="hidden" name="employee_id" value="{{$employee->id}}">
                <div class="col-md-12">
                    <div class="c-title-input mb-3">
                        <label for="first_name" class="form-label fw-600">First Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-30 bg-f6 @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{$employee->first_name}}">
                    </div>
                    @error('first_name')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <div class="c-title-input mb-3">
                        <label for="last_name" class="form-label fw-600">Last Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-30 bg-f6 @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{$employee->last_name}}">
                    </div>
                    @error('last_name')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <div class="c-title-input mb-3">
                        <label for="phone_number" class="form-label fw-600">Phone Number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-30 bg-f6 @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{$employee->phone_number}}">
                    </div>
                    @error('phone_number')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <div class="c-title-input mb-3">
                        <label for="email" class="form-label fw-600">Email<span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-30 bg-f6 @error('email') is-invalid @enderror" id="email" name="email" value="{{$employee->email}}" disabled>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="text-editor-box">
                    <div class="submit-btn mt-4">
                        <button type="submit" class="btn bg-black text-white px-5 py-2 rounded-8 fw-500">Update</button>
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
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script>

    // Adding a custom validation method for alphanumeric characters
    jQuery.validator.addMethod("strongPassword", function(value, element) {
        var regex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&])[A-Za-z\\d@$!%*?&]{8,}$");
        return this.optional(element) || regex.test(value); // Validates alphanumeric + special characters
    }, "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.");

    // Define custom regex method
    jQuery.validator.addMethod('regex', function(value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, 'Please enter a valid value.');

    $("#add-employee").validate({
        rules: {
            first_name: {
                required: true,
                regex: /^[A-Za-z]+$/
            },
            last_name: {
                required: true,
                regex: /^[A-Za-z]+$/
            },
            phone_number: {
                required: true,
                digits: true,
                maxlength: 12,
                minlength: 10,
            },
            email: {
                required: true,
                email: true,
                regex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/
            },
            password: {
                required: true,
                minlength: 8,
                strongPassword: true
            },
        },
        messages: {
            first_name: {
                required: "First name is required.",
                 regex: "First name can only contain letters."
            },
            last_name: {
                required: "Last name is required.",
                regex: "Last name can only contain letters."
            },
            phone_number: {
                required: "Phone number is required.",
                digits: "Phone number must contain only numbers.",
            },
            email: {
                required: "Email is required.",
                email: "Please enter a valid email address.",
                regex: "Please enter a valid email address with a domain extension (e.g., .com, .org)."
            },
            password: {
                required: "Password is required.",
                minlength: "Password must be at least 8 characters long.",
            }
        }
    });

    // Restrict phone number input to numbers only
    $('#phone_number').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
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