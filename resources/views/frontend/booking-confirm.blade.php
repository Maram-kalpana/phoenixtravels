@extends('layouts.web')

@section('title', 'Phoenix Tours & Travels - Booking Confirmation')

@section('content')

<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Booking Confirmation</h2>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="isax isax-home5"></i>
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('bus.list') }}">Bus List</a>
                        </li>

                        <li class="breadcrumb-item active" aria-current="page">
                            Booking Confirmation
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="content content-two">
    <div class="container">

        <a href="{{ route('bus.list') }}"
           class="fs-14 fw-medium d-inline-flex align-items-center gap-1 mb-4">
            <i class="isax isax-arrow-left4 fw-semibold"></i>
            Back to Bus List
        </a>

        <div class="row">

            <!-- Booking Details -->
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Passenger & Booking Details</h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input
                                    type="email"
                                    id="bookingEmail"
                                    class="form-control"
                                    placeholder="Enter email address"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input
                                    type="text"
                                    id="bookingPhone"
                                    class="form-control"
                                    placeholder="Enter mobile number"
                                >
                            </div>

                        </div>

                        <hr>

                        <h5 class="mb-3">Selected Seats</h5>

                        <div id="selectedSeatsContainer">
                            <p class="text-muted">
                                Loading selected seats...
                            </p>
                        </div>

                        <hr>

                        <h5 class="mb-3">Passenger Details</h5>

                        <div id="passengerContainer"></div>

                    </div>
                </div>

            </div>

            <!-- Booking Summary -->
            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header">
                        <h5 class="mb-0">Booking Summary</h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3 pb-3 border-bottom">

                            <p class="d-flex justify-content-between mb-2">
                                <span>Selected Seats</span>
                                <strong id="summarySeats">0</strong>
                            </p>

                            <p class="d-flex justify-content-between mb-0">
                                <span>Number of Seats</span>
                                <strong id="summaryCount">0</strong>
                            </p>

                        </div>

                        <div class="d-flex justify-content-between fw-semibold mb-4">
                            <span>Total Amount</span>
                            <span class="fs-20 text-primary" id="summaryTotal">
                                ₹0.00
                            </span>
                        </div>

                        <button
                            type="button"
                            class="btn btn-primary w-100"
                            id="proceedPayment">
                            Proceed to Payment
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection


@section('script')

