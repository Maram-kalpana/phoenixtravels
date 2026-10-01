@extends('layouts.app')
@section('title', 'Honeymoon Packages List')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
    <style>
        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #0d6efd;
            color: #333;
        }
        
        .details-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .details-table td {
            vertical-align: middle;
        }
        
        .modal-xl {
            max-width: 1140px;
        }
        
        @media (min-width: 992px) {
            .modal-xl {
                max-width: 1200px;
            }
        }
        
        .price-badge {
            font-size: 14px;
            font-weight: 500;
        }
    </style>
@endsection

@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Packages</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a></li>
                    <li class="breadcrumb-item active" aria-current="page">List Packages</li>
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
                        <div class="position-relative"></div>
                        <div class="ms-auto">
                            <a href="{{ route('add.honeymoon.packages', Request::segment(3)) }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                                <i class="bx bxs-plus-square"></i>Add New Packages
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered student-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Package ID</th>
                                    <th>Package Name</th>
                                    <th>Date</th>
                                    <th>Itinerary</th>
                                    <th>Package Price</th>
                                    <th>Package Highlights</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table_data"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Package Price Details Modal -->
    <div class="modal fade booking-details-modal" id="viewBookingModal" tabindex="-1" aria-labelledby="viewBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewBookingModalLabel">
                        <i class="fas fa-info-circle"></i> Package Price Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="bookingDetailsLoader" class="text-center py-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading package details...</p>
                    </div>
                    <div id="bookingDetailsContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('backend/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('backend/dataTables/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.student-table').DataTable({
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
            "serverSide": true,
            "ajax": {
                url: "{{ route('list.honeymoon.packages.data', Request::segment(3)) }}",
                dataFilter: function(data) {
                    var json = jQuery.parseJSON(data);
                    json.recordsTotal = json.recordsTotal;
                    json.recordsFiltered = json.recordsFiltered;
                    json.data = json.data;
                    return JSON.stringify(json);
                }
            },
            'columnDefs': [{
                    'targets': 0,
                    'searchable': false,
                    'orderable': false,
                },
                {
                    "targets": 1,
                    "searchable": false,
                    'orderable': false
                },
                {
                    "targets": 2,
                    "searchable": false,
                    'orderable': false
                },
                {
                    "targets": 3,
                    "searchable": false,
                    'orderable': false
                },
                {
                    "targets": 4,
                    "searchable": false,
                    'orderable': false
                },
                {
                    "targets": 5,
                    "searchable": false,
                    'orderable': false
                },
                {
                    "targets": 6,
                    "searchable": false,
                    'orderable': false
                },
                {
                    "targets": 7,
                    "searchable": false,
                    'orderable': false
                }
            ],
            'order': [
                [1, 'desc']
            ],
        });
    });

    // Status Update
    $('body').on('click', '.status-update', function() {
        let id = $(this).attr('data-id');
        let status = this.checked ? 1 : 0;
        $.ajax({
            url: "{{ route('update.honeymoon.packages.status') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                id: id,
                status: status,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                notify(null, 'Status Updated successfully', 'top right', 'success', false, "{{ route('list.honeymoon.packages', Request::segment(3)) }}", 1000);
            }
        });
    });

    // View Package Price Details
    $(document).on('click', '.view-price-details', function() {
        var id = $(this).data('id');
        
        // Show modal and loader
        $('#viewBookingModal').modal('show');
        $('#bookingDetailsLoader').show();
        $('#bookingDetailsContent').html('');
        
        // Fetch package price details
        $.ajax({
            url: "{{ route('honeymoon.package.price.details', '') }}/" + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#bookingDetailsLoader').hide();
                
                if (response.success) {
                    displayPackagePriceDetails(response.data);
                } else {
                    $('#bookingDetailsContent').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> 
                            ${response.message}
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                $('#bookingDetailsLoader').hide();
                $('#bookingDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Failed to load package details. Please try again.<br>
                        Error: ${error}
                    </div>
                `);
            }
        });
    });

    // Display Package Price Details
    function displayPackagePriceDetails(data) {
    var roomPrices = data.room_prices;
    var carPrices = data.car_prices;
    
    var html = '';
    
    // Room Price Details Section
    html += '<div class="room-prices-section">';
    html += '<h6 class="section-title mt-3"><i class="fas fa-hotel"></i> Room Price Details</h6>';
    
    if (roomPrices && roomPrices.length > 0) {
        html += '<div class="table-responsive">';
        html += '<table class="table table-bordered details-table">';
        html += '<thead class="table-light">';
        html += '<tr>';
        html += '<th>Star Category</th>';
        html += '<th>Hotel Name</th>';
        html += '<th>Room Cost (₨)</th>';
        html += '<th>Extra Bed Cost (₨)</th>';
        html += '<th>Attract/Entry Tickets (₨)</th>';
        html += '<th>Honeymoon Cost (₨)</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';
        
        roomPrices.forEach(function(room) {
            // Parse values as floats, default to 0 if not a number
            var roomsCost = parseFloat(room.rooms_cost) || 0;
            var extraBedCost = parseFloat(room.extra_bed_cost) || 0;
            var totalRoomPrice = roomsCost + extraBedCost;
            var AttractTickets = parseFloat(room.attract_tickets) || 0;
            var HoneymoonCost = parseFloat(room.honeymoon_cost) || 0;
            
            html += '<tr>';
            html += '<td>' + (room.star_category || '-') +'Star'+' '+ '</td>';
            html += '<td>' + (room.hotel_name || '-') + '</td>';
            html += '<td>₹' + roomsCost.toFixed(2) + '</td>';
            html += '<td>₹' + extraBedCost.toFixed(2) + '</td>';
            html += '<td>₹' + AttractTickets.toFixed(2) + '</span></td>';
            html += '<td>₹' + HoneymoonCost.toFixed(2) + '</span></td>';
            html += '</tr>';
        });
        
        html += '</tbody>';
        html += '</table>';
        html += '</div>';
    } else {
        html += '<div class="alert alert-info">';
        html += '<i class="fas fa-info-circle"></i> No room prices found for this package.';
        html += '</div>';
    }
    html += '</div>';
    
    // Car Price Details Section
    html += '<div class="car-prices-section mt-4">';
    html += '<h6 class="section-title"><i class="fas fa-car"></i> Car Price Details</h6>';
    
    if (carPrices && carPrices.length > 0) {
        html += '<div class="table-responsive">';
        html += '<table class="table table-bordered details-table">';
        html += '<thead class="table-light">';
        html += '<tr>';
        html += '<th>PAX</th>';
        html += '<th>Car Name</th>';
        html += '<th>Car Cost Per Day (₨)</th>';
        html += '<th>Driver Batta Per Day (₨)</th>';
        html += '<th>Total Price (₨)</th>';
        html += '</tr>';
        html += '</thead>';
        html += '<tbody>';
        
        carPrices.forEach(function(car) {
            // Parse values as floats, default to 0 if not a number
            var pax = parseFloat(car.pax) || 1;
            var carPerDay = parseFloat(car.car_per_day) || 0;
            var driverBatta = parseFloat(car.driver_batta) || 0;
            
            // Calculate totals
            var totalCarCost = carPerDay; // Car cost per day (not multiplied by pax as it's per vehicle)
            var totalDriverBattaCost = driverBatta; // Driver batta per day
            var totalPrice = totalCarCost + totalDriverBattaCost;
            
            html += '<tr>';
            html += '<td>' + (car.pax || '-') + '</td>';
            html += '<td>' + (car.car_name || '-') + '</td>';
            html += '<td>₹' + carPerDay.toFixed(2) + '</td>';
            html += '<td>₹' + driverBatta.toFixed(2) + '</td>';
            html += '<td><span class="badge bg-primary price-badge">₹' + totalPrice.toFixed(2) + '</span></td>';
            html += '</tr>';
        });
        
        html += '</tbody>';
        html += '</table>';
        html += '</div>';
    } else {
        html += '<div class="alert alert-info">';
        html += '<i class="fas fa-info-circle"></i> No car prices found for this package.';
        html += '</div>';
    }
    html += '</div>';
    
    // Summary Section
   
    
    $('#bookingDetailsContent').html(html);
}

    // Delete Package
    

</script>
<script>
    function validate(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "Are you sure you want to delete this record?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('honeymoon.packages.destroy') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    },
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your record has been deleted.",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: data.message || "Something went wrong!",
                                icon: "error"
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error!",
                            text: "Failed to delete the record. Please try again.",
                            icon: "error"
                        });
                    }
                });
            }
        });
    }
</script>
@endsection