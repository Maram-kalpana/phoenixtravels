@extends('layouts.web')
@section('title', 'Booking info | Phoenix Tours & Travels')
@section('css')
<style>
    .coupon-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    .coupon-input {
        border-radius: 8px;
        height: 45px;
    }

    .apply-btn {
        border-radius: 8px;
        padding: 0 20px;
        height: 45px;
    }

    .coupon-success {
        background: #e9f9ef;
        color: #1ca64c;
        border-radius: 8px;
        padding: 15px;
        font-size: 14px;
    }

    .coupon-error {
        background: #fdeaea;
        color: #dc3545;
        border-radius: 8px;
        padding: 15px;
        font-size: 14px;
    }

    .coupon-badge {
        background: #1ca64c;
        color: #fff;
        border-radius: 20px;
        padding: 5px 12px;
        font-weight: 500;
        font-size: 13px;
    }

    .coupon-badge-error {
        background: #dc3545;
        color: #fff;
        border-radius: 20px;
        padding: 5px 12px;
        font-weight: 500;
        font-size: 13px;
    }

    .remove-coupon-btn {
        color: #dc3545;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
    }

    .remove-coupon-btn:hover {
        color: #c82333;
        text-decoration: none;
    }

    .close-coupon-btn {
        color: #dc3545;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
    }

    .close-coupon-btn:hover {
        color: #c82333;
        text-decoration: none;
    }

    .coupon-message-container {
        margin-top: 15px;
    }

    /* New coupon list styles */
    .available-coupons {
        margin-top: 20px;
    }
    
    .coupon-item {
        background: #fff;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .coupon-item:hover {
        border-color: #1d9cd0;
        box-shadow: 0 2px 8px rgba(29, 156, 208, 0.1);
        transform: translateY(-2px);
    }
    
    .coupon-code-badge {
        background: #f3f4f6;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
        color: #374151;
        font-family: monospace;
        letter-spacing: 0.5px;
        font-weight: 700;
    }
    
    .discount-amount {
        font-size: 16px;
        font-weight: 700;
    }
    
    .apply-coupon-btn {
        padding: 5px 15px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    
    .apply-coupon-btn:hover {
        background: #1d9cd0;
        color: #fff;
        border-color: #1d9cd0;
    }
    
    .available-coupons {
        max-height: 450px;
        overflow-y: auto;
    }
    
    .available-coupons::-webkit-scrollbar {
        width: 4px;
    }
    
    .available-coupons::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }
    
    .available-coupons::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 2px;
    }
    
    .available-coupons h6 {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
    }
    
    .coupon-description small {
        font-size: 11px;
        line-height: 1.4;
        display: block;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #1d9cd0;
        display: inline-block;
    }
    
    /* Loading spinner for coupon button */
    .coupon-btn-loading {
        pointer-events: none;
        opacity: 0.7;
    }
    
    .coupon-btn-loading .spinner-border {
        width: 14px;
        height: 14px;
        margin-right: 5px;
    }
    
    @media (max-width: 768px) {
        .coupon-item {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .apply-coupon-btn {
            margin-top: 10px;
            margin-left: 0 !important;
            width: 100%;
        }
        
        .coupon-info {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
@php
$res = $payment->adults_count + $payment->children_count ?? 0;
$gst_amount = get_gst_cal($total_tour_cost);
$booking_fees = booking_fees($gst_amount,$gst_amount);
$original_sub_total = $total_tour_cost;
$original_tax = calculateCutoff($total_tour_cost, 5);
$original_final_total = $original_sub_total + $original_tax;
@endphp

<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Tour Booking</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="isax isax-home5"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tour Booking</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Tour Booking Submission Areas -->
<div class="content content-two">
    <div class="container">
        <div class="row">
            <!-- Checkout -->
            <div class="col-lg-8">
                <form id="submit_info" method="POST">
                    @csrf
                    <div class="card checkout-card">
                        <div class="card-header">
                            <h5>Secure Checkout</h5>
                        </div>
                        <div class="card-body">
                            <!-- Adults Section -->
                            @for($i = 1; $i <= $payment->adults_count; $i++)
                                <div id="tour_bookking_form_item_adult{{ $i }}">
                                    <h4>Traveller {{ $i }} <span style="font-size:small;">*</span></h4><br>
                            
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <input type="text" 
                                                    class="form-control bg_input"
                                                    placeholder="First name*"
                                                    name="adults[{{ $i }}][first]"
                                                    id="adult_first_{{ $i }}">
                                                <span class="text-danger error-adult-first-{{ $i }}"></span>
                                            </div>
                                        </div>
                            
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <input type="text"
                                                    class="form-control bg_input"
                                                    placeholder="Last name*"
                                                    name="adults[{{ $i }}][last]"
                                                    id="adult_last_{{ $i }}">
                                                <span class="text-danger error-adult-last-{{ $i }}"></span>
                                            </div>
                                        </div>
                            
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <input type="number"
                                                    class="form-control bg_input"
                                                    placeholder="Age*"
                                                    name="adults[{{ $i }}][age]"
                                                    id="adult_age_{{ $i }}"
                                                    min="1"
                                                    max="120">
                                                <span class="text-danger error-adult-age-{{ $i }}"></span>
                                            </div>
                                        </div>
                            
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <select class="form-select form-control bg_input"
                                                    name="adults[{{ $i }}][gender]"
                                                    id="adult_gender_{{ $i }}">
                                                    <option value="">Please Select</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                                <span class="text-danger error-adult-gender-{{ $i }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            @endfor

                            <!-- Children Section -->
                            @if($payment->children_count > 0)
                            <h4>Children</h4><br>
                            @endif
                            @for($i = 1; $i <= $payment->children_count; $i++)
                                <div id="tour_bookking_form_item_child{{ $i }}">
                                    <h4>Child {{ $i }} <span style="font-size:small;">*</span></h4><br>
                            
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <input type="text"
                                                    class="form-control bg_input"
                                                    placeholder="First name*"
                                                    name="children[{{ $i }}][first]"
                                                    id="child_first_{{ $i }}">
                                                <span class="text-danger error-child-first-{{ $i }}"></span>
                                            </div>
                                        </div>
                            
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <input type="text"
                                                    class="form-control bg_input"
                                                    placeholder="Last name*"
                                                    name="children[{{ $i }}][last]"
                                                    id="child_last_{{ $i }}">
                                                <span class="text-danger error-child-last-{{ $i }}"></span>
                                            </div>
                                        </div>
                            
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <input type="number"
                                                    class="form-control bg_input"
                                                    placeholder="Age*"
                                                    name="children[{{ $i }}][age]"
                                                    id="child_age_{{ $i }}"
                                                    min="1"
                                                    max="17">
                                                <span class="text-danger error-child-age-{{ $i }}"></span>
                                            </div>
                                        </div>
                            
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <select class="form-select form-control bg_input"
                                                    name="children[{{ $i }}][gender]"
                                                    id="child_gender_{{ $i }}">
                                                    <option value="">Please Select</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                                <span class="text-danger error-child-gender-{{ $i }}"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            @endfor
                            
                            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                            <input type="hidden" name="sub_total" id="sub_total" value="{{ $total_tour_cost }}">
                            <input type="hidden" name="gst_amount" id="gst_amount" value="{{calculateCutoff($total_tour_cost, 5)}}">
                            <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                            <input type="hidden" name="coupon_code" id="applied_coupon_code" value="">
                            <input type="hidden" name="discount_amount" id="applied_discount_amount" value="0">
                        </div>
                        <div class="booking_tour_form mt-3 card-body">
                            <h3 class="heading_theme">Please enter contact details</h3>
                            <div class="tour_booking_form_box">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control bg_input"
                                                placeholder="Email address*" name="mail" value="{{$customer_details->email}}" required>
                                            <span class="text-danger error-email"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control bg_input"
                                                placeholder="Mobile number*" value="{{$customer_details->phone}}" pattern="[0-9+ ]{10,14}" name="phone" required>
                                            <span class="text-danger error-phone"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control bg_input"
                                                placeholder="Street address" name="address" value="{{$customer_details->address}}" required>
                                            <span class="text-danger error-address"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control bg_input"
                                                placeholder="Apartment, Suite, House no (optional)" name="apartment_number" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <select name="country_id" id="country_id" class="form-control form-select bg_input">
                                                <option value="">Select Country</option>
                                                @foreach($country as $val)
                                                    <option value="{{ $val->id }}" 
                                                        {{ (isset($data) && $customer_details->country_id == $val->id) ? 'selected' : '' }}>
                                                        {{ $val->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger error-country"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <select class="form-control form-select bg_input state_id" name="state_id" id="state_id" required>
                                                <option value="">State</option>
                                            </select>
                                            <span class="text-danger error-state"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <select class="form-control form-select bg_input city_id" name="city_id" id="city_id">
                                                <option value="">City</option>
                                            </select>
                                            <span class="text-danger error-city"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Coupon Section -->
                    <!-- Coupon Section - Redesigned to match image -->
<div class="card coupon-card mt-4">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0 fw-semibold">Have a Coupon Code?</h5>
    </div>
    <div class="card-body">
        <!-- Input -->
        <div class="d-flex gap-2 mb-4">
            <input type="text" 
                   class="form-control coupon-input" 
                   id="coupon_code_input"
                   placeholder="Enter Code">
            <button class="btn btn-primary apply-btn" 
                    type="button" 
                    id="apply_coupon_btn">
                APPLY
            </button>
        </div>

        <!-- Available Coupons List -->
        <div class="available-coupons">
            <h6 class="section-title">Available Coupons</h6>
            <div class="row">
                @php
                    // Calculate discount amount for each coupon based on type
                    $basePrice = $with_out_attractions_cost ?? 0;
                @endphp
                
                @foreach($coupon_list as $key => $val)
                <div class="col-md-6">
                    <div class="coupon-item d-flex justify-content-between align-items-center p-3 mb-3 rounded border" 
                         data-coupon-code="{{ $val->coupons_code }}" 
                         data-coupon-discount="{{ $val->discount }}"
                         data-coupon-type="{{ $val->type }}">
                        <div class="coupon-info flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="coupon-code-badge fw-bold">{{ $val->coupons_code }}</span>
                                @php
                                    $displayDiscount = 0;
                                    if(strtoupper($val->type) == 'P') {
                                        $displayDiscount = ($basePrice * $val->discount) / 100;
                                        $displayDiscount = round($displayDiscount, 2);
                                    } else {
                                        $displayDiscount = $val->discount;
                                    }
                                @endphp
                                <span class="discount-amount text-success fw-bold">- ₹{{ $displayDiscount }}</span>
                                @if(strtoupper($val->type) == 'P')
                                <span class="badge bg-light text-dark">({{ $val->discount }}% OFF)</span>
                                @endif
                            </div>
                            <div class="coupon-description">
                                <small class="text-muted">{{ $val->coupons_description ?: 'Special discount for you!' }}</small>
                            </div>
                            @if($val->total_amount > 0)
                            <div class="mt-1">
                                <small class="text-muted">Min. Order: ₹{{ $val->total_amount }}</small>
                            </div>
                            @endif
                        </div>
                        <button class="btn btn-sm btn-outline-primary apply-coupon-btn ms-3" 
                                data-code="{{ $val->coupons_code }}"
                                data-discount="{{ $displayDiscount }}"
                                data-type="{{ $val->type }}">
                            APPLY
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Coupon Messages Container -->
        <div id="coupon_message_container" class="coupon-message-container mt-3"></div>
    </div>
</div>

                    <input type="hidden" name="total_amount" id="total_amount_field" value="{{$original_final_total}}">
                    <div class="card payment-details">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-2">
                                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                                <button type="button" class="btn btn-primary buy-btn" id="payBtn">
                                    Confirm & Pay ₹<span id="pay_button_amount">{{$original_final_total}}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Checkout -->

            <!-- Review Order Details -->
            <div class="col-lg-4 theiaStickySidebar">
                <div class="card order-details">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between header-content">
                            <h5>Review Order Details</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="pb-3 border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-2">{{getPackageName($payment->package_id)}}</h6>
                                    <p class="fs-14 "></p>
                                </div>
                                <h6 class="fs-14 fw-normal text-gray-9">₹{{ $total_tour_cost }}.00</h6>
                            </div>
                        </div>
                        <div class="mt-3 pb-3 border-bottom">
                            <h6 class="text-primary mb-3">Order Info</h6>
                            <div class="d-flex align-items-center details-info">
                                <h6 class="fs-16">Departure</h6>
                                <p class="fs-16">{{ \Carbon\Carbon::parse($payment->from_date)->format('d M Y') }}</p>
                            </div>
                            <div class="d-flex align-items-center details-info">
                                <h6 class="fs-16">Return</h6>
                                <p class="fs-16">{{ \Carbon\Carbon::parse($payment->to_date)->format('d M Y') }}</p>
                            </div>
                            <div class="d-flex align-items-center  details-info">
                                <h6 class="fs-16">Adults</h6>
                                <p class="fs-16">{{$payment->adults_count}}</p>
                            </div>
                            <div class="d-flex align-items-center  details-info">
                                <h6 class="fs-16">Children</h6>
                                <p class="fs-16">{{ $payment->children_count ? $payment->children_count.' Children' : '0' }}</p>
                            </div>
                            <div class="d-flex align-items-center details-info">
                                <h6 class="fs-16">No of Days</h6>
                                <p class="fs-16">{{getPackageinfo($payment->package_id)['nights_count']}}Nights / {{getPackageinfo($payment->package_id)['days_count']}}Days</p>
                            </div>
                        </div>
                        <div class="mt-3 border-bottom">
                            <h6 class="text-primary mb-3">Payment Info</h6>
                            
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fs-16">Sub Total</h6>
                                <p class="fs-16">₹{{ $total_tour_cost }}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fs-16">Tax <span class="text-gray-6"> (5%)</span></h6>
                                <p class="fs-16">₹{{calculateCutoff($total_tour_cost, 5)}}</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-3" id="discount_row" style="display: none;">
                                <h6 class="fs-16">Discount</h6>
                                <p class="fs-16 text-success" id="discount_amount">-₹0</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>Amount to Pay</h6>
                                <h6 class="text-primary" id="final_total">₹{{$original_final_total}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Review Order Details -->
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// Global variables
let appliedCoupon = null;
let originalSubTotal = {{ $total_tour_cost }};
let originalTax = {{ calculateCutoff($total_tour_cost, 5) }};
let originalFinalTotal = {{ $original_final_total }};
let currentTotal = originalFinalTotal;

// Setup CSRF token for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Validation Function for Traveler Details
function validateForm() {
    let isValid = true;
    let errorMessages = [];
    
    // Clear all previous error messages
    $('.text-danger').html('');
    
    // Validate Adults
    let adultCount = {{ $payment->adults_count }};
    if(adultCount > 0) {
        for(let i = 1; i <= adultCount; i++) {
            let firstName = $(`#adult_first_${i}`).val().trim();
            let lastName = $(`#adult_last_${i}`).val().trim();
            let age = $(`#adult_age_${i}`).val();
            let gender = $(`#adult_gender_${i}`).val();
            
            if(firstName === '') {
                $(`.error-adult-first-${i}`).html('First name is required');
                isValid = false;
                errorMessages.push(`Adult ${i}: First name is required`);
            } else if(firstName.length < 2) {
                $(`.error-adult-first-${i}`).html('First name must be at least 2 characters');
                isValid = false;
            } else if(!/^[a-zA-Z\s]+$/.test(firstName)) {
                $(`.error-adult-first-${i}`).html('First name should contain only letters');
                isValid = false;
            }
            
            if(lastName === '') {
                $(`.error-adult-last-${i}`).html('Last name is required');
                isValid = false;
                errorMessages.push(`Adult ${i}: Last name is required`);
            } else if(lastName.length < 2) {
                $(`.error-adult-last-${i}`).html('Last name must be at least 2 characters');
                isValid = false;
            } else if(!/^[a-zA-Z\s]+$/.test(lastName)) {
                $(`.error-adult-last-${i}`).html('Last name should contain only letters');
                isValid = false;
            }
            
            if(age === '') {
                $(`.error-adult-age-${i}`).html('Age is required');
                isValid = false;
                errorMessages.push(`Adult ${i}: Age is required`);
            } else if(age < 18) {
                $(`.error-adult-age-${i}`).html('Adult age must be 18 or above');
                isValid = false;
                errorMessages.push(`Adult ${i}: Age must be 18 or above`);
            } else if(age > 120) {
                $(`.error-adult-age-${i}`).html('Invalid age');
                isValid = false;
            }
            
            if(gender === '') {
                $(`.error-adult-gender-${i}`).html('Gender is required');
                isValid = false;
                errorMessages.push(`Adult ${i}: Gender is required`);
            }
        }
    }
    
    // Validate Children
    let childCount = {{ $payment->children_count }};
    if(childCount > 0) {
        for(let i = 1; i <= childCount; i++) {
            let firstName = $(`#child_first_${i}`).val().trim();
            let lastName = $(`#child_last_${i}`).val().trim();
            let age = $(`#child_age_${i}`).val();
            let gender = $(`#child_gender_${i}`).val();
            
            if(firstName === '') {
                $(`.error-child-first-${i}`).html('First name is required');
                isValid = false;
                errorMessages.push(`Child ${i}: First name is required`);
            } else if(firstName.length < 2) {
                $(`.error-child-first-${i}`).html('First name must be at least 2 characters');
                isValid = false;
            }
            
            if(lastName === '') {
                $(`.error-child-last-${i}`).html('Last name is required');
                isValid = false;
                errorMessages.push(`Child ${i}: Last name is required`);
            } else if(lastName.length < 2) {
                $(`.error-child-last-${i}`).html('Last name must be at least 2 characters');
                isValid = false;
            }
            
            if(age === '') {
                $(`.error-child-age-${i}`).html('Age is required');
                isValid = false;
                errorMessages.push(`Child ${i}: Age is required`);
            } else if(age < 1) {
                $(`.error-child-age-${i}`).html('Age must be at least 1');
                isValid = false;
            } else if(age > 17) {
                $(`.error-child-age-${i}`).html('Child age must be 17 or below');
                isValid = false;
            }
            
            if(gender === '') {
                $(`.error-child-gender-${i}`).html('Gender is required');
                isValid = false;
                errorMessages.push(`Child ${i}: Gender is required`);
            }
        }
    }
    
    // Show error if any
    if(!isValid && errorMessages.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: errorMessages.join('<br>'),
            confirmButtonColor: '#1d9cd0'
        });
    }
    
    return isValid;
}

// Validate Contact Details
function validateContactDetails() {
    let email = $('input[name="mail"]').val().trim();
    let phone = $('input[name="phone"]').val().trim();
    let address = $('input[name="address"]').val().trim();
    let country = $('#country_id').val();
    let state = $('#state_id').val();
    let city = $('#city_id').val();
    
    // Clear previous errors
    $('.error-email, .error-phone, .error-address, .error-country, .error-state, .error-city').html('');
    
    // Validate Email
    if(email === '') {
        $('.error-email').html('Email address is required');
        $('input[name="mail"]').focus();
        Swal.fire({
            icon: 'error',
            title: 'Missing Information',
            text: 'Please enter your email address',
            confirmButtonColor: '#1d9cd0'
        });
        return false;
    }
    
    if(!isValidEmail(email)) {
        $('.error-email').html('Please enter a valid email address');
        $('input[name="mail"]').focus();
        Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid email address (e.g., name@example.com)',
            confirmButtonColor: '#1d9cd0'
        });
        return false;
    }
    
    // Validate Phone
    if(phone === '') {
        $('.error-phone').html('Mobile number is required');
        $('input[name="phone"]').focus();
        Swal.fire({
            icon: 'error',
            title: 'Missing Information',
            text: 'Please enter your mobile number',
            confirmButtonColor: '#1d9cd0'
        });
        return false;
    }
    
    let phoneRegex = /^[0-9+\-\s]{10,15}$/;
    if(!phoneRegex.test(phone)) {
        $('.error-phone').html('Please enter a valid mobile number (10-15 digits)');
        $('input[name="phone"]').focus();
        Swal.fire({
            icon: 'error',
            title: 'Invalid Phone',
            text: 'Please enter a valid mobile number (10-15 digits)',
            confirmButtonColor: '#1d9cd0'
        });
        return false;
    }
    
    // Validate Address
    if(address === '') {
        $('.error-address').html('Street address is required');
        $('input[name="address"]').focus();
        Swal.fire({
            icon: 'error',
            title: 'Missing Information',
            text: 'Please enter your street address',
            confirmButtonColor: '#1d9cd0'
        });
        return false;
    }
    
    if(address.length < 3) {
        $('.error-address').html('Address must be at least 3 characters');
        $('input[name="address"]').focus();
        Swal.fire({
            icon: 'error',
            title: 'Invalid Address',
            text: 'Address must be at least 3 characters',
            confirmButtonColor: '#1d9cd0'
        });
        return false;
    }
    
    // Validate Country
    if(country === '' || country === null) {
        $('.error-country').html('Please select a country');
        $('#country_id').focus();
        Swal.fire({
            icon: 'error',
            title: 'Missing Information',
            text: 'Please select your country',
            confirmButtonColor: '#1d9cd0'
        });
        return false;
    }
    
    // Validate State
    if(state === '' || state === null) {
        $('.error-state').html('Please select a state');
        $('#state_id').focus();
        Swal.fire({
            icon: 'error',
            title: 'Missing Information',
            text: 'Please select your state',
            confirmButtonColor: '#1d9cd0'
        });
        return false;
    }
    
    // Validate City
    if(city === '' || city === null) {
        $('.error-city').html('Please select a city');
        $('#city_id').focus();
        Swal.fire({
            icon: 'error',
            title: 'Missing Information',
            text: 'Please select your city',
            confirmButtonColor: '#1d9cd0'
        });
        return false;
    }
    
    return true;
}

// Email validation helper function
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/;
    return emailRegex.test(email);
}

// Real-time validation on input change
$(document).ready(function(){
    // Adult fields real-time validation
    @for($i = 1; $i <= $payment->adults_count; $i++)
        $(`#adult_first_${$i}`).on('input', function() {
            if($(this).val().trim() !== '') {
                $(`.error-adult-first-${$i}`).html('');
            }
        });
        $(`#adult_last_${$i}`).on('input', function() {
            if($(this).val().trim() !== '') {
                $(`.error-adult-last-${$i}`).html('');
            }
        });
        $(`#adult_age_${$i}`).on('input', function() {
            let age = $(this).val();
            if(age >= 18 && age <= 120) {
                $(`.error-adult-age-${$i}`).html('');
            }
        });
        $(`#adult_gender_${$i}`).on('change', function() {
            if($(this).val() !== '') {
                $(`.error-adult-gender-${$i}`).html('');
            }
        });
    @endfor
    
    // Child fields real-time validation
    @for($i = 1; $i <= $payment->children_count; $i++)
        $(`#child_first_${$i}`).on('input', function() {
            if($(this).val().trim() !== '') {
                $(`.error-child-first-${$i}`).html('');
            }
        });
        $(`#child_last_${$i}`).on('input', function() {
            if($(this).val().trim() !== '') {
                $(`.error-child-last-${$i}`).html('');
            }
        });
        $(`#child_age_${$i}`).on('input', function() {
            let age = $(this).val();
            if(age >= 1 && age <= 17) {
                $(`.error-child-age-${$i}`).html('');
            }
        });
        $(`#child_gender_${$i}`).on('change', function() {
            if($(this).val() !== '') {
                $(`.error-child-gender-${$i}`).html('');
            }
        });
    @endfor
    
    // Contact fields real-time validation
    $('input[name="mail"]').on('input', function() {
        if($(this).val().trim() !== '') {
            $('.error-email').html('');
        }
    });
    
    $('input[name="phone"]').on('input', function() {
        if($(this).val().trim() !== '') {
            $('.error-phone').html('');
        }
    });
    
    $('input[name="address"]').on('input', function() {
        if($(this).val().trim() !== '') {
            $('.error-address').html('');
        }
    });
    
    $('#country_id').on('change', function() {
        if($(this).val() !== '') {
            $('.error-country').html('');
        }
    });
    
    $('#state_id').on('change', function() {
        if($(this).val() !== '') {
            $('.error-state').html('');
        }
    });
    
    $('#city_id').on('change', function() {
        if($(this).val() !== '') {
            $('.error-city').html('');
        }
    });
});

// Apply coupon from list
$(document).on('click', '.apply-coupon-btn', function(e) {
    e.stopPropagation();
    let couponCode = $(this).data('code');
    
    // Set the coupon code in input field
    $('#coupon_code_input').val(couponCode);
    
    // Show loading state on the clicked button
    let $btn = $(this);
    let originalText = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Applying...');
    
    // Make AJAX request to apply coupon
    $.ajax({
        url: "{{ route('apply.coupon.ajax') }}",
        method: "POST",
        data: {
            coupon_code: couponCode,
            price: {{ $with_out_attractions_cost ?? 0 }},
            attractions_cost: {{ $attractions_cost ?? 0 }},
            person_count: {{ $person_count ?? 0 }},
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.status === true) {
                // Update global variables
                appliedCoupon = couponCode;
                currentTotal = response.new_total;
                
                // Update hidden fields
                $('#applied_coupon_code').val(couponCode);
                $('#applied_discount_amount').val(response.discount);
                $('#total_amount_field').val(response.new_total);
                
                // Update payment info display
                $('#discount_row').show();
                $('#discount_amount').text('-₹' + response.discount);
                $('#final_total').text('₹' + response.new_total);
                $('#pay_button_amount').text(response.new_total);
                
                // Clear any existing messages
                $('#coupon_message_container').empty();
                
                // Show success message
                let successHtml = `
                    <div class="coupon-success d-flex justify-content-between align-items-center">
                        <div>
                            <span class="coupon-badge">${couponCode}</span> Applied!
                            <br>
                            <small>You saved ₹${response.discount}</small>
                        </div>
                        <a href="#" class="remove-coupon-btn" onclick="removeCoupon(event)">REMOVE</a>
                    </div>
                `;
                $('#coupon_message_container').html(successHtml);
                $('#coupon_code_input').val('');
                
                // Show success toast
                Swal.fire({
                    icon: 'success',
                    title: 'Coupon Applied!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                // Show error message
                let errorHtml = `
                    <div class="coupon-error d-flex justify-content-between align-items-center">
                        <div>
                            <span class="coupon-badge-error">${couponCode}</span>
                            <small>${response.message}</small>
                        </div>
                        <a href="#" class="close-coupon-btn" onclick="closeCouponError(event)">CLOSE</a>
                    </div>
                `;
                $('#coupon_message_container').html(errorHtml);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Coupon',
                    text: response.message,
                    confirmButtonColor: '#1d9cd0'
                });
            }
        },
        error: function(xhr) {
            let errorMsg = 'Something went wrong. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            
            let errorHtml = `
                <div class="coupon-error d-flex justify-content-between align-items-center">
                    <div>
                        <span class="coupon-badge-error">${couponCode}</span>
                        <small>${errorMsg}</small>
                    </div>
                    <a href="#" class="close-coupon-btn" onclick="closeCouponError(event)">CLOSE</a>
                </div>
            `;
            $('#coupon_message_container').html(errorHtml);
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMsg,
                confirmButtonColor: '#1d9cd0'
            });
        },
        complete: function() {
            // Reset button
            $btn.prop('disabled', false).html(originalText);
        }
    });
});