<script>
$(document).ready(function () {

    console.log('===== BOOKING CONFIRMATION PAGE =====');

    var selectedSeats = [];
    var totalAmount = 0;
    var seatLayoutData = null;

    /*
     * Get selected seats
     */
    try {

        var storedSeats = sessionStorage.getItem('selectedSeats');

        if (storedSeats) {
            selectedSeats = JSON.parse(storedSeats);
        }

    } catch (e) {

        console.error('Error reading selected seats:', e);
        selectedSeats = [];

    }


    /*
     * Get total amount
     */
    var storedTotal = sessionStorage.getItem('totalAmount');

    if (storedTotal) {
        totalAmount = Number(storedTotal) || 0;
    }


    /*
     * Get seat layout data
     */
    try {

        var storedLayout = sessionStorage.getItem('seatLayoutData');

        if (storedLayout) {
            seatLayoutData = JSON.parse(storedLayout);
        }

    } catch (e) {

        console.error('Error reading seat layout data:', e);

    }


    console.log('Selected Seats:', selectedSeats);
    console.log('Total Amount:', totalAmount);
    console.log('Seat Layout Data:', seatLayoutData);


    /*
     * Display selected seats
     */
    var seatNames = [];

    selectedSeats.forEach(function (seat) {

        seatNames.push(seat.name || seat.id);

    });


    if (seatNames.length > 0) {

        $('#selectedSeatsContainer').html(
            '<div class="d-flex gap-2 flex-wrap">' +
            seatNames.map(function (name) {

                return '<span class="badge bg-primary p-2">' +
                    $('<div>').text(name).html() +
                    '</span>';

            }).join('') +
            '</div>'
        );

    } else {

        $('#selectedSeatsContainer').html(
            '<p class="text-danger">No seats selected.</p>'
        );

    }


    /*
     * Summary
     */
    $('#summarySeats').text(
        seatNames.length > 0
            ? seatNames.join(', ')
            : 'None'
    );

    $('#summaryCount').text(selectedSeats.length);

    $('#summaryTotal').text(
        '₹' + totalAmount.toFixed(2)
    );


    /*
     * Create passenger fields
     */
    var passengerHtml = '';

    selectedSeats.forEach(function (seat, index) {

        passengerHtml += `
            <div class="border rounded p-3 mb-3">

                <h6 class="mb-3">
                    Passenger ${index + 1}
                    - Seat ${escapeHtml(seat.name || seat.id)}
                </h6>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Passenger Name
                        </label>

                        <input
                            type="text"
                            class="form-control passenger-name"
                            data-index="${index}"
                            placeholder="Enter passenger name"
                            required
                        >

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Age
                        </label>

                        <input
                            type="number"
                            class="form-control passenger-age"
                            data-index="${index}"
                            min="1"
                            max="100"
                            placeholder="Age"
                            required
                        >

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Gender
                        </label>

                        <select
                            class="form-control passenger-gender"
                            data-index="${index}"
                            required>

                            <option value="">Select</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>

                        </select>

                    </div>

                </div>

            </div>
        `;

    });

    $('#passengerContainer').html(passengerHtml);


    /*
     * Proceed to payment
     */
    $('#proceedPayment').on('click', function () {

        var email = $('#bookingEmail').val().trim();
        var phone = $('#bookingPhone').val().trim();

        if (!email) {
            alert('Please enter your email address.');
            return;
        }

        if (!phone) {
            alert('Please enter your mobile number.');
            return;
        }

        if (selectedSeats.length === 0) {
            alert('Please select at least one seat.');
            return;
        }


        var passengers = [];
        var valid = true;


        selectedSeats.forEach(function (seat, index) {

            var name = $('.passenger-name[data-index="' + index + '"]')
                .val()
                .trim();

            var age = $('.passenger-age[data-index="' + index + '"]')
                .val();

            var gender = $('.passenger-gender[data-index="' + index + '"]')
                .val();


            if (!name || !age || !gender) {
                valid = false;
            }


            passengers.push({

                name: name,
                age: Number(age),
                gender: gender,

                seatIds: [
                    seat.id
                ]

            });

        });


        if (!valid) {

            alert(
                'Please enter all passenger details.'
            );

            return;
        }


        /*
         * Save booking information.
         */
        sessionStorage.setItem(
            'bookingEmail',
            email
        );

        sessionStorage.setItem(
            'bookingPhone',
            phone
        );

        sessionStorage.setItem(
            'passengers',
            JSON.stringify(passengers)
        );


        /*
         * Temporary message.
         *
         * BLOCK API should be called here next.
         */
       
        // Read the original bus and seat-layout API data.
        var selectedBus = null;

        try {
            selectedBus = JSON.parse(
                sessionStorage.getItem('selectedBus') || 'null'
            );
        } catch (e) {
            selectedBus = null;
        }

        var layout = seatLayoutData || {};

        var traceId =
            layout.traceId ||
            (selectedBus && selectedBus.trace_id) ||
            '';

        // tripKey must come from the eTravos seat-layout response.
        var tripKey = layout.tripKey || '';

        var boardingPoints = Array.isArray(layout.boarding)
            ? layout.boarding
            : [];

        var droppingPoints = Array.isArray(layout.dropping)
            ? layout.dropping
            : [];

        var bpid =
            (selectedBus && selectedBus.boarding_point_id) ||
            (boardingPoints[0] && boardingPoints[0].id) ||
            '';

        var dpid =
            (selectedBus && selectedBus.dropping_point_id) ||
            (droppingPoints[0] && droppingPoints[0].id) ||
            '';

        if (!traceId || !tripKey || !bpid || !dpid) {
            alert(
                'Required bus booking information is missing. ' +
                'Please return to the seat selection page and try again.'
            );
            return;
        }

        var $button = $('#proceedPayment');

        $button.prop('disabled', true).text('Blocking Seats...');

        $.ajax({
            url: '{{ route("bus.block") }}',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                traceId: traceId,
                tripKey: tripKey,
                bpid: String(bpid),
                dpid: String(dpid),
                email: email,
                phone: phone,
                passengers: passengers
            },
            success: function (response) {

    if (!response.success || !response.refNo) {

        alert(
            response.message ||
            'Unable to block the selected seats.'
        );

        $button.prop('disabled', false)
            .text('Proceed to Payment');

        return;
    }


    /*
     * Save eTravos BLOCK information
     */
    sessionStorage.setItem(
        'bookingRefNo',
        String(response.refNo)
    );


    sessionStorage.setItem(
        'bookingBlockData',
        JSON.stringify(response.data || {})
    );


    /*
     * Make sure payment page has the latest
     * booking amount.
     */
    sessionStorage.setItem(
        'totalAmount',
        String(totalAmount)
    );


    /*
     * Payment page also needs the contact details.
     */
    sessionStorage.setItem(
        'bookingEmail',
        email
    );


    sessionStorage.setItem(
        'bookingPhone',
        phone
    );


    /*
     * Save passenger information.
     */
    sessionStorage.setItem(
        'passengers',
        JSON.stringify(passengers)
    );


    /*
     * Redirect to Razorpay payment page.
     */
    window.location.href =
        "{{ route('bus.payment') }}";
},
        });

    });


    function escapeHtml(value) {

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }

});
</script>

@endsection