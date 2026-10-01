@extends('layouts.app')
@section('title', 'Corporate Tours Enquiries')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .details-modal .modal-dialog {
            max-width: 900px;
        }
        .details-table {
            width: 100%;
            margin-bottom: 0;
        }
        .details-table tr td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .details-table tr td:first-child {
            font-weight: 600;
            background-color: #f8f9fa;
            width: 35%;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin: 15px 0 10px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #0d6efd;
            color: #0d6efd;
        }
        .section-title i {
            margin-right: 8px;
        }
        .status-badge {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Group Tours</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Corporate Tours Enquiries</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="position-relative">
                            <h5 class="mb-0">Corporate Tour Enquiries</h5>
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
                        <table id="corporateToursTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Company Name</th>
                                    <th>Contact Person</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Destination</th>
                                    <th>Preferred Date</th>
                                    <th>No. of People</th>
                                    <th>Status</th>
                                    <th>Created At</th>
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

<!-- View Details Modal -->
<div class="modal fade details-modal" id="viewEnquiryModal" tabindex="-1" aria-labelledby="viewEnquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewEnquiryModalLabel">
                    <i class="fas fa-info-circle"></i> Corporate Tour Enquiry Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="enquiryDetailsLoader" class="text-center py-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading enquiry details...</p>
                </div>
                <div id="enquiryDetailsContent"></div>
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

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="enquiry_id" name="enquiry_id">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="1">Pending</option>
                            <option value="2">Processing</option>
                            <option value="3">Completed</option>
                            <option value="4">Cancelled</option>
                        </select>
                    </div>
                    <div id="statusErrorMsg"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Enquiry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <input type="hidden" id="delete_enquiry_id" name="enquiry_id">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 48px; margin-bottom: 15px;"></i>
                        <p>Are you sure you want to delete this enquiry?</p>
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

<script>
$(document).ready(function() {
    var table = $('#corporateToursTable').DataTable({
        pageLength: 10,
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('get.corporate.tours.data') }}",
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.log('AJAX Error:', error);
                Swal.fire('Error!', 'Failed to load data. Please refresh the page.', 'error');
            }
        },
        columns: [
            { data: 'id', className: 'text-center' },
            { data: 'company_name' },
            { data: 'full_name' },
            { data: 'email' },
            { data: 'phone' },
            { data: 'destination' },
            { data: 'preferred_date', className: 'text-center' },
            { data: 'number_of_people', className: 'text-center' },
            { data: 'status', className: 'text-center' },
            { data: 'created_at', className: 'text-center' },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[0, 'desc']]
    });
    
    $('#refreshBtn').click(function() {
        table.ajax.reload(null, false);
        Swal.fire('Refreshed!', 'Data has been refreshed.', 'success', 1500);
    });
    
    // View enquiry details
    $(document).on('click', '.view-enquiry', function() {
        var id = $(this).data('id');
        $('#viewEnquiryModal').modal('show');
        $('#enquiryDetailsLoader').show();
        $('#enquiryDetailsContent').html('');
        
        $.ajax({
            url: "{{ route('corporate.tours.details', ':id') }}".replace(':id', id),
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#enquiryDetailsLoader').hide();
                if (response.success) {
                    displayEnquiryDetails(response.data);
                } else {
                    $('#enquiryDetailsContent').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#enquiryDetailsLoader').hide();
                $('#enquiryDetailsContent').html('<div class="alert alert-danger">Failed to load enquiry details.</div>');
            }
        });
    });
    
    // Edit status
    $(document).on('click', '.edit-status', function() {
        var id = $(this).data('id');
        $('#enquiry_id').val(id);
        $('#statusModal').modal('show');
    });
    
    // Delete enquiry
    $(document).on('click', '.delete-enquiry', function() {
        var id = $(this).data('id');
        $('#delete_enquiry_id').val(id);
        $('#deleteModal').modal('show');
    });
    
    // Update status form submit
    $('#statusForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#enquiry_id').val();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ url('corporate-tours/status') }}/" + id,
            type: 'POST',
            data: formData,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                if (response.success) {
                    $('#statusModal').modal('hide');
                    Swal.fire('Updated!', response.message, 'success');
                    table.ajax.reload(null, false);
                } else {
                    $('#statusErrorMsg').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#statusErrorMsg').html('<div class="alert alert-danger">Error updating status.</div>');
            }
        });
    });
    
    // Delete form submit
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#delete_enquiry_id').val();
        $.ajax({
            url: "{{ url('users-booking/corporate-tours') }}/" + id,
            type: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    Swal.fire('Deleted!', response.message, 'success');
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Error deleting enquiry.', 'error');
            }
        });
    });
    
    // Export CSV
    $('#exportCSVBtn').click(function() {
        Swal.fire({
            title: 'Export Data',
            text: 'Export all enquiries to CSV?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Yes, Export!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('corporate.tours.export') }}";
                Swal.fire('Export Started!', 'Your file will download shortly.', 'success', 1500);
            }
        });
    });
    
    // Print details
    $('#printDetailsBtn').click(function() {
        var printContent = $('#enquiryDetailsContent').html();
        var printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Corporate Tour Enquiry Details</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { padding: 20px; }
                        .section-title { color: #0d6efd; border-bottom: 2px solid #0d6efd; margin: 15px 0 10px 0; }
                        .details-table tr td:first-child { background-color: #f8f9fa; font-weight: 600; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2 class="text-center mb-4">Corporate Tour Enquiry Details</h2>
                        ${printContent}
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    });
});

