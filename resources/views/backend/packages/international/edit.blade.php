@extends('layouts.app')
@section('title', 'Edit International Packages')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
        <div class="breadcrumb-title pe-3">Tour International Packages</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit International Tour Packages</li>
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
                        <h6 class="mb-0 text-uppercase">Edit International Tour Packages</h6>
                        <hr>
                        <form method="post" id="add_tour" action="" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="package_title" value="{{$package->package_title}}" id="package_title" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Tour Destinations</label>
                                    <select name="destination_type" id="destination_type" class="form-control">
                                        <option value="">Select Tour Destinations</option>
                                        <option value="Wildlife" {{ $package->destination_type == 'Wildlife' ? 'selected' : '' }}>Wildlife</option>
                                        <option value="Beaches" {{ $package->destination_type == 'Beaches' ? 'selected' : '' }}>Beaches</option>
                                        <option value="Pilgrimage" {{ $package->destination_type == 'Pilgrimage' ? 'selected' : '' }}>Pilgrimage</option>
                                        <option value="Hillstations" {{ $package->destination_type == 'Hillstations' ? 'selected' : '' }}>Hillstations</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Number of Days</label>
                                    <input type="text" name="days_count" value="{{$package->days_count}}" id="days_count" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Number of Nights</label>
                                    <input type="text" name="nights_count" value="{{$package->nights_count}}" id="nights_count" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-3">
                                    <label class="form-label">Old Price</label>
                                    <input type="text" name="old_price" value="{{$package->old_price}}" id="old_price" class="form-control">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Start From Price</label>
                                    <input type="text" name="new_price" value="{{$package->new_price}}" id="new_price" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Offer Text</label>
                                    <input type="text" name="offer_text" value="{{$package->offer_text}}" id="offer_text" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Slug</label>
                                    <input type="text" name="slug" value="{{$package->slug}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Standard Price</label>
                                    <input type="text" name="s_individual" value="{{$package->s_individual}}" id="s_individual" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Premium Price</label>
                                    <input type="text" name="d_individual" value="{{$package->d_individual}}" id="d_individual" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Luxury Price</label>
                                    <input type="text" name="p_individual" value="{{$package->p_individual}}" id="p_individual" class="form-control">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Destination Covered</label>
                                <textarea class="form-control" name="pack_location_cover" id="pack_location_cover">{{$package->pack_location_cover}}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Overview Description</label>
                                <textarea class="form-control" name="overview" id="overview">{{$package->overview}}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Tour Inclusions</label>
                                <textarea class="form-control" name="inclusions" id="inclusions">{{$package->inclusions}}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Tour Exclusions</label>
                                <textarea class="form-control" name="exclusions" id="exclusions">{{$package->exclusions}}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Amenities</label>
                                <textarea class="form-control" name="amenities" id="amenities">{{$package->amenities}}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Visit Places</label>
                                <textarea class="form-control" name="visit_places" id="visit_places">{{$package->visit_places}}</textarea>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Package Homepage Image</label>
                                    <input type="file" name="package_homepage_image" id="package_homepage_image" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Package Banner Image</label>
                                    <input type="file" name="package_banner_image" id="package_banner_image" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Current Homepage Image</label><br>
                                    <img src="{{asset($package->package_homepage_image)}}" style="height: auto;width: 155px;">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Current Banner Image</label><br>
                                    <img src="{{asset($package->package_banner_image)}}" style="height: auto;width: 155px;">
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div id="dynamic_field">
                                    <div class="row mb-2">
                                        <div class="col-10">
                                            <label class="form-label">Additional Images</label>
                                            <input type="file" name="image[]" id="image" class="form-control">
                                        </div>
                                        <div class="col-2 d-flex align-items-end">
                                            <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                                        </div>
                                    </div>
                                </div>
                                @foreach($package_image as $key => $val)
                                <div class="col-6 position-relative" data-image-id="{{$val->id}}">
                                    <img src="{{asset('uploads/package/'.$val->images)}}" style="height: auto;width: 155px;">
                                    <a href="javascript:void(0)" class="deleteMCQ" 
                                        onclick="deleteImage({{$val->id}})" 
                                        style="position: absolute; top: 5px; right: 5px;font-size: 22px;">
                                        <i class="bx bxs-trash text-danger"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            
                            
                            <!--<div class="row g-3">-->
                            <!--    <div id="dynamic_field_vehicle">-->
                            <!--        <div class="row mb-2">-->
                            <!--            <div class="col-10">-->
                            <!--                <label class="form-label">Vehicle Images</label>-->
                            <!--                <input type="file" name="vehicle_image[]" id="vehicle_image" class="form-control">-->
                            <!--            </div>-->
                            <!--            <div class="col-2 d-flex align-items-end">-->
                            <!--                <button type="button" name="add" id="add_vehicle" class="btn btn-success">Add More</button>-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--    @foreach($vehicle_image as $key => $val)-->
                            <!--    <div class="col-6 position-relative" data-image-id="{{$val->id}}">-->
                            <!--        <img src="{{asset('uploads/package/'.$val->images)}}" style="height: auto;width: 155px;">-->
                            <!--        <a href="javascript:void(0)" class="deleteMCQ" -->
                            <!--            onclick="deleteVehicleImage({{$val->id}})" -->
                            <!--            style="position: absolute; top: 5px; right: 5px;font-size: 22px;">-->
                            <!--            <i class="bx bxs-trash text-danger"></i>-->
                            <!--        </a>-->
                            <!--    </div>-->
                            <!--    @endforeach-->
                            <!--</div>-->
                            
                            <!--<div class="row g-3">-->
                            <!--    <div id="dynamic_field_hotels">-->
                            <!--        <div class="row mb-2">-->
                            <!--            <div class="col-10">-->
                            <!--                <label class="form-label">Hotels Images</label>-->
                            <!--                <input type="file" name="vehicle_image[]" id="vehicle_image" class="form-control">-->
                            <!--            </div>-->
                            <!--            <div class="col-2 d-flex align-items-end">-->
                            <!--                <button type="button" name="add" id="add_hotels" class="btn btn-success">Add More</button>-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--    @foreach($hotels_image as $key => $val)-->
                            <!--    <div class="col-6 position-relative" data-image-id="{{$val->id}}">-->
                            <!--        <img src="{{asset('uploads/package/'.$val->images)}}" style="height: auto;width: 155px;">-->
                            <!--        <a href="javascript:void(0)" class="deleteMCQ" -->
                            <!--            onclick="deleteHotelImage({{$val->id}})" -->
                            <!--            style="position: absolute; top: 5px; right: 5px;font-size: 22px;">-->
                            <!--            <i class="bx bxs-trash text-danger"></i>-->
                            <!--        </a>-->
                            <!--    </div>-->
                            <!--    @endforeach-->
                            <!--</div>-->
                        <div class="border p-3 rounded"> 
                            <!-- Package Price Section -->
                                @if(!empty($package_room_prices) && count($package_room_prices) > 0)
                                <h6 class="mb-0 text-uppercase">Edit Package Price</h6>
                                <hr>
                            
                                @foreach($package_room_prices as $key => $val)
                                    <div class="section-title star-{{ $val->star_category }}">
                                        <span>&nbsp;&nbsp;{{ $val->star_category }} Star Category - Room Pricing</span>
                                    </div>
                            
                                    <div class="price-table-container">
                                        <table class="price-table">
                                            <thead>
                                                <tr>
                                                    <th>Hotel / Resort / Cottage</th>
                                                    <th>Rooms Cost (per person)</th>
                                                    <th>Extra Bed / Mattress Cost</th>
                                                    <th>Attract/Entry Tickets</th>
                                                    <th>Honeymoon Cost</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="text" value="{{ $val->hotel_name }}" class="table-input" readonly></td>
                                                    <td><input type="text" value="{{ $val->rooms_cost }}" class="table-input" readonly></td>
                                                    <td><input type="number" value="{{ $val->extra_bed_cost }}" class="table-input" readonly></td>
                                                    <td><input type="number" value="{{ $val->attract_tickets }}" class="table-input" readonly></td>
                                                    <td><input type="number" value="{{ $val->honeymoon_cost }}" class="table-input" readonly></td>
                                                    <td>
                                                        <a href="javascript:void(0)" class="editRoomModule" data-id="{{ $val->id }}" data-star="{{ $val->star_category }}">
                                                            <i class="bx bxs-edit text-success" style="font-size: 22px;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            @else
                                <h6 class="mb-0 text-uppercase">Add Package Price</h6>
                                <hr>
                                <div class="section-title star-2">
                                <span>&nbsp;&nbsp;2 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_2">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                            <th>Honeymoon Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_2">
                                        <tr>
                                            <td><input type="text" name="room_config_2[0][hotel]" value="Hotel / Resort / Cottage" class="table-input"></td>
                                            <td><input type="text" name="room_config_2[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_2[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_2[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                            <td><input type="number" name="room_config_2[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Honeymoon cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>



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
                                            <td><input type="number" name="room_config_3[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Honeymoon cost"></td>
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
                                            <td><input type="number" name="room_config_4[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>

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
                                            <td><input type="number" name="room_config_4[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                        <div class="section-title mt-4">
                            <span>&nbsp;&nbsp;Transport / Car Pricing</span>
                        </div>
                        
                        <div class="price-table-container">
                            <table class="price-table" id="car_price_table">
                                <thead>
                                    <tr>
                                        <th>Pax</th>
                                        <th>Car Name</th>
                                        <th>Car P/D (per day)</th>
                                        <th>Driver Batta (per day)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="car_price_body">
                                    @if(!empty($package_car_prices) && count($package_car_prices) > 0)
                                        @foreach($package_car_prices as $carIndex => $car)
                                            <tr class="car-row" data-car-id="{{ $car->id }}">
                                                <td><input type="number" value="{{ $car->pax }}" class="table-input" style="width:70px" readonly></td>
                                                <td><input type="text"  value="{{ $car->car_name }}" class="table-input" readonly></td>
                                                <td><input type="number" value="{{ $car->car_per_day }}" class="table-input" style="width:100px" readonly></td>
                                                <td><input type="number" value="{{ $car->driver_batta }}" class="table-input" style="width:100px" readonly></td>
                                                 <td>
                                                        <a href="javascript:void(0)" class="editCarModule" data-id="{{ $car->id }}">
                                                            <i class="bx bxs-edit text-success" style="font-size: 22px;"></i>
                                                        </a>
                                                    </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="car-row">
                                            <td><input type="number" name="car_config[0][pax]" value="" class="table-input" style="width:70px" placeholder="Pax"></td>
                                            <td><input type="text" name="car_config[0][car_name]" value="" class="table-input" placeholder="Car Name"></td>
                                            <td><input type="number" name="car_config[0][car_per_day]" value="" class="table-input" style="width:100px" placeholder="Car P/D"></td>
                                            <td><input type="number" name="car_config[0][driver_batta]" value="" class="table-input" style="width:100px" placeholder="Driver Batta"></td>
                                            <td><button type="button" class="btn btn-danger btn-sm remove-car-row">Remove</button></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="border p-3">
                            <h6 class="text-uppercase mb-3">Edit Flight</h6>
                        
                            <input type="hidden" name="package_flight_id" value="{{ $package_flight?->id }}">
                        
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="date" name="form_date"
                                                    value="{{ $package_flight?->form_date }}"
                                                    class="form-control">
                                            </td>
                        
                                            <td>
                                                <input type="date" name="to_date"
                                                    value="{{ $package_flight?->to_date }}"
                                                    class="form-control">
                                            </td>
                        
                                            <td>
                                                <input type="time" name="from_time"
                                                    value="{{ $package_flight?->from_time }}"
                                                    class="form-control">
                                            </td>
                        
                                            <td>
                                                <input type="time" name="to_time"
                                                    value="{{ $package_flight?->to_time }}"
                                                    class="form-control">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Start Place</th>
                                            <th>End Place</th>
                                            <th>Cabin</th>
                                            <th>Check-in</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" name="start_place"
                                                    value="{{ $package_flight?->start_place }}"
                                                    class="form-control">
                                            </td>
                        
                                            <td>
                                                <input type="text" name="end_place"
                                                    value="{{ $package_flight?->end_place }}"
                                                    class="form-control">
                                            </td>
                        
                                            <td>
                                                <input type="text" name="cabin_place"
                                                    value="{{ $package_flight?->cabin_place }}"
                                                    class="form-control">
                                            </td>
                        
                                            <td>
                                                <input type="text" name="check_in"
                                                    value="{{ $package_flight?->check_in }}"
                                                    class="form-control">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Meta Title</label>
                                        <input type="text" name="meta_title" value="{{$package->meta_title}}" id="meta_title" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Meta keywords</label>
                                        <input type="text" name="meta_keywords" value="{{$package->meta_keywords}}" id="meta_keywords" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Meta Description</label>
                                    <input type="text" name="meta_desc" value="{{$package->meta_desc}}" id="meta_desc" class="form-control">
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

<!-- Edit Room Price Modal -->
<div class="modal fade" id="editRoomPriceModal" tabindex="-1" aria-labelledby="editRoomPriceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoomPriceModalLabel">Edit Room Pricing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoomPriceForm">
                    @csrf
                    <input type="hidden" name="price_id" id="edit_price_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Hotel / Resort / Cottage <span class="text-danger">*</span></label>
                            <input type="text" name="hotel_name" id="edit_hotel_name" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Star Category <span class="text-danger">*</span></label>
                            <select name="star_category" id="edit_star_category" class="form-control" required>
                                <option value="2">2 Star</option>
                                <option value="3">3 Star</option>
                                <option value="4">4 Star</option>
                                <option value="5">5 Star</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Rooms Cost (per person) <span class="text-danger">*</span></label>
                            <input type="number" name="rooms_cost" id="edit_rooms_cost" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Extra Bed / Mattress Cost <span class="text-danger">*</span></label>
                            <input type="number" name="extra_bed_cost" id="edit_extra_bed_cost" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Attract/Entry Tickets <span class="text-danger">*</span></label>
                            <input type="number" name="attract_tickets" id="edit_attract_tickets" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Honeymoon Cost <span class="text-danger">*</span></label>
                            <input type="number" name="honeymoon_cost" id="edit_honeymoon_cost" class="form-control" step="0.01" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveRoomPriceBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Car Price Modal -->
<div class="modal fade" id="editCarPriceModal" tabindex="-1" aria-labelledby="editCarPriceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCarPriceModalLabel">Edit Car/Transport Pricing</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCarPriceForm">
                    @csrf
                    <input type="hidden" name="car_id" id="edit_car_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Pax (Number of People) <span class="text-danger">*</span></label>
                            <input type="number" name="pax" id="edit_pax" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Car Name / Vehicle Type <span class="text-danger">*</span></label>
                            <input type="text" name="car_name" id="edit_car_name" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Car P/D (per day) <span class="text-danger">*</span></label>
                            <input type="number" name="car_per_day" id="edit_car_per_day" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Driver Batta (per day) <span class="text-danger">*</span></label>
                            <input type="number" name="driver_batta" id="edit_driver_batta" class="form-control" step="0.01" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCarPriceBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('backend/ckeditor/ckeditor.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>



<script>
    // Form submission handler
    $("#add_tour").submit(function(event) {
        event.preventDefault();
 
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
        
        var formData = new FormData($("#add_tour")[0]);
        
        axios.post("{{ route('edit.international.packages', [Request::segment(3), $package->id]) }}", formData)
            .then(response => {
                var data = response.data;
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Package updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        window.location.href = "{{ route('list.international.packages', Request::segment(3)) }}";
                    }, 2000);
                } else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Submit');
                    Swal.fire('Error!', data.message || 'Something went wrong', 'error');
                }
            })
            .catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('Submit');
                if (error.response && error.response.data && error.response.data.errors) {
                    let errors = error.response.data.errors;
                    let errorMsg = '';
                    for (let key in errors) {
                        errorMsg += errors[key][0] + '\n';
                    }
                    Swal.fire('Validation Error!', errorMsg, 'error');
                } else {
                    Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                }
                console.log(error);
            });
    });
