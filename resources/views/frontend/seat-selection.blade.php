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

            <!-- SEAT LAYOUT -->
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-body">

                        <div class="seat-content">
                            <div>
                                <h2 class="fs-20 mb-1">Seat Layout</h2>
                                <p>Click on a seat to select/deselect</p>
                            </div>

                            <div class="seat-selected-count">
                                <span id="seatCounter">0/0</span>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="bus-legend">
                            <span><i class="legend-box"></i> Available</span>
                            <span><i class="legend-box selected"></i> Selected</span>
                            <span><i class="legend-box booked"></i> Sold</span>
                            <span><i class="legend-box female"></i> Female</span>
                        </div>

                        <!-- Loading -->
                        <div id="seatLoading" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading seat layout...</p>
                        </div>

                        <!-- Seats -->
                        <div class="seat-booking" id="seatBooking" style="display:none;">
                            <div class="seat-layout" id="seatLayout"></div>
                        </div>

                    </div>
                </div>

            </div>


            <!-- BOOKING SUMMARY -->
            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header">
                        <h5>Booking Summary</h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="mb-2">Selected Seats</label>

                            <div class="d-flex align-items-center gap-2 flex-wrap"
                                 id="selectedSeatsDisplay">
                                <span class="text-muted">No seats selected</span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <p class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                Price per seat:
                                <span class="fw-medium text-dark" id="pricePerSeat">₹0</span>
                            </p>

                            <p class="d-flex align-items-center justify-content-between gap-2 mb-0">
                                Number of seats:
                                <span class="fw-medium text-dark" id="seatCountDisplay">0</span>
                            </p>
                        </div>

                        <div class="d-flex align-items-center justify-content-between fw-semibold text-dark mb-4">
                            Total Amount:
                            <span class="fs-20 text-primary" id="totalAmount">₹0</span>
                        </div>

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

    var busId = null;
    var selectedSeats = [];
    var busData = null;
    var apiData = null;


    /* ---------------------------------------------------------------
     * Get selected bus from sessionStorage
     * ------------------------------------------------------------- */

    try {
        var storedBus = sessionStorage.getItem('selectedBus');
        if (storedBus) {
            busData = JSON.parse(storedBus);
        }
    } catch (error) {
        console.error('Error parsing selectedBus:', error);
    }

    if (busData && busData.id) {
        busId = busData.id;
    } else {
        busId = decodeURIComponent('{{ $busId }}');
    }


    /* ---------------------------------------------------------------
     * Load seat layout from Laravel
     * ------------------------------------------------------------- */

    function loadSeatLayout() {

        $('#seatLoading').show();
        $('#seatBooking').hide();

        if (!busData) {
            showError('Bus information was not found. Please go back and select a bus again.');
            return;
        }

        var traceId = busData.trace_id || '';

        var bpid = busData.boarding_point_id || '';
        if (busData.boarding_points && Array.isArray(busData.boarding_points) && busData.boarding_points.length > 0) {
            bpid = busData.boarding_points[0].id || bpid;
        }

        var dpid = busData.dropping_point_id || '';
        if (busData.dropping_points && Array.isArray(busData.dropping_points) && busData.dropping_points.length > 0) {
            dpid = busData.dropping_points[0].id || dpid;
        }

        if (!busId || !traceId || !bpid || !dpid) {
            showError('Unable to load seat layout because bus, boarding, dropping or trace information is missing.');
            return;
        }

        $.ajax({
            url: '{{ route("bus.seat-layout") }}',
            type: 'POST',
            data: {
                busId: busId,
                bpid: bpid,
                dpid: dpid,
                traceId: traceId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function (response) {

                $('#seatLoading').hide();

                if (response && response.success === true && response.data) {

                    apiData = response.data;

                    sessionStorage.setItem('seatLayoutData', JSON.stringify(apiData));

                    renderSeats(apiData);

                    $('#seatBooking').show();

                    fitDecks();

                } else {
                    showError(
                        response && response.message
                            ? response.message
                            : 'Seat layout is not available for this bus.'
                    );
                }
            },

            error: function (xhr) {

                $('#seatLoading').hide();

                var message = 'Failed to load seat layout. Please try again.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                showError(message);
            }
        });
    }


    /* ---------------------------------------------------------------
     * Layout helpers
     * ------------------------------------------------------------- */

    // Smallest gap between distinct values (used to turn coordinates into grid cells)
    function stepInfo(values) {
        var u = Array.from(new Set(values)).sort(function (a, b) { return a - b; });
        var step = Infinity;
        for (var i = 1; i < u.length; i++) {
            var d = u[i] - u[i - 1];
            if (d > 0 && d < step) step = d;
        }
        return { min: u[0], step: step === Infinity ? 1 : step };
    }

    function isSleeperSeat(seat) {
        var n = String(seat.name || seat.id || '').toUpperCase();
        var d = String(seat.desc || seat.type || '').toLowerCase();
        return n.indexOf('SL') === 0 ||
            d.indexOf('sleeper') !== -1 ||
            Number(seat.length) > 1 || Number(seat.width) > 1 ||
            Number(seat.l) > 1 || Number(seat.w) > 1;
    }

    var CHAIR_SVG =
        '<svg class="chair" viewBox="0 0 26 27" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">' +
        '<path d="M2 10.5v9a4 4 0 0 0 4 4h14a4 4 0 0 0 4-4v-9a3 3 0 0 0-6 0v4H8v-4a3 3 0 0 0-6 0z"/>' +
        '<rect x="6" y="1.5" width="14" height="13" rx="4"/></svg>';

    var PERSON_SVG =
        '<svg class="person" viewBox="0 0 16 22" width="16" height="22" fill="currentColor">' +
        '<circle cx="8" cy="4" r="3.2"/>' +
        '<path d="M2.5 20v-8a5.5 5.5 0 0 1 11 0v8a1 1 0 0 1-1 1h-9a1 1 0 0 1-1-1z"/></svg>';

    // x runs along the bus (rows), y runs across it (columns)
    function buildDeckHtml(seats) {

        var seaters  = seats.filter(function (s) { return !isSleeperSeat(s); });
        var sleepers = seats.filter(isSleeperSeat);

        var xs = seats.map(function (s) { return Number(s.x) || 0; });
        var rowMin = Math.min.apply(null, xs);

        // One row = one seater step. A deck with only sleepers: a sleeper is 2 rows tall.
        var rowStep = 1;
        if (seaters.length > 1) {
            rowStep = stepInfo(seaters.map(function (s) { return Number(s.x) || 0; })).step;
        } else if (sleepers.length > 1) {
            rowStep = stepInfo(sleepers.map(function (s) { return Number(s.x) || 0; })).step / 2;
        }

        var colInfo = stepInfo(seats.map(function (s) { return Number(s.y) || 0; }));
        var cols = 1;
        var occupied = {};

        var placed = seats.map(function (seat) {
            var row = Math.round(((Number(seat.x) || 0) - rowMin) / rowStep) + 1;
            var col = Math.round(((Number(seat.y) || 0) - colInfo.min) / colInfo.step) + 1;
            if (col > cols) cols = col;
            occupied[row + ',' + col] = true;
            return { seat: seat, row: row, col: col };
        });

        var cells = placed.map(function (p) {

            var seat = p.seat;
            var status = seat.status || 'AFA';
            var booked = isBookedStatus(status);
            var female = seat.desc && String(seat.desc).toLowerCase().indexOf('female') !== -1;
            var fare = (seat.fare && seat.fare.total !== undefined) ? Number(seat.fare.total) || 0 : 0;
            var name = seat.name || seat.id || '';
            var sleeper = isSleeperSeat(seat);

            var span = (sleeper && !occupied[(p.row + 1) + ',' + p.col]) ? 2 : 1;

            var inner;
            if (booked) {
                inner = PERSON_SVG;
            } else if (sleeper) {
                inner = '<i class="pillow"></i>';
            } else {
                inner = CHAIR_SVG;
            }

            return '<div class="bus-seat ' + (booked ? 'booked' : 'available') +
                (female ? ' female' : '') + (sleeper ? ' sleeper' : ' seater') + '"' +
                ' style="grid-column:' + p.col + ';grid-row:' + p.row + ' / span ' + span + ';"' +
                ' title="Seat ' + escapeHtml(name) + '"' +
                ' data-seat-id="' + escapeHtml(seat.id || '') + '"' +
                ' data-seat-key="' + escapeHtml(seat.key || '') + '"' +
                ' data-seat-name="' + escapeHtml(name) + '"' +
                ' data-seat-status="' + escapeHtml(status) + '"' +
                ' data-seat-fare="' + fare + '"' +
                (booked ? '' : ' onclick="toggleSeat(this)"') + '>' +
                '<div class="seat-box">' + inner + '</div>' +
                '<div class="seat-price">' + (booked ? 'Sold' : '₹' + Math.round(fare)) + '</div>' +
                '</div>';

        }).join('');

        return '<div class="bus-outline"><div class="bus-grid" style="grid-template-columns:repeat(' +
            cols + ', 48px);">' + cells + '</div></div>';
    }

    // Keep both decks side by side: shrink to fit the available width
    function fitDecks() {

        var wrap = $('#seatLayout .deck-wrap');
        if (!wrap.length) return;

        wrap.css({ zoom: 1, flexWrap: 'nowrap' });

        var avail = wrap.parent().width();
        var needed = wrap[0].scrollWidth;

        if (avail > 0 && needed > avail) {

            var z = avail / needed;

            if (z < 0.6) {
                // Very narrow screen (phone): stack the decks instead
                wrap.css('flex-wrap', 'wrap');
                var widest = 0;
                wrap.find('.deck-card').each(function () {
                    widest = Math.max(widest, $(this).outerWidth(true));
                });
                z = widest > avail ? avail / widest : 1;
            }

            wrap.css('zoom', z);
        }
    }

    $(window).on('resize', fitDecks);

    var STEERING_WHEEL =
        '<svg class="wheel" viewBox="0 0 48 48" width="34" height="34" fill="none" stroke="#b9bcc3" stroke-width="4">' +
        '<circle cx="24" cy="24" r="19"/><circle cx="24" cy="24" r="5" fill="#b9bcc3"/>' +
        '<path d="M24 29v14M19 22L6 20M29 22l13-2"/></svg>';


    /* ---------------------------------------------------------------
     * Render seats (decks side by side)
     * ------------------------------------------------------------- */

    function renderSeats(data) {

        var container = $('#seatLayout');
        container.empty();

        if (!data.seats || !Array.isArray(data.seats) || data.seats.length === 0) {
            container.html('<div class="text-center py-5"><p class="text-muted mb-2">No seats available for this bus.</p></div>');
            return;
        }

        var maxSeats = Number(data.maxSeatsPerTicket) || 10;
        $('#seatCounter').text('0/' + maxSeats);

        // Group seats by deck
        var decks = {};
        data.seats.forEach(function (seat) {
            var z = seat.z !== undefined ? seat.z : (seat.deck !== undefined ? seat.deck : 0);
            (decks[z] = decks[z] || []).push(seat);
        });

        var deckKeys = Object.keys(decks).sort(function (a, b) { return Number(a) - Number(b); });
        var multi = deckKeys.length > 1;
        var html = '<div class="deck-wrap">';

        deckKeys.forEach(function (k, i) {
            html += '<div class="deck-card">' +
                '<div class="deck-head"><span>' +
                (multi ? (i === 0 ? 'Lower deck' : 'Upper deck') : 'Seats') +
                '</span>' + (i === 0 ? STEERING_WHEEL : '') + '</div>' +
                buildDeckHtml(decks[k]) + '</div>';
        });

        html += '</div>';
        container.html(html);

        var firstAvailable = data.seats.find(function (s) { return !isBookedStatus(s.status); });
        if (firstAvailable && firstAvailable.fare) {
            $('#pricePerSeat').text('₹' + (Number(firstAvailable.fare.total) || 0).toFixed(2));
        }
    }


    /* ---------------------------------------------------------------
     * Seat status helpers
     * ------------------------------------------------------------- */

    function isBookedStatus(status) {
        return (
            status === 'BFA' ||
            status === 'BFM' ||
            status === 'BFF' ||
            status === 'BKD' ||
            status === 'UNA'
        );
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function getMaxSeats() {
        if (apiData && apiData.maxSeatsPerTicket) {
            return Number(apiData.maxSeatsPerTicket);
        }
        return 10;
    }


    /* ---------------------------------------------------------------
     * Select / deselect a seat
     * ------------------------------------------------------------- */

    window.toggleSeat = function (element) {

        var $seat = $(element);

        var seatId = String($seat.attr('data-seat-id') || '');
        var seatName = $seat.attr('data-seat-name') || seatId;
        var status = $seat.attr('data-seat-status') || 'AFA';
        var fare = Number($seat.attr('data-seat-fare')) || 0;

        if (isBookedStatus(status)) {
            return;
        }

        var maxSeats = getMaxSeats();

        if ($seat.hasClass('selected')) {

            $seat.removeClass('selected');

            selectedSeats = selectedSeats.filter(function (seat) {
                return seat.id !== seatId;
            });

        } else {

            if (selectedSeats.length >= maxSeats) {
                alert('You can select maximum ' + maxSeats + ' seats.');
                return;
            }

            $seat.addClass('selected');

            selectedSeats.push({
                id: seatId,
                name: seatName,
                key: $seat.attr('data-seat-key') || '',
                fare: fare,
                isFemale: $seat.hasClass('female')
            });
        }

        updateBookingSummary();
    };


    /* ---------------------------------------------------------------
     * Booking summary
     * ------------------------------------------------------------- */

    function updateBookingSummary() {

        var count = selectedSeats.length;

        var total = selectedSeats.reduce(function (sum, seat) {
            return sum + Number(seat.fare || 0);
        }, 0);

        $('#seatCounter').text(count + '/' + getMaxSeats());
        $('#seatCountDisplay').text(count);
        $('#totalAmount').text('₹' + total.toFixed(2));

        if (count > 0) {

            var html = '';

            selectedSeats.forEach(function (seat) {
                html += '<span class="badge bg-primary me-1">' + escapeHtml(seat.name) + '</span>';
            });

            $('#selectedSeatsDisplay').html(html);

            $('#pricePerSeat').text('₹' + (Number(selectedSeats[0].fare) || 0).toFixed(2));

        } else {

            $('#selectedSeatsDisplay').html('<span class="text-muted">No seats selected</span>');
            $('#pricePerSeat').text('₹0');
        }
    }


    /* ---------------------------------------------------------------
     * Error message
     * ------------------------------------------------------------- */

    function showError(message) {

        $('#seatLoading').hide();
        $('#seatBooking').show();

        $('#seatLayout').html(
            '<div class="text-center py-5 text-danger">' +
                '<i class="isax isax-danger-circle" style="font-size:48px;"></i>' +
                '<p class="mt-3">' + escapeHtml(message) + '</p>' +
                '<button type="button" class="btn btn-primary mt-2" onclick="location.reload()">' +
                    'Try Again' +
                '</button>' +
            '</div>'
        );
    }


    /* ---------------------------------------------------------------
     * Continue to checkout
     * ------------------------------------------------------------- */

    $('#continueCheckout').on('click', function (e) {

        e.preventDefault();

        if (selectedSeats.length === 0) {
            alert('Please select at least one seat.');
            return;
        }

        var total = selectedSeats.reduce(function (sum, seat) {
            return sum + Number(seat.fare || 0);
        }, 0);

        sessionStorage.setItem('selectedSeats', JSON.stringify(selectedSeats));
        sessionStorage.setItem('totalAmount', total.toFixed(2));
        sessionStorage.setItem('seatLayoutData', JSON.stringify(apiData));

        sessionStorage.setItem('busBookingData', JSON.stringify({

            busId: busId,

            traceId: busData.trace_id || '',

            bpid: busData.boarding_point_id ||
                (busData.boarding_points && busData.boarding_points.length
                    ? busData.boarding_points[0].id
                    : ''),

            dpid: busData.dropping_point_id ||
                (busData.dropping_points && busData.dropping_points.length
                    ? busData.dropping_points[0].id
                    : ''),

            tripKey: apiData && apiData.tripKey ? apiData.tripKey : '',

            api: apiData && apiData.api ? apiData.api : '',

            selectedSeats: selectedSeats,

            total: total
        }));

        window.location.href = '/bus/booking/confirm';
    });


    /* ---------------------------------------------------------------
     * Start
     * ------------------------------------------------------------- */

    loadSeatLayout();

});