function displayEnquiryDetails(enquiry) {
    var html = `
        <div class="row">
            <div class="col-md-12">
                <h6 class="section-title"><i class="fas fa-building"></i> Company Information</h6>
                <table class="table table-bordered details-table">
                    <tr><td>Company Name</td><td><strong>${escapeHtml(enquiry.company_name)}</strong></td></tr>
                    <tr><td>Company Address</td><td>${escapeHtml(enquiry.company_address)}</td></tr>
                    <tr><td>Company Phone 1</td><td>${escapeHtml(enquiry.company_phone_1)}</td></tr>
                    ${enquiry.company_phone_2 ? `<tr><td>Company Phone 2</td><td>${escapeHtml(enquiry.company_phone_2)}</td></tr>` : ''}
                </table>
                
                <h6 class="section-title"><i class="fas fa-user"></i> Contact Person Information</h6>
                <table class="table table-bordered details-table">
                    <tr><td>Full Name</td><td>${escapeHtml(enquiry.full_name)}</td></tr>
                    <tr><td>Email</td><td>${escapeHtml(enquiry.email)}</td></tr>
                    <tr><td>Phone</td><td>${escapeHtml(enquiry.phone)}</td></tr>
                    <tr><td>Designation</td><td>${escapeHtml(enquiry.designation)}</td></tr>
                    <tr><td>Authorized Contact 1</td><td>${escapeHtml(enquiry.authorized_contact_1)}</td></tr>
                    ${enquiry.authorized_contact_2 ? `<tr><td>Authorized Contact 2</td><td>${escapeHtml(enquiry.authorized_contact_2)}</td></tr>` : ''}
                </table>
                
                <h6 class="section-title"><i class="fas fa-route"></i> Tour Details</h6>
                <table class="table table-bordered details-table">
                    <tr><td>Starting Place</td><td>${escapeHtml(enquiry.starting_place)}</td></tr>
                    <tr><td>Destination</td><td>${escapeHtml(enquiry.destination)}</td></tr>
                    <tr><td>Preferred Date</td><td>${enquiry.preferred_date}</td></tr>
                    <tr><td>Number of People</td><td>${enquiry.number_of_people}</td></tr>
                    ${enquiry.number_of_buses ? `<tr><td>Number of Buses</td><td>${enquiry.number_of_buses}</td></tr>` : ''}
                    ${enquiry.bus_type ? `<tr><td>Bus Type</td><td>${enquiry.bus_type}</td></tr>` : ''}
                    ${enquiry.train_class ? `<tr><td>Train Class</td><td>${enquiry.train_class}</td></tr>` : ''}
                    ${enquiry.flight_class ? `<tr><td>Flight Class</td><td>${enquiry.flight_class}</td></tr>` : ''}
                    <tr><td>Pickup Point</td><td>${enquiry.pickup_point || '-'}</td></tr>
                    <tr><td>Dropping Point</td><td>${enquiry.dropping_point || '-'}</td></tr>
                    <tr><td>Sightseeing</td><td>${enquiry.sightseeing || '-'}</td></tr>
                </table>
                
                <h6 class="section-title"><i class="fas fa-hotel"></i> Accommodation Details</h6>
                <table class="table table-bordered details-table">
                    <tr><td>Adults</td><td>${enquiry.adults}</td></tr>
                    <tr><td>Children</td><td>${enquiry.children}</td></tr>
                    <tr><td>Infants</td><td>${enquiry.infants}</td></tr>
                    <tr><td>Hotel Category</td><td>${enquiry.hotel_category || '-'}</td></tr>
                    <tr><td>Hotel Location</td><td>${enquiry.hotel_location || '-'}</td></tr>
                    <tr><td>Number of Rooms</td><td>${enquiry.number_of_rooms || '-'}</td></tr>
                    <tr><td>Sharing Type</td><td>${enquiry.sharing_type || '-'}</td></tr>
                </table>
                
                <h6 class="section-title"><i class="fas fa-chalkboard"></i> Conference & Event Details</h6>
                <table class="table table-bordered details-table">
                    <tr><td>Conference Hall</td><td>${enquiry.conference_hall === 'yes' ? 'Yes' : 'No'}</td></tr>
                    ${enquiry.seating_capacity ? `<tr><td>Seating Capacity</td><td>${enquiry.seating_capacity}</td></tr>` : ''}
                    ${enquiry.stage_type ? `<tr><td>Stage Type</td><td>${enquiry.stage_type}</td></tr>` : ''}
                    ${enquiry.seating_arrangement ? `<tr><td>Seating Arrangement</td><td>${enquiry.seating_arrangement}</td></tr>` : ''}
                </table>
                
                <h6 class="section-title"><i class="fas fa-utensils"></i> Food & Beverage</h6>
                <table class="table table-bordered details-table">
                    ${enquiry.breakfast_menu ? `<tr><td>Breakfast Menu</td><td>${formatMenuItems(enquiry.breakfast_menu)}</td></tr>` : ''}
                    ${enquiry.lunch_menu ? `<tr><td>Lunch Menu</td><td>${formatMenuItems(enquiry.lunch_menu)}</td></tr>` : ''}
                    ${enquiry.dinner_menu ? `<tr><td>Dinner Menu</td><td>${formatMenuItems(enquiry.dinner_menu)}</td></tr>` : ''}
                    ${enquiry.morning_snacks_menu ? `<tr><td>Morning Snacks</td><td>${formatMenuItems(enquiry.morning_snacks_menu)}</td></tr>` : ''}
                    ${enquiry.evening_snacks_menu ? `<tr><td>Evening Snacks</td><td>${formatMenuItems(enquiry.evening_snacks_menu)}</td></tr>` : ''}
                    <tr><td>Night Party</td><td>${enquiry.night_party === 'yes' ? 'Yes' : 'No'}</td></tr>
                    ${enquiry.drinks_type ? `<tr><td>Drinks Type</td><td>${enquiry.drinks_type}</td></tr>` : ''}
                </table>
                
                <h6 class="section-title"><i class="fas fa-credit-card"></i> Payment Information</h6>
                <table class="table table-bordered details-table">
                    <tr><td>Payment Method</td><td>${enquiry.payment_method || '-'}</td></tr>
                    ${enquiry.payment_method === 'bank_transfer' ? `
                        <tr><td>Bank Name</td><td>${enquiry.bank_name || '-'}</td></tr>
                        <tr><td>Bank Branch</td><td>${enquiry.bank_branch || '-'}</td></tr>
                        <tr><td>Account Number</td><td>${enquiry.account_number || '-'}</td></tr>
                        <tr><td>IFSC Code</td><td>${enquiry.ifsc_code || '-'}</td></tr>
                    ` : ''}
                    <tr><td>Payment Terms</td><td>${enquiry.payment_terms || '-'}</td></tr>
                    <tr><td>Cancellation Policy</td><td>${enquiry.cancellation_policy || '-'}</td></tr>
                    <tr><td>Refund Policy</td><td>${enquiry.refund_policy || '-'}</td></tr>
                </table>
                
                ${enquiry.additional_info ? `
                <h6 class="section-title"><i class="fas fa-info-circle"></i> Additional Information</h6>
                <table class="table table-bordered details-table">
                    <tr><td>${escapeHtml(enquiry.additional_info)}</td></tr>
                </table>
                ` : ''}
            </div>
        </div>
    `;
    
    $('#enquiryDetailsContent').html(html);
}

function formatMenuItems(menuData) {
    if (!menuData) return '-';
    try {
        var items = typeof menuData === 'string' ? JSON.parse(menuData) : menuData;
        if (Array.isArray(items) && items.length > 0) {
            return items.join(', ');
        }
        return menuData;
    } catch(e) {
        return menuData;
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>
@endsection