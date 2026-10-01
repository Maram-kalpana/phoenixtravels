@extends('layouts.web')
@section('title', 'Phoenix Tours & Travels')
@section('css')
<style>
.stepper-wrapper {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
    position: relative;
}

.stepper-wrapper::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e0e0e0;
    z-index: 1;
}

.stepper-item {
    position: relative;
    z-index: 2;
    text-align: center;
    flex: 1;
}

.step-counter {
    width: 40px;
    height: 40px;
    background: #e0e0e0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: bold;
    color: #666;
}

.stepper-item.active .step-counter {
    background: #1fa5d2;
    color: white;
}

.stepper-item.completed .step-counter {
    background: #198754;
    color: white;
}

.step-name {
    font-size: 12px;
    font-weight: 500;
}

.stepper-item.active .step-name {
    color: #1fa8d4;
    font-weight: bold;
}

.step-content {
    display: none;
}

.step-content.active {
    display: block;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-label.required:after {
    content: '*';
    color: red;
    margin-left: 5px;
}

.custom-increment .input-group {
    width: 140px;
}

.custom-increment .btn-number {
    padding: 8px 12px;
}

.custom-increment .input-number {
    text-align: center;
}

.checkout-card {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
}

.checkout-title h6 {
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #1ea0d1;
    display: inline-block;
}
</style>
@endsection
@section('content')
 <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">School Excursion</h2>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="content">
         <div class="container">

            <!-- row start -->
            <div class="row">
                
                <div class="col-lg-12 theiaStickySidebar">
                    <div class="card">
    <div class="card-body">
        <div class="wizard-container">
            <form id="corporateTourForm" method="POST" action="">
                @csrf
                
                <!-- Progress Stepper -->
                <div class="stepper-wrapper mb-4">
                    <div class="stepper-item active" data-step="1">
                        <div class="step-counter">1</div>
                        <div class="step-name">Tour Details</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="step-counter">2</div>
                        <div class="step-name">Amenities & Food</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="step-counter">3</div>
                        <div class="step-name">Payment & Terms</div>
                    </div>
                    <div class="stepper-item" data-step="4">
                        <div class="step-counter">4</div>
                        <div class="step-name">Company Info</div>
                    </div>
                </div>

                <!-- STEP 1 - Tour Details -->
                <div class="step-content active" data-step="1">
                    <div class="card checkout-card">
                        <div class="card-body">
                            <!-- Contact Info -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Contact Information</h6>
                            </div>
                            <div class="checkout-details pb-3 border-bottom mb-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Full Name</label>
                                            <input type="text" class="form-control" name="full_name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Email Address</label>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Phone Number</label>
                                            <input type="text" class="form-control" name="phone" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label required">Country</label>
                                            <select name="country_id" id="country_id" class="form-control form-select" required>
                                                <option value="">Select Country</option>
                                                @foreach($country as $key => $val)
                                                <option value="{{$val->id}}">{{$val->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label required">State</label>
                                            <select class="form-control form-select state_id" name="state_id" id="state_id" required>
                                                <option value="">Select State</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label required">City</label>
                                            <select class="form-control form-select city_id" name="city_id" id="city_id" required>
                                                <option value="">Select City</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label required">Address</label>
                                            <input type="text" class="form-control" placeholder="Street address" name="address" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Apartment, Suite, House no (optional)</label>
                                            <input type="text" class="form-control" placeholder="Apartment, Suite, House no" name="apartment_number">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tour Information -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Tour Information</h6>
                            </div>
                            <div class="checkout-details pb-3 border-bottom mb-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Starting Place</label>
                                            <input type="text" class="form-control" name="starting_place" placeholder="Enter starting city/location" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Destination</label>
                                            <input type="text" class="form-control" name="destination" placeholder="Enter destination" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Number of People</label>
                                            <input type="number" class="form-control" name="number_of_people" id="numberOfPeople" min="1" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Number of Buses (30-40 Pax each bus)</label>
                                            <input type="number" class="form-control" name="number_of_buses" id="numberOfBuses" min="0" step="1">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Bus Type</label>
                                            <select class="form-control form-select" name="bus_type">
                                                <option value="">Select Bus Type</option>
                                                <option value="non_ac">Non AC</option>
                                                <option value="ac">AC</option>
                                                <option value="both">Both</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Train Class</label>
                                            <select class="form-control form-select" name="train_class">
                                                <option value="">Select Train Class</option>
                                                <option value="sleeper">Sleeper</option>
                                                <option value="3rd_ac">3rd AC</option>
                                                <option value="2nd_ac">2nd AC</option>
                                                <option value="1st_ac">1st AC</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Flight Class</label>
                                            <select class="form-control form-select" name="flight_class">
                                                <option value="">Select Flight Class</option>
                                                <option value="economy">Economy</option>
                                                <option value="business">Business</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Preferred Date</label>
                                            <div class="input-icon-end position-relative">
                                                <input type="date" class="form-control" name="preferred_date" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Pickup Point</label>
                                            <select class="form-control form-select" name="pickup_point">
                                                <option value="">Select Pickup Point</option>
                                                <option value="city">City</option>
                                                <option value="city_airport">City Airport</option>
                                                <option value="railway_station">Railway Station</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Dropping Point</label>
                                            <select class="form-control form-select" name="dropping_point">
                                                <option value="">Select Dropping Point</option>
                                                <option value="city">City</option>
                                                <option value="city_airport">City Airport</option>
                                                <option value="railway_station">Railway Station</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sightseeing</label>
                                            <select class="form-control form-select" name="sightseeing">
                                                <option value="">Select Sightseeing</option>
                                                <option value="within_city">Within the City</option>
                                                <option value="out_of_city">Out of the City</option>
                                                <option value="both">Both</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Participants Details -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Participants Details</h6>
                            </div>
                            <div class="checkout-details pb-3">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="custom-increment mb-3">
                                            <label class="form-label">Adults</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-light btn-number" data-type="minus" data-field="adults">
                                                        <i class="isax isax-minus"></i>
                                                    </button>
                                                </span>
                                                <input type="text" name="adults" class="input-number form-control text-center" value="0" readonly>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-light btn-number" data-type="plus" data-field="adults">
                                                        <i class="isax isax-add"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="custom-increment mb-3">
                                            <label class="form-label">Children (2-12 Yrs)</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-light btn-number" data-type="minus" data-field="children">
                                                        <i class="isax isax-minus"></i>
                                                    </button>
                                                </span>
                                                <input type="text" name="children" class="input-number form-control text-center" value="0" readonly>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-light btn-number" data-type="plus" data-field="children">
                                                        <i class="isax isax-add"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="custom-increment mb-3">
                                            <label class="form-label">Infants (0-2 Yrs)</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-light btn-number" data-type="minus" data-field="infants">
                                                        <i class="isax isax-minus"></i>
                                                    </button>
                                                </span>
                                                <input type="text" name="infants" class="input-number form-control text-center" value="0" readonly>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-light btn-number" data-type="plus" data-field="infants">
                                                        <i class="isax isax-add"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="button" class="btn btn-primary next-step d-inline-flex align-items-center gap-1">
                                    Next: Amenities & Food <i class="isax isax-arrow-right-3 ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2 - Amenities & Food -->
                <div class="step-content" data-step="2">
                    <div class="card checkout-card">
                        <div class="card-body">
                            <!-- Hotel Information -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Hotel / Resort Information</h6>
                            </div>
                            <div class="checkout-details pb-3 border-bottom mb-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Hotel Category</label>
                                            <select class="form-control form-select" name="hotel_category">
                                                <option value="">Select Category</option>
                                                <option value="3_star">3 Star</option>
                                                <option value="4_star">4 Star</option>
                                                <option value="5_star">5 Star</option>
                                                <option value="5_star_plus">5 Star +</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Location Preference</label>
                                            <select class="form-control form-select" name="hotel_location">
                                                <option value="">Select Location</option>
                                                <option value="near_beach">Near Beach</option>
                                                <option value="city">City</option>
                                                <option value="city_outskirts">City Outskirts</option>
                                                <option value="hill_front">Hill Front</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Number of Rooms</label>
                                            <input type="number" class="form-control" name="number_of_rooms" min="1">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Room Sharing Type</label>
                                            <select class="form-control form-select" name="sharing_type">
                                                <option value="">Select Sharing</option>
                                                <option value="double">Double Sharing</option>
                                                <option value="triple">Triple Sharing</option>
                                                <option value="quad">Quad Sharing</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Conference Hall -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Conference Hall Requirements</h6>
                            </div>
                            <div class="checkout-details pb-3 border-bottom mb-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label">Conference Hall Required</label>
                                            <select class="form-control form-select" name="conference_hall" id="conferenceHall">
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="conference-fields" style="display: none;">
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label">Seating Capacity</label>
                                                <select class="form-control form-select" name="seating_capacity">
                                                    <option value="">Select Capacity</option>
                                                    <option value="50">50 Pax</option>
                                                    <option value="100">100 Pax</option>
                                                    <option value="200">200 Pax</option>
                                                    <option value="300">300 Pax</option>
                                                    <option value="500">500 Pax</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label">Stage Type</label>
                                                <select class="form-control form-select" name="stage_type">
                                                    <option value="normal">Normal Stage</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label">Seating Arrangement</label>
                                                <select class="form-control form-select" name="seating_arrangement">
                                                    <option value="round_table">Round Table</option>
                                                    <option value="normal">Normal</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Equipment Required</label>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="equipment[]" value="music_system" id="musicSystem">
                                                            <label class="form-check-label" for="musicSystem">Music System</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="equipment[]" value="mice" id="mice">
                                                            <label class="form-check-label" for="mice">MICE</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="equipment[]" value="digital_screen" id="digitalScreen">
                                                            <label class="form-check-label" for="digitalScreen">Digital Screen</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Food Details -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Food & Beverages</h6>
                            </div>
                            <div class="checkout-details pb-3 border-bottom mb-4">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold">Breakfast Menu</label>
                                          
                                            <div class="row">
                                                    @foreach($breakfast_menu as $key => $val)
                                                        <div class="col-md-4 col-sm-6 mb-2">
                                                            <div class="form-check">
                                                                <input 
                                                                    class="form-check-input" 
                                                                    type="checkbox" 
                                                                    name="evening_snacks_menu[]" 
                                                                    value="{{ $val->category_name }}" 
                                                                    id="drink_{{ $key }}"
                                                                >
                                                                <label class="form-check-label" for="drink_{{ $key }}">
                                                                    {{ $val->category_name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            
                                        </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold">Lunch Menu</label>
                                               <div class="row">
                                                    @foreach($lunch_menu as $key => $val)
                                                        <div class="col-md-4 col-sm-6 mb-2">
                                                            <div class="form-check">
                                                                <input 
                                                                    class="form-check-input" 
                                                                    type="checkbox" 
                                                                    name="evening_snacks_menu[]" 
                                                                    value="{{ $val->category_name }}" 
                                                                    id="drink_{{ $key }}"
                                                                >
                                                                <label class="form-check-label" for="drink_{{ $key }}">
                                                                    {{ $val->category_name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-bold">Dinner Menu</label>
                                        <div class="row">
                                                    @foreach($dinner_menu as $key => $val)
                                                        <div class="col-md-4 col-sm-6 mb-2">
                                                            <div class="form-check">
                                                                <input 
                                                                    class="form-check-input" 
                                                                    type="checkbox" 
                                                                    name="evening_snacks_menu[]" 
                                                                    value="{{ $val->category_name }}" 
                                                                    id="drink_{{ $key }}"
                                                                >
                                                                <label class="form-check-label" for="drink_{{ $key }}">
                                                                    {{ $val->category_name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Conference - Morning Snacks</label>
                                        <div class="row">
                                                    @foreach($morning_snacks_menu as $key => $val)
                                                        <div class="col-md-4 col-sm-6 mb-2">
                                                            <div class="form-check">
                                                                <input 
                                                                    class="form-check-input" 
                                                                    type="checkbox" 
                                                                    name="evening_snacks_menu[]" 
                                                                    value="{{ $val->category_name }}" 
                                                                    id="drink_{{ $key }}"
                                                                >
                                                                <label class="form-check-label" for="drink_{{ $key }}">
                                                                    {{ $val->category_name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Conference - Evening Snacks</label>
                                         
                                        
                                        <div class="row">
                                                    @foreach($evening_snacks_menu as $key => $val)
                                                        <div class="col-md-4 col-sm-6 mb-2">
                                                            <div class="form-check">
                                                                <input 
                                                                    class="form-check-input" 
                                                                    type="checkbox" 
                                                                    name="evening_snacks_menu[]" 
                                                                    value="{{ $val->category_name }}" 
                                                                    id="drink_{{ $key }}"
                                                                >
                                                                <label class="form-check-label" for="drink_{{ $key }}">
                                                                    {{ $val->category_name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                    </div>
                                </div>
                            </div>

                          
                             
                             
                             
                             
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step d-inline-flex align-items-center gap-1">
                                    <i class="isax isax-arrow-left-3 me-1"></i> Back
                                </button>
                                <button type="button" class="btn btn-primary next-step d-inline-flex align-items-center gap-1">
                                    Next: Payment & Terms <i class="isax isax-arrow-right-3 ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3 - Payment & Terms -->
                <div class="step-content" data-step="3">
                    <div class="card checkout-card">
                        <div class="card-body">
                            <!-- Payment Information -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Payment Information</h6>
                            </div>
                            <div class="checkout-details pb-3 border-bottom mb-4">
                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label fw-bold">Payment Method</label>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method" value="cash" id="paymentCash">
                                                    <label class="form-check-label" for="paymentCash">Cash</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method" value="card" id="paymentCard">
                                                    <label class="form-check-label" for="paymentCard">Card</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer" id="paymentBank">
                                                    <label class="form-check-label" for="paymentBank">Bank Transfer</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method" value="cheque" id="paymentCheque">
                                                    <label class="form-check-label" for="paymentCheque">Cheque</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Details -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Bank Details (For Bank Transfer)</h6>
                            </div>
                            <div class="checkout-details pb-3 border-bottom mb-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Bank Name</label>
                                            <input type="text" class="form-control" name="bank_name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Branch</label>
                                            <input type="text" class="form-control" name="bank_branch">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Account Number</label>
                                            <input type="text" class="form-control" name="account_number">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">IFSC Code</label>
                                            <input type="text" class="form-control" name="ifsc_code">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Terms & Policies</h6>
                            </div>
                            <div class="checkout-details pb-3">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Payment Terms & Conditions</label>
                                        <textarea class="form-control" name="payment_terms" rows="3" placeholder="Enter payment terms and conditions"></textarea>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Cancellation Policy</label>
                                        <textarea class="form-control" name="cancellation_policy" rows="3" placeholder="Enter cancellation policy"></textarea>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Refund Policy</label>
                                        <textarea class="form-control" name="refund_policy" rows="3" placeholder="Enter refund policy"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step d-inline-flex align-items-center gap-1">
                                    <i class="isax isax-arrow-left-3 me-1"></i> Back
                                </button>
                                <button type="button" class="btn btn-primary next-step d-inline-flex align-items-center gap-1">
                                    Next: Company Info <i class="isax isax-arrow-right-3 ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4 - Company Information -->
                <div class="step-content" data-step="4">
                    <div class="card checkout-card">
                        <div class="card-body">
                            <!-- Company Information -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Company Information</h6>
                            </div>
                            <div class="checkout-details pb-3 border-bottom mb-4">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label required">Company Name</label>
                                        <input type="text" class="form-control" name="company_name" required>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label required">Company Address</label>
                                        <textarea class="form-control" name="company_address" rows="2" required></textarea>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label required">Company Phone Number 1</label>
                                        <input type="text" class="form-control" name="company_phone_1" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Company Phone Number 2</label>
                                        <input type="text" class="form-control" name="company_phone_2">
                                    </div>
                                </div>
                            </div>

                            <!-- Authorized Person -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Authorized Person Details</h6>
                            </div>
                            <div class="checkout-details pb-3 border-bottom mb-4">
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label required">Authorized Person Name</label>
                                        <input type="text" class="form-control" name="authorized_name" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label required">Designation</label>
                                        <input type="text" class="form-control" name="designation" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label required">Contact Number 1</label>
                                        <input type="text" class="form-control" name="authorized_contact_1" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Contact Number 2</label>
                                        <input type="text" class="form-control" name="authorized_contact_2">
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Authority -->
                            <div class="checkout-title">
                                <h6 class="mb-2">Payment Authority</h6>
                            </div>
                            <div class="checkout-details pb-3 border-bottom mb-4">
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Payment Authority Name</label>
                                        <input type="text" class="form-control" name="payment_authority_name">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Designation</label>
                                        <input type="text" class="form-control" name="payment_authority_designation">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Contact Number 1</label>
                                        <input type="text" class="form-control" name="payment_authority_contact_1">
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Contact Number 2</label>
                                        <input type="text" class="form-control" name="payment_authority_contact_2">
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="checkout-details pb-3">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Additional Information / Special Requests</label>
                                        <textarea class="form-control" name="additional_info" rows="4" placeholder="Any special requirements or additional information..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step d-inline-flex align-items-center gap-1">
                                    <i class="isax isax-arrow-left-3 me-1"></i> Back
                                </button>
                                <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-1">
                                    <i class="isax isax-tick-circle me-1"></i> Submit Enquiry
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>





                </div>
            </div>
            <!-- row end -->

        </div>
    </div>
@endsection

@section('script')

<script>
$(document).ready(function(){
    $(document).on('change','#country_id', function() {
        let country_id = $(this).val();
        $.ajax({
            method: 'post',
            url: "{{ route('get.state.name') }}",
            data: {country_id: country_id, _token: '{{csrf_token()}}'},
            success: function(res) {
                if (res!='') {
                    $('#state_id').html(res);
                }
            }
        });
    });
    
    $(document).on('change','#state_id', function() {
        let state_id = $(this).val();
        let country_id = $('#country_id').val();
        $.ajax({
            method: 'post',
            url: "{{ route('get.city.name') }}",
            data: {country_id: country_id, state_id: state_id, _token: '{{csrf_token()}}'},
            success: function(res) {
                if (res!='') {
                    $('#city_id').html(res);
                }
            }
        });
    });
});
</script>


    <script>
        $("#contact_form_content").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('contact') }}", new FormData($("#contact_form_content")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true, "{{ route('contact') }}", 1000);
                else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Submit');
                    for (var a in data['error']['message']) {
                        notify(null, data['error']['message'][a][0], 'botton left');
                        if (a == 'success' | a == 'error') notify(null, data['error']['message'][a][0],'botton left');
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
    
<script>
$(document).ready(function() {
    // Stepper navigation
    let currentStep = 1;
    const totalSteps = 4;

    // Next step
    $('.next-step').click(function() {
        if (validateStep(currentStep)) {
            $(`.step-content[data-step="${currentStep}"]`).removeClass('active');
            currentStep++;
            $(`.step-content[data-step="${currentStep}"]`).addClass('active');
            updateStepper();
        }
    });

    // Previous step
    $('.prev-step').click(function() {
        $(`.step-content[data-step="${currentStep}"]`).removeClass('active');
        currentStep--;
        $(`.step-content[data-step="${currentStep}"]`).addClass('active');
        updateStepper();
    });

    function updateStepper() {
        $('.stepper-item').removeClass('active completed');
        for(let i = 1; i <= totalSteps; i++) {
            if(i < currentStep) {
                $(`.stepper-item[data-step="${i}"]`).addClass('completed');
            } else if(i === currentStep) {
                $(`.stepper-item[data-step="${i}"]`).addClass('active');
            }
        }
    }

    function validateStep(step) {
        let isValid = true;
        $(`.step-content[data-step="${step}"] input[required], .step-content[data-step="${step}"] select[required]`).each(function() {
            if(!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if(!isValid) {
             notify(null, 'Please fill all required fields', 'top right');
        }
        return isValid;
    }

 

    // Number of people auto-calculate buses
    $('#numberOfPeople').change(function() {
        let people = $(this).val();
        let buses = Math.ceil(people / 40);
        $('#numberOfBuses').val(buses);
    });

    // Conference hall toggle
    $('#conferenceHall').change(function() {
        if($(this).val() === 'yes') {
            $('.conference-fields').show();
        } else {
            $('.conference-fields').hide();
        }
    });

    // Night party toggle
    $('#nightParty').change(function() {
        if($(this).val() === 'yes') {
            $('.party-fields').show();
        } else {
            $('.party-fields').hide();
        }
    });

    // Increment/Decrement functionality
    $('.btn-number').click(function(e) {
        e.preventDefault();
        let fieldName = $(this).attr('data-field');
        let type = $(this).attr('data-type');
        let input = $(this).closest('.input-group').find('input');
        let currentVal = parseInt(input.val());
        
        if(type === 'minus') {
            if(currentVal > 0) {
                input.val(currentVal - 1);
            }
        } else if(type === 'plus') {
            input.val(currentVal + 1);
        }
    });

    // Form submission
    $('#corporateTourForm').submit(function(e) {
        e.preventDefault();
        
        if(validateStep(currentStep)) {
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success) {
                        alert('Thank you! Your enquiry has been submitted successfully. We will contact you shortly.');
                        window.location.href = response.redirect_url;
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    });
});
</script>
    @stop