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


@push('scripts')

<script>

$(document).ready(function () {

    /*
     * Data saved by payment.blade.php
     */
    const ticketDataRaw =
        sessionStorage.getItem('ticketData');

    const bookingRefNo =
        sessionStorage.getItem('bookingRefNo');

    const bookingBlockDataRaw =
        sessionStorage.getItem('bookingBlockData');

    const passengersRaw =
        sessionStorage.getItem('passengers');

    const bookingEmail =
        sessionStorage.getItem('bookingEmail') || '';

    const bookingPhone =
        sessionStorage.getItem('bookingPhone') || '';

    const paymentId =
        sessionStorage.getItem('razorpayPaymentId') || '';

    let ticketData = null;
    let bookingBlockData = null;
    let passengers = [];

    try {

        if (ticketDataRaw) {
            ticketData = JSON.parse(ticketDataRaw);
        }

        if (bookingBlockDataRaw) {
            bookingBlockData =
                JSON.parse(bookingBlockDataRaw);
        }

        if (passengersRaw) {
            passengers =
                JSON.parse(passengersRaw);
        }

    } catch (error) {

        console.error(
            'Failed to parse ticket data:',
            error
        );

    }


    /*
     * Display ticket information
     */
    if (ticketData) {

        $('#loadingMessage').hide();
        $('#ticketContent').show();

        populateTicket(ticketData);

    } else {

        /*
         * If payment page did not store the complete
         * ticket response, show the booking information
         * we still have.
         */
        $('#loadingMessage').hide();

        if (bookingRefNo) {

            $('#ticketContent').show();

            $('#ticketNumber').text('Available after ticket retrieval');
            $('#pnrNumber').text('Available after ticket retrieval');

            $('#bookingReference')
                .text(bookingRefNo);

            $('#contactEmail')
                .text(bookingEmail);

            $('#contactPhone')
                .text(bookingPhone);

            renderPassengers(passengers);

        } else {

            showError(
                'Ticket information was not found. Please check your booking again.'
            );

        }

    }


    /*
     * Print ticket
     */
    $('#printTicket').on('click', function () {

        window.print();

    });


    function populateTicket(ticket) {

        /*
         * eTravos response fields can vary slightly.
         * These fallbacks allow the page to display
         * the documented ticket information.
         */

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

        $('#ticketNumber')
            .text(ticketNumber || 'N/A');

        $('#pnrNumber')
            .text(pnr || 'N/A');

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
            'Confirmed'
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

        $('#bookingReference')
            .text(bookingRefNo || '-');

        $('#paymentId')
            .text(paymentId || '-');


        /*
         * eTravos ticket response may contain pax.
         */
        let ticketPassengers =
            ticket.pax ||
            ticket.passengers ||
            [];

        if (
            !Array.isArray(ticketPassengers) ||
            ticketPassengers.length === 0
        ) {
            ticketPassengers = passengers;
        }

        renderPassengers(ticketPassengers);

    }


    function renderPassengers(passengerList) {

        const tbody =
            $('#passengerTableBody');

        tbody.empty();

        if (
            !Array.isArray(passengerList) ||
            passengerList.length === 0
        ) {

            tbody.append(`
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Passenger information not available.
                    </td>
                </tr>
            `);

            return;
        }


        passengerList.forEach(function (passenger, index) {

            let seat = '';

            if (Array.isArray(passenger.seatIds)) {

                seat =
                    passenger.seatIds.join(', ');

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

        });

    }


    function showError(message) {

        $('#loadingMessage').hide();

        $('#errorMessage')
            .text(message)
            .show();

    }


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

@endpush


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