</script>


<style>

/* ---------- Legend ---------- */

.bus-legend { display:flex; gap:20px; flex-wrap:wrap; margin:15px 0; font-size:14px; }
.bus-legend span { display:inline-flex; align-items:center; gap:8px; }
.legend-box { width:22px; height:22px; border:2px solid #1b8a4b; border-radius:6px; display:inline-block; }
.legend-box.selected { background:#1b8a4b; }
.legend-box.booked { background:#e5e7eb; border-color:#e5e7eb; }
.legend-box.female { border-color:#e83e8c; }

/* ---------- Decks ---------- */

.deck-wrap { display:flex; gap:24px; justify-content:center; align-items:flex-start; flex-wrap:nowrap; }
.deck-card { flex:none; background:#f9f9fb; border-radius:16px; padding:18px 16px; }
.deck-head { display:flex; justify-content:space-between; align-items:center; font-weight:700; font-size:16px; color:#1a1a1a; margin-bottom:14px; padding:0 6px; min-height:34px; }

.bus-outline { background:#fff; border:1px solid #d6d6dc; border-radius:22px; padding:34px 26px 18px; }
.bus-grid { display:grid; grid-auto-rows:66px; column-gap:12px; row-gap:0; justify-content:center; }

/* ---------- Seats ---------- */

.bus-seat { display:flex; flex-direction:column; align-items:center; height:calc(100% - 6px); user-select:none; color:#0b8a3e; }

.bus-seat .seat-price { font-size:14px; line-height:1; color:#7b7b82; margin-top:4px; flex:none; }

.bus-seat .seat-box { position:relative; display:flex; align-items:center; justify-content:center; }

/* Seater (chair) */
.bus-seat.seater .seat-box { width:40px; height:42px; flex:none; }
.bus-seat .chair { width:100%; height:100%; display:block; --f:#fff; }
.bus-seat .chair path,
.bus-seat .chair rect { fill:var(--f); }

/* Sleeper */
.bus-seat.sleeper .seat-box { width:48px; flex:1; border:2px solid #0b8a3e; border-radius:8px; background:#fff; }
.bus-seat .pillow { position:absolute; bottom:8px; left:50%; transform:translateX(-50%); width:30px; height:8px; border-radius:4px; background:#dcefe2; }

/* Available */
.bus-seat.available { cursor:pointer; }
.bus-seat.available:hover .seat-box { filter:brightness(.97); transform:translateY(-1px); }

/* Selected */
.bus-seat.selected .chair { color:#fff; --f:#0b8a3e; }
.bus-seat.selected.seater .seat-box { filter:none; }
.bus-seat.selected.sleeper .seat-box { background:#0b8a3e; }
.bus-seat.selected .pillow { background:rgba(255,255,255,.4); }

/* Female */
.bus-seat.female { color:#e83e8c; }
.bus-seat.female.sleeper .seat-box { border-color:#e83e8c; }
.bus-seat.female.selected .chair { color:#fff; --f:#e83e8c; }
.bus-seat.female.selected.sleeper .seat-box { background:#e83e8c; }

/* Sold */
.bus-seat.booked { cursor:not-allowed; color:#c9c9ee; }
.bus-seat.booked .seat-box { background:#ececf0; border:2px solid #dcdcf6; border-radius:8px; }
.bus-seat.booked.seater .seat-box { width:40px; height:40px; }
.bus-seat.booked .seat-price { color:#b9b9c1; }

@media (max-width: 575px) {
    .bus-legend { gap:12px; }
}

</style>

@endsection