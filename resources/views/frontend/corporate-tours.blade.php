@extends('layouts.web')
@section('title', 'Phoenix Tours & Travels - Corporate Tours')
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

.is-invalid {
    border-color: #dc3545 !important;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
    position: relative;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.alert-dismissible {
    padding-right: 40px;
}

.alert-dismissible .btn-close {
    position: absolute;
    top: 10px;
    right: 10px;
    background: transparent;
    border: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0.5;
}

.alert-dismissible .btn-close:hover {
    opacity: 1;
}

.error-summary {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 20px;
}

.error-summary ul {
    margin: 0;
    padding-left: 20px;
    color: #721c24;
}
</style>
@endsection

@section('content')
<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Corporate Tours</h2>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 theiaStickySidebar">
                <div class="card">
                    <div class="card-body">
                        <div class="wizard-container">
                            
                            <!-- Display Validation Errors Summary -->
                            @if($errors->any())
                            <div class="error-summary">
                                <strong>Please fix the following errors:</strong>
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <!-- Display Session Messages -->
                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="isax isax-tick-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';">&times;</button>
                            </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="isax isax-close-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';">&times;</button>
                            </div>
                            @endif

                            <form id="corporateTourForm" method="POST" action="{{ route('corporate.tours') }}">
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
                                                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{ old('full_name') }}" required>
                                                            @error('full_name')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label required">Email Address</label>
                                                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                                            @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label required">Phone Number</label>
                                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                                                            @error('phone')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label required">Country</label>
                                                            <select name="country_id" id="country_id" class="form-control form-select @error('country_id') is-invalid @enderror" required>
                                                                <option value="">Select Country</option>
                                                                @foreach($country as $val)
                                                                <option value="{{$val->id}}" {{ old('country_id') == $val->id ? 'selected' : '' }}>{{$val->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('country_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label required">State</label>
                                                            <select class="form-control form-select @error('state_id') is-invalid @enderror" name="state_id" id="state_id" required>
                                                                <option value="">Select State</option>
                                                            </select>
                                                            @error('state_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label required">City</label>
                                                            <select class="form-control form-select @error('city_id') is-invalid @enderror" name="city_id" id="city_id" required>
                                                                <option value="">Select City</option>
                                                            </select>
                                                            @error('city_id')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label required">Address</label>
                                                            <input type="text" class="form-control @error('address') is-invalid @enderror" placeholder="Street address" name="address" value="{{ old('address') }}" required>
                                                            @error('address')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Apartment, Suite, House no (optional)</label>
                                                            <input type="text" class="form-control" placeholder="Apartment, Suite, House no" name="apartment_number" value="{{ old('apartment_number') }}">
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
                                                            <input type="text" class="form-control @error('starting_place') is-invalid @enderror" name="starting_place" placeholder="Enter starting city/location" value="{{ old('starting_place') }}" required>
                                                            @error('starting_place')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label required">Destination</label>
                                                            <input type="text" class="form-control @error('destination') is-invalid @enderror" name="destination" placeholder="Enter destination" value="{{ old('destination') }}" required>
                                                            @error('destination')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label required">Number of People</label>
                                                            <input type="number" class="form-control @error('number_of_people') is-invalid @enderror" name="number_of_people" id="numberOfPeople" min="1" value="{{ old('number_of_people') }}" required>
                                                            @error('number_of_people')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Number of Buses (30-40 Pax each bus)</label>
                                                            <input type="number" class="form-control" name="number_of_buses" id="numberOfBuses" min="0" step="1" value="{{ old('number_of_buses') }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Bus Type</label>
                                                            <select class="form-control form-select" name="bus_type">
                                                                <option value="">Select Bus Type</option>
                                                                <option value="non_ac" {{ old('bus_type') == 'non_ac' ? 'selected' : '' }}>Non AC</option>
                                                                <option value="ac" {{ old('bus_type') == 'ac' ? 'selected' : '' }}>AC</option>
                                                                <option value="both" {{ old('bus_type') == 'both' ? 'selected' : '' }}>Both</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Train Class</label>
                                                            <select class="form-control form-select" name="train_class">
                                                                <option value="">Select Train Class</option>
                                                                <option value="sleeper" {{ old('train_class') == 'sleeper' ? 'selected' : '' }}>Sleeper</option>
                                                                <option value="3rd_ac" {{ old('train_class') == '3rd_ac' ? 'selected' : '' }}>3rd AC</option>
                                                                <option value="2nd_ac" {{ old('train_class') == '2nd_ac' ? 'selected' : '' }}>2nd AC</option>
                                                                <option value="1st_ac" {{ old('train_class') == '1st_ac' ? 'selected' : '' }}>1st AC</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Flight Class</label>
                                                            <select class="form-control form-select" name="flight_class">
                                                                <option value="">Select Flight Class</option>
                                                                <option value="economy" {{ old('flight_class') == 'economy' ? 'selected' : '' }}>Economy</option>
                                                                <option value="business" {{ old('flight_class') == 'business' ? 'selected' : '' }}>Business</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label required">Preferred Date</label>
                                                            <input type="date" class="form-control @error('preferred_date') is-invalid @enderror" name="preferred_date" value="{{ old('preferred_date') }}" required>
                                                            @error('preferred_date')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Pickup Point</label>
                                                            <select class="form-control form-select" name="pickup_point">
                                                                <option value="">Select Pickup Point</option>
                                                                <option value="city" {{ old('pickup_point') == 'city' ? 'selected' : '' }}>City</option>
                                                                <option value="city_airport" {{ old('pickup_point') == 'city_airport' ? 'selected' : '' }}>City Airport</option>
                                                                <option value="railway_station" {{ old('pickup_point') == 'railway_station' ? 'selected' : '' }}>Railway Station</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Dropping Point</label>
                                                            <select class="form-control form-select" name="dropping_point">
                                                                <option value="">Select Dropping Point</option>
                                                                <option value="city" {{ old('dropping_point') == 'city' ? 'selected' : '' }}>City</option>
                                                                <option value="city_airport" {{ old('dropping_point') == 'city_airport' ? 'selected' : '' }}>City Airport</option>
                                                                <option value="railway_station" {{ old('dropping_point') == 'railway_station' ? 'selected' : '' }}>Railway Station</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Sightseeing</label>
                                                            <select class="form-control form-select" name="sightseeing">
                                                                <option value="">Select Sightseeing</option>
                                                                <option value="within_city" {{ old('sightseeing') == 'within_city' ? 'selected' : '' }}>Within the City</option>
                                                                <option value="out_of_city" {{ old('sightseeing') == 'out_of_city' ? 'selected' : '' }}>Out of the City</option>
                                                                <option value="both" {{ old('sightseeing') == 'both' ? 'selected' : '' }}>Both</option>
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
                                                                <input type="text" name="adults" class="input-number form-control text-center" value="{{ old('adults', 0) }}" readonly>
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
                                                                <input type="text" name="children" class="input-number form-control text-center" value="{{ old('children', 0) }}" readonly>
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
                                                                <input type="text" name="infants" class="input-number form-control text-center" value="{{ old('infants', 0) }}" readonly>
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
                                                                <option value="3_star" {{ old('hotel_category') == '3_star' ? 'selected' : '' }}>3 Star</option>
                                                                <option value="4_star" {{ old('hotel_category') == '4_star' ? 'selected' : '' }}>4 Star</option>
                                                                <option value="5_star" {{ old('hotel_category') == '5_star' ? 'selected' : '' }}>5 Star</option>
                                                                <option value="5_star_plus" {{ old('hotel_category') == '5_star_plus' ? 'selected' : '' }}>5 Star +</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Location Preference</label>
                                                            <select class="form-control form-select" name="hotel_location">
                                                                <option value="">Select Location</option>
                                                                <option value="near_beach" {{ old('hotel_location') == 'near_beach' ? 'selected' : '' }}>Near Beach</option>
                                                                <option value="city" {{ old('hotel_location') == 'city' ? 'selected' : '' }}>City</option>
                                                                <option value="city_outskirts" {{ old('hotel_location') == 'city_outskirts' ? 'selected' : '' }}>City Outskirts</option>
                                                                <option value="hill_front" {{ old('hotel_location') == 'hill_front' ? 'selected' : '' }}>Hill Front</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Number of Rooms</label>
                                                            <input type="number" class="form-control" name="number_of_rooms" min="1" value="{{ old('number_of_rooms') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Room Sharing Type</label>
                                                            <select class="form-control form-select" name="sharing_type">
                                                                <option value="">Select Sharing</option>
                                                                <option value="double" {{ old('sharing_type') == 'double' ? 'selected' : '' }}>Double Sharing</option>
                                                                <option value="triple" {{ old('sharing_type') == 'triple' ? 'selected' : '' }}>Triple Sharing</option>
                                                                <option value="quad" {{ old('sharing_type') == 'quad' ? 'selected' : '' }}>Quad Sharing</option>
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
                                                                <option value="no" {{ old('conference_hall') == 'no' ? 'selected' : '' }}>No</option>
                                                                <option value="yes" {{ old('conference_hall') == 'yes' ? 'selected' : '' }}>Yes</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="conference-fields" style="display: {{ old('conference_hall') == 'yes' ? 'block' : 'none' }};">
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Seating Capacity</label>
                                                                <select class="form-control form-select" name="seating_capacity">
                                                                    <option value="">Select Capacity</option>
                                                                    <option value="50" {{ old('seating_capacity') == '50' ? 'selected' : '' }}>50 Pax</option>
                                                                    <option value="100" {{ old('seating_capacity') == '100' ? 'selected' : '' }}>100 Pax</option>
                                                                    <option value="200" {{ old('seating_capacity') == '200' ? 'selected' : '' }}>200 Pax</option>
                                                                    <option value="300" {{ old('seating_capacity') == '300' ? 'selected' : '' }}>300 Pax</option>
                                                                    <option value="500" {{ old('seating_capacity') == '500' ? 'selected' : '' }}>500 Pax</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Stage Type</label>
                                                                <select class="form-control form-select" name="stage_type">
                                                                    <option value="normal" {{ old('stage_type') == 'normal' ? 'selected' : '' }}>Normal Stage</option>
                                                                    <option value="others" {{ old('stage_type') == 'others' ? 'selected' : '' }}>Others</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div class="mb-3">
                                                                <label class="form-label">Seating Arrangement</label>
                                                                <select class="form-control form-select" name="seating_arrangement">
                                                                    <option value="round_table" {{ old('seating_arrangement') == 'round_table' ? 'selected' : '' }}>Round Table</option>
                                                                    <option value="normal" {{ old('seating_arrangement') == 'normal' ? 'selected' : '' }}>Normal</option>
                                                                    <option value="others" {{ old('seating_arrangement') == 'others' ? 'selected' : '' }}>Others</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Equipment Required</label>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" name="equipment[]" value="music_system" id="musicSystem" {{ in_array('music_system', old('equipment', [])) ? 'checked' : '' }}>
                                                                            <label class="form-check-label" for="musicSystem">Music System</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" name="equipment[]" value="mice" id="mice" {{ in_array('mice', old('equipment', [])) ? 'checked' : '' }}>
                                                                            <label class="form-check-label" for="mice">MICE</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" name="equipment[]" value="digital_screen" id="digitalScreen" {{ in_array('digital_screen', old('equipment', [])) ? 'checked' : '' }}>
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
                                                                    <input class="form-check-input" type="checkbox" name="breakfast_menu[]" value="{{ $val->category_name }}" id="breakfast_{{ $key }}" {{ in_array($val->category_name, old('breakfast_menu', [])) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="breakfast_{{ $key }}">{{ $val->category_name }}</label>
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
                                                                    <input class="form-check-input" type="checkbox" name="lunch_menu[]" value="{{ $val->category_name }}" id="lunch_{{ $key }}" {{ in_array($val->category_name, old('lunch_menu', [])) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="lunch_{{ $key }}">{{ $val->category_name }}</label>
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
                                                                    <input class="form-check-input" type="checkbox" name="dinner_menu[]" value="{{ $val->category_name }}" id="dinner_{{ $key }}" {{ in_array($val->category_name, old('dinner_menu', [])) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="dinner_{{ $key }}">{{ $val->category_name }}</label>
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
                                                                    <input class="form-check-input" type="checkbox" name="morning_snacks_menu[]" value="{{ $val->category_name }}" id="morning_{{ $key }}" {{ in_array($val->category_name, old('morning_snacks_menu', [])) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="morning_{{ $key }}">{{ $val->category_name }}</label>
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
                                                                    <input class="form-check-input" type="checkbox" name="evening_snacks_menu[]" value="{{ $val->category_name }}" id="evening_{{ $key }}" {{ in_array($val->category_name, old('evening_snacks_menu', [])) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="evening_{{ $key }}">{{ $val->category_name }}</label>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Night Party & Drinks -->
                                            <div class="checkout-title">
                                                <h6 class="mb-2">Night Party & Drinks</h6>
                                            </div>
                                            <div class="checkout-details pb-3">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Night Party Required</label>
                                                            <select class="form-control form-select" name="night_party" id="nightParty">
                                                                <option value="no" {{ old('night_party') == 'no' ? 'selected' : '' }}>No</option>
                                                                <option value="yes" {{ old('night_party') == 'yes' ? 'selected' : '' }}>Yes</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="party-fields" style="display: {{ old('night_party') == 'yes' ? 'block' : 'none' }};">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Drinks Type</label>
                                                                <select class="form-control form-select" name="drinks_type">
                                                                    <option value="limited" {{ old('drinks_type') == 'limited' ? 'selected' : '' }}>Limited</option>
                                                                    <option value="unlimited" {{ old('drinks_type') == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            @foreach($drink_menu as $key => $val)
                                                            <div class="col-lg-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label">{{$val->category_name}} (Bottles)</label>
                                                                    <input type="number" class="form-control" name="drinks_bottles[{{ $val->category_name }}]" min="0" value="{{ old('drinks_bottles.'.$val->category_name, 0) }}">
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="mb-3">
                                                                <label class="form-label">Starters Menu</label>
                                                                <div class="row">
                                                                    @foreach($staters_menu as $key => $val)
                                                                    <div class="col-md-4 col-sm-6 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" name="starters_menu[]" value="{{ $val->category_name }}" id="starter_{{ $key }}" {{ in_array($val->category_name, old('starters_menu', [])) ? 'checked' : '' }}>
                                                                            <label class="form-check-label" for="starter_{{ $key }}">{{ $val->category_name }}</label>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
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
                                                                    <input class="form-check-input" type="radio" name="payment_method" value="cash" id="paymentCash" {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="paymentCash">Cash</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="payment_method" value="card" id="paymentCard" {{ old('payment_method') == 'card' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="paymentCard">Card</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="payment_method" value="bank_transfer" id="paymentBank" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="paymentBank">Bank Transfer</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="payment_method" value="cheque" id="paymentCheque" {{ old('payment_method') == 'cheque' ? 'checked' : '' }}>
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
                                                            <input type="text" class="form-control" name="bank_name" value="{{ old('bank_name') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Branch</label>
                                                            <input type="text" class="form-control" name="bank_branch" value="{{ old('bank_branch') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Account Number</label>
                                                            <input type="text" class="form-control" name="account_number" value="{{ old('account_number') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">IFSC Code</label>
                                                            <input type="text" class="form-control" name="ifsc_code" value="{{ old('ifsc_code') }}">
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
                                                        <textarea class="form-control" name="payment_terms" rows="3" placeholder="Enter payment terms and conditions">{{ old('payment_terms') }}</textarea>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Cancellation Policy</label>
                                                        <textarea class="form-control" name="cancellation_policy" rows="3" placeholder="Enter cancellation policy">{{ old('cancellation_policy') }}</textarea>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Refund Policy</label>
                                                        <textarea class="form-control" name="refund_policy" rows="3" placeholder="Enter refund policy">{{ old('refund_policy') }}</textarea>
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
                                                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" required>
                                                        @error('company_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label required">Company Address</label>
                                                        <textarea class="form-control @error('company_address') is-invalid @enderror" name="company_address" rows="2" required>{{ old('company_address') }}</textarea>
                                                        @error('company_address')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label required">Company Phone Number 1</label>
                                                        <input type="text" class="form-control @error('company_phone_1') is-invalid @enderror" name="company_phone_1" value="{{ old('company_phone_1') }}" required>
                                                        @error('company_phone_1')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">Company Phone Number 2</label>
                                                        <input type="text" class="form-control" name="company_phone_2" value="{{ old('company_phone_2') }}">
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
                                                        <input type="text" class="form-control @error('authorized_name') is-invalid @enderror" name="authorized_name" value="{{ old('authorized_name') }}" required>
                                                        @error('authorized_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label required">Designation</label>
                                                        <input type="text" class="form-control @error('designation') is-invalid @enderror" name="designation" value="{{ old('designation') }}" required>
                                                        @error('designation')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label required">Contact Number 1</label>
                                                        <input type="text" class="form-control @error('authorized_contact_1') is-invalid @enderror" name="authorized_contact_1" value="{{ old('authorized_contact_1') }}" required>
                                                        @error('authorized_contact_1')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">Contact Number 2</label>
                                                        <input type="text" class="form-control" name="authorized_contact_2" value="{{ old('authorized_contact_2') }}">
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
                                                        <input type="text" class="form-control" name="payment_authority_name" value="{{ old('payment_authority_name') }}">
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">Designation</label>
                                                        <input type="text" class="form-control" name="payment_authority_designation" value="{{ old('payment_authority_designation') }}">
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">Contact Number 1</label>
                                                        <input type="text" class="form-control" name="payment_authority_contact_1" value="{{ old('payment_authority_contact_1') }}">
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">Contact Number 2</label>
                                                        <input type="text" class="form-control" name="payment_authority_contact_2" value="{{ old('payment_authority_contact_2') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Additional Info -->
                                            <div class="checkout-details pb-3">
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Additional Information / Special Requests</label>
                                                        <textarea class="form-control" name="additional_info" rows="4" placeholder="Any special requirements or additional information...">{{ old('additional_info') }}</textarea>
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
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Country-State-City Ajax
    $(document).on('change','#country_id', function() {
        let country_id = $(this).val();
        $('#state_id').html('<option value="">Loading...</option>');
        $('#city_id').html('<option value="">Select City</option>');
        
        $.ajax({
            method: 'post',
            url: "{{ route('get.state.name') }}",
            data: {country_id: country_id, _token: '{{csrf_token()}}'},
            success: function(res) {
                if (res != '') {
                    $('#state_id').html(res);
                    @if(old('state_id'))
                        $('#state_id').val('{{ old('state_id') }}').trigger('change');
                    @endif
                } else {
                    $('#state_id').html('<option value="">Select State</option>');
                }
            },
            error: function() {
                $('#state_id').html('<option value="">Error loading states</option>');
            }
        });
    });
    
    $(document).on('change','#state_id', function() {
        let state_id = $(this).val();
        let country_id = $('#country_id').val();
        
        if(state_id) {
            $('#city_id').html('<option value="">Loading...</option>');
            $.ajax({
                method: 'post',
                url: "{{ route('get.city.name') }}",
                data: {country_id: country_id, state_id: state_id, _token: '{{csrf_token()}}'},
                success: function(res) {
                    if (res != '') {
                        $('#city_id').html(res);
                        @if(old('city_id'))
                            $('#city_id').val('{{ old('city_id') }}');
                        @endif
                    } else {
                        $('#city_id').html('<option value="">Select City</option>');
                    }
                },
                error: function() {
                    $('#city_id').html('<option value="">Error loading cities</option>');
                }
            });
        }
    });

    @if(old('country_id'))
        $('#country_id').trigger('change');
    @endif

    // Stepper navigation
    let currentStep = 1;
    const totalSteps = 4;

    $('.next-step').click(function() {
        if (validateStep(currentStep)) {
            $(`.step-content[data-step="${currentStep}"]`).removeClass('active');
            currentStep++;
            $(`.step-content[data-step="${currentStep}"]`).addClass('active');
            updateStepper();
            $('html, body').animate({
                scrollTop: $('.stepper-wrapper').offset().top - 100
            }, 300);
        }
    });

    $('.prev-step').click(function() {
        $(`.step-content[data-step="${currentStep}"]`).removeClass('active');
        currentStep--;
        $(`.step-content[data-step="${currentStep}"]`).addClass('active');
        updateStepper();
        $('html, body').animate({
            scrollTop: $('.stepper-wrapper').offset().top - 100
        }, 300);
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
        let errorMessages = [];
        
        $(`.step-content[data-step="${step}"] .is-invalid`).removeClass('is-invalid');
        $(`.step-content[data-step="${step}"] .invalid-feedback`).remove();
        
        $(`.step-content[data-step="${step}"] input[required], .step-content[data-step="${step}"] select[required], .step-content[data-step="${step}"] textarea[required]`).each(function() {
            let fieldValue = $(this).val();
            let labelText = $(this).closest('.mb-3, .form-group').find('label').text().replace('*', '').trim();
            
            if(!fieldValue || fieldValue === '') {
                $(this).addClass('is-invalid');
                $(this).after(`<div class="invalid-feedback">${labelText} is required</div>`);
                errorMessages.push(labelText + ' is required');
                isValid = false;
            }
        });
        
        $(`.step-content[data-step="${step}"] input[type="email"][required]`).each(function() {
            let emailValue = $(this).val();
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if(emailValue && !emailRegex.test(emailValue)) {
                $(this).addClass('is-invalid');
                $(this).after(`<div class="invalid-feedback">Please enter a valid email address</div>`);
                errorMessages.push('Please enter a valid email address');
                isValid = false;
            }
        });
        
        if(!isValid && errorMessages.length > 0) {
            showNotification('Validation Error', errorMessages.join('<br>'), 'error');
        }
        
        return isValid;
    }

    $('#numberOfPeople').on('input change', function() {
        let people = parseInt($(this).val()) || 0;
        let buses = Math.ceil(people / 40);
        $('#numberOfBuses').val(buses);
    });

    $('#conferenceHall').change(function() {
        if($(this).val() === 'yes') {
            $('.conference-fields').show();
        } else {
            $('.conference-fields').hide();
        }
    });

    $('#nightParty').change(function() {
        if($(this).val() === 'yes') {
            $('.party-fields').show();
        } else {
            $('.party-fields').hide();
        }
    });

    $(document).on('click', '.btn-number', function(e) {
        e.preventDefault();
        let type = $(this).attr('data-type');
        let input = $(this).closest('.input-group').find('input.input-number');
        let currentVal = parseInt(input.val()) || 0;
        
        if(type === 'minus') {
            if(currentVal > 0) {
                input.val(currentVal - 1);
            }
        } else if(type === 'plus') {
            input.val(currentVal + 1);
        }
    });

    // Form submission
    $('#corporateTourForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear any existing alerts
        $('.error-summary, .alert').remove();
        
        let allStepsValid = true;
        for(let step = 1; step <= totalSteps; step++) {
            if(!validateStep(step)) {
                allStepsValid = false;
                $(`.step-content[data-step="${currentStep}"]`).removeClass('active');
                currentStep = step;
                $(`.step-content[data-step="${currentStep}"]`).addClass('active');
                updateStepper();
                $('html, body').animate({
                    scrollTop: $('.stepper-wrapper').offset().top - 100
                }, 300);
                break;
            }
        }
        
        if(allStepsValid) {
            let submitBtn = $(this).find('button[type="submit"]');
            let originalText = submitBtn.html();
            submitBtn.prop('disabled', true);
            submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('Success Response:', response);
                    
                    let successMsg = 'Your corporate tour enquiry has been submitted successfully!';
                    if (response.message) {
                        if (typeof response.message === 'string') {
                            successMsg = response.message;
                        } else if (response.message.success) {
                            successMsg = Array.isArray(response.message.success) ? response.message.success[0] : response.message.success;
                        }
                    }
                    
                    // Show success message
                    showNotification('Success!', successMsg, 'success');
                    
                    // Display success alert at top of form
                    $('.wizard-container').prepend(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="isax isax-tick-circle me-2"></i> ${successMsg}
                            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';">&times;</button>
                        </div>
                    `);
                    
                    // Reset form
                    $('#corporateTourForm')[0].reset();
                    $('.input-number').val('0');
                    $('.conference-fields').hide();
                    $('.party-fields').hide();
                    
                    // Reset to first step
                    $(`.step-content[data-step="${currentStep}"]`).removeClass('active');
                    currentStep = 1;
                    $(`.step-content[data-step="${currentStep}"]`).addClass('active');
                    updateStepper();
                    
                    $('html, body').animate({
                        scrollTop: $('.breadcrumb-bar').offset().top
                    }, 300);
                },
                error: function(xhr, status, error) {
                    console.log('Error Response:', xhr);
                    let errorMsg = '';
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            errorMsg = '<strong>Please fix the following errors:</strong><ul>';
                            
                            $.each(errors, function(key, value) {
                                errorMsg += '<li>' + value[0] + '</li>';
                                
                                // Highlight invalid fields
                                let field = $(`[name="${key}"]`);
                                if(field.length) {
                                    field.addClass('is-invalid');
                                    if(field.next('.invalid-feedback').length === 0) {
                                        field.after(`<div class="invalid-feedback">${value[0]}</div>`);
                                    }
                                    
                                    // Switch to the step containing the error
                                    let errorStep = field.closest('.step-content').data('step');
                                    if(errorStep && errorStep !== currentStep) {
                                        $(`.step-content[data-step="${currentStep}"]`).removeClass('active');
                                        currentStep = errorStep;
                                        $(`.step-content[data-step="${currentStep}"]`).addClass('active');
                                        updateStepper();
                                    }
                                }
                            });
                            errorMsg += '</ul>';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else {
                            errorMsg = 'Validation failed. Please check your inputs.';
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else {
                        errorMsg = 'An error occurred. Please try again.';
                    }
                    
                    // Display error summary
                    $('.wizard-container').prepend(`
                        <div class="error-summary alert alert-danger">
                            ${errorMsg}
                            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none';">&times;</button>
                        </div>
                    `);
                    
                    showNotification('Error', 'Please check the form for errors.', 'error');
                    
                    // Scroll to error summary
                    $('html, body').animate({
                        scrollTop: $('.error-summary').offset().top - 100
                    }, 300);
                },
                complete: function() {
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        }
    });
    
    $(document).on('input change', '.form-control, .form-select', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });

    // Notification function
    function showNotification(title, message, type) {
        if(typeof toastr !== 'undefined') {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": 5000
            };
            
            if(type === 'success') {
                toastr.success(message, title || 'Success');
            } else if(type === 'error') {
                toastr.error(message, title || 'Error');
            } else if(type === 'warning') {
                toastr.warning(message, title || 'Warning');
            } else {
                toastr.info(message, title || 'Info');
            }
        } 
    }
});
</script>
@endsection