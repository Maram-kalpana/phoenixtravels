@extends('layouts.web')

@section('title', 'Payment - Phoenix Tours & Travels')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7 col-md-9">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">

                        <h2 class="mb-2">
                            Complete Your Payment
                        </h2>

                        <p class="text-muted mb-0">
                            Secure payment powered by Razorpay
                        </p>

                    </div>


                    {{-- Booking Information --}}

                    <div class="border rounded p-3 mb-4">

                        <h5 class="mb-3">
                            Booking Details
                        </h5>

                        <div class="row mb-2">

                            <div class="col-6 text-muted">
                                Reference Number
                            </div>

                            <div
                                class="col-6 text-end fw-semibold"
                                id="bookingRefNo"
                            >
                                {{ $blockData['refNo'] ?? '--' }}
                            </div>

                        </div>


                        <div class="row mb-2">

                            <div class="col-6 text-muted">
                                Selected Seats
                            </div>

                            <div
                                class="col-6 text-end fw-semibold"
                                id="selectedSeats"
                            >
                                --
                            </div>

                        </div>


                        <div class="row">

                            <div class="col-6 text-muted">
                                Total Amount
                            </div>

                          <div
    class="col-6 text-end fw-bold text-primary"
    id="totalAmount"
    style="font-size: 22px;"
>
    ₹0.00
</div>

                        </div>

                    </div>


                    {{-- Payment Status --}}

                    <div
                        id="paymentMessage"
                        class="alert d-none"
                        role="alert"
                    >
                    </div>


                    {{-- Pay Button --}}

                    <div class="text-center">

                        <button
                            type="button"
                            id="payNowBtn"
                            class="btn btn-primary btn-lg px-5"
                        >
                            Pay Now
                        </button>

                    </div>


                    <div class="text-center mt-3">

                        <small class="text-muted">
                            You will be redirected to Razorpay's secure
                            payment window.
                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@section('script')

