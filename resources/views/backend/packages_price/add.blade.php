@extends('layouts.app')
@section('title', 'Package Price Add')
@section('css')
<style>
    .price-table-container {
        overflow-x: auto;
        margin-top: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
    }
    .price-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
        min-width: 800px;
    }
    .price-table th,
    .price-table td {
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
        text-align: center;
        vertical-align: middle;
    }
    .price-table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #1e293b;
    }
    .table-input {
        width: 100%;
        min-width: 80px;
        padding: 0.25rem 0.5rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        text-align: center;
    }
    .table-input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        padding-bottom: 0.25rem;
        border-bottom: 2px solid #e2e8f0;
    }
    .btn-add-row {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .star-3 {
        background-color: #fef3c7;
        border-left: 4px solid #f59e0b;
    }
    .star-4 {
        background-color: #e0f2fe;
        border-left: 4px solid #3b82f6;
    }
    .star-5 {
        background-color: #f3e8ff;
        border-left: 4px solid #a855f7;
    }
</style>
@endsection
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Package Price</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Package Price</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">Add Package Price</h6>
                        <hr>
                        <form method="post" id="package_price_form" action="" enctype="multipart/form-data">
                          
                            <!-- 3 Star Category Table -->
                            <div class="section-title star-3">
                                <span>🏨 3 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_3">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_3">
                                        <tr>
                                            <td><input type="text" name="room_config_3[0][hotel]" value="Hotel / Resort" class="table-input"></td>
                                            <td><input type="text" name="room_config_3[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_3[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_3[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- 4 Star Category Table -->
                            <div class="section-title star-4 mt-4">
                                <span>⭐⭐⭐⭐ 4 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_4">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_4">
                                        <tr>
                                            <td><input type="text" name="room_config_4[0][hotel]" value="Hotel / Resort" class="table-input"></td>
                                            <td><input type="text" name="room_config_4[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_4[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_4[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- 5 Star Category Table -->
                            <div class="section-title star-5 mt-4">
                                <span>⭐⭐⭐⭐⭐ 5 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_5">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_5">
                                        <tr>
                                            <td><input type="text" name="room_config_5[0][hotel]" value="Hotel / Resort" class="table-input"></td>
                                            <td><input type="text" name="room_config_5[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_5[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_5[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Car & Transport Table -->
                            <div class="section-title mt-4">
                                <span>🚗 Transport / Car Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="car_price_table">
                                    <thead>
                                        <tr>
                                            <th>Pax</th>
                                            <th>Car Name</th>
                                            <th>Car P/D (₹)</th>
                                            <th>Driver Batta (₹)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="car_price_body">
                                        <tr>
                                            <td><input type="number" name="car_config[0][pax]" value="1" class="table-input" style="width:70px"></td>
                                            <td><input type="text" name="car_config[0][car_name]" value="Sedan / Swift Dzire AC" class="table-input"></td>
                                            <td><input type="number" name="car_config[0][car_per_day]" value="2500" class="table-input" style="width:100px"></td>
                                            <td><input type="number" name="car_config[0][driver_batta]" value="500" class="table-input" style="width:100px"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-car-row">Remove</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-muted small mb-3">
                                <span class="sharing-badge" style="background-color: #dbeafe; color: #1e40af; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.7rem;">Triple Sharing Available</span>
                            </div>

                            <br>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="">
                                        <button type="submit" id="submit" class="btn btn-primary">Submit Package Price</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    let carRowCount = 1;
    
    // Add new car row
    $('#add_car_row').remove();
    const addButton = $('<button type="button" id="add_car_row" class="btn btn-primary btn-sm btn-add-row">+ Add More Car</button>');
    $('.price-table-container').eq(3).after(addButton);
    
    // Add row functionality for car
    $('#add_car_row').on('click', function() {
        const newRow = `
            <tr class="car-row">
                <td><input type="number" name="car_config[${carRowCount}][pax]" class="table-input" style="width:70px" placeholder="Pax"></td>
                <td><input type="text" name="car_config[${carRowCount}][car_name]" class="table-input" placeholder="Car Name"></td>
                <td><input type="number" name="car_config[${carRowCount}][car_per_day]" class="table-input" style="width:100px" placeholder="Car P/D"></td>
                <td><input type="number" name="car_config[${carRowCount}][driver_batta]" class="table-input" style="width:100px" placeholder="Driver Batta"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-car-row">Remove</button></td>
            </tr>
        `;
        $('#car_price_body').append(newRow);
        carRowCount++;
    });
    
    // Remove row functionality for car
    $(document).on('click', '.remove-car-row', function() {
        $(this).closest('tr').remove();
        reindexCarRows();
    });
    
    // Function to reindex car rows
    function reindexCarRows() {
        $('#car_price_body tr').each(function(index, row) {
            $(row).find('input[name^="car_config["]').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    const newName = name.replace(/car_config\[\d+\]/, `car_config[${index}]`);
                    $(this).attr('name', newName);
                }
            });
            carRowCount = index + 1;
        });
    }
    
    // Form submission
    $("#package_price_form").submit(function(event) {
        event.preventDefault();
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');
        
        // Reindex car rows before submit
        reindexCarRows();
        
        const formData = new FormData(this);
        
        axios.post("{{ route('add.package.price',[Request::segment(3),Request::segment(4)]) }}", formData)
            .then(response => {
                var data = response.data;
                if (data.success) {
                    // Success - redirect to list page
                    if (typeof notify === 'function') {
                        notify(null, 'Package price added successfully', 'top right', 'success', true, "{{ route('package.price.list',[Request::segment(3),Request::segment(4)]) }}", 1000);
                    } else {
                        window.location.href = "{{ route('package.price.list',[Request::segment(3),Request::segment(4)]) }}";
                    }
                } else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Submit Package Price');
                    
                    // Handle validation errors
                    if (data.error) {
                        let errorMessage = '';
                        if (typeof data.error === 'object') {
                            for (let key in data.error) {
                                if (data.error[key] && Array.isArray(data.error[key])) {
                                    errorMessage += data.error[key][0] + '\n';
                                } else if (typeof data.error[key] === 'string') {
                                    errorMessage += data.error[key] + '\n';
                                }
                            }
                        } else if (typeof data.error === 'string') {
                            errorMessage = data.error;
                        }
                        
                        if (typeof notify === 'function') {
                            notify(null, errorMessage || 'Validation error occurred', 'bottom left');
                        } else {
                            alert(errorMessage || 'Validation error occurred');
                        }
                    } else {
                        if (typeof notify === 'function') {
                            notify(null, 'Validation error occurred', 'bottom left');
                        } else {
                            alert('Validation error occurred');
                        }
                    }
                }
            })
            .catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('Submit Package Price');
                console.error('Error:', error);
                
                let errorMsg = 'Something went wrong';
                if (error.response && error.response.data) {
                    if (error.response.data.message) {
                        errorMsg = error.response.data.message;
                    } else if (error.response.data.error) {
                        errorMsg = error.response.data.error;
                    }
                }
                
                if (typeof notify === 'function') {
                    notify(null, errorMsg, 'top right', 'error');
                } else {
                    alert(errorMsg);
                }
            });
    });
</script>
@stop