// Also allow clicking on the entire coupon item
$(document).on('click', '.coupon-item', function(e) {
    // Don't trigger if clicking on the apply button
    if (!$(e.target).closest('.apply-coupon-btn').length) {
        let couponCode = $(this).data('coupon-code');
        $('#coupon_code_input').val(couponCode);
        
        // Auto-apply the coupon
        $('#apply_coupon_btn').trigger('click');
    }
});

// Coupon Apply Function (for manual input)
$('#apply_coupon_btn').on('click', function() {
    let couponCode = $('#coupon_code_input').val().trim();
    
    if (couponCode === '') {
        Swal.fire({
            icon: 'warning',
            title: 'Empty Field',
            text: 'Please enter a coupon code.',
            confirmButtonColor: '#1d9cd0'
        });
        return;
    }

    // Show loading state
    let $btn = $(this);
    let originalText = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

    $.ajax({
        url: "{{ route('apply.coupon.ajax') }}",
        method: "POST",
        data: {
            coupon_code: couponCode,
            price: {{ $with_out_attractions_cost ?? 0 }},
            attractions_cost: {{ $attractions_cost ?? 0 }},
            person_count: {{ $person_count ?? 0 }},
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.status === true) {
                appliedCoupon = couponCode;
                currentTotal = response.new_total;
                
                // Update hidden fields
                $('#applied_coupon_code').val(couponCode);
                $('#applied_discount_amount').val(response.discount);
                $('#total_amount_field').val(response.new_total);
                
                // Update payment info display
                $('#discount_row').show();
                $('#discount_amount').text('-₹' + response.discount);
                $('#final_total').text('₹' + response.new_total);
                $('#pay_button_amount').text(response.new_total);
                
                // Show success message
                let successHtml = `
                    <div class="coupon-success d-flex justify-content-between align-items-center">
                        <div>
                            <span class="coupon-badge">${couponCode}</span> Applied!
                            <br>
                            <small>You saved ₹${response.discount}</small>
                        </div>
                        <a href="#" class="remove-coupon-btn" onclick="removeCoupon(event)">REMOVE</a>
                    </div>
                `;
                $('#coupon_message_container').html(successHtml);
                $('#coupon_code_input').val('');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Coupon Applied!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                let errorHtml = `
                    <div class="coupon-error d-flex justify-content-between align-items-center">
                        <div>
                            <span class="coupon-badge-error">${couponCode}</span>
                            <small>${response.message}</small>
                        </div>
                        <a href="#" class="close-coupon-btn" onclick="closeCouponError(event)">CLOSE</a>
                    </div>
                `;
                $('#coupon_message_container').html(errorHtml);
                $('#coupon_code_input').val('');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Coupon',
                    text: response.message,
                    confirmButtonColor: '#1d9cd0'
                });
            }
        },
        error: function(xhr) {
            let errorMsg = 'Something went wrong. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            
            let errorHtml = `
                <div class="coupon-error d-flex justify-content-between align-items-center">
                    <div>${errorMsg}</div>
                    <a href="#" class="close-coupon-btn" onclick="closeCouponError(event)">CLOSE</a>
                </div>
            `;
            $('#coupon_message_container').html(errorHtml);
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMsg,
                confirmButtonColor: '#1d9cd0'
            });
        },
        complete: function() {
            $btn.prop('disabled', false).html(originalText);
        }
    });
});

