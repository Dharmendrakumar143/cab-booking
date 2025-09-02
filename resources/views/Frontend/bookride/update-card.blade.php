@extends('Frontend.layouts.master')
@section('title', 'Book Ride')
@section('content')
<main class="container-fluid px-lg-5 px-2 py-5">
    <div class="row book-bg mx-md-0 mx-0 my-md-0 my-4 m ">
        <div class="add-card-input col-md-6 align-content-center p-0 rohit">
            <div class="">
                <div class="book-your-ride h-100 p-4">
                    <form id="payment-form" class="update-card">

                        <div class="input-crd">
                            <div id="card-number"></div>
                        </div>

                        <div class="input-crd">
                            <div id="card-expiry"></div>
                        </div>

                        <div class="input-crd">
                            <div id="card-cvc"></div>
                        </div>

                        <div id="card-errors" role="alert"></div>

                        <div class="loader" id="loader" bis_skin_checked="1"></div>
                        <div class="add-btn text-end mt-4">
                            <button type="sumit" class="btn bg-black text-white w-50 rounded-10 py-2">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-img text-center">
                <img src="{{asset('assets/images/card-img.png')}}" class="img-fluid">
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://js.stripe.com/v3/"></script>


<script >
    document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        document.querySelectorAll(".__PrivateStripeElement").forEach(element => {
            element.removeAttribute("style"); // Removes inline styles
            element.style.width = "100%";
            element.style.height = "auto";
        });
    }, 2000); // Delay to allow Stripe to load
});
setTimeout(() => {
    let stripeInputs = document.querySelectorAll(".__PrivateStripeElement iframe");
    stripeInputs.forEach(iframe => {
        iframe.contentWindow.document.querySelector("input").style.color = "red"; // Change color
    });
}, 2000); // Delay ensures iframe is loaded


const stripe = Stripe('{{ env('STRIPE_PUBLISHABLE_KEY') }}');
var elements = stripe.elements();

var style = {
    base: {
        color: '#fff', // Text color (white)
        fontSize: '14px',
        '::placeholder': {
            color: '#fff' // Placeholder color (white)
        }
    }
};

// Create the card elements
var cardNumber = elements.create('cardNumber', { style });
var cardExpiry = elements.create('cardExpiry', { style });
var cardCvc = elements.create('cardCvc', { style });

// Mount the elements to their respective divs
cardNumber.mount('#card-number');
cardExpiry.mount('#card-expiry');
cardCvc.mount('#card-cvc');

</script>

<script>
    $(document).ready(function () {
        // Handle form submission
        $('#payment-form').on('submit', async function (event) {
            event.preventDefault(); // Prevent form from submitting the traditional way
            // const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $("div#loader").show();
            var card_update_status = true;
            
            // Step 1: Create a SetupIntent to get the client secret
            $.ajax({
                url: '{{route("create-payment-intent")}}', // Endpoint for creating payment intent
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (setupIntent) {
                    const { clientSecret } = setupIntent;

                    // Step 2: Confirm the card setup using the client secret
                    stripe.confirmCardSetup(clientSecret, {
                        payment_method: {
                            card: cardNumber, // Your Stripe card element
                        },
                    }).then(function (result) {

                        console.log(result);
                        $("div#loader").hide();
                        if (result.error) {
                            // Handle error (e.g., insufficient funds)
                            $('#card-errors').text(result.error.message);
                        } else {
                            // Step 3: Send the payment method ID to your backend to save the card
                            $.ajax({
                                url: '{{route("store-card")}}', // Backend endpoint to store the card info
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    payment_method_id: result.setupIntent.payment_method,
                                    card_update_status:card_update_status
                                },
                                success: function (response) {
                                    // console.log('response');
                                    // console.log(response);
                                    $("div#loader").hide();
                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Card Added Successful!',
                                            text: response.message,
                                            confirmButtonColor: '#000000',
                                        }).then(() => {
                                            window.location.href = "{{route('home')}}";
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Card Failed!',
                                            text: response.message,
                                            confirmButtonColor: '#000000',
                                        }).then(() => {
                                            window.location.href = "{{route('update-card-details')}}";
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    // Handle errors if the request fails
                                    console.log('Error saving card:', error);
                                }
                            });
                        }
                    });
                },
                error: function (xhr, status, error) {
                    // Handle error if the SetupIntent request fails
                    console.log('Error creating setup intent:', error);
                }
            });
        });
    });
</script>

@endsection