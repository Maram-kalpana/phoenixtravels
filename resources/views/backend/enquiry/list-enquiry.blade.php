@extends('layouts.app')
@section('title', 'Contact Enquiry Phoenix Tours & Travels')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .daterangepicker {
        z-index: 9999 !important;
    }
    .dataTables_filter {
        display: none;
    }
    .custom-filter-container {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }
    .filter-row {
        margin-bottom: 10px;
    }
    .date-range-input {
        background: white !important;
        cursor: pointer;
    }
    .reset-filters {
        margin-top: 24px;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Contact Enquiry</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">List Contact Enquiry</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <!-- Filter Container -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Filters</h5>
                    <div class="row g-3">
                        <!-- Search Filter -->
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" id="search-input" class="form-control" placeholder="Search by name, email, phone...">
                        </div>
                        
                        <!-- Date Range Filter -->
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <input type="text" id="date-range" class="form-control date-range-input" 
                                   placeholder="Select date range">
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select id="status-filter" class="form-select">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        
                        <!-- Phone Filter -->
                        <div class="col-md-2">
                            <label class="form-label">Phone</label>
                            <input type="text" id="phone-filter" class="form-control" placeholder="Filter by phone">
                        </div>
                        
                        <!-- Email Filter -->
                        <div class="col-md-2">
                            <label class="form-label">Email</label>
                            <input type="text" id="email-filter" class="form-control" placeholder="Filter by email">
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="col-md-12 mt-3">
                            <button id="apply-filters" class="btn btn-primary">
                                <i class="bx bx-filter-alt"></i> Apply Filters
                            </button>
                            <button id="reset-filters" class="btn btn-secondary">
                                <i class="bx bx-reset"></i> Reset Filters
                            </button>
                            <button id="export-csv" class="btn btn-success">
                                <i class="bx bx-download"></i> Export CSV
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Data Table -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="card-title mb-0">Contact Enquiries</h5>
                            <small class="text-muted" id="record-count">Loading records...</small>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bx bx-cog"></i> Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" id="select-all"><i class="bx bx-check-square"></i> Select All</a></li>
                                    <li><a class="dropdown-item" href="#" id="deselect-all"><i class="bx bx-square"></i> Deselect All</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" id="bulk-delete"><i class="bx bx-trash"></i> Delete Selected</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="contact-enquiry-table" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" id="select-all-checkbox">
                                    </th>
                                    <th width="50">S.NO</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table_data">
                                <!-- Data will be loaded via DataTable -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this enquiry?</p>
                <div id="delete-error-msg"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Bulk Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <span id="selected-count">0</span> selected enquiries?</p>
                <div id="bulk-delete-error-msg"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-bulk-delete">Delete Selected</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('backend/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('backend/dataTables/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(document).ready(function() {
    let dataTable;
    let deleteId = null;
    let selectedIds = [];

    // Initialize Date Range Picker
    $('#date-range').daterangepicker({
        opens: 'left',
        locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Clear'
        },
        autoUpdateInput: false,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    // Update date range input when dates are selected
    $('#date-range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
        applyFilters();
    });

    // Clear date range
    $('#date-range').on('cancel.daterangepicker', function() {
        $(this).val('');
        applyFilters();
    });

    // Initialize DataTable
    function initializeDataTable() {
        dataTable = $('#contact-enquiry-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('list.enquiry.data') }}",
                data: function(d) {
                    // Add custom filters to DataTable request
                    d.search = $('#search-input').val();
                    
                    // Date range filter
                    const dateRange = $('#date-range').val();
                    if (dateRange) {
                        const dates = dateRange.split(' to ');
                        if (dates.length === 2) {
                            d.start_date = dates[0].trim();
                            d.end_date = dates[1].trim();
                        }
                    }
                    
                    // Other filters
                    d.status = $('#status-filter').val();
                    d.phone = $('#phone-filter').val();
                    d.email = $('#email-filter').val();
                }
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="row-select" value="${row.id}">`;
                    }
                },
                { 
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false
                },
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { 
                    data: 'message', 
                    name: 'message',
                    render: function(data) {
                        return data.length > 50 ? data.substring(0, 50) + '...' : data;
                    }
                },
                { 
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data) {
                        return moment(data).format('YYYY-MM-DD HH:mm');
                    }
                },
                { 
                    data: 'status', 
                    name: 'status',
                    render: function(data, type, row) {
                        const checked = data == 1 ? 'checked' : '';
                        return `
                            <div class="form-check form-switch">
                                <input class="form-check-input status-toggle" type="checkbox" 
                                       data-id="${row.id}" ${checked}>
                            </div>
                        `;
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-danger delete-enquiry" 
                                        data-id="${data}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [[7, 'desc']], // Default order by created_at
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                processing: '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: 'No enquiries found',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'Showing 0 to 0 of 0 entries',
                infoFiltered: '(filtered from _MAX_ total entries)'
            },
            drawCallback: function(settings) {
                // Update record count
                const info = this.api().page.info();
                $('#record-count').text(`Total: ${info.recordsTotal} | Showing: ${info.recordsDisplay}`);
                
                // Reset select all checkbox
                $('#select-all-checkbox').prop('checked', false);
                selectedIds = [];
                updateBulkActions();
            }
        });
    }

    // Apply filters
    $('#apply-filters').click(function() {
        applyFilters();
    });

    // Apply filters on Enter key in search input
    $('#search-input, #phone-filter, #email-filter').keypress(function(e) {
        if (e.which == 13) {
            applyFilters();
        }
    });

    function applyFilters() {
        dataTable.ajax.reload();
    }

    // Reset filters
    $('#reset-filters').click(function() {
        $('#search-input').val('');
        $('#date-range').val('');
        $('#status-filter').val('');
        $('#phone-filter').val('');
        $('#email-filter').val('');
        applyFilters();
    });

    // Export CSV
    $('#export-csv').click(function() {
        const search = $('#search-input').val();
        const dateRange = $('#date-range').val();
        const status = $('#status-filter').val();
        const phone = $('#phone-filter').val();
        const email = $('#email-filter').val();
        
        let url = "{{ route('export.enquiries') }}?";
        const params = [];
        
        if (search) params.push(`search=${encodeURIComponent(search)}`);
        if (dateRange) params.push(`date_range=${encodeURIComponent(dateRange)}`);
        if (status) params.push(`status=${status}`);
        if (phone) params.push(`phone=${encodeURIComponent(phone)}`);
        if (email) params.push(`email=${encodeURIComponent(email)}`);
        
        window.location.href = url + params.join('&');
    });

    // Status toggle
    $(document).on('change', '.status-toggle', function() {
        const id = $(this).data('id');
        const status = $(this).is(':checked') ? 1 : 0;
        
        $.ajax({
            url: "{{ route('update.enquiry.status') }}",
            method: 'POST',
            data: {
                id: id,
                status: status,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Status updated successfully');
                } else {
                    showToast('error', response.message || 'Failed to update status');
                    dataTable.ajax.reload();
                }
            },
            error: function() {
                showToast('error', 'Failed to update status');
                dataTable.ajax.reload();
            }
        });
    });

    // View message
    $(document).on('click', '.view-message', function() {
        const message = $(this).data('message');
        Swal.fire({
            title: 'Message',
            text: message,
            icon: 'info',
            confirmButtonText: 'Close',
            width: '600px'
        });
    });

    // Delete enquiry
    $(document).on('click', '.delete-enquiry', function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirm-delete').click(function() {
        if (!deleteId) return;
        
        $.ajax({
            url: "{{ route('delete.enquiry') }}",
            method: 'DELETE',
            data: {
                id: deleteId,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', 'Enquiry deleted successfully');
                    dataTable.ajax.reload();
                    $('#deleteModal').modal('hide');
                } else {
                    $('#delete-error-msg').html(`
                        <div class="alert alert-danger">${response.message}</div>
                    `);
                }
            },
            error: function() {
                $('#delete-error-msg').html(`
                    <div class="alert alert-danger">Failed to delete enquiry</div>
                `);
            }
        });
    });

    // Row selection
    $(document).on('change', '.row-select', function() {
        const id = $(this).val();
        if ($(this).is(':checked')) {
            if (!selectedIds.includes(id)) {
                selectedIds.push(id);
            }
        } else {
            selectedIds = selectedIds.filter(item => item != id);
        }
        updateBulkActions();
    });

    // Select all
    $('#select-all-checkbox').change(function() {
        const isChecked = $(this).is(':checked');
        $('.row-select').prop('checked', isChecked);
        
        if (isChecked) {
            // Get all IDs from current page
            selectedIds = [];
            dataTable.rows({ page: 'current' }).every(function() {
                const data = this.data();
                selectedIds.push(data.id);
            });
        } else {
            selectedIds = [];
        }
        updateBulkActions();
    });

    // Select all action
    $('#select-all').click(function(e) {
        e.preventDefault();
        $('#select-all-checkbox').prop('checked', true).trigger('change');
    });

    // Deselect all action
    $('#deselect-all').click(function(e) {
        e.preventDefault();
        $('#select-all-checkbox').prop('checked', false).trigger('change');
    });

    // Bulk delete
    $('#bulk-delete').click(function(e) {
        e.preventDefault();
        if (selectedIds.length === 0) {
            showToast('warning', 'Please select at least one enquiry to delete');
            return;
        }
        
        $('#selected-count').text(selectedIds.length);
        $('#bulkDeleteModal').modal('show');
    });

    $('#confirm-bulk-delete').click(function() {
        $.ajax({
            url: "{{ route('bulk.delete.enquiries') }}",
            method: 'POST',
            data: {
                ids: selectedIds,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', `${selectedIds.length} enquiries deleted successfully`);
                    dataTable.ajax.reload();
                    $('#bulkDeleteModal').modal('hide');
                    selectedIds = [];
                    updateBulkActions();
                } else {
                    $('#bulk-delete-error-msg').html(`
                        <div class="alert alert-danger">${response.message}</div>
                    `);
                }
            },
            error: function() {
                $('#bulk-delete-error-msg').html(`
                    <div class="alert alert-danger">Failed to delete enquiries</div>
                `);
            }
        });
    });

    // Update bulk action buttons state
    function updateBulkActions() {
        const hasSelection = selectedIds.length > 0;
        $('#bulk-delete').toggleClass('disabled', !hasSelection);
    }

    // Show toast notification
    function showToast(type, message) {
        const toast = $(`
            <div class="toast align-items-center text-bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);
        
        $('.toast-container').remove();
        $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
        $('.toast-container').append(toast);
        
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Initialize DataTable
    initializeDataTable();
});
</script>
@endsection