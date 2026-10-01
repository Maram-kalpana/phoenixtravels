@extends('layouts.app')
@section('title', 'Add Vendor')
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Vendor</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Vendor</li>
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
                        <h6 class="mb-0 text-uppercase">Phoenix Tours & Travels - Vendor Onboarding</h6>
                        <hr>
                        <form method="post" id="add_vendor" action="" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- SECTION 1: VENDOR TYPE & BASIC INFORMATION -->
                            <h6 class="text-primary mb-3">Vendor Type & Basic Information</h6>
                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="form-label">Vendor Category <span class="text-danger">*</span></label>
                                    <select name="vendor_category" id="vendor_category" class="form-control" required>
                                        <option value="">Select Category</option>
                                        <option value="hotel">Hotel / Resort / Homestay</option>
                                        <option value="transport">Transport Operator (Bus/Car/Tempo)</option>
                                        <option value="dmc">DMC - Destination Management Company</option>
                                        <option value="airline">Airline / Flight Consolidator</option>
                                        <option value="cruise">Cruise Line Operator</option>
                                        <option value="visa">Visa Processing Agency</option>
                                        <option value="insurance">Travel Insurance Provider</option>
                                        <option value="activities">Activities / Sightseeing / Guide</option>
                                        <option value="mice">MICE / Event Venue Partner</option>
                                        <option value="foreign_exchange">Foreign Exchange / Forex</option>
                                        <option value="restaurant">Restaurant / Meal Provider</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Vendor Sub-Category</label>
                                    <select name="vendor_sub_category" id="vendor_sub_category" class="form-control">
                                        <option value="">Select Sub-Category</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Vendor Tier</label>
                                    <select name="vendor_tier" class="form-control">
                                        <option value="">Select Tier</option>
                                        <option value="premium">Premium / 5-Star Partner</option>
                                        <option value="standard">Standard Partner</option>
                                        <option value="budget">Budget Partner</option>
                                        <option value="seasonal">Seasonal / On-Demand</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Company/Firm Name <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control" placeholder="Registered Business Name" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Trade/Brand Name</label>
                                    <input type="text" name="brand_name" class="form-control" placeholder="If different from company name">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Year of Establishment</label>
                                    <input type="number" name="year_established" class="form-control" placeholder="e.g., 2010" min="1900" max="2026">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Vendor Since (with Phoenix)</label>
                                    <input type="date" name="vendor_since" class="form-control">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Vendor Status</label>
                                    <select name="vendor_status" class="form-control">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="blacklisted">Blacklisted</option>
                                        <option value="under_review">Under Review</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Website URL</label>
                                    <input type="url" name="website_url" class="form-control" placeholder="https://www.vendorwebsite.com">
                                </div>
                            </div>

                            <!-- SECTION 2: PRIMARY CONTACT PERSON -->
                            <h6 class="text-primary mb-3 mt-3">Primary Contact Person (Key Account Manager)</h6>
                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_person_name" class="form-control" required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Designation</label>
                                    <input type="text" name="contact_person_designation" class="form-control" placeholder="e.g., Sales Head, Director">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Department</label>
                                    <input type="text" name="contact_person_department" class="form-control" placeholder="e.g., Sales, Operations">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Mobile Number (Primary) <span class="text-danger">*</span></label>
                                    <input type="text" name="primary_mobile" class="form-control" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Alternate Mobile</label>
                                    <input type="text" name="alternate_mobile" class="form-control">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">WhatsApp Number</label>
                                    <input type="text" name="whatsapp_number" class="form-control">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Email (Official) <span class="text-danger">*</span></label>
                                    <input type="email" name="primary_email" class="form-control" required>
                                </div>
                            </div>

                            <!-- SECTION 3: OPERATIONAL DETAILS -->
                            <h6 class="text-primary mb-3 mt-3">Operational Details & Service Area</h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Primary Service Location/City <span class="text-danger">*</span></label>
                                    <input type="text" name="primary_location" class="form-control" placeholder="e.g., Jaipur, Goa, Dubai" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Pan India / Multi-City Operations?</label>
                                    <select name="pan_india_operations" class="form-control">
                                        <option value="no">No - Single Location</option>
                                        <option value="yes">Yes - Multiple Cities</option>
                                        <option value="international">International Destinations</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Service Areas Covered (Cities/Regions)</label>
                                    <textarea name="service_areas" class="form-control" rows="2" placeholder="List all cities/regions where services are provided"></textarea>
                                </div>
                            </div>

                            <!-- Hotel Specific Fields -->
                            <div class="vendor-hotel-fields" style="display: none;">
                                <h6 class="text-primary mb-3 mt-3">Hotel Specific Details</h6>
                                <div class="row g-3">
                                    <div class="col-3">
                                        <label class="form-label">Property Type</label>
                                        <select name="property_type" class="form-control">
                                            <option value="">Select</option>
                                            <option>5-Star Luxury</option>
                                            <option>4-Star</option>
                                            <option>3-Star</option>
                                            <option>2-Star/Budget</option>
                                            <option>Resort</option>
                                            <option>Homestay</option>
                                            <option>Villa</option>
                                            <option>Houseboat</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label">Total Rooms</label>
                                        <input type="number" name="total_rooms" class="form-control">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label">Contracted Rooms</label>
                                        <input type="number" name="contracted_rooms" class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <label class="form-label">Meal Plan Options</label>
                                        <select name="meal_plans[]" class="form-control" multiple size="3">
                                            <option>EP - Room Only</option>
                                            <option>CP - With Breakfast</option>
                                            <option>MAP - Breakfast + Dinner</option>
                                            <option>AP - All Meals</option>
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label">Check-in Time</label>
                                        <input type="time" name="checkin_time" class="form-control" value="12:00">
                                    </div>
                                </div>
                            </div>

                            <!-- Transport Specific Fields -->
                            <div class="vendor-transport-fields" style="display: none;">
                                <h6 class="text-primary mb-3 mt-3">Transport Specific Details</h6>
                                <div class="row g-3">
                                    <div class="col-4">
                                        <label class="form-label">Vehicle Types</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="vehicle_types[]" value="sedan"> Sedan (Dzire/Etios)
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="vehicle_types[]" value="suv"> SUV (Innova/Ertiga)
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="vehicle_types[]" value="tempo"> Tempo Traveller
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="vehicle_types[]" value="mini_bus"> Mini Bus
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="vehicle_types[]" value="luxury_bus"> Luxury Coach/Volvo
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="vehicle_types[]" value="luxury_car"> Luxury (BMW/Audi/Mercedes)
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label">Fleet Size</label>
                                        <input type="number" name="fleet_size" class="form-control">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label">Driver Accommodation</label>
                                        <select name="driver_accommodation" class="form-control">
                                            <option value="self_arranged">Self Arranged by Driver</option>
                                            <option value="vendor_provided">Provided by Vendor</option>
                                            <option value="extra_charge">Extra Chargeable</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- DMC Specific Fields -->
                            <div class="vendor-dmc-fields" style="display: none;">
                                <h6 class="text-primary mb-3 mt-3">DMC Specific Details</h6>
                                <div class="row g-3">
                                    <div class="col-4">
                                        <label class="form-label">Destinations Covered</label>
                                        <textarea name="dmc_destinations" class="form-control" rows="2" placeholder="e.g., Kashmir, Ladakh, Bhutan"></textarea>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Services Offered</label>
                                        <select name="dmc_services[]" class="form-control" multiple size="3">
                                            <option>Hotel Bookings</option>
                                            <option>Transportation</option>
                                            <option>Sightseeing</option>
                                            <option>Guide Services</option>
                                            <option>Permits & Entry</option>
                                            <option>Meal Arrangements</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">IATA/TAAI/AITOA Member?</label>
                                        <input type="text" name="dmc_membership" class="form-control" placeholder="Membership Number">
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 4: FINANCIAL & CREDIT TERMS -->
                            <h6 class="text-primary mb-3 mt-3">Financial & Credit Terms</h6>
                            <div class="row g-3">
                                <div class="col-3">
                                    <label class="form-label">Credit Period Allowed <span class="text-danger">*</span></label>
                                    <select name="credit_period" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="advance">100% Advance</option>
                                        <option value="7_days">7 Days</option>
                                        <option value="15_days">15 Days</option>
                                        <option value="30_days">30 Days</option>
                                        <option value="45_days">45 Days</option>
                                        <option value="60_days">60 Days</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Credit Limit (₹)</label>
                                    <input type="text" name="credit_limit" class="form-control" placeholder="e.g., 5,00,000">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Payment Mode Preferred</label>
                                    <select name="payment_mode" class="form-control">
                                        <option value="neft">NEFT/RTGS</option>
                                        <option value="imps">IMPS</option>
                                        <option value="upi">UPI</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Commission Structure</label>
                                    <select name="commission_structure" class="form-control">
                                        <option value="net_rate">Net Rate (No Commission)</option>
                                        <option value="commissionable">Commissionable Rate</option>
                                        <option value="markup">Markup Based</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Default Commission %</label>
                                    <input type="text" name="default_commission_percent" class="form-control" placeholder="e.g., 10%">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">TDS Applicable %</label>
                                    <select name="tds_percent" class="form-control">
                                        <option value="1">1% (Individual/HUF)</option>
                                        <option value="2">2% (Company/Firm)</option>
                                        <option value="5">5% (Rent/Professional)</option>
                                        <option value="0">Not Applicable</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">GST Registration Type</label>
                                    <select name="gst_registration_type" class="form-control">
                                        <option value="regular">Regular</option>
                                        <option value="composition">Composition Scheme</option>
                                        <option value="unregistered">Unregistered</option>
                                    </select>
                                </div>
                            </div>

                            <!-- SECTION 5: BANK DETAILS -->
                            <h6 class="text-primary mb-3 mt-3">Bank Account Details (For Payments)</h6>
                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_account_holder" class="form-control" required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_account_number" class="form-control" required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Confirm Account Number <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_account_number_confirmation" class="form-control" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Branch Name</label>
                                    <input type="text" name="bank_branch" class="form-control">
                                </div>
                                <div class="col-3">
                                    <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                    <input type="text" name="bank_ifsc" class="form-control" required>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Account Type</label>
                                    <select name="bank_account_type" class="form-control">
                                        <option value="savings">Savings</option>
                                        <option value="current">Current</option>
                                        <option value="overdraft">Overdraft/Cash Credit</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">UPI ID (Optional)</label>
                                    <input type="text" name="upi_id" class="form-control" placeholder="vendor@okhdfcbank">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Cancelled Cheque / Bank Letter Upload</label>
                                    <input type="file" name="cancelled_cheque" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>

                            <!-- SECTION 6: KYC & LEGAL DOCUMENTS -->
                            <h6 class="text-primary mb-3 mt-3">KYC & Legal Documents</h6>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">PAN Card Number <span class="text-danger">*</span></label>
                                    <input type="text" name="pan_number" class="form-control" placeholder="ABCDE1234F" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">PAN Card Upload <span class="text-danger">*</span></label>
                                    <input type="file" name="pan_card_upload" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">GST Number</label>
                                    <input type="text" name="gst_number" class="form-control" placeholder="22AAAAA0000A1Z5">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">GST Certificate Upload</label>
                                    <input type="file" name="gst_certificate_upload" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">MSME Registration Number</label>
                                    <input type="text" name="msme_number" class="form-control" placeholder="UDYAM-XX-00-0000000">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">MSME Certificate Upload</label>
                                    <input type="file" name="msme_certificate_upload" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>

                            <!-- SECTION 7: ADDRESS -->
                            <h6 class="text-primary mb-3 mt-3">Registered Office Address</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Street Address</label>
                                    <textarea name="registered_address" id="address" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Country</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value="">Select Country</option>
                                        @foreach($country as $key => $val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">State</label>
                                    <select name="state_id" id="state_id" class="form-control state_id">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">City</label>
                                    <select name="city_id" id="city_id" class="form-control city_id">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Postcode</label>
                                    <input type="text" name="pin_code" id="pin_code" class="form-control">
                                </div>
                            </div>

                            <!-- SECTION 8: CONTRACT & POLICIES -->
                            <h6 class="text-primary mb-3 mt-3">Contract & Policies</h6>
                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="form-label">Contract Start Date</label>
                                    <input type="date" name="contract_start_date" class="form-control">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Contract End Date</label>
                                    <input type="date" name="contract_end_date" class="form-control">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Contract Renewal Type</label>
                                    <select name="contract_renewal_type" class="form-control">
                                        <option value="auto">Auto-Renewal</option>
                                        <option value="manual">Manual Renewal Required</option>
                                        <option value="one_time">One-Time Contract</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Rate Sheet Upload (Excel/PDF)</label>
                                    <input type="file" name="rate_sheet" class="form-control" accept=".xlsx,.xls,.pdf">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Signed Agreement Upload</label>
                                    <input type="file" name="signed_agreement" class="form-control" accept=".pdf">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Cancellation Policy</label>
                                    <textarea name="cancellation_policy" class="form-control" rows="2" placeholder="e.g., 30+ days: 10%, 15-30 days: 25%, 7-15 days: 50%, <7 days: 100%"></textarea>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Child Policy</label>
                                    <textarea name="child_policy" class="form-control" rows="2" placeholder="e.g., Below 5 years complimentary, 5-12 years 50% of adult rate"></textarea>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Extra Bed / Person Policy</label>
                                    <textarea name="extra_bed_policy" class="form-control" rows="2"></textarea>
                                </div>
                            </div>

                            <!-- SECTION 9: INTERNAL RATING & PREFERENCES -->
                            <h6 class="text-primary mb-3 mt-3">Performance & Internal Rating</h6>
                            <div class="row g-3">
                                <div class="col-3">
                                    <label class="form-label">Quality Rating</label>
                                    <select name="quality_rating" class="form-control">
                                        <option value="">Not Rated</option>
                                        <option value="5">★★★★★ (Excellent)</option>
                                        <option value="4">★★★★ (Good)</option>
                                        <option value="3">★★★ (Average)</option>
                                        <option value="2">★★ (Below Average)</option>
                                        <option value="1">★ (Poor)</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Reliability Score</label>
                                    <select name="reliability_score" class="form-control">
                                        <option value="">Not Rated</option>
                                        <option value="5">5 - Highly Reliable</option>
                                        <option value="4">4 - Reliable</option>
                                        <option value="3">3 - Moderate</option>
                                        <option value="2">2 - Unreliable</option>
                                        <option value="1">1 - Very Unreliable</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Pricing Competitiveness</label>
                                    <select name="pricing_rating" class="form-control">
                                        <option value="">Not Rated</option>
                                        <option value="excellent">Excellent (Best in Market)</option>
                                        <option value="good">Good Value</option>
                                        <option value="average">Market Average</option>
                                        <option value="high">Premium/High</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="form-label">Preferred Partner?</label>
                                    <select name="preferred_partner" class="form-control">
                                        <option value="no">No</option>
                                        <option value="yes">Yes - Preferred Vendor</option>
                                    </select>
                                </div>
                            </div>

                            <!-- SECTION 10: TERMS & CONDITIONS -->
                            <h6 class="text-primary mb-3 mt-3">Terms & Conditions Acknowledgment</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_terms" value="1" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I/We agree to abide by <strong>Phoenix Tours & Travels Vendor Terms & Conditions</strong>.
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_payment_terms" value="1" id="payment_terms" required>
                                        <label class="form-check-label" for="payment_terms">
                                            I/We acknowledge the <strong>Payment & Credit Terms</strong> as specified above.
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_data_privacy" value="1" id="data_privacy" required>
                                        <label class="form-check-label" for="data_privacy">
                                            I/We agree to <strong>Data Privacy & Confidentiality Agreement</strong> regarding client information.
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_quality" value="1" id="quality" required>
                                        <label class="form-check-label" for="quality">
                                            I/We commit to maintaining <strong>Phoenix Quality Standards</strong> for all services provided.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- INTERNAL USE ONLY -->
                            <h6 class="text-primary mb-3 mt-3">Internal Use Only (Phoenix Admin)</h6>
                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="form-label">Assigned Account Manager (Phoenix)</label>
                                    <input type="text" name="phoenix_account_manager" class="form-control" placeholder="Phoenix Employee Name">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Vendor ID (Auto-generated)</label>
                                    <input type="text" name="vendor_id" class="form-control" placeholder="VND-XXXX" readonly>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Approval Status</label>
                                    <select name="approval_status" class="form-control">
                                        <option value="pending">Pending Approval</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Internal Notes / Remarks</label>
                                    <textarea name="internal_notes" class="form-control" rows="2" placeholder="For Phoenix internal use only"></textarea>
                                </div>
                            </div>

                            <br>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" id="submit" class="btn btn-primary px-4">Register Vendor</button>
                                        <button type="reset" class="btn btn-secondary">Reset Form</button>
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
<script src="{{asset('backend/ckeditor/ckeditor.js')}}"></script>
<script>
    CKEDITOR.replace('editor');
</script>
<script>
    // Vendor Category Change - Show/Hide specific fields
    $(document).ready(function(){
        $('#vendor_category').change(function() {
            var category = $(this).val();
            
            // Hide all specific fields first
            $('.vendor-hotel-fields, .vendor-transport-fields, .vendor-dmc-fields').hide();
            
            // Show relevant fields based on category
            if (category === 'hotel') {
                $('.vendor-hotel-fields').show();
            } else if (category === 'transport') {
                $('.vendor-transport-fields').show();
            } else if (category === 'dmc') {
                $('.vendor-dmc-fields').show();
            }
            
            // Update sub-category dropdown
            updateSubCategories(category);
        });
        
        function updateSubCategories(category) {
            var subCategories = {
                'hotel': ['Luxury Hotel', 'Business Hotel', 'Resort', 'Homestay', 'Budget Hotel'],
                'transport': ['Car Rental', 'Bus Operator', 'Tempo Service', 'Luxury Transport'],
                'dmc': ['Inbound DMC', 'Outbound DMC', 'Local DMC', 'International DMC'],
                'airline': ['Full Service Carrier', 'Low Cost Carrier', 'Charter Service'],
                'visa': ['Visa Facilitation', 'Documentation Only', 'Premium Lounge Service']
            };
            
            var html = '<option value="">Select Sub-Category</option>';
            if (subCategories[category]) {
                $.each(subCategories[category], function(index, value) {
                    html += '<option value="' + value.toLowerCase().replace(/ /g, '_') + '">' + value + '</option>';
                });
            }
            $('#vendor_sub_category').html(html);
        }
    });

    // Form Submit
    $("#add_vendor").submit(function(event) {
        event.preventDefault();
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
        
        axios.post("{{ route('add.vendor') }}", new FormData($("#add_vendor")[0]))
            .then(response => {
                var data = response.data;
                if (data.success) {
                    notify(null, 'Vendor added successfully', 'top right', 'success', true, "{{ route('list.vendor') }}", 1000);
                } else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Register Vendor');
                    for (var a in data['error']['message']) {
                        notify(null, data['error']['message'][a][0], 'bottom left');
                        if (a == 'success' | a == 'error') {
                            notify(null, data['error']['message'][a][0], 'bottom left');
                        }
                    }
                }
            })
            .catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('Register Vendor');
                notify(null, 'Something went wrong', 'top right');
                console.log(error);
            });
    });
</script>
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
            })
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
            })
        });
    });
</script>
@stop