</script>

<script type="text/javascript">
    // Dynamic image field management
    $(document).ready(function(){      
        var i = {{ count($package_image) + 1 }};  

        $('#add').click(function(){  
            i++;  
            $('#dynamic_field').append(
                '<div class="row mb-2" id="row'+i+'">' +
                    '<div class="col-10">' +
                        '<label class="form-label">Image</label>' +
                        '<input type="file" name="image[]" class="form-control" />' +
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
    // Delete image function
    const deleteRoute = "{{ route('images.delete', '') }}";
    const deleteVehicleRoute = "{{ route('vehicle.images.delete', '') }}";
    const deleteHotelRoute = "{{ route('hotel.images.delete', '') }}";
    
    function deleteImage(imageId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const deleteUrl = deleteRoute + '/' + imageId;
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Deleted!', 'Image deleted successfully!', 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete image', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Something went wrong', 'error');
                });
            }
        });
    }
    
    
    function deleteVehicleImage(imageId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const deleteUrl = deleteVehicleRoute + '/' + imageId;
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Deleted!', 'Image deleted successfully!', 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete image', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Something went wrong', 'error');
                });
            }
        });
    }
    
    function deleteHotelImage(imageId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const deleteUrl = deleteHotelRoute + '/' + imageId;
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Deleted!', 'Image deleted successfully!', 'success');
                        location.reload();
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete image', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'Something went wrong', 'error');
                });
            }
        });
    }
