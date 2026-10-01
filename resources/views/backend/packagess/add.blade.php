@extends('layouts.app')
@section('title', 'Packages Add')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
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
        background-color: #14aa62;
        border-left: 4px solid #1ea2d2;
        color:#fff;
    }
    .star-2 {
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
        <div class="breadcrumb-title pe-3">Tour Packages</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Tour Packages</li>
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
                        <h6 class="mb-0 text-uppercase">Add Tour Packages</h6>
                        <hr>
                        <form method="post" id="add_tour" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Name</label>
                                        <input type="text" name="package_title" value="" id="package_title" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Tour Destinations</label>
                                        <select  name="destination_type" id="destination_type" class="form-control">
                                            <option value="">Select Tour Destinations</option>
                                            <option value="Wildlife">Wildlife</option>
                                            <option value="Beaches">Beaches</option>
                                            <option value="Pilgrimage">Pilgrimage</option>
                                            <option value="Hillstations">Hillstations</option>
                                        </select>
                                </div>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Number of Days</label>
                                            <input type="text" name="days_count" value="" id="days_count" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Number of Nights</label>
                                            <input type="text" name="nights_count" value="" id="nights_count" class="form-control">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Old Price</label>
                                            <input type="text" name="old_price" value="" id="old_price" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Start From Price</label>
                                            <input type="text" name="new_price" value="" id="new_price" class="form-control">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Slug</label>
                                            <input type="text" name="slug" value="" id="slug" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Standard Price</label>
                                            <input type="text" name="s_individual" value="" id="s_individual" class="form-control">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Premium Price</label>
                                            <input type="text" name="d_individual" value="" id="d_individual" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Luxury Price</label>
                                            <input type="text" name="p_individual" value="" id="p_individual" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Destination Covered</label>
                                    <textarea class="form-control" name="pack_location_cover" id="pack_location_cover"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Overview Description</label>
                                    <textarea class="form-control" name="overview" id="overview"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Tour Inclusions</label>
                                    <textarea class="form-control" name="inclusions" id="inclusions"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Tour Exclusions</label>
                                    <textarea class="form-control" name="exclusions" id="exclusions"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Amenities</label>
                                    <textarea class="form-control" name="amenities" id="amenities"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Visit Places</label>
                                    <textarea class="form-control" name="visit_places" id="visit_places"></textarea>
                                </div>
                                
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Package Homepage Image</label>
                                        <input type="file" name="package_homepage_image" value="" id="package_homepage_image" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Package Banner Image</label>
                                        <input type="file" name="package_banner_image" value="" id="package_banner_image" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div id="dynamic_field">
                                        <div class="row mb-2">
                                            <div class="col-10">
                                                <label class="form-label">Image</label>
                                                <input type="file" name="image[]" id="image" class="form-control">
                                            </div>
                                            <div class="col-2 d-flex align-items-end">
                                                <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            
                             <div class="row g-3">
                                <div id="dynamic_field_vehicle">
                                        <div class="row mb-2">
                                            <div class="col-10">
                                                <label class="form-label">Vehicle Images</label>
                                                <input type="file" name="vehicle_image[]" id="vehicle_image" class="form-control">
                                            </div>
                                            <div class="col-2 d-flex align-items-end">
                                                <button type="button" name="add" id="add_vehicle" class="btn btn-success">Add More</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            
                             <div class="row g-3">
                                <div id="dynamic_field_hotels">
                                        <div class="row mb-2">
                                            <div class="col-10">
                                                <label class="form-label">Hotels Images</label>
                                                <input type="file" name="hotels_image[]" id="hotels_image" class="form-control">
                                            </div>
                                            <div class="col-2 d-flex align-items-end">
                                                <button type="button" name="add" id="add_vehicle" class="btn btn-success">Add More</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            
                            <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">Add Package Price</h6>
                        <hr>
                           <!-- 2 Star Category Table -->
                            <div class="section-title star-2">
                                <span>&nbsp;&nbsp;2 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_3">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                            <th>Honeymoon Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_3">
                                        <tr>
                                            <td><input type="text" name="room_config_2[0][hotel]" value="Hotel / Resort / Cottage" class="table-input"></td>
                                            <td><input type="text" name="room_config_2[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_2[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_2[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                            <td><input type="number" name="room_config_2[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Honeymoon Cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- 3 Star Category Table -->
                            <div class="section-title star-3">
                                <span>&nbsp;&nbsp;3 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_3">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                            <th>Honeymoon Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_3">
                                        <tr>
                                            <td><input type="text" name="room_config_3[0][hotel]" value="Hotel / Resort / Cottage" class="table-input"></td>
                                            <td><input type="text" name="room_config_3[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_3[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_3[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                            <td><input type="number" name="room_config_3[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Honeymoon Cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- 4 Star Category Table -->
                            <div class="section-title star-4 mt-4">
                                <span>&nbsp;&nbsp;4 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_4">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                            <th>Honeymoon Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_4">
                                        <tr>
                                            <td><input type="text" name="room_config_4[0][hotel]" value="Hotel / Resort / Cottage" class="table-input"></td>
                                            <td><input type="text" name="room_config_4[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_4[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_4[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                            <td><input type="number" name="room_config_4[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Honeymoon Cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- 5 Star Category Table -->
                            <div class="section-title star-5 mt-4">
                                <span>&nbsp;&nbsp;5 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_5">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                            <th>Honeymoon Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_5">
                                        <tr>
                                            <td><input type="text" name="room_config_5[0][hotel]" value="Hotel / Resort / Cottage" class="table-input"></td>
                                            <td><input type="text" name="room_config_5[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_5[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_5[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                            <td><input type="number" name="room_config_5[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Honeymoon Cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Car & Transport Table -->
                            <div class="section-title mt-4">
                                <span>&nbsp;&nbsp;Transport / Car Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="car_price_table">
                                    <thead>
                                        <tr>
                                            <th>Pax</th>
                                            <th>Car Name</th>
                                            <th>Car P/D </th>
                                            <th>Driver Batta </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="car_price_body">
                                        <tr>
                                            <td><input type="number" name="car_config[0][pax]" value="" class="table-input" style="width:70px"></td>
                                            <td><input type="text" name="car_config[0][car_name]" value="" class="table-input"></td>
                                            <td><input type="number" name="car_config[0][car_per_day]" value="" class="table-input" style="width:100px"></td>
                                            <td><input type="number" name="car_config[0][driver_batta]" value="" class="table-input" style="width:100px"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-car-row">Remove</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-muted small mb-3">
                                <span class="sharing-badge" style="background-color: #dbeafe; color: #1e40af; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.7rem;">Triple Sharing Available</span>
                            </div>

                    </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Meta Title</label>
                                        <input type="text" name="meta_title" value="" id="meta_title" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Meta keywords</label>
                                        <input type="text" name="meta_keywords" value="" id="meta_keywords" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3">
                            <div class="col-12">
                                    <label class="form-label">Meta Description</label>
                                        <input type="text" name="meta_desc" value="" id="meta_desc" class="form-control">
                                </div>
                            </div>
                            <br>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="">
                                        <button type="submit" id="submit" class="btn btn-primary">Submit</button>
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
<script src="{{ asset('backend/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('description');
</script>
<script>
    $("#add_tour").submit(function() {
        event.preventDefault();
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
        axios.post("{{ route('add.packages',Request::segment(3)) }}", new FormData($("#add_tour")[0])).then(response => {
            var data = response.data;
            if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true,
                "{{ route('list.packages',Request::segment(3)) }}", 1000);
            else {
                $("#submit").prop('disabled', false);
                $("#submit").html('Submit');
                for (var a in data['error']['message']) {
                    notify(null, data['error']['message'][a][0], 'botton left');
                    if (a == 'success' | a == 'error') notify(null, data['error']['message'][a][0],
                        'botton left');
                }
            }
        }).catch(error => {
            $("#submit").prop('disabled', false);
            $("#submit").html('Submit');
            notify(null, 'Something went wrong', 'top right');
            console.log(error);
        });
    });

</script>


<script type="text/javascript">
$(document).ready(function(){      
    var i = 1;  
    $('#add').click(function(){  
        i++;  
        $('#dynamic_field').append(
            '<div class="row mb-2" id="row'+i+'">' +
                '<div class="col-10">' +
                    '<label class="form-label">Image</label>' +
                    '<input type="file" name="image[]" class="form-control" required />' +
                '</div>' +
                '<div class="col-2 d-flex align-items-end">' +
                    '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>' +
                '</div>' +
            '</div>'
        );  
    });
    
    
    var i = 1;  
    $('#add_vehicle').click(function(){  
        i++;  
        $('#dynamic_field_vehicle').append(
            '<div class="row mb-2" id="row'+i+'">' +
                '<div class="col-10">' +
                    '<label class="form-label">Vehicle Images</label>' +
                    '<input type="file" name="vehicle_image[]" class="form-control" required />' +
                '</div>' +
                '<div class="col-2 d-flex align-items-end">' +
                    '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>' +
                '</div>' +
            '</div>'
        );  
    });
    
    
    var i = 1;  
    $('#add_hotels').click(function(){  
        i++;  
        $('#dynamic_field_hotels').append(
            '<div class="row mb-2" id="row'+i+'">' +
                '<div class="col-10">' +
                    '<label class="form-label">Hotels Images</label>' +
                    '<input type="file" name="hotels_image[]" class="form-control" required />' +
                '</div>' +
                '<div class="col-2 d-flex align-items-end">' +
                    '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>' +
                '</div>' +
            '</div>'
        );  
    });

    $(document).on('click', '.btn_remove', function(){  
        var button_id = $(this).attr("id");   
        $('#row'+button_id).remove();  
    });  
});
</script>


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
</script>
@stop

