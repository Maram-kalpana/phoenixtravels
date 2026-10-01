@extends('layouts.web')
@section('title','Payment History | Phoenix Tours & Travels')
@section('css')
<style>
    /* Payment History Page Styles */
#loadingOverlay {
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(2px);
}

#loadingOverlay .spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.25em;
}

/* Pagination Styles */
.pagination-nav {
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.pagination-nav:hover {
    opacity: 0.7;
}

.page-link-1 {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    background: #f8f9fa;
    color: #6c757d;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
}

.page-link-1:hover {
    background: #e9ecef;
    color: #495057;
}

.page-link-1.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

/* Status Badge Styles */
.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
}

.badge-info {
    background-color: #17a2b8;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

/* Table Styles */
.table tbody tr {
    transition: background-color 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Input Icons */
.input-icon-start, .input-icon-end {
    position: relative;
}

.icon-addon, .input-icon-addon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 10;
}

.input-icon-start .icon-addon {
    left: 12px;
}

.input-icon-start input {
    padding-left: 35px;
}

.input-icon-end .input-icon-addon {
    right: 12px;
}

.input-icon-end input {
    padding-right: 35px;
}

/* Responsive */
@media (max-width: 768px) {
    .list-header {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .list-header > div {
        margin-top: 10px;
        width: 100%;
    }
    
    .input-icon-start, .input-icon-end {
        width: 100%;
    }
    
    .dropdown {
        width: 100%;
        margin: 5px 0 !important;
    }
    
    .dropdown-toggle {
        width: 100%;
        justify-content: space-between;
    }
}
</style>
@endsection
@section('content')
<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Payment History</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item active" aria-current="page">Track all your travel payments</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-xl-3 col-lg-4 theiaStickySidebar">
                @include('user.siderbar')
            </div>
            <!-- /Sidebar -->

            <!-- Payments -->
            <div class="col-xl-9 col-lg-8">
                <div class="card hotel-list">
                    <div class="card-body p-0">
                        <div class="list-header d-flex align-items-center justify-content-between flex-wrap">
                            <h6>Payments</h6>
                            <div class="d-flex align-items-center flex-wrap">
                                <!-- Search Input -->
                                <div class="input-icon-start me-2 position-relative">
                                    <span class="icon-addon">
                                        <i class="isax isax-search-normal-1 fs-14"></i>
                                    </span>
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search">
                                </div>
                                
                                <!-- Status Filter Dropdown -->
                                <div class="dropdown me-3">
                                    <a href="#"
                                        class="dropdown-toggle text-gray-6 btn rounded border d-inline-flex align-items-center"
                                        data-bs-toggle="dropdown" aria-expanded="false" id="statusDropdownBtn">
                                        Status: <span id="selectedStatusText">All</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end p-3" id="statusFilterMenu">
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item rounded-1 status-filter-item" data-status="all">All</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item rounded-1 status-filter-item" data-status="completed">Completed</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item rounded-1 status-filter-item" data-status="pending">Pending</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item rounded-1 status-filter-item" data-status="cancelled">Cancelled</a>
                                        </li>
                                    </ul>
                                </div>
                                
                                <!-- Date Range Picker -->
                                <div class="input-icon-end position-relative">
                                    <span class="input-icon-addon">
                                        <i class="isax isax-calendar-edit"></i>
                                    </span>
                                    <input type="text" class="form-control date-range bookingrange" id="dateRangePicker"
                                        placeholder="dd/mm/yyyy - dd/mm/yyyy" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="custom-datatable-filter table-responsive">
                            <table class="table datatable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Payment</th>
                                        <th>Service</th>
                                        <th>Payment Type</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="paymentsTableBody">
                                    <!-- Table content will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination Section -->
                <div class="table-paginate d-flex justify-content-between align-items-center flex-wrap row-gap-3 mt-3">
                    <div class="value d-flex align-items-center">
                        <span>Show</span>
                        <select class="form-select form-select-sm ms-2" id="perPageSelect" style="width: 80px;">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                        <span class="ms-2" id="showingResults">of 0 Results</span>
                    </div>
                    
                    <div id="paginationContainer">
                        <!-- Pagination will be loaded here dynamically -->
                    </div>
                </div>
            </div>
            <!-- /Payments -->
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Include required libraries -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(document).ready(function() {
    // State variables
    var currentPage = 1;
    var currentSearch = '';
    var currentStatus = 'all';
    var currentDateRange = '';
    var currentPerPage = 10;
    var searchTimeout = null;
    
    // Initialize Date Range Picker
    if (typeof $.fn.daterangepicker === 'function') {
        $('#dateRangePicker').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY'
            }
        });
        
        $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            currentDateRange = $(this).val();
            currentPage = 1;
            loadPayments();
        });
        
        $('#dateRangePicker').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            currentDateRange = '';
            currentPage = 1;
            loadPayments();
        });
    }
    
    // Load payments via AJAX
    function loadPayments() {
        showLoading();
        
        $.ajax({
            url: window.location.href,
            method: 'GET',
            data: {
                page: currentPage,
                per_page: currentPerPage,
                search: currentSearch,
                status: currentStatus,
                date_range: currentDateRange
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    renderTableBody(response.data);
                    renderPagination(response.pagination);
                    updateShowingResults(response.pagination);
                } else {
                    showError(response.message || 'Failed to load payments');
                }
                hideLoading();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                showError('Error loading payments. Please try again.');
                hideLoading();
            }
        });
    }
    
    // Render table body
    function renderTableBody(payments) {
        var tbody = $('#paymentsTableBody');
        
        if (payments.length === 0) {
            tbody.html('<tr><td colspan="7" class="text-center py-5">No payments found</td></tr>');
            return;
        }
        
        var html = '';
        for (var i = 0; i < payments.length; i++) {
            var payment = payments[i];
            var statusClass = getStatusClass(payment.payment_status);
            var statusIcon = getStatusIcon(payment.payment_status);
            var formattedDate = formatDate(payment.created_at);
            var formattedAmount = formatAmount(payment.amount);
            var invoiceLink = payment.invoice_id ? '/invoices/' + payment.invoice_id : '#';
            
            html += '<tr>';
            html += '<td><a href="' + invoiceLink + '" class="link-primary fw-medium">#' + (payment.payment_id || 'PA-' + String(payment.id).padStart(4, '0')) + '</a></td>';
            html += '<td class="text-gray-9 fw-medium">' + escapeHtml(payment.service_name || 'N/A') + '</td>';
            html += '<td>' + escapeHtml(payment.service_type || 'N/A') + '</td>';
            html += '<td>' + escapeHtml(payment.payment_method || 'N/A') + '</td>';
            html += '<td>' + formattedDate + '</td>';
            html += '<td>' + formattedAmount + '</td>';
            html += '<td><span class="badge ' + statusClass + ' rounded-pill d-inline-flex align-items-center fs-10">';
            html += '<i class="fa-solid ' + statusIcon + ' fs-5 me-1"></i>';
            html += capitalizeFirst(payment.payment_status) + '</span></td>';
            html += '</tr>';
        }
        
        tbody.html(html);
    }
    
    // Render pagination
    function renderPagination(pagination) {
        var container = $('#paginationContainer');
        
        if (pagination.last_page <= 1) {
            container.html('');
            return;
        }
        
        var html = '<div class="d-flex align-items-center justify-content-center">';
        
        // Previous button
        if (pagination.current_page > 1) {
            html += '<a href="javascript:void(0)" class="pagination-nav me-2" data-page="' + (pagination.current_page - 1) + '">';
            html += '<span><i class="isax isax-arrow-left-2"></i></span></a>';
        }
        
        // Page numbers
        html += '<nav aria-label="Page navigation"><ul class="paginations d-flex justify-content-center align-items-center">';
        
        var startPage = Math.max(1, pagination.current_page - 2);
        var endPage = Math.min(pagination.last_page, pagination.current_page + 2);
        
        // First page
        if (startPage > 1) {
            html += '<li class="page-item me-2">';
            html += '<a class="page-link-1 d-flex justify-content-center align-items-center pagination-nav" href="javascript:void(0)" data-page="1">1</a>';
            html += '</li>';
            if (startPage > 2) {
                html += '<li class="page-item me-2"><span class="page-link-1">...</span></li>';
            }
        }
        
        // Page numbers
        for (var i = startPage; i <= endPage; i++) {
            var activeClass = (i === pagination.current_page) ? 'active' : '';
            html += '<li class="page-item me-2">';
            html += '<a class="page-link-1 d-flex justify-content-center align-items-center ' + activeClass + ' pagination-nav" href="javascript:void(0)" data-page="' + i + '">' + i + '</a>';
            html += '</li>';
        }
        
        // Last page
        if (endPage < pagination.last_page) {
            if (endPage < pagination.last_page - 1) {
                html += '<li class="page-item me-2"><span class="page-link-1">...</span></li>';
            }
            html += '<li class="page-item me-2">';
            html += '<a class="page-link-1 d-flex justify-content-center align-items-center pagination-nav" href="javascript:void(0)" data-page="' + pagination.last_page + '">' + pagination.last_page + '</a>';
            html += '</li>';
        }
        
        html += '</ul></nav>';
        
        // Next button
        if (pagination.current_page < pagination.last_page) {
            html += '<a href="javascript:void(0)" class="pagination-nav ms-2" data-page="' + (pagination.current_page + 1) + '">';
            html += '<span><i class="isax isax-arrow-right-3"></i></span></a>';
        }
        
        html += '</div>';
        container.html(html);
    }
    
    // Update showing results text
    function updateShowingResults(pagination) {
        var text = '';
        if (pagination.total > 0) {
            text = 'Showing ' + pagination.from + ' to ' + pagination.to + ' of ' + pagination.total + ' results';
        } else {
            text = 'No results found';
        }
        $('#showingResults').text(text);
    }
    
    // Helper Functions
    function getStatusClass(status) {
        var classes = {
            'completed': 'badge-success',
            'pending': 'badge-warning',
            'cancelled': 'badge-danger',
            'processing': 'badge-info'
        };
        return classes[status] || 'badge-secondary';
    }
    
    function getStatusIcon(status) {
        var icons = {
            'completed': 'fa-circle-check',
            'pending': 'fa-clock',
            'cancelled': 'fa-circle-xmark',
            'processing': 'fa-spinner'
        };
        return icons[status] || 'fa-circle';
    }
    
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        var date = new Date(dateString);
        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    function formatAmount(amount) {
        if (!amount) return '₹0.00';
        return '₹' + parseFloat(amount).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
    
    function capitalizeFirst(string) {
        if (!string) return '';
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function showError(message) {
        $('#paymentsTableBody').html('<tr><td colspan="7" class="text-center py-5 text-danger">' + escapeHtml(message) + '</td></tr>');
    }
    
    function showLoading() {
        $('#loadingOverlay').fadeIn(200);
    }
    
    function hideLoading() {
        $('#loadingOverlay').fadeOut(200);
    }
    
    // Event Handlers
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        var searchValue = $(this).val();
        
        searchTimeout = setTimeout(function() {
            currentSearch = searchValue;
            currentPage = 1;
            loadPayments();
        }, 500);
    });
    
    $(document).on('click', '.status-filter-item', function(e) {
        e.preventDefault();
        var status = $(this).data('status');
        currentStatus = status;
        currentPage = 1;
        
        $('#selectedStatusText').text(capitalizeFirst(status));
        loadPayments();
    });
    
    $('#perPageSelect').on('change', function() {
        currentPerPage = parseInt($(this).val());
        currentPage = 1;
        loadPayments();
    });
    
    $(document).on('click', '.pagination-nav', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        if (page && page !== currentPage) {
            currentPage = page;
            loadPayments();
            $('html, body').animate({
                scrollTop: $('.custom-datatable-filter').offset().top - 100
            }, 300);
        }
    });
    
    // Initial load
    loadPayments();
});
</script>
@endpush