{{-- Razorpay Checkout --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Server-side Booking Data
    |--------------------------------------------------------------------------
    */

    const serverBookingRefNo = @json($blockData['refNo'] ?? '');

    const serverTotalAmount = parseFloat(
        @json($blockData['totalAmount'] ?? 0)
    ) || 0;


    /*
    |--------------------------------------------------------------------------
    | Browser Booking Data
    |--------------------------------------------------------------------------
    */

    const selectedSeats = JSON.parse(
        sessionStorage.getItem('selectedSeats') || '[]'
    );

    const bookingBlockData = JSON.parse(
        sessionStorage.getItem('bookingBlockData') || '{}'
    );

    const bookingEmail =
        sessionStorage.getItem('bookingEmail') || '';

    const bookingPhone =
        sessionStorage.getItem('bookingPhone') || '';


    /*
    |--------------------------------------------------------------------------
    | Booking Reference
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Use Laravel session as the source of truth.
    |
    */

    const bookingRefNo = serverBookingRefNo;


    /*
    |--------------------------------------------------------------------------
    | Total Amount
    |--------------------------------------------------------------------------
    */

    let totalAmount = serverTotalAmount;


    /*
    |--------------------------------------------------------------------------
    | Fallback Amount
    |--------------------------------------------------------------------------
    |
    | Only used for display if the server session does not contain
    | totalAmount.
    |
    */

    if (!totalAmount || totalAmount <= 0) {

        const sessionAmount = parseFloat(
            sessionStorage.getItem('totalAmount') || '0'
        );

        if (sessionAmount > 0) {
            totalAmount = sessionAmount;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Validate Booking
    |--------------------------------------------------------------------------
    */

    if (!bookingRefNo) {

        showMessage(
            'danger',
            'Booking reference not found. Please start the booking again.'
        );

        $('#payNowBtn')
            .prop('disabled', true);

        return;
    }


    if (!totalAmount || totalAmount <= 0) {

        showMessage(
            'danger',
            'Invalid booking amount.'
        );

        $('#payNowBtn')
            .prop('disabled', true);

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Display Booking Reference
    |--------------------------------------------------------------------------
    */

    $('#bookingRefNo').text(
        bookingRefNo
    );


    /*
    |--------------------------------------------------------------------------
    | Display Selected Seats
    |--------------------------------------------------------------------------
    */

    if (selectedSeats.length > 0) {

        const seatNames = selectedSeats
            .map(function (seat) {

                if (typeof seat === 'object') {

                    return seat.name ||
                           seat.id ||
                           seat.key ||
                           '';

                }

                return seat;

            })
            .filter(Boolean);


        $('#selectedSeats').text(
            seatNames.join(', ')
        );

    } else {

        $('#selectedSeats').text('--');

    }


    /*
    |--------------------------------------------------------------------------
    | Display Amount
    |--------------------------------------------------------------------------
    */

    $('#totalAmount').text(
        '₹' + totalAmount.toFixed(2)
    );


    /*
    |--------------------------------------------------------------------------
    | Pay Button Text
    |--------------------------------------------------------------------------
    */

    $('#payNowBtn').text(
        'Pay ₹' + totalAmount.toFixed(2)
    );


    /*
    |--------------------------------------------------------------------------
    | Pay Now
    |--------------------------------------------------------------------------
    */

    $('#payNowBtn').on('click', function () {

        createRazorpayOrder();

    });


    /*
    |--------------------------------------------------------------------------
    | Create Razorpay Order
    |--------------------------------------------------------------------------
    */

    function createRazorpayOrder() {

        const button = $('#payNowBtn');

        button
            .prop('disabled', true)
            .text('Preparing Payment...');

        hideMessage();


        $.ajax({

            url: "{{ route('bus.razorpay.order') }}",

            type: 'POST',

            data: {

                _token: "{{ csrf_token() }}",

                refNo: bookingRefNo,

                amount: totalAmount

            },


            success: function (response) {

                console.log(
                    'Razorpay Order Response:',
                    response
                );


                if (!response.success) {

                    showMessage(
                        'danger',
                        response.message ||
                        'Unable to create payment order.'
                    );

                    resetPayButton();

                    return;
                }


                const order = response.order;


                if (!order || !order.id) {

                    showMessage(
                        'danger',
                        'Invalid Razorpay order received from server.'
                    );

                    resetPayButton();

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Store Razorpay Order ID
                |--------------------------------------------------------------------------
                */

                sessionStorage.setItem(
                    'razorpayOrderId',
                    order.id
                );


                /*
                |--------------------------------------------------------------------------
                | Open Razorpay
                |--------------------------------------------------------------------------
                */

                openRazorpayCheckout(order);

            },


            error: function (xhr) {

                console.error(
                    'Razorpay Order Error:',
                    xhr.responseText
                );


                let message =
                    'Unable to create payment order.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                showMessage(
                    'danger',
                    message
                );


                resetPayButton();

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Open Razorpay Checkout
    |--------------------------------------------------------------------------
    */

    function openRazorpayCheckout(order) {

        const options = {

            key: "{{ config('services.razorpay.key_id') }}",

            amount: order.amount,

            currency: order.currency || 'INR',

            name: "Phoenix Tours & Travels",

            description: "Bus Ticket Booking",

            order_id: order.id,


            /*
            |--------------------------------------------------------------------------
            | Customer Details
            |--------------------------------------------------------------------------
            */

            prefill: {

                name:
                    sessionStorage.getItem('passengerName') || '',

                email:
                    bookingEmail,

                contact:
                    bookingPhone

            },


            /*
            |--------------------------------------------------------------------------
            | Theme
            |--------------------------------------------------------------------------
            */

            theme: {

                color: "#0d6efd"

            },


            /*
            |--------------------------------------------------------------------------
            | Payment Success
            |--------------------------------------------------------------------------
            */

            handler: function (paymentResponse) {

                console.log(
                    'Razorpay Payment Response:',
                    paymentResponse
                );


                verifyPayment(
                    paymentResponse
                );

            },


            /*
            |--------------------------------------------------------------------------
            | Modal Closed
            |--------------------------------------------------------------------------
            */

            modal: {

                ondismiss: function () {

                    resetPayButton();

                    showMessage(
                        'warning',
                        'Payment window was closed. Your seats are still blocked temporarily. Please complete the payment.'
                    );

                }

            }

        };


        const razorpay =
            new Razorpay(options);


        /*
        |--------------------------------------------------------------------------
        | Payment Failed
        |--------------------------------------------------------------------------
        */

        razorpay.on(
            'payment.failed',
            function (response) {

                console.error(
                    'Razorpay Payment Failed:',
                    response
                );


                let message =
                    'Payment failed. Please try again.';


                if (
                    response.error &&
                    response.error.description
                ) {

                    message =
                        response.error.description;

                }


                showMessage(
                    'danger',
                    message
                );


                resetPayButton();

            }
        );


        razorpay.open();

    }


    /*
    |--------------------------------------------------------------------------
    | Verify Payment
    |--------------------------------------------------------------------------
    */

    function verifyPayment(paymentResponse) {

        $('#payNowBtn')
            .prop('disabled', true)
            .text('Verifying Payment...');

        hideMessage();


        $.ajax({

            url: "{{ route('bus.razorpay.verify') }}",

            type: 'POST',

            data: {

                _token: "{{ csrf_token() }}",

                refNo: bookingRefNo,

                razorpay_order_id:
                    paymentResponse.razorpay_order_id,

                razorpay_payment_id:
                    paymentResponse.razorpay_payment_id,

                razorpay_signature:
                    paymentResponse.razorpay_signature

            },


            success: function (response) {

                console.log(
                    'Payment Verification Response:',
                    response
                );


                if (!response.success) {

                    showMessage(
                        'danger',
                        response.message ||
                        'Payment verification failed.'
                    );

                    resetPayButton();

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Payment Verified
                |--------------------------------------------------------------------------
                */

                sessionStorage.setItem(
                    'paymentStatus',
                    'SUCCESS'
                );


                sessionStorage.setItem(
                    'razorpayPaymentId',
                    paymentResponse.razorpay_payment_id
                );


                sessionStorage.setItem(
                    'razorpayOrderId',
                    paymentResponse.razorpay_order_id
                );


                /*
                |--------------------------------------------------------------------------
                | eTravos Booking Information
                |--------------------------------------------------------------------------
                */

                if (response.booking) {

                    sessionStorage.setItem(
                        'bookingData',
                        JSON.stringify(response.booking)
                    );


                    if (response.booking.tktNo) {

                        sessionStorage.setItem(
                            'ticketNumber',
                            response.booking.tktNo
                        );

                    }


                    if (response.booking.pnrNo) {

                        sessionStorage.setItem(
                            'pnrNumber',
                            response.booking.pnrNo
                        );

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | eTravos Ticket Information
                |--------------------------------------------------------------------------
                */

                if (response.ticket) {

                    sessionStorage.setItem(
                        'ticketData',
                        JSON.stringify(response.ticket)
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Redirect to Ticket
                |--------------------------------------------------------------------------
                */

                window.location.href =
                    "{{ route('bus.ticket.page') }}";

            },


            error: function (xhr) {

                console.error(
                    'Payment Verification Error:',
                    xhr.responseText
                );


                let message =
                    'Payment verification failed.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                showMessage(
                    'danger',
                    message
                );


                resetPayButton();

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | UI Helpers
    |--------------------------------------------------------------------------
    */

    function showMessage(type, message) {

        $('#paymentMessage')
            .removeClass(
                'd-none alert-success alert-danger alert-warning alert-info'
            )
            .addClass(
                'alert-' + type
            )
            .text(message);

    }


    function hideMessage() {

        $('#paymentMessage')
            .addClass('d-none')
            .text('');

    }


    function resetPayButton() {

        $('#payNowBtn')
            .prop('disabled', false)
            .text(
                'Pay ₹' + totalAmount.toFixed(2)
            );

    }

});

</script>

@endsection