@extends('layouts.app')
@section('title', 'Holidays List Dashboard')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .booking-details-modal .modal-dialog {
            max-width: 800px;
        }
        .details-table {
            width: 100%;
            margin-bottom: 0;
        }
        .details-table tr td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        .details-table tr td:first-child {
            font-weight: 600;
            background-color: #f8f9fa;
            width: 35%;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0d6efd;
        }
        
        /* DataTables Pagination Styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0;
            margin: 0;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: none;
            border: none;
        }
        .page-link {
            border-radius: 0.25rem;
            margin: 0 2px;
        }
        .pagination {
            margin: 0;
        }
        .dataTables_wrapper .dataTables_info {
            padding-top: 0.85em;
        }
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 0.25em;
        }
    </style>
@endsection

@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Bookings</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Holidays List</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="position-relative">
                            <h5 class="mb-0">Holidays Bookings</h5>
                        </div>
                        <div class="ms-auto">
                            <div class="btn-group">
                                <button type="button" class="btn btn-success radius-30" id="exportCSVBtn">
                                    <i class="fas fa-file-csv"></i> Export CSV
                                </button>
                                <button type="button" class="btn btn-info radius-30" id="refreshBtn">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="fixedDeparturesTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Booking No.</th>
                                    <th>Tour Name</th>
                                    <th>Travel Start Date</th>
                                    <th>Total End Date</th>
                                    <th>Total Amount</th>
                                    <th>Customer Name</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Booking Details Modal -->
<div class="modal fade booking-details-modal" id="viewBookingModal" tabindex="-1" aria-labelledby="viewBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewBookingModalLabel">
                    <i class="fas fa-info-circle"></i> Booking Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="bookingDetailsLoader" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading booking details...</p>
                </div>
                <div id="bookingDetailsContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printDetailsBtn">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <input type="hidden" id="booking_id" name="booking_id">
                    <div id="deleteErrorMsg"></div>
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 48px; margin-bottom: 15px;"></i>
                        <p>Are you sure you want to delete this booking?</p>
                        <p class="text-danger"><small>This action cannot be undone!</small></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('backend/dataTables/datatables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with enhanced pagination
    var table = $('#fixedDeparturesTable').DataTable({
        pageLength: 10,
        responsive: true,
        processing: true,
        serverSide: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                previous: '<i class="fas fa-angle-left"></i> Previous',
                next: 'Next <i class="fas fa-angle-right"></i>'
            },
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            lengthMenu: "Show _MENU_ entries",
            search: "Search:",
            zeroRecords: "No matching records found"
        },
        ajax: {
            url: "{{ route('get.holidays-booking.data') }}",
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.log('AJAX Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to load data. Please refresh the page.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        columns: [
            { data: 'id', name: 'id', className: 'text-center' },
            { data: 'booking_number', name: 'booking_number' },
            { data: 'tour_name', name: 'tour_name' },
            { data: 'travel_start_date', name: 'travel_start_date', className: 'text-center' },
            { data: 'travel_end_date', name: 'travel_end_date', className: 'text-center' },
            { data: 'total_amount', name: 'total_amount', className: 'text-center' },
            { data: 'name', name: 'name' },
            { data: 'phone', name: 'phone' },
            { data: 'status', name: 'status', orderable: false, className: 'text-center' },
            { data: 'payment_status', name: 'payment_status', orderable: false, className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        drawCallback: function() {
            // Add custom class to pagination buttons
            $('.paginate_button').addClass('page-item');
            $('.paginate_button a').addClass('page-link');
            $('.paginate_button.disabled a').addClass('disabled');
            $('.paginate_button.active').addClass('active-custom');
        }
    });
    
    // Refresh button functionality
    $('#refreshBtn').click(function() {
        table.ajax.reload(null, false);
        Swal.fire({
            title: 'Refreshed!',
            text: 'Data has been refreshed.',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    });
    
    // View booking details with AJAX popup
    $(document).on('click', '.view-booking', function() {
        var id = $(this).data('id');
        
        // Show modal and loader
        $('#viewBookingModal').modal('show');
        $('#bookingDetailsLoader').show();
        $('#bookingDetailsContent').html('');
        
        // Fetch booking details
        $.ajax({
            url: "{{ route('holidays.booking.details', ':id') }}".replace(':id', id),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#bookingDetailsLoader').hide();
                
                if (response.success) {
                    //alert(response.data);
                    displayBookingDetails(response.data);
                } else {
                    $('#bookingDetailsContent').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> 
                            ${response.message}
                        </div>
                    `);
                }
            },
            error: function() {
                $('#bookingDetailsLoader').hide();
                $('#bookingDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Failed to load booking details. Please try again.
                    </div>
                `);
            }
        });
    });
    
    // Print booking details
    $('#printDetailsBtn').click(function() {
        var printContent = $('#bookingDetailsContent').html();
        var printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Booking Details</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { padding: 20px; }
                        .section-title { 
                            font-size: 18px; 
                            font-weight: 600; 
                            margin-bottom: 15px; 
                            padding-bottom: 10px; 
                            border-bottom: 2px solid #0d6efd; 
                        }
                        .details-table tr td:first-child {
                            background-color: #f8f9fa;
                            font-weight: 600;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2 class="text-center mb-4">Booking Details</h2>
                        ${printContent}
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    });
    
    function displayBookingDetails(booking) {
    var statusBadge = getStatusBadgeText(booking.status);
    var paymentBadge = getPaymentBadgeText(booking.payment_status);
    
    // Parse passenger details - FIXED: Ensure these are always arrays
    var adultsList = [];
    var childrenList = [];
    
    // Helper function to convert any data to array
    function ensureArray(data) {
        if (!data) return [];
        if (Array.isArray(data)) return data;
        if (typeof data === 'string') {
            try {
                var parsed = JSON.parse(data);
                return Array.isArray(parsed) ? parsed : [];
            } catch(e) {
                console.error('Error parsing JSON:', e);
                return [];
            }
        }
        if (typeof data === 'object') {
            // If it's an object with numeric keys, convert to array
            return Object.values(data);
        }
        return [];
    }
    
    // Process adult passengers - ensure they are arrays
    adultsList = ensureArray(booking.adults);
    childrenList = ensureArray(booking.children);
    
    // Debug: Log to console to check what we got
    console.log('Adults List:', adultsList);
    console.log('Children List:', childrenList);
    
    var html = `
        <div class="row">
            <div class="col-md-12">
                <h6 class="section-title">
                    <i class="fas fa-user"></i> Customer Information
                </h6>
                <table class="table table-bordered details-table">
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600; width: 35%;">Booking Number:</td>
                        <td><strong>${escapeHtml(booking.booking_id)}</strong></td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Customer Name:</td>
                        <td>${escapeHtml(booking.name) || '-'}</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Email:</td>
                        <td>${booking.mail || '-'}</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Phone:</td>
                        <td>${booking.phone || '-'}</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Address:</td>
                        <td>${booking.address || '-'}</td>
                    </tr>
                </table>
                
                <h6 class="section-title mt-3">
                    <i class="fas fa-bus"></i> Journey Details
                </h6>
                <table class="table table-bordered details-table">
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Tour Name:</td>
                        <td>${booking.tour_name}</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Travel Start Date:</td>
                        <td>${booking.from_date}</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Travel End Date:</td>
                        <td>${booking.to_date}</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Adults Count:</td>
                        <td>${booking.adults_count || 0}</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Children Count:</td>
                        <td>${booking.children_count || 0}</td>
                    </tr>
                </table>
                
                <!-- Adult Passengers Details -->
                <h6 class="section-title mt-3">
                    <i class="fas fa-user"></i> Adult Passengers Details
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered details-table">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                            </tr>
                        </thead>
                        <tbody>`;
    
    if (adultsList.length > 0) {
        for (var i = 0; i < adultsList.length; i++) {
            var adult = adultsList[i];
            html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${escapeHtml(adult.first || adult.first_name || '-')}</td>
                            <td>${escapeHtml(adult.last || adult.last_name || '-')}</td>
                            <td>${adult.age || '-'}</td>
                            <td>${adult.gender || '-'}</td>
                        </tr>`;
        }
    } else {
        html += `
                        <tr>
                            <td colspan="5" class="text-center">No adult passengers found</td>
                        </tr>`;
    }
    
    html += `
                        </tbody>
                    </table>
                </div>
                
                <!-- Children Passengers Details -->
                <h6 class="section-title mt-3">
                    <i class="fas fa-child"></i> Children Passengers Details
                </h6>
                <div class="table-responsive">
                    <table class="table table-bordered details-table">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                            </tr>
                        </thead>
                        <tbody>`;
    
    if (childrenList.length > 0) {
        for (var j = 0; j < childrenList.length; j++) {
            var child = childrenList[j];
            html += `
                        <tr>
                            <td>${j + 1}</td>
                            <td>${escapeHtml(child.first || child.first_name || '-')}</td>
                            <td>${escapeHtml(child.last || child.last_name || '-')}</td>
                            <td>${child.age || '-'}</td>
                            <td>${child.gender || '-'}</td>
                        </tr>`;
        }
    } else {
        html += `
                        <tr>
                            <td colspan="5" class="text-center">No children passengers found</td>
                        </tr>`;
    }
    
    html += `
                        </tbody>
                    </table>
                </div>
                
                <h6 class="section-title mt-3">
                    <i class="fas fa-money-bill"></i> Payment Information
                </h6>
                <table class="table table-bordered details-table">
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Total Amount:</td>
                        <td><strong>₹${parseFloat(booking.total_amount).toFixed(2)}</strong></td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Payment Status:</td>
                        <td>${paymentBadge}</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Payment Method:</td>
                        <td>Razorpay</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Payment Date:</td>
                        <td>${booking.created_at ? formatDateTime(booking.created_at) : '-'}</td>
                    </tr>
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Payment ID:</td>
                        <td>${booking.razorpay_payment_id || '-'}</td>
                    </tr>
                 licensierad`;
    
    if (booking.failure_reason) {
        html += `
                <h6 class="section-title mt-3">
                    <i class="fas fa-info-circle"></i> Cancellation Information
                </h6>
                <table class="table table-bordered details-table">
                    <tr>
                        <td style="background: #f8f9fa; font-weight: 600;">Cancellation Reason:</td>
                        <td class="text-danger">${escapeHtml(booking.failure_reason)}</td>
                    </tr>
                </table>`;
    }
    
    html += `
            </div>
        </div>`;
    
    $('#bookingDetailsContent').html(html);
}
    
    function formatSeatNumbersDisplay(seatNumbers) {
        if (!seatNumbers) return '-';
        var seats = seatNumbers.split(',');
        var html = '';
        seats.forEach(function(seat) {
            html += '<span class="badge bg-secondary me-1">' + seat.trim() + '</span>';
        });
        return html;
    }
    
    function getStatusBadgeText(status) {
        switch(status) {
            case 'confirmed': return '<span class="badge bg-success">Confirmed</span>';
            case 'pending': return '<span class="badge bg-warning text-dark">Pending</span>';
            case 'cancelled': return '<span class="badge bg-danger">Cancelled</span>';
            case 'failed': return '<span class="badge bg-dark">Failed</span>';
            default: return '<span class="badge bg-secondary">' + (status || 'N/A') + '</span>';
        }
    }
    
    function getPaymentBadgeText(paymentStatus) {
        switch(paymentStatus) {
            case 'paid': return '<span class="badge bg-success">Paid</span>';
            case 'pending': return '<span class="badge bg-warning text-dark">Pending</span>';
            case 'failed': return '<span class="badge bg-danger">Failed</span>';
            case 'refunded': return '<span class="badge bg-info">Refunded</span>';
            default: return '<span class="badge bg-secondary">' + (paymentStatus || 'N/A') + '</span>';
        }
    }
    
    // Delete booking
    $(document).on('click', '.delete-booking', function() {
        var id = $(this).data('id');
        $('#booking_id').val(id);
        $('#deleteModal').modal('show');
    });
    
    // Handle delete form submission
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#booking_id').val();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ url('delete-booking') }}/" + id,
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    Swal.fire({
                        title: 'Deleted!',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#fixedDeparturesTable').DataTable().ajax.reload(null, false);
                } else {
                    $('#deleteErrorMsg').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#deleteErrorMsg').html('<div class="alert alert-danger">Error deleting booking. Please try again.</div>');
            }
        });
    });
    
    // Export to CSV
    $('#exportCSVBtn').click(function() {
        Swal.fire({
            title: 'Export Data',
            text: 'Are you sure you want to export all bookings to CSV?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Export!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('export.holidays.bookings') }}";
                Swal.fire({
                    title: 'Export Started!',
                    text: 'Your file will download shortly.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });
    
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-IN', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
    
    function formatDateTime(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleString('en-IN', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    function escapeHtml(text) {
        if (!text) return text;
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});

// Custom styling for pagination
document.addEventListener('DOMContentLoaded', function() {
    // Add custom CSS for pagination
    var style = document.createElement('style');
    style.textContent = `
        .dataTables_paginate .paginate_button {
            margin: 0 2px;
        }
        .dataTables_paginate .paginate_button .page-link {
            border-radius: 0.25rem;
            color: #0d6efd;
        }
        .dataTables_paginate .paginate_button.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        .dataTables_paginate .paginate_button.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
        }
        .dataTables_paginate .paginate_button:not(.disabled) .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection
