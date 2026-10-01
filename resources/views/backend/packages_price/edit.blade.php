@extends('layouts.app')
@section('title', 'Package Price Edit')
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
        padding: 10px;
    }
    .star-4 {
        background-color: #e0f2fe;
        border-left: 4px solid #3b82f6;
        padding: 10px;
    }
    .star-5 {
        background-color: #f3e8ff;
        border-left: 4px solid #a855f7;
        padding: 10px;
    }
    .package-basic-info {
        background-color: #f0fdf4;
        border-left: 4px solid #22c55e;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    }
</style>
@endsection
@section('content')
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Package Price</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Package Price</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">Edit Package Price</h6>
                        <hr>
                        
                        <form method="post" id="package_price_form" action="" enctype="multipart/form-data">
                            @csrf
                            
                            
                            <!-- 3 Star Category Table -->
                            <div class="section-title star-3">
                                <span>⭐ 3 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_3">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_3">
                                        @php $roomCount3 = 0; @endphp
                                        @if(isset($roomPricesByStar['3']) && count($roomPricesByStar['3']) > 0)
                                            @foreach($roomPricesByStar['3'] as $index => $room)
                                                <tr>
                                                    <td><input type="text" name="room_config_3[{{ $index }}][hotel]" value="{{ $room->hotel_name }}" class="table-input"></td>
                                                    <td><input type="text" name="room_config_3[{{ $index }}][rooms]" value="{{ $room->rooms_cost }}" class="table-input" placeholder="Room cost"></td>
                                                    <td><input type="number" name="room_config_3[{{ $index }}][extra_bed_cost]" value="{{ $room->extra_bed_cost }}" class="table-input" placeholder="Extra bed cost"></td>
                                                    <td><input type="number" name="room_config_3[{{ $index }}][total_cost]" value="{{ $room->attract_tickets }}" class="table-input" placeholder="Ticket cost"></td>
                                                    <td><button type="button" class="btn btn-danger btn-sm remove-room-row">Remove</button></td>
                                                </tr>
                                                @php $roomCount3 = $index + 1; @endphp
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><input type="text" name="room_config_3[0][hotel]" value="Hotel / Resort" class="table-input"></td>
                                                <td><input type="text" name="room_config_3[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                                <td><input type="number" name="room_config_3[0][extra_bed_cost]" value="" class="table-input" placeholder="Extra bed cost"></td>
                                                <td><input type="number" name="room_config_3[0][total_cost]" value="" class="table-input" placeholder="Ticket cost"></td>
                                                <td><button type="button" class="btn btn-danger btn-sm remove-room-row">Remove</button></td>
                                            </tr>
                                            @php $roomCount3 = 1; @endphp
                                        @endif
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_4">
                                        @if(isset($roomPricesByStar['4']) && count($roomPricesByStar['4']) > 0)
                                            @foreach($roomPricesByStar['4'] as $index => $room)
                                                <tr>
                                                    <td><input type="text" name="room_config_4[{{ $index }}][hotel]" value="{{ $room->hotel_name }}" class="table-input"></td>
                                                    <td><input type="text" name="room_config_4[{{ $index }}][rooms]" value="{{ $room->rooms_cost }}" class="table-input" placeholder="Room cost"></td>
                                                    <td><input type="number" name="room_config_4[{{ $index }}][extra_bed_cost]" value="{{ $room->extra_bed_cost }}" class="table-input" placeholder="Extra bed cost"></td>
                                                    <td><input type="number" name="room_config_4[{{ $index }}][total_cost]" value="{{ $room->attract_tickets }}" class="table-input" placeholder="Ticket cost"></td>
                                                    <td><button type="button" class="btn btn-danger btn-sm remove-room-row">Remove</button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><input type="text" name="room_config_4[0][hotel]" value="Hotel / Resort" class="table-input"></td>
                                                <td><input type="text" name="room_config_4[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                                <td><input type="number" name="room_config_4[0][extra_bed_cost]" value="" class="table-input" placeholder="Extra bed cost"></td>
                                                <td><input type="number" name="room_config_4[0][total_cost]" value="" class="table-input" placeholder="Ticket cost"></td>
                                                <td><button type="button" class="btn btn-danger btn-sm remove-room-row">Remove</button></td>
                                            </tr>
                                        @endif
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_5">
                                        @if(isset($roomPricesByStar['5']) && count($roomPricesByStar['5']) > 0)
                                            @foreach($roomPricesByStar['5'] as $index => $room)
                                                <tr>
                                                    <td><input type="text" name="room_config_5[{{ $index }}][hotel]" value="{{ $room->hotel_name }}" class="table-input"></td>
                                                    <td><input type="text" name="room_config_5[{{ $index }}][rooms]" value="{{ $room->rooms_cost }}" class="table-input" placeholder="Room cost"></td>
                                                    <td><input type="number" name="room_config_5[{{ $index }}][extra_bed_cost]" value="{{ $room->extra_bed_cost }}" class="table-input" placeholder="Extra bed cost"></td>
                                                    <td><input type="number" name="room_config_5[{{ $index }}][total_cost]" value="{{ $room->attract_tickets }}" class="table-input" placeholder="Ticket cost"></td>
                                                    <td><button type="button" class="btn btn-danger btn-sm remove-room-row">Remove</button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><input type="text" name="room_config_5[0][hotel]" value="Hotel / Resort" class="table-input"></td>
                                                <td><input type="text" name="room_config_5[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                                <td><input type="number" name="room_config_5[0][extra_bed_cost]" value="" class="table-input" placeholder="Extra bed cost"></td>
                                                <td><input type="number" name="room_config_5[0][total_cost]" value="" class="table-input" placeholder="Ticket cost"></td>
                                                <td><button type="button" class="btn btn-danger btn-sm remove-room-row">Remove</button></td>
                                            </tr>
                                        @endif
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
                                        @if(isset($carPrices) && count($carPrices) > 0)
                                            @foreach($carPrices as $index => $car)
                                                <tr>
                                                    <td><input type="number" name="car_config[{{ $index }}][pax]" value="{{ $car->pax }}" class="table-input" style="width:70px"></td>
                                                    <td><input type="text" name="car_config[{{ $index }}][car_name]" value="{{ $car->car_name }}" class="table-input"></td>
                                                    <td><input type="number" name="car_config[{{ $index }}][car_per_day]" value="{{ $car->car_per_day }}" class="table-input" style="width:100px"></td>
                                                    <td><input type="number" name="car_config[{{ $index }}][driver_batta]" value="{{ $car->driver_batta }}" class="table-input" style="width:100px"></td>
                                                    <td><button type="button" class="btn btn-danger btn-sm remove-car-row">Remove</button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><input type="number" name="car_config[0][pax]" value="1" class="table-input" style="width:70px"></td>
                                                <td><input type="text" name="car_config[0][car_name]" value="Sedan / Swift Dzire AC" class="table-input"></td>
                                                <td><input type="number" name="car_config[0][car_per_day]" value="2500" class="table-input" style="width:100px"></td>
                                                <td><input type="number" name="car_config[0][driver_batta]" value="500" class="table-input" style="width:100px"></td>
                                                <td><button type="button" class="btn btn-danger btn-sm remove-car-row">Remove</button></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            
                            <br>
                            <div class="row g-3">
                                <div class="col-12">
                                    <button type="submit" id="submit" class="btn btn-primary">Update Package Price</button>
                                    <a href="{{ route('package.price.list', [request()->segment(3), request()->segment(4)]) }}" class="btn btn-secondary">Cancel</a>
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
    let carRowCount = {{ isset($carPrices) ? count($carPrices) : 1 }};
    let roomRowCount3 = {{ $roomCount3 ?? 1 }};
    let roomRowCount4 = {{ isset($roomPricesByStar['4']) ? count($roomPricesByStar['4']) : 1 }};
    let roomRowCount5 = {{ isset($roomPricesByStar['5']) ? count($roomPricesByStar['5']) : 1 }};
    
    // Add button for car rows
    const addCarButton = $('<button type="button" id="add_car_row" class="btn btn-primary btn-sm btn-add-row">+ Add More Car</button>');
    $('.price-table-container').eq(3).after(addCarButton);
    
    // Add button for 3 star rooms
    const addRoom3Button = $('<button type="button" id="add_room_row_3" class="btn btn-primary btn-sm btn-add-row">+ Add More 3 Star Room</button>');
    $('#room_price_table_3').after(addRoom3Button);
    
    // Add button for 4 star rooms
    const addRoom4Button = $('<button type="button" id="add_room_row_4" class="btn btn-primary btn-sm btn-add-row">+ Add More 4 Star Room</button>');
    $('#room_price_table_4').after(addRoom4Button);
    
    // Add button for 5 star rooms
    const addRoom5Button = $('<button type="button" id="add_room_row_5" class="btn btn-primary btn-sm btn-add-row">+ Add More 5 Star Room</button>');
    $('#room_price_table_5').after(addRoom5Button);
    
    // Add car row
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
    
    // Add 3 star room row
    $('#add_room_row_3').on('click', function() {
        const newRow = `
            <tr>
                <td><input type="text" name="room_config_3[${roomRowCount3}][hotel]" class="table-input" placeholder="Hotel Name"></td>
                <td><input type="text" name="room_config_3[${roomRowCount3}][rooms]" class="table-input" placeholder="Room cost"></td>
                <td><input type="number" name="room_config_3[${roomRowCount3}][extra_bed_cost]" class="table-input" placeholder="Extra bed cost"></td>
                <td><input type="number" name="room_config_3[${roomRowCount3}][total_cost]" class="table-input" placeholder="Ticket cost"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-room-row">Remove</button></td>
            </tr>
        `;
        $('#room_price_body_3').append(newRow);
        roomRowCount3++;
    });
    
    // Add 4 star room row
    $('#add_room_row_4').on('click', function() {
        const newRow = `
            <tr>
                <td><input type="text" name="room_config_4[${roomRowCount4}][hotel]" class="table-input" placeholder="Hotel Name"></td>
                <td><input type="text" name="room_config_4[${roomRowCount4}][rooms]" class="table-input" placeholder="Room cost"></td>
                <td><input type="number" name="room_config_4[${roomRowCount4}][extra_bed_cost]" class="table-input" placeholder="Extra bed cost"></td>
                <td><input type="number" name="room_config_4[${roomRowCount4}][total_cost]" class="table-input" placeholder="Ticket cost"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-room-row">Remove</button></td>
            </tr>
        `;
        $('#room_price_body_4').append(newRow);
        roomRowCount4++;
    });
    
    // Add 5 star room row
    $('#add_room_row_5').on('click', function() {
        const newRow = `
            <tr>
                <td><input type="text" name="room_config_5[${roomRowCount5}][hotel]" class="table-input" placeholder="Hotel Name"></td>
                <td><input type="text" name="room_config_5[${roomRowCount5}][rooms]" class="table-input" placeholder="Room cost"></td>
                <td><input type="number" name="room_config_5[${roomRowCount5}][extra_bed_cost]" class="table-input" placeholder="Extra bed cost"></td>
                <td><input type="number" name="room_config_5[${roomRowCount5}][total_cost]" class="table-input" placeholder="Ticket cost"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-room-row">Remove</button></td>
            </tr>
        `;
        $('#room_price_body_5').append(newRow);
        roomRowCount5++;
    });
    
    // Remove row handlers
    $(document).on('click', '.remove-car-row', function() {
        $(this).closest('tr').remove();
        reindexCarRows();
    });
    
    $(document).on('click', '.remove-room-row', function() {
        $(this).closest('tr').remove();
    });
    
    // Reindex car rows
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
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
        
        reindexCarRows();
        const formData = new FormData(this);
        formData.append('_method', 'POST');
        
        axios.post("{{ route('edit.package.price', [request()->segment(3), request()->segment(4)]) }}", formData)
            .then(response => {
                var data = response.data;
                if (data.success) {
                    if (typeof notify === 'function') {
                        notify(null, 'Package price updated successfully', 'top right', 'success', true, "{{ route('package.price.list',[request()->segment(3),request()->segment(4)]) }}", 1000);
                    } else {
                        window.location.href = "{{ route('package.price.list',[request()->segment(3),request()->segment(4)]) }}";
                    }
                } else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Update Package Price');
                    
                    if (data.error) {
                        let errorMessage = '';
                        if (typeof data.error === 'object') {
                            for (let key in data.error) {
                                if (data.error[key] && Array.isArray(data.error[key])) {
                                    errorMessage += data.error[key][0] + '\n';
                                }
                            }
                        }
                        if (typeof notify === 'function') {
                            notify(null, errorMessage || 'Validation error occurred', 'bottom left');
                        } else {
                            alert(errorMessage || 'Validation error occurred');
                        }
                    }
                }
            })
            .catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('Update Package Price');
                console.error('Error:', error);
                
                let errorMsg = 'Something went wrong';
                if (error.response && error.response.data && error.response.data.message) {
                    errorMsg = error.response.data.message;
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