</script>

<script>
    // Edit Room Price Functionality
    $(document).on('click', '.editRoomModule', function() {
        var priceId = $(this).data('id');
        var starCategory = $(this).data('star');
        var row = $(this).closest('tr');
        
        // Get values from the row
        var hotelName = row.find('td:eq(0) input').val();
        var roomsCost = row.find('td:eq(1) input').val();
        var extraBedCost = row.find('td:eq(2) input').val();
        var attractTickets = row.find('td:eq(3) input').val();
        var HoneymoonCost = row.find('td:eq(4) input').val();
        
        // Populate modal fields
        $('#edit_price_id').val(priceId);
        $('#edit_hotel_name').val(hotelName);
        $('#edit_rooms_cost').val(roomsCost);
        $('#edit_extra_bed_cost').val(extraBedCost);
        $('#edit_attract_tickets').val(attractTickets);
         $('#edit_honeymoon_cost').val(HoneymoonCost);
        $('#edit_star_category').val(starCategory);
        
        // Show modal
        $('#editRoomPriceModal').modal('show');
    });
    
    // Save Room Price Changes
    $('#saveRoomPriceBtn').click(function() {
        // Validate form
        if (!$('#edit_hotel_name').val()) {
            Swal.fire('Error!', 'Hotel name is required', 'error');
            return;
        }
        if (!$('#edit_rooms_cost').val()) {
            Swal.fire('Error!', 'Rooms cost is required', 'error');
            return;
        }
        
        var formData = new FormData();
        formData.append('price_id', $('#edit_price_id').val());
        formData.append('hotel_name', $('#edit_hotel_name').val());
        formData.append('star_category', $('#edit_star_category').val());
        formData.append('rooms_cost', $('#edit_rooms_cost').val());
        formData.append('extra_bed_cost', $('#edit_extra_bed_cost').val());
        formData.append('attract_tickets', $('#edit_attract_tickets').val());
        formData.append('honeymoon_cost', $('#edit_honeymoon_cost').val());
        formData.append('_token', '{{ csrf_token() }}');
        
        // Show loading state
        $('#saveRoomPriceBtn').prop('disabled', true);
        $('#saveRoomPriceBtn').html('<span class="spinner-border spinner-border-sm" role="status"></span> Saving...');
        
        // Send AJAX request
        $.ajax({
            url: "{{ route('update.room.price', $package->id) }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Update the table row with new values
                    var priceId = $('#edit_price_id').val();
                    var row = $(`.editRoomModule[data-id="${priceId}"]`).closest('tr');
                    
                    row.find('td:eq(0) input').val(response.data.hotel_name);
                    row.find('td:eq(1) input').val(response.data.rooms_cost);
                    row.find('td:eq(2) input').val(response.data.extra_bed_cost);
                    row.find('td:eq(3) input').val(response.data.attract_tickets);
                    row.find('td:eq(4) input').val(response.data.honeymoon_cost);
                    
                    // Update star category if changed
                    var newStar = response.data.star_category;
                    var oldStar = row.closest('.price-table-container').prev('.section-title').find('span').text().match(/\d+/)[0];
                    if (newStar != oldStar) {
                        // Reload page to reorganize star categories
                        Swal.fire('Success!', 'Star category updated. Page will reload.', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        // Close modal and show success
                        $('#editRoomPriceModal').modal('hide');
                        Swal.fire('Success!', 'Room price updated successfully', 'success');
                    }
                } else {
                    Swal.fire('Error!', response.message || 'Failed to update', 'error');
                }
            },
            error: function(xhr) {
                var errorMsg = 'Something went wrong';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error!', errorMsg, 'error');
            },
            complete: function() {
                $('#saveRoomPriceBtn').prop('disabled', false);
                $('#saveRoomPriceBtn').html('Save Changes');
            }
        });
    });
    
    // Edit Car Price Functionality
    $(document).on('click', '.editCarModule', function() {
        var carId = $(this).data('id');
        var row = $(this).closest('tr');
        
        // Get values from the row
        var pax = row.find('td:eq(0) input').val();
        var carName = row.find('td:eq(1) input').val();
        var carPerDay = row.find('td:eq(2) input').val();
        var driverBatta = row.find('td:eq(3) input').val();
        
        // Populate modal fields
        $('#edit_car_id').val(carId);
        $('#edit_pax').val(pax);
        $('#edit_car_name').val(carName);
        $('#edit_car_per_day').val(carPerDay);
        $('#edit_driver_batta').val(driverBatta);
        
        // Show modal
        $('#editCarPriceModal').modal('show');
    });
    
    // Save Car Price Changes
    $('#saveCarPriceBtn').click(function() {
        // Validate form
        if (!$('#edit_car_name').val()) {
            Swal.fire('Error!', 'Car name is required', 'error');
            return;
        }
        if (!$('#edit_car_per_day').val()) {
            Swal.fire('Error!', 'Car per day cost is required', 'error');
            return;
        }
        
        var formData = new FormData();
        formData.append('car_id', $('#edit_car_id').val());
        formData.append('pax', $('#edit_pax').val());
        formData.append('car_name', $('#edit_car_name').val());
        formData.append('car_per_day', $('#edit_car_per_day').val());
        formData.append('driver_batta', $('#edit_driver_batta').val());
        formData.append('_token', '{{ csrf_token() }}');
        
        // Show loading state
        $('#saveCarPriceBtn').prop('disabled', true);
        $('#saveCarPriceBtn').html('<span class="spinner-border spinner-border-sm" role="status"></span> Saving...');
        
        // Send AJAX request
        $.ajax({
            url: "{{ route('update.car.price', $package->id) }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Update the table row with new values
                    var carId = $('#edit_car_id').val();
                    var row = $(`.editCarModule[data-id="${carId}"]`).closest('tr');
                    
                    row.find('td:eq(0) input').val(response.data.pax);
                    row.find('td:eq(1) input').val(response.data.car_name);
                    row.find('td:eq(2) input').val(response.data.car_per_day);
                    row.find('td:eq(3) input').val(response.data.driver_batta);
                    
                    // Close modal and show success
                    $('#editCarPriceModal').modal('hide');
                    Swal.fire('Success!', 'Car price updated successfully', 'success');
                } else {
                    Swal.fire('Error!', response.message || 'Failed to update', 'error');
                }
            },
            error: function(xhr) {
                var errorMsg = 'Something went wrong';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error!', errorMsg, 'error');
            },
            complete: function() {
                $('#saveCarPriceBtn').prop('disabled', false);
                $('#saveCarPriceBtn').html('Save Changes');
            }
        });
    });
</script>

<script>
    // Car Transport Management
    let carRowCount = {{ isset($package_car_prices) ? count($package_car_prices) : 1 }};
    
    // Add new car row button
    setTimeout(function() {
        if ($('#add_car_row').length === 0) {
            const addButton = $('<button type="button" id="add_car_row" class="btn btn-primary btn-sm btn-add-row">+ Add More Car</button>');
            $('.price-table-container').last().after(addButton);
        }
    }, 100);
    
    // Add row functionality for car
    $(document).on('click', '#add_car_row', function() {
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
        if ($('#car_price_body tr').length > 1) {
            $(this).closest('tr').remove();
            reindexCarRows();
        } else {
            Swal.fire('Warning!', 'At least one car row is required', 'warning');
        }
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
        });
        carRowCount = $('#car_price_body tr').length;
    }
</script>
@stop