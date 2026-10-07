@extends('layouts.web')

@section('title', 'Phoenix Tours & Travels - Booking Confirmed')

@section('content')

<div class="container py-5">

    <div class="text-center mb-4">
        <div style="
            width:70px;
            height:70px;
            margin:0 auto 15px;
            border-radius:50%;
            background:#198754;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:35px;
        ">
            ✓
        </div>

        <h2 class="fw-bold">Booking Confirmed</h2>

        <p class="text-muted">
            Your bus ticket has been booked successfully.
        </p>
    </div>

    <div id="ticketContainer">

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <div id="loadingMessage" class="text-center py-4">
                    <div class="spinner-border text-primary mb-3"></div>
                    <p class="mb-0">Loading ticket details...</p>
                </div>

                <div id="ticketContent" style="display:none;">

                    <div class="row mb-4">

                        <div class="col-md-6">
                            <small class="text-muted">Ticket Number</small>
                            <h5 id="ticketNumber" class="fw-bold mb-0"></h5>
                        </div>

                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <small class="text-muted">PNR Number</small>
                            <h5 id="pnrNumber" class="fw-bold mb-0"></h5>
                        </div>

                    </div>

                    <hr>

                    <div class="row mb-4">

                        <div class="col-md-6">
                            <small class="text-muted">From</small>
                            <h5 id="source" class="fw-bold"></h5>
                        </div>

                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <small class="text-muted">To</small>
                            <h5 id="destination" class="fw-bold"></h5>
                        </div>

                    </div>

                    <div class="row mb-4">

                        <div class="col-md-4">
                            <small class="text-muted">Journey Date</small>
                            <p id="journeyDate" class="fw-semibold mb-0"></p>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted">Bus</small>
                            <p id="busName" class="fw-semibold mb-0"></p>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted">Status</small>
                            <p id="ticketStatus" class="fw-semibold text-success mb-0"></p>
                        </div>

                    </div>

                    <hr>

                    <h5 class="fw-bold mb-3">Passenger Details</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Seat</th>
                                </tr>
                            </thead>

                            <tbody id="passengerTableBody">
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="row">

                        <div class="col-md-6">
                            <small class="text-muted">Email</small>
                            <p id="contactEmail" class="mb-2"></p>

                            <small class="text-muted">Phone</small>
                            <p id="contactPhone" class="mb-0"></p>
                        </div>

                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <small class="text-muted">Booking Reference</small>
                            <p id="bookingReference" class="fw-bold"></p>

                            <small class="text-muted">Payment ID</small>
                            <p id="paymentId" class="mb-0"></p>
                        </div>

                    </div>

                    <div class="text-center mt-4">

                        <button
                            type="button"
                            id="printTicket"
                            class="btn btn-primary me-2">
                            Print Ticket
                        </button>

                        <a
                            href="{{ route('bus.list') }}"
                            class="btn btn-outline-secondary">
                            Book Another Bus
                        </a>

                    </div>

                </div>

                <div
                    id="errorMessage"
                    class="alert alert-danger"
                    style="display:none;">
                </div>

            </div>
        </div>

    </div>

</div>

@endsection


@section('script')

<script>