// Remove Coupon Function - FIXED: Resets discount to zero
function removeCoupon(e) {
    e.preventDefault();
    
    // Reset all values to original (without discount)
    appliedCoupon = null;
    currentTotal = originalFinalTotal;
    
    // Reset hidden fields
    $('#applied_coupon_code').val('');
    $('#applied_discount_amount').val('0');
    $('#total_amount_field').val(originalFinalTotal);
    
    // Reset display fields - Discount becomes ₹0
    $('#discount_row').hide();
    $('#discount_amount').text('-₹0');
    $('#final_total').text('₹' + originalFinalTotal);
    $('#pay_button_amount').text(originalFinalTotal);
    
    // Clear coupon message container and input
    $('#coupon_message_container').html('');
    $('#coupon_code_input').val('');
    
    // Show success message
    Swal.fire({
        icon: 'success',
        title: 'Coupon Removed',
        text: 'Coupon has been removed successfully. Discount amount is now ₹0.',
        timer: 2000,
        showConfirmButton: false
    });
}

// Close Coupon Error Function
function closeCouponError(e) {
    e.preventDefault();
    $('#coupon_message_container').html('');
    $('#coupon_code_input').val('');
}

// Function to reset discount to zero (can be called from other places if needed)
function resetDiscountToZero() {
    $('#applied_coupon_code').val('');
    $('#applied_discount_amount').val('0');
    $('#total_amount_field').val(originalFinalTotal);
    $('#discount_row').hide();
    $('#discount_amount').text('-₹0');
    $('#final_total').text('₹' + originalFinalTotal);
    $('#pay_button_amount').text(originalFinalTotal);
    $('#coupon_message_container').html('');
    $('#coupon_code_input').val('');
    appliedCoupon = null;
    currentTotal = originalFinalTotal;
}

