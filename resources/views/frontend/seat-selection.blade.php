@extends('layouts.web')

@section('title', 'Phoenix Tours & Travels - Seat Selection')

@section('content')

<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Select Your Seats</h2>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="isax isax-home5"></i>
                            </a>
                        </li>

                        <li class="breadcrumb-item active" aria-current="page">
                            Select Your Seats
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

            <!-- ========================= -->
            <!-- SEAT LAYOUT -->
            <!-- ========================= -->

            <div class="col-lg-8">

                <div class="card">

                    <div class="card-body">

                        <div class="seat-content">

                            <div>
                                <h2 class="fs-20 mb-1">
                                    Seat Layout
                                </h2>

                                <p>
                                    Click on a seat to select/deselect
                                </p>
                            </div>

                            <div class="seat-selected-count">
                                <span id="seatCounter">
                                    0/0
                                </span>
                            </div>

                        </div>


                        <!-- Seat Legend -->

                        <div class="seat-selected">

                            <div class="seat-count">
                                <div class="seat bg-white border border-light"></div>
                                <p>Available</p>
                            </div>

                            <div class="seat-count">
                                <div class="seat bg-primary border-primary"></div>
                                <p>Selected</p>
                            </div>

                            <div class="seat-count">
                                <div class="seat bg-light border border-light"></div>
                                <p>Booked</p>
                            </div>

                            <div class="seat-count">
                                <div class="seat bg-white border border-pink"></div>
                                <p>Female</p>
                            </div>

                        </div>


                        <!-- Loading -->

                        <div id="seatLoading" class="text-center py-5">

                            <div class="spinner-border text-primary"
                                 role="status">

                                <span class="visually-hidden">
                                    Loading...
                                </span>

                            </div>

                            <p class="mt-2">
                                Loading seat layout...
                            </p>

                        </div>


                        <!-- Seat Booking -->

                        <div class="seat-booking"
                             id="seatBooking"
                             style="display:none;">

                            <!-- Driver -->

                            <div class="d-flex align-items-center justify-content-end mb-4 pb-4 border-bottom end-item">

                                <div class="driver-seat">
                                    Driver
                                </div>

                            </div>


                            <!-- Seats -->

                            <div class="seat-layout"
                                 id="seatLayout">
                            </div>


                            <!-- Emergency Exit -->

                            <div class="d-flex align-items-center justify-content-center mt-4 pt-4 border-top end-item">

                                <a href="#"
                                   class="btn btn-danger exit-btn">
                                    Emergency Exit
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ========================= -->
            <!-- BOOKING SUMMARY -->
            <!-- ========================= -->

            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header">

                        <h5>
                            Booking Summary
                        </h5>

                    </div>


                    <div class="card-body">

                        <!-- Selected Seats -->

                        <div class="mb-3 pb-3 border-bottom">

                            <label class="mb-2">
                                Selected Seats
                            </label>

                            <div class="d-flex align-items-center gap-2 flex-wrap"
                                 id="selectedSeatsDisplay">

                                <span class="text-muted">
                                    No seats selected
                                </span>

                            </div>

                        </div>


                        <!-- Price -->

                        <div class="mb-3 pb-3 border-bottom">

                            <p class="d-flex align-items-center justify-content-between gap-2 mb-3">

                                Price per seat:

                                <span class="fw-medium text-dark"
                                      id="pricePerSeat">
                                    ₹0
                                </span>

                            </p>


                            <p class="d-flex align-items-center justify-content-between gap-2 mb-0">

                                Number of seats:

                                <span class="fw-medium text-dark"
                                      id="seatCountDisplay">
                                    0
                                </span>

                            </p>

                        </div>


                        <!-- Total -->

                        <div class="d-flex align-items-center justify-content-between fw-semibold text-dark mb-4">

                            Total Amount:

                            <span class="fs-20 text-primary"
                                  id="totalAmount">
                                ₹0
                            </span>

                        </div>


                        <!-- Checkout -->

                        <a href="#"
                           class="btn btn-primary d-flex justify-content-center"
                           id="continueCheckout">

                            Continue to Checkout

                        </a>

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

    console.log('====================================');
    console.log('SEAT SELECTION PAGE');
    console.log('====================================');


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    var busId = null;

    var selectedSeats = [];

    var busData = null;

    var apiData = null;


    /*
    |--------------------------------------------------------------------------
    | Get selected bus from sessionStorage
    |--------------------------------------------------------------------------
    */

    try {

        var storedBus = sessionStorage.getItem('selectedBus');

        console.log('Stored selectedBus:', storedBus);

        if (storedBus) {

            busData = JSON.parse(storedBus);

        }

    } catch (error) {

        console.error(
            'Error parsing selectedBus:',
            error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Get actual eTravos bus ID
    |--------------------------------------------------------------------------
    */

    if (busData && busData.id) {

        busId = busData.id;

    } else {

        busId = decodeURIComponent(
            '{{ $busId }}'
        );

    }


    console.log('Bus Data:', busData);

    console.log('Actual Bus ID:', busId);


    /*
    |--------------------------------------------------------------------------
    | Load Seat Layout
    |--------------------------------------------------------------------------
    */

    function loadSeatLayout() {

        console.log('====================================');
        console.log('LOADING SEAT LAYOUT');
        console.log('====================================');


        $('#seatLoading').show();

        $('#seatBooking').hide();


        /*
        |--------------------------------------------------------------------------
        | Validate bus data
        |--------------------------------------------------------------------------
        */

        if (!busData) {

            showError(
                'Bus information was not found. Please go back and select a bus again.'
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Trace ID
        |--------------------------------------------------------------------------
        */

        var traceId =
            busData.trace_id || '';


        /*
        |--------------------------------------------------------------------------
        | Boarding Point
        |--------------------------------------------------------------------------
        */

        var bpid =
            busData.boarding_point_id || '';


        if (
            busData.boarding_points &&
            Array.isArray(busData.boarding_points) &&
            busData.boarding_points.length > 0
        ) {

            bpid =
                busData.boarding_points[0].id ||
                bpid;

        }


        /*
        |--------------------------------------------------------------------------
        | Dropping Point
        |--------------------------------------------------------------------------
        */

        var dpid =
            busData.dropping_point_id || '';


        if (
            busData.dropping_points &&
            Array.isArray(busData.dropping_points) &&
            busData.dropping_points.length > 0
        ) {

            dpid =
                busData.dropping_points[0].id ||
                dpid;

        }


        console.log(
            'Seat Layout Parameters:',
            {
                busId: busId,
                traceId: traceId,
                bpid: bpid,
                dpid: dpid
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Validate parameters
        |--------------------------------------------------------------------------
        */

        if (
            !busId ||
            !traceId ||
            !bpid ||
            !dpid
        ) {

            console.error(
                'Missing seat layout parameters'
            );

            showError(
                'Unable to load seat layout because bus, boarding, dropping or trace information is missing.'
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Call Laravel Seat Layout API
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url: '{{ route("bus.seat-layout") }}',

            type: 'POST',

            data: {

                busId: busId,

                bpid: bpid,

                dpid: dpid,

                traceId: traceId,

                _token:
                    $('meta[name="csrf-token"]').attr(
                        'content'
                    )

            },


            beforeSend: function () {

                console.log(
                    'Sending seat layout request...'
                );

            },


            success: function (response) {

                console.log(
                    '===================================='
                );

                console.log(
                    'SEAT LAYOUT RESPONSE'
                );

                console.log(
                    '===================================='
                );

                console.log(response);


                $('#seatLoading').hide();


                /*
                |--------------------------------------------------------------------------
                | Successful response
                |--------------------------------------------------------------------------
                */

                if (
                    response &&
                    response.success === true &&
                    response.data
                ) {

                    apiData =
                        response.data;


                    console.log(
                        'Seat count:',
                        apiData.seats
                            ? apiData.seats.length
                            : 0
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Store important API data
                    |--------------------------------------------------------------------------
                    */

                    sessionStorage.setItem(
                        'seatLayoutData',
                        JSON.stringify(
                            apiData
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Render seats
                    |--------------------------------------------------------------------------
                    */

                    renderSeats(
                        apiData
                    );


                    $('#seatBooking').show();


                    console.log(
                        'Seat layout displayed successfully'
                    );


                } else {

                    console.error(
                        'Seat layout API returned failure:',
                        response
                    );


                    showError(

                        response &&
                        response.message

                            ? response.message

                            : 'Seat layout is not available for this bus.'

                    );

                }

            },


            error: function (
                xhr,
                status,
                error
            ) {

                console.error(
                    '===================================='
                );

                console.error(
                    'SEAT LAYOUT AJAX ERROR'
                );

                console.error(
                    '===================================='
                );

                console.error(
                    'HTTP Status:',
                    xhr.status
                );

                console.error(
                    'Status:',
                    status
                );

                console.error(
                    'Error:',
                    error
                );

                console.error(
                    'Response:',
                    xhr.responseText
                );


                $('#seatLoading').hide();


                var message =
                    'Failed to load seat layout. Please try again.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                showError(message);

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Render Seats
    |--------------------------------------------------------------------------
    */

    function renderSeats(data) {

        console.log(
            '===================================='
        );

        console.log(
            'RENDERING SEATS'
        );

        console.log(
            '===================================='
        );


        var container =
            $('#seatLayout');


        container.empty();


        /*
        |--------------------------------------------------------------------------
        | No seats
        |--------------------------------------------------------------------------
        */

        if (
            !data.seats ||
            !Array.isArray(data.seats) ||
            data.seats.length === 0
        ) {

            container.html(

                '<div class="text-center py-5">' +

                    '<p class="text-muted mb-2">' +

                        'No seats available for this bus.'

                    + '</p>' +

                '</div>'

            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Max seats
        |--------------------------------------------------------------------------
        */

        var maxSeats =
            Number(
                data.maxSeatsPerTicket
            ) || 10;


        $('#seatCounter').text(
            '0/' + maxSeats
        );


        /*
        |--------------------------------------------------------------------------
        | Group seats by Y coordinate
        |--------------------------------------------------------------------------
        */

        var seatsByRow = {};


        data.seats.forEach(
            function (seat) {

                var row =
                    Number(seat.y) || 0;


                if (
                    !seatsByRow[row]
                ) {

                    seatsByRow[row] = [];

                }


                seatsByRow[row].push(
                    seat
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Sort rows
        |--------------------------------------------------------------------------
        */

        var sortedRows =
            Object.keys(
                seatsByRow
            ).sort(
                function (a, b) {

                    return (
                        Number(a) -
                        Number(b)
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Build HTML
        |--------------------------------------------------------------------------
        */

        var gridHtml =
            '<div class="seat-grid">';


        sortedRows.forEach(
            function (row) {


                gridHtml +=
                    '<div class="seat-row">';


                /*
                |--------------------------------------------------------------------------
                | Sort seats horizontally
                |--------------------------------------------------------------------------
                */

                var seats =
                    seatsByRow[row].sort(
                        function (a, b) {

                            return (
                                (Number(a.x) || 0) -
                                (Number(b.x) || 0)
                            );

                        }
                    );


                seats.forEach(
                    function (seat) {


                        var status =
                            seat.status ||
                            'AFA';


                        var statusClass =
                            getSeatStatusClass(
                                status
                            );


                        var isBooked =
                            isBookedStatus(
                                status
                            );


                        var isFemale =
                            seat.desc &&
                            String(
                                seat.desc
                            )
                            .toLowerCase()
                            .indexOf(
                                'female'
                            ) !== -1;


                        var fare = 0;


                        if (
                            seat.fare &&
                            seat.fare.total !== undefined
                        ) {

                            fare =
                                Number(
                                    seat.fare.total
                                ) || 0;

                        }


                        var seatName =
                            seat.name ||
                            seat.id ||
                            '';


                        var seatId =
                            seat.id ||
                            '';


                        var seatKey =
                            seat.key ||
                            '';


                        /*
                        |--------------------------------------------------------------------------
                        | Seat HTML
                        |--------------------------------------------------------------------------
                        */

                        gridHtml +=

                            '<div class="seat-wrapper">' +

                                '<div ' +

                                    'class="seat ' +
                                    statusClass +

                                    (
                                        isBooked
                                            ? ' booked'
                                            : ''
                                    ) +

                                    (
                                        isFemale
                                            ? ' female'
                                            : ''
                                    ) +

                                    '"' +

                                    'data-seat-id="' +
                                    escapeHtml(
                                        seatId
                                    ) +
                                    '"' +

                                    'data-seat-key="' +
                                    escapeHtml(
                                        seatKey
                                    ) +
                                    '"' +

                                    'data-seat-name="' +
                                    escapeHtml(
                                        seatName
                                    ) +
                                    '"' +

                                    'data-seat-status="' +
                                    escapeHtml(
                                        status
                                    ) +
                                    '"' +

                                    'data-seat-fare="' +
                                    fare +
                                    '"' +

                                    (
                                        isBooked
                                            ? ''
                                            : 'onclick="toggleSeat(this)"'
                                    ) +

                                    'style="' +

                                        (
                                            isBooked
                                                ? 'cursor:not-allowed;'
                                                : 'cursor:pointer;'
                                        ) +

                                    '"' +

                                '>' +

                                    /*
                                    | Seat icon
                                    */

                                    '<div class="seat-icon">' +

                                        '<i class="isax isax-chair"></i>' +

                                    '</div>' +

                                    '<div class="seat-number">' +

                                        escapeHtml(
                                            seatName
                                        ) +

                                    '</div>' +

                                '</div>' +

                            '</div>';

                    }
                );


                gridHtml +=
                    '</div>';

            }
        );


        gridHtml +=
            '</div>';


        container.html(
            gridHtml
        );


        /*
        |--------------------------------------------------------------------------
        | Update initial price
        |--------------------------------------------------------------------------
        */

        var firstAvailableSeat =
            data.seats.find(
                function (seat) {

                    return (
                        !isBookedStatus(
                            seat.status
                        )
                    );

                }
            );


        if (
            firstAvailableSeat &&
            firstAvailableSeat.fare
        ) {

            var firstFare =
                Number(
                    firstAvailableSeat.fare.total
                ) || 0;


            $('#pricePerSeat').text(
                '₹' +
                firstFare.toFixed(2)
            );

        }


        console.log(
            'Seat rendering completed:',
            data.seats.length
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Seat Status Class
    |--------------------------------------------------------------------------
    */

    function getSeatStatusClass(status) {

        switch (status) {


            /*
            |--------------------------------------------------------------------------
            | Available
            |--------------------------------------------------------------------------
            */

            case 'AFA':
            case 'AFM':
            case 'AFF':

                return 'available';


            /*
            |--------------------------------------------------------------------------
            | Booked
            |--------------------------------------------------------------------------
            */

            case 'BFA':
            case 'BFM':
            case 'BFF':
            case 'BKD':
            case 'UNA':

                return 'booked';


            /*
            |--------------------------------------------------------------------------
            | Selected
            |--------------------------------------------------------------------------
            */

            case 'SEL':

                return 'selected';


            /*
            |--------------------------------------------------------------------------
            | Default
            |--------------------------------------------------------------------------
            */

            default:

                return 'available';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Check Booked Status
    |--------------------------------------------------------------------------
    */

    function isBookedStatus(status) {

        return (

            status === 'BFA' ||

            status === 'BFM' ||

            status === 'BFF' ||

            status === 'BKD' ||

            status === 'UNA'

        );

    }


    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        return String(value)

            .replace(
                /&/g,
                '&amp;'
            )

            .replace(
                /</g,
                '&lt;'
            )

            .replace(
                />/g,
                '&gt;'
            )

            .replace(
                /"/g,
                '&quot;'
            )

            .replace(
                /'/g,
                '&#039;'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Select / Deselect Seat
    |--------------------------------------------------------------------------
    */

    window.toggleSeat =
        function (element) {


            var $seat =
                $(element);


            var seatId =
                String(
                    $seat.attr(
                        'data-seat-id'
                    ) || ''
                );


            var seatName =
                $seat.attr(
                    'data-seat-name'
                ) ||
                seatId;


            var status =
                $seat.attr(
                    'data-seat-status'
                ) ||
                'AFA';


            var fare =
                Number(
                    $seat.attr(
                        'data-seat-fare'
                    )
                ) || 0;


            /*
            |--------------------------------------------------------------------------
            | Prevent booked seats
            |--------------------------------------------------------------------------
            */

            if (
                isBookedStatus(
                    status
                )
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Maximum seats
            |--------------------------------------------------------------------------
            */

            var maxSeats = 10;


            if (
                apiData &&
                apiData.maxSeatsPerTicket
            ) {

                maxSeats =
                    Number(
                        apiData.maxSeatsPerTicket
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | Already selected?
            |--------------------------------------------------------------------------
            */

            var isSelected =
                $seat.hasClass(
                    'selected'
                );


            var isFemale =
                $seat.hasClass(
                    'female'
                );


            /*
            |--------------------------------------------------------------------------
            | Deselect
            |--------------------------------------------------------------------------
            */

            if (isSelected) {


                $seat.removeClass(
                    'selected'
                );


                selectedSeats =
                    selectedSeats.filter(
                        function (seat) {

                            return (
                                seat.id !==
                                seatId
                            );

                        }
                    );


            }


            /*
            |--------------------------------------------------------------------------
            | Select
            |--------------------------------------------------------------------------
            */

            else {


                if (
                    selectedSeats.length >=
                    maxSeats
                ) {

                    alert(
                        'You can select maximum ' +
                        maxSeats +
                        ' seats.'
                    );

                    return;

                }


                $seat.addClass(
                    'selected'
                );


                selectedSeats.push({

                    id: seatId,

                    name: seatName,

                    key:
                        $seat.attr(
                            'data-seat-key'
                        ) || '',

                    fare: fare,

                    isFemale:
                        isFemale

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Update summary
            |--------------------------------------------------------------------------
            */

            updateBookingSummary();

        };


    /*
    |--------------------------------------------------------------------------
    | Update Booking Summary
    |--------------------------------------------------------------------------
    */

    function updateBookingSummary() {

        var count =
            selectedSeats.length;


        /*
        |--------------------------------------------------------------------------
        | Total based on actual seat fare
        |--------------------------------------------------------------------------
        */

        var total =
            selectedSeats.reduce(

                function (
                    sum,
                    seat
                ) {

                    return (
                        sum +
                        Number(
                            seat.fare || 0
                        )
                    );

                },

                0

            );


        /*
        |--------------------------------------------------------------------------
        | Maximum seats
        |--------------------------------------------------------------------------
        */

        var maxSeats = 10;


        if (
            apiData &&
            apiData.maxSeatsPerTicket
        ) {

            maxSeats =
                Number(
                    apiData.maxSeatsPerTicket
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Counter
        |--------------------------------------------------------------------------
        */

        $('#seatCounter').text(
            count +
            '/' +
            maxSeats
        );


        $('#seatCountDisplay').text(
            count
        );


        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $('#totalAmount').text(
            '₹' +
            total.toFixed(2)
        );


        /*
        |--------------------------------------------------------------------------
        | Selected seats
        |--------------------------------------------------------------------------
        */

        if (
            count > 0
        ) {


            var html = '';


            selectedSeats.forEach(
                function (seat) {

                    html +=

                        '<span class="badge bg-primary me-1">' +

                            escapeHtml(
                                seat.name
                            ) +

                        '</span>';

                }
            );


            $('#selectedSeatsDisplay')
                .html(html);


            /*
            |--------------------------------------------------------------------------
            | Price per seat
            |--------------------------------------------------------------------------
            */

            var firstFare =
                Number(
                    selectedSeats[0].fare
                ) || 0;


            $('#pricePerSeat').text(
                '₹' +
                firstFare.toFixed(2)
            );


        }


        /*
        |--------------------------------------------------------------------------
        | Nothing selected
        |--------------------------------------------------------------------------
        */

        else {


            $('#selectedSeatsDisplay')
                .html(
                    '<span class="text-muted">' +
                        'No seats selected' +
                    '</span>'
                );


            $('#pricePerSeat')
                .text('₹0');

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Show Error
    |--------------------------------------------------------------------------
    */

    function showError(message) {

        $('#seatLoading').hide();

        $('#seatBooking').hide();


        $('#seatLayout').html(

            '<div class="text-center py-5 text-danger">' +

                '<i class="isax isax-danger-circle" ' +
                   'style="font-size:48px;">' +
                '</i>' +

                '<p class="mt-3">' +

                    escapeHtml(
                        message
                    ) +

                '</p>' +

                '<button ' +

                    'type="button" ' +

                    'class="btn btn-primary mt-2" ' +

                    'onclick="location.reload()">' +

                    'Try Again' +

                '</button>' +

            '</div>'

        );

    }


    /*
    |--------------------------------------------------------------------------
    | Continue to Checkout
    |--------------------------------------------------------------------------
    */

    $('#continueCheckout').on(
        'click',
        function (e) {

            e.preventDefault();


            /*
            |--------------------------------------------------------------------------
            | No seats
            |--------------------------------------------------------------------------
            */

            if (
                selectedSeats.length === 0
            ) {

                alert(
                    'Please select at least one seat.'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | Calculate total
            |--------------------------------------------------------------------------
            */

            var total =
                selectedSeats.reduce(

                    function (
                        sum,
                        seat
                    ) {

                        return (
                            sum +
                            Number(
                                seat.fare || 0
                            )
                        );

                    },

                    0

                );


            /*
            |--------------------------------------------------------------------------
            | Store selected seats
            |--------------------------------------------------------------------------
            */

            sessionStorage.setItem(

                'selectedSeats',

                JSON.stringify(
                    selectedSeats
                )

            );


            /*
            |--------------------------------------------------------------------------
            | Store total
            |--------------------------------------------------------------------------
            */

            sessionStorage.setItem(

                'totalAmount',

                total.toFixed(2)

            );


            /*
            |--------------------------------------------------------------------------
            | Store seat layout
            |--------------------------------------------------------------------------
            */

            sessionStorage.setItem(

                'seatLayoutData',

                JSON.stringify(
                    apiData
                )

            );


            /*
            |--------------------------------------------------------------------------
            | Store booking information
            |--------------------------------------------------------------------------
            */

            sessionStorage.setItem(

                'busBookingData',

                JSON.stringify({

                    busId:
                        busId,

                    traceId:
                        busData.trace_id || '',

                    bpid:
                        busData.boarding_point_id ||
                        (
                            busData.boarding_points &&
                            busData.boarding_points.length
                                ? busData.boarding_points[0].id
                                : ''
                        ),

                    dpid:
                        busData.dropping_point_id ||
                        (
                            busData.dropping_points &&
                            busData.dropping_points.length
                                ? busData.dropping_points[0].id
                                : ''
                        ),

                    tripKey:
                        apiData &&
                        apiData.tripKey
                            ? apiData.tripKey
                            : '',

                    api:
                        apiData &&
                        apiData.api
                            ? apiData.api
                            : '',

                    selectedSeats:
                        selectedSeats,

                    total:
                        total

                })

            );


            console.log(
                'Booking data stored:',
                {
                    busId: busId,
                    traceId:
                        busData.trace_id,
                    tripKey:
                        apiData
                            ? apiData.tripKey
                            : '',
                    selectedSeats:
                        selectedSeats,
                    total:
                        total
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Go to confirmation page
            |--------------------------------------------------------------------------
            */

            window.location.href =
                '/bus/booking/confirm';

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Start
    |--------------------------------------------------------------------------
    */

    loadSeatLayout();

});

</script>


<style>

/*
|--------------------------------------------------------------------------
| Seat Grid
|--------------------------------------------------------------------------
*/

.seat-grid {

    display: flex;

    flex-direction: column;

    gap: 18px;

    padding: 15px;

}


/*
|--------------------------------------------------------------------------
| Seat Row
|--------------------------------------------------------------------------
*/

.seat-row {

    display: flex;

    gap: 16px;

    justify-content: center;

    align-items: center;

    flex-wrap: wrap;

}


/*
|--------------------------------------------------------------------------
| Seat Wrapper
|--------------------------------------------------------------------------
*/

.seat-wrapper {

    display: inline-block;

}


/*
|--------------------------------------------------------------------------
| Seat
|--------------------------------------------------------------------------
*/

.seat {

    width: 140px;

    height: 140px;

    display: flex;

    flex-direction: column;

    align-items: center;

    justify-content: center;

    border-radius: 15px;

    font-size: 16px;

    font-weight: 600;

    border: 4px solid #e1e8ef;

    background: #fff;

    transition:
        all 0.2s ease;

    user-select: none;

    position: relative;

}


/*
|--------------------------------------------------------------------------
| Seat Icon
|--------------------------------------------------------------------------
*/

.seat-icon {

    font-size: 38px;

    line-height: 1;

    margin-bottom: 8px;

}


/*
|--------------------------------------------------------------------------
| Seat Number
|--------------------------------------------------------------------------
*/

.seat-number {

    font-size: 18px;

    font-weight: 600;

}


/*
|--------------------------------------------------------------------------
| Available
|--------------------------------------------------------------------------
*/

.seat.available {

    background: #fff;

    border-color: #dfe7ef;

    color: #111;

}


.seat.available:hover {

    transform: scale(1.04);

    box-shadow:
        0 5px 18px
        rgba(0,0,0,0.12);

}


/*
|--------------------------------------------------------------------------
| Selected
|--------------------------------------------------------------------------
*/

.seat.selected {

    background: #209fd0;

    border-color: #209fd0;

    color: #fff;

    box-shadow:
        0 5px 15px
        rgba(32,159,208,0.3);

}


/*
|--------------------------------------------------------------------------
| Booked
|--------------------------------------------------------------------------
*/

.seat.booked {

    background: #e5ebf1;

    border-color: #e5ebf1;

    color: #8c969e;

    opacity: 0.8;

}


/*
|--------------------------------------------------------------------------
| Female
|--------------------------------------------------------------------------
*/

.seat.female {

    border-color: #e83e8c;

}


.seat.female.selected {

    background: #e83e8c;

    border-color: #e83e8c;

    color: #fff;

}


/*
|--------------------------------------------------------------------------
| Driver
|--------------------------------------------------------------------------
*/

.driver-seat {

    background: #dc3545;

    color: #fff;

    padding: 8px 20px;

    border-radius: 6px;

    font-weight: 600;

}


/*
|--------------------------------------------------------------------------
| Emergency Exit
|--------------------------------------------------------------------------
*/

.exit-btn {

    padding: 8px 30px;

    font-weight: 600;

}


/*
|--------------------------------------------------------------------------
| Mobile
|--------------------------------------------------------------------------
*/

@media (max-width: 767px) {

    .seat {

        width: 75px;

        height: 75px;

        border-width: 3px;

    }


    .seat-icon {

        font-size: 24px;

        margin-bottom: 4px;

    }


    .seat-number {

        font-size: 13px;

    }


    .seat-row {

        gap: 7px;

    }

}

</style>

@endsection