$(document).ready(function () {

    console.log('Ticket page loaded.');

    /*
     * Data saved after successful Razorpay + eTravos BOOK
     */
    const serverBookingData =
    @json(session('etravos_booking'));

const bookingDataRaw =
    sessionStorage.getItem('bookingData');

    /*
     * Optional older ticket data
     */
    const ticketDataRaw =
        sessionStorage.getItem('ticketData');

    /*
     * Booking information saved during block/payment
     */
    const bookingBlockDataRaw =
        sessionStorage.getItem('bookingBlockData');

    /*
     * Passenger information
     */
    const passengersRaw =
        sessionStorage.getItem('passengers');

    const bookingEmail =
        sessionStorage.getItem('bookingEmail') || '';

    const bookingPhone =
        sessionStorage.getItem('bookingPhone') || '';

    const paymentId =
        sessionStorage.getItem('razorpayPaymentId') || '';

    let bookingData = null;
    let ticketData = null;
    let bookingBlockData = null;
    let passengers = [];
/*
 * Prefer Laravel session data because it comes
 * directly from the successful eTravos booking.
 */
if (
    serverBookingData &&
    serverBookingData.tktNo
) {

    bookingData = serverBookingData;

    console.log(
        'Booking data from Laravel session:',
        bookingData
    );

}
    /*
     * Parse bookingData
     */
    /*
 * Use sessionStorage only as fallback.
 */
if (!bookingData) {

    try {

        if (bookingDataRaw) {

            bookingData =
                JSON.parse(bookingDataRaw);

            console.log(
                'Booking data from sessionStorage:',
                bookingData
            );

        }

    } catch (error) {

        console.error(
            'Failed to parse bookingData:',
            error
        );

    }

}


    /*
     * Parse old ticketData if available
     */
    try {

        if (ticketDataRaw) {

            ticketData =
                JSON.parse(ticketDataRaw);

            console.log(
                'ticketData:',
                ticketData
            );
        }

    } catch (error) {

        console.error(
            'Failed to parse ticketData:',
            error
        );

    }


    /*
     * Parse block data
     */
    try {

        if (bookingBlockDataRaw) {

            bookingBlockData =
                JSON.parse(
                    bookingBlockDataRaw
                );

        }

    } catch (error) {

        console.error(
            'Failed to parse bookingBlockData:',
            error
        );

    }


    /*
     * Parse passengers
     */
    try {

        if (passengersRaw) {

            passengers =
                JSON.parse(passengersRaw);

        }

    } catch (error) {

        console.error(
            'Failed to parse passengers:',
            error
        );

    }


    /*
     * IMPORTANT
     *
     * The current payment success response
     * stores the ticket number directly in
     * bookingData.tktNo
     */
    if (bookingData && bookingData.tktNo) {

        console.log(
            'Confirmed booking found:',
            bookingData
        );

        /*
         * Hide loading
         */
        $('#loadingMessage').hide();

        /*
         * Show ticket content
         */
        $('#ticketContent').show();


        /*
         * Ticket number
         */
        $('#ticketNumber').text(
            bookingData.tktNo || 'N/A'
        );


        /*
         * PNR
         */
        $('#pnrNumber').text(
            bookingData.pnrNo ||
            bookingData.tktNo ||
            'N/A'
        );


        /*
         * Booking reference
         */
        $('#bookingReference').text(
            bookingData.refNo || 'N/A'
        );


        /*
         * Status
         */
        $('#ticketStatus').text(
            bookingData.status ||
            'CONFIRMED'
        );


        /*
         * Payment ID
         */
        $('#paymentId').text(
            paymentId || 'N/A'
        );


        /*
         * Contact information
         */
        $('#contactEmail').text(
            bookingEmail || '-'
        );

        $('#contactPhone').text(
            bookingPhone || '-'
        );


        /*
         * Try to get journey information
         * from block data if available.
         */
        if (bookingBlockData) {

            $('#source').text(
                bookingBlockData.source ||
                bookingBlockData.src ||
                '-'
            );

            $('#destination').text(
                bookingBlockData.destination ||
                bookingBlockData.dst ||
                '-'
            );

            $('#journeyDate').text(
                bookingBlockData.journeyDate ||
                bookingBlockData.doj ||
                '-'
            );

            $('#busName').text(
                bookingBlockData.busName ||
                bookingBlockData.name ||
                '-'
            );

        } else {

            $('#source').text('-');
            $('#destination').text('-');
            $('#journeyDate').text('-');
            $('#busName').text('-');

        }


        /*
         * Render passengers
         */
        renderPassengers(passengers);


    } else if (ticketData) {

        /*
         * Fallback for older ticketData format
         */
        console.log(
            'Using ticketData fallback.'
        );

        $('#loadingMessage').hide();

        $('#ticketContent').show();

        populateTicket(ticketData);


    } else {

        /*
         * No booking information found
         */
        showError(
            'Booking information was not found. Please return to the booking page and try again.'
        );

        console.error(
            'No bookingData or ticketData found in sessionStorage.'
        );

    }


    /*
     * Print ticket
     */
    $('#printTicket').on(
        'click',
        function () {

            window.print();

        }
    );


    /*
     * Populate ticket from eTravos ticket response
     */
    function populateTicket(ticket) {

        const ticketNumber =
            ticket.ticketNo ||
            ticket.tktNo ||
            ticket.ticketNumber ||
            '';

        const pnr =
            ticket.pnrNo ||
            ticket.pnr ||
            '';

        const bus =
            ticket.bus ||
            {};

        const summary =
            ticket.summary ||
            {};

        const contact =
            ticket.contact ||
            {};


        $('#ticketNumber').text(
            ticketNumber || 'N/A'
        );


        $('#pnrNumber').text(
            pnr || 'N/A'
        );


        $('#source').text(
            summary.src ||
            bus.src ||
            ticket.src ||
            '-'
        );


        $('#destination').text(
            summary.dst ||
            bus.dst ||
            ticket.dst ||
            '-'
        );


        $('#journeyDate').text(
            summary.doj ||
            ticket.doj ||
            ticket.journeyDate ||
            '-'
        );


        $('#busName').text(
            bus.name ||
            ticket.busName ||
            '-'
        );


        $('#ticketStatus').text(
            ticket.status ||
            'CONFIRMED'
        );


        $('#contactEmail').text(
            contact.email ||
            ticket.email ||
            bookingEmail ||
            '-'
        );


        $('#contactPhone').text(
            contact.phone ||
            ticket.phone ||
            bookingPhone ||
            '-'
        );


        $('#bookingReference').text(
            ticket.refNo ||
            (bookingData && bookingData.refNo) ||
            '-'
        );


        $('#paymentId').text(
            paymentId || '-'
        );


        let ticketPassengers =
            ticket.pax ||
            ticket.passengers ||
            [];


        if (
            !Array.isArray(ticketPassengers) ||
            ticketPassengers.length === 0
        ) {

            ticketPassengers =
                passengers;

        }


        renderPassengers(
            ticketPassengers
        );

    }


    /*
     * Render passengers
     */
    function renderPassengers(
        passengerList
    ) {

        const tbody =
            $('#passengerTableBody');

        tbody.empty();


        if (
            !Array.isArray(passengerList) ||
            passengerList.length === 0
        ) {

            tbody.append(`
                <tr>
                    <td colspan="5"
                        class="text-center text-muted">
                        Passenger information not available.
                    </td>
                </tr>
            `);

            return;

        }


        passengerList.forEach(
            function (
                passenger,
                index
            ) {

                let seat = '';


                if (
                    Array.isArray(
                        passenger.seatIds
                    )
                ) {

                    seat =
                        passenger.seatIds.join(
                            ', '
                        );

                } else {

                    seat =
                        passenger.seatId ||
                        passenger.seat ||
                        '';

                }


                tbody.append(`
                    <tr>
                        <td>${index + 1}</td>

                        <td>${escapeHtml(
                            passenger.name || '-'
                        )}</td>

                        <td>${escapeHtml(
                            passenger.gender || '-'
                        )}</td>

                        <td>${escapeHtml(
                            passenger.age || '-'
                        )}</td>

                        <td>${escapeHtml(
                            seat || '-'
                        )}</td>
                    </tr>
                `);

            }
        );

    }


    /*
     * Show error
     */
    function showError(
        message
    ) {

        $('#loadingMessage').hide();

        $('#ticketContent').hide();

        $('#errorMessage')
            .text(message)
            .show();

    }


    /*
     * Prevent HTML injection
     */
    function escapeHtml(
        value
    ) {

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

});

</script>


@endsection


<style>

@media print {

    body * {
        visibility: hidden;
    }

    #ticketContainer,
    #ticketContainer * {
        visibility: visible;
    }

    #ticketContainer {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    #printTicket,
    #ticketContainer .btn {
        display: none !important;
    }

}

</style>