// Form submission with validation
$("#submit_info").submit(function(e) {
    e.preventDefault();
    
    // Validate traveler details and contact details
    if(!validateForm() || !validateContactDetails()) {
        return false;
    }
    
    $("#payBtn").prop('disabled', true);
    $("#payBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
    
    let formData = new FormData(this);
    
    axios.post("{{ route('international.update.pay.submit') }}", formData)
        .then(response => {
            var data = response.data;
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Booking confirmed successfully!',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('home') }}";
                });
            } else {
                $("#payBtn").prop('disabled', false);
                $("#payBtn").html('Confirm & Pay ₹' + currentTotal);
                
                let errorMsg = 'Something went wrong';
                if(data.message && data.message.error) {
                    errorMsg = data.message.error[0];
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg,
                    confirmButtonColor: '#1d9cd0'
                });
            }
        })
        .catch(error => {
            $("#payBtn").prop('disabled', false);
            $("#payBtn").html('Confirm & Pay ₹' + currentTotal);
            
            let errorMsg = 'Something went wrong. Please try again.';
            if(error.response && error.response.data && error.response.data.message) {
                if(error.response.data.message.error) {
                    errorMsg = error.response.data.message.error[0];
                }
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: errorMsg,
                confirmButtonColor: '#1d9cd0'
            });
            console.log(error);
        });
});

// Razorpay Payment Integration
var totalAmount = originalFinalTotal;
var courseId = {{$payment->package_id}};

var options = {
    "key": "{{ env('RAZORPAY_LIVE_KEY_ID') }}",
    "amount": totalAmount * 100,
    "name": "Phoenix Tours & Travels",
    "description": "Tour Booking Payment",
    "image": "{{asset(getFav())}}",
    "prefill": {
        "name": "",
        "email": $('input[name="mail"]').val() || "",
        "phone": $('input[name="phone"]').val() || "",
    },
    "theme": {
        "color": "#1d9cd0"
    },
    "handler": function(res) {
        document.getElementById('razorpay_payment_id').value = res.razorpay_payment_id;
        document.getElementById('razorpay_order_id').value = res.razorpay_order_id;
        
        // Submit form after payment success
        let formData = new FormData(document.getElementById('submit_info'));
        
        Swal.fire({
            title: 'Processing Payment',
            text: 'Please wait while we confirm your booking',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        axios.post("{{ route('international.update.pay.submit') }}", formData)
            .then(response => {
                Swal.close();
                if (response.data.success === true) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Payment Successful!',
                        text: 'Your booking has been confirmed',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        const bookingUrl = response.data.error.message.booking_url;
                        if (bookingUrl) {
                            window.location.href = bookingUrl;
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: "Something went wrong while saving data",
                        confirmButtonColor: '#1d9cd0'
                    });
                }
            }).catch(error => {
                Swal.close();
                console.log(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "Server error. Please try again.",
                    confirmButtonColor: '#1d9cd0'
                });
            });
    },
    "modal": {
        "ondismiss": function() {
            Swal.fire({
                icon: 'warning',
                title: 'Payment Cancelled',
                text: 'Payment process was cancelled',
                confirmButtonColor: '#1d9cd0'
            });
        }
    }
};

var rzp = new Razorpay(options);

// Pay Button Click Handler
document.getElementById('payBtn').onclick = function(e) {
    e.preventDefault();
    
    // First validate traveler details
    if(!validateForm()) {
        return false;
    }
    
    // Then validate contact details
    if(!validateContactDetails()) {
        return false;
    }
    
    // Update amount based on current total
    options.amount = currentTotal * 100;
    
    // Update prefill data
    options.prefill.email = $('input[name="mail"]').val();
    options.prefill.phone = $('input[name="phone"]').val();
    rzp = new Razorpay(options);
    
    // Collect all form data
    let formData = new FormData(document.getElementById('submit_info'));
    
    // Show loading
    Swal.fire({
        title: 'Processing...',
        text: 'Please wait while we validate your information',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // First save the data, then open Razorpay
    axios.post("{{ route('international.update.pay.submit') }}", formData)
        .then(response => {
            Swal.close();
            if(response.data.success === true) {
                // Data saved successfully, now open Razorpay
                rzp.open();
            } else {
                let errorMessage = 'Please fill all required fields correctly';
                if(response.data.message && response.data.message.error) {
                    errorMessage = response.data.message.error[0];
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    confirmButtonColor: '#1d9cd0'
                });
            }
        })
        .catch(error => {
            Swal.close();
            let errorMessage = 'Please check all fields and try again';
            
            if(error.response && error.response.data && error.response.data.message) {
                if(error.response.data.message.error) {
                    errorMessage = error.response.data.message.error[0];
                } else if(typeof error.response.data.message === 'string') {
                    errorMessage = error.response.data.message;
                }
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: errorMessage,
                confirmButtonColor: '#1d9cd0'
            });
        });
};

// Razorpay payment failure handler
rzp.on('payment.failed', function(response) {
    if (response.error.reason === "payment_failed") {
        const { error, reason } = response;
        $.ajax({
            url: "/payment/failure",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                response: { error, reason }
            },
            success: function(response) {
                console.log('Payment failure data sent to server', response);
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Failed',
                    text: 'Your payment was unsuccessful. Please try again.',
                    confirmButtonColor: '#1d9cd0'
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Error sending payment failure information:', textStatus, errorThrown);
            }
        });
    }
});

// Country/State/City dropdown handlers
$(document).ready(function(){
    $(document).on('change','#country_id', function() {
        let country_id = $(this).val();
        $.ajax({
            method: 'post',
            url: "{{ route('get.state.name') }}",
            data: {
                country_id: country_id, 
                _token: '{{csrf_token()}}'
            },
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
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
            data: {
                country_id: country_id, 
                state_id: state_id, 
                _token: '{{csrf_token()}}'
            },
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            success: function(res) {
                if (res!='') {
                    $('#city_id').html(res);
                }
            }
        });
    });
});
</script>
@stop