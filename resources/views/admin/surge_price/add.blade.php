@extends('admin.layouts.master')
@section('title', 'Surge Price')
@section('content')
<div class="passenger custom-table mt-4" bis_skin_checked="1">    
    <div class="container mt-5">
        <div class="table-responsive" style="overflow-x: auto;">
            <form action="{{route('add-price')}}" method="post" id="surgePrice">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <select name="name" id="names" class="form-control">
                        <option value="">--Select--</option>
                        <option value="weekend">Weekend</option>
                        <option value="holiday">Holiday</option>
                        <option value="night">Night</option>
                        <option value="night 2">Night 2</option>
                        <option value="rush hour 1">Rush Hour 1</option>
                        <option value="rush hour 2">Rush Hour 2</option>
                    </select>
                </div>
                <div id="additional-fields" class="mt-3 hidden">
                    <!-- Weekend Fields -->
                    <div id="weekend-fields" class="hidden">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Monday" id="monday">
                            <label class="form-check-label" for="monday">Monday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Tuesday" id="tuesday">
                            <label class="form-check-label" for="tuesday">Tuesday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Wednesday" id="wednesday">
                            <label class="form-check-label" for="wednesday">Wednesday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Thursday" id="thursday">
                            <label class="form-check-label" for="thursday">Thursday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Friday" id="friday">
                            <label class="form-check-label" for="friday">Friday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Saturday" id="saturday">
                            <label class="form-check-label" for="saturday">Saturday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="days[]" value="Sunday" id="sunday">
                            <label class="form-check-label" for="sunday">Sunday</label>
                        </div>
                    </div>

                    <!-- Holiday Fields -->
                    <div id="holiday-fields" class="hidden">
                        <label>Select Holiday Dates</label>
                        <input type="text" class="form-control" name="holiday_dates" id="holiday_dates" autocomplete="off">
                    </div>

                    <!-- Night Fields -->
                    <div id="night-fields" class="hidden">
                        <label>Start Time</label>
                        <input type="text" class="form-control" name="start_time" id="start_time" autocomplete="off">
                        <label class="mt-2">End Time</label>
                        <input type="text" class="form-control" name="end_time" id="end_time" autocomplete="off">
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label>Price</label>
                    <input type="text" class="form-control" name="value" id="value">
                </div>
                <!-- <button type="submit" class="btn btn-primary mt-3">Submit</button> -->
                <div class="submit-btn mt-4">
                    <button type="submit" class="btn bg-black text-white px-5 py-2 rounded-8 fw-500">Submit</button>
                </div>
            </form>
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

<!-- jQuery UI JS from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function() {
        $('input#value').on('input', function() {
            var numbers = $(this).val();

            $(this).val(numbers.replace(/[^0-9]/g, ''));
        });

        // Prevent Enter key from being pressed
        $('input#value').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent Enter key from submitting or focusing out
            }
        });
    });

    $(document).ready(function() {
        $('#holiday_dates').on('input', function() {
            var inputVal = $(this).val();

            // Regular expression for date format YYYY-MM-DD
            var validDateFormat = /^\d{4}-\d{2}-\d{2}$/;

            // Split the input value by commas for multiple dates
            var dates = inputVal.split(',');

            // Validate each date and remove any invalid dates or those with '00'
            dates = dates.map(function(date) {
                date = date.trim(); // Trim spaces around dates
                if (validDateFormat.test(date) && !date.includes('00')) {
                    return date; // Return the valid date
                } else {
                    return ''; // Return empty string for invalid date
                }
            });

            // Join the valid dates back with commas
            $(this).val(dates.filter(function(date) { return date !== ''; }).join(', '));
        });
    });

    $("#surgePrice").validate({
        rules: {
            name: {
                required: true,
            },
            value: {
                required: true,
            },
            holiday_dates: {
                required: true,
            },
            start_time: {
                required: true,
            },
            end_time: {
                required: true,
                greaterThanStart: true
            }
        },
        messages: {
            holiday_dates: {
                pattern: "Please enter dates in the format YYYY-MM-DD, separated by commas."
            },
            end_time: {
                greaterThanStart: "End time must be later than start time."
            }
        }
    });
</script>

<script>
    flatpickr("#start_time, #end_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K", 
        minuteIncrement: 1,
    });


    // $(document).ready(function () {
    //     // Initialize the datetime picker
    //     $('#start_time,#end_time').datetimepicker({
    //         format: 'H:i',
    //         step: 1,
    //         datepicker: false,            
    //         minDate: 0,    
    //         // onShow: function (currentDateTime) {
    //         //     let currentDate = new Date(); 
    //         //     this.setOptions({
    //         //         minDate: 0,
    //         //         minTime: currentDate.getDate() === currentDateTime.getDate() 
    //         //             ? currentDate.getHours() + ':' + (currentDate.getMinutes()) 
    //         //             : false 
    //         //     });
    //         // },
    //         // Additional option to use AM/PM
    //         formatTime: 'g:i A' // Ensure time is displayed in AM/PM format
    //     });
    // });
</script>

<script>
    $(document).ready(function () {
        var selectedDates = []; // Array to hold selected dates

        // Initialize the datepicker with multiple date selection
        $('#holiday_dates').datepicker({
            dateFormat: 'yy-mm-dd',  // Date format
            minDate: 0,              // Disable past dates
            onSelect: function(dateText) {
                if ($.inArray(dateText, selectedDates) === -1) {
                    // Add the selected date to the array if not already selected
                    selectedDates.push(dateText);
                } else {
                    // If the date is already selected, remove it
                    selectedDates = selectedDates.filter(function(date) {
                        return date !== dateText;
                    });
                }
                // Update the input field with selected dates
                $('#holiday_dates').val(selectedDates.join(', '));
            }
        });
    });
</script>

<script>
    document.getElementById('names').addEventListener('change', function() {
        const selectedValue = this.value;

        // Hide all additional fields
        document.getElementById('additional-fields').classList.add('hidden');
        document.getElementById('weekend-fields').classList.add('hidden');
        document.getElementById('holiday-fields').classList.add('hidden');
        document.getElementById('night-fields').classList.add('hidden');

        // Show appropriate fields based on the selection
        if (selectedValue) {
            document.getElementById('additional-fields').classList.remove('hidden');

            if (selectedValue === 'weekend') {
                document.getElementById('weekend-fields').classList.remove('hidden');
            } else if (selectedValue === 'holiday') {
                document.getElementById('holiday-fields').classList.remove('hidden');
            } else if (selectedValue === 'night' || selectedValue === 'night 2' || selectedValue === 'rush hour 1' || selectedValue === 'rush hour 2') {
                document.getElementById('night-fields').classList.remove('hidden');
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