@extends('layouts.app')
@section('title', 'Edit Agent')
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Agents</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Agent</li>
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
                        <h6 class="mb-0 text-uppercase">Phoenix Tours & Travels - Edit Agent</h6>
                        <hr>
                        
                        <form method="post" id="edit_agent" action="" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">

                                <!-- SECTION 1: PERSONAL & CONTACT INFORMATION -->
                                <h6 class="text-primary">Personal & Contact Information</h6>

                                <div class="col-6">
                                    <label>Full Legal Name <span class="text-danger">*</span> (as on PAN)</label>
                                    <input type="text" name="full_legal_name" class="form-control" value="{{ old('full_legal_name', $agent->full_legal_name) }}" required>
                                </div>

                                <div class="col-6">
                                    <label>Preferred Name (for email/nametag)</label>
                                    <input type="text" name="preferred_name" class="form-control" value="{{ old('preferred_name', $agent->preferred_name) }}">
                                </div>

                                <div class="col-6">
                                    <label>Personal Mobile <span class="text-danger">*</span></label>
                                    <input type="text" name="personal_mobile" class="form-control" value="{{ old('personal_mobile', $agent->personal_mobile) }}" required>
                                </div>

                                <div class="col-6">
                                    <label>Personal Email <span class="text-danger">*</span></label>
                                    <input type="email" name="personal_email" class="form-control" value="{{ old('personal_email', $agent->personal_email) }}" required>
                                </div>

                                <div class="col-6">
                                    <label>Date of Birth</label>
                                    <input type="date" name="dob" class="form-control" value="{{ old('dob', $agent->dob) }}">
                                </div>

                                <div class="col-6">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="">Select</option>
                                        <option value="Male" {{ old('gender', $agent->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $agent->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $agent->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label>Profile Image</label>
                                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                                    @if($agent->profile_image)
                                        <div class="mt-2">
                                            <img src="{{ asset($agent->profile_image) }}" style="height: 80px; width: 80px; object-fit: cover; border-radius: 50%;">
                                            <small class="d-block text-muted">Current image</small>
                                        </div>
                                    @endif
                                </div>

                                <!-- SECTION 2: EMPLOYMENT & ROLE SPECIFICS -->
                                <h6 class="text-primary mt-3">Employment & Role Specifics</h6>

                                <div class="col-4">
                                    <label>Employee ID</label>
                                    <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id', $agent->employee_id) }}" placeholder="Auto-generated or Manual" readonly>
                                </div>

                                <div class="col-4">
                                    <label>Official Phoenix Email</label>
                                    <input type="email" name="phoenix_email" class="form-control" value="{{ old('phoenix_email', $agent->phoenix_email) }}" placeholder="name@phoenixtours.com">
                                </div>

                                <div class="col-4">
                                    <label>Date of Joining <span class="text-danger">*</span></label>
                                    <input type="date" name="date_of_joining" class="form-control" value="{{ old('date_of_joining', $agent->date_of_joining) }}" required>
                                </div>

                                <div class="col-4">
                                    <label>Designation <span class="text-danger">*</span></label>
                                    <select name="designation" class="form-control" required>
                                        <option value="">Select Designation</option>
                                        <option value="Travel Consultant" {{ old('designation', $agent->designation) == 'Travel Consultant' ? 'selected' : '' }}>Travel Consultant</option>
                                        <option value="Senior Holiday Specialist" {{ old('designation', $agent->designation) == 'Senior Holiday Specialist' ? 'selected' : '' }}>Senior Holiday Specialist</option>
                                        <option value="Corporate Sales Manager" {{ old('designation', $agent->designation) == 'Corporate Sales Manager' ? 'selected' : '' }}>Corporate Sales Manager</option>
                                        <option value="Visa Executive" {{ old('designation', $agent->designation) == 'Visa Executive' ? 'selected' : '' }}>Visa Executive</option>
                                        <option value="Air Ticketing Executive" {{ old('designation', $agent->designation) == 'Air Ticketing Executive' ? 'selected' : '' }}>Air Ticketing Executive</option>
                                        <option value="Branch Manager" {{ old('designation', $agent->designation) == 'Branch Manager' ? 'selected' : '' }}>Branch Manager</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label>Department <span class="text-danger">*</span></label>
                                    <select name="department" class="form-control" required>
                                        <option value="">Select Department</option>
                                        <option value="Outbound Tours" {{ old('department', $agent->department) == 'Outbound Tours' ? 'selected' : '' }}>Outbound Tours</option>
                                        <option value="Domestic Packages" {{ old('department', $agent->department) == 'Domestic Packages' ? 'selected' : '' }}>Domestic Packages</option>
                                        <option value="Air Ticketing" {{ old('department', $agent->department) == 'Air Ticketing' ? 'selected' : '' }}>Air Ticketing</option>
                                        <option value="Visa Services" {{ old('department', $agent->department) == 'Visa Services' ? 'selected' : '' }}>Visa Services</option>
                                        <option value="Corporate Travel (MICE)" {{ old('department', $agent->department) == 'Corporate Travel (MICE)' ? 'selected' : '' }}>Corporate Travel (MICE)</option>
                                        <option value="Cruise Division" {{ old('department', $agent->department) == 'Cruise Division' ? 'selected' : '' }}>Cruise Division</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label>Reporting Manager</label>
                                    <input type="text" name="reporting_manager" class="form-control" value="{{ old('reporting_manager', $agent->reporting_manager) }}">
                                </div>

                                <div class="col-4">
                                    <label>Work Location</label>
                                    <select name="work_location" class="form-control">
                                        <option value="">Select Location</option>
                                        <option value="Head Office" {{ old('work_location', $agent->work_location) == 'Head Office' ? 'selected' : '' }}>Head Office</option>
                                        <option value="Branch - Delhi" {{ old('work_location', $agent->work_location) == 'Branch - Delhi' ? 'selected' : '' }}>Branch - Delhi</option>
                                        <option value="Branch - Mumbai" {{ old('work_location', $agent->work_location) == 'Branch - Mumbai' ? 'selected' : '' }}>Branch - Mumbai</option>
                                        <option value="Branch - Bangalore" {{ old('work_location', $agent->work_location) == 'Branch - Bangalore' ? 'selected' : '' }}>Branch - Bangalore</option>
                                        <option value="Branch - Kolkata" {{ old('work_location', $agent->work_location) == 'Branch - Kolkata' ? 'selected' : '' }}>Branch - Kolkata</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label>Work Type</label>
                                    <select name="work_type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="On-Field Sales" {{ old('work_type', $agent->work_type) == 'On-Field Sales' ? 'selected' : '' }}>On-Field Sales</option>
                                        <option value="In-Office Desk" {{ old('work_type', $agent->work_type) == 'In-Office Desk' ? 'selected' : '' }}>In-Office Desk</option>
                                        <option value="Hybrid" {{ old('work_type', $agent->work_type) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    </select>
                                </div>

                                <!-- SECTION 3: TRAVEL INDUSTRY EXPERTISE -->
                                <h6 class="text-primary mt-3">Travel Industry Expertise & Specialization</h6>

                                <div class="col-6">
                                    <label>Core Specialization</label>
                                    <select name="core_specialization" class="form-control">
                                        <option value="">Select Primary Specialization</option>
                                        <option value="Domestic Packages (Kashmir, Andaman, Rajasthan, etc.)" {{ old('core_specialization', $agent->core_specialization) == 'Domestic Packages (Kashmir, Andaman, Rajasthan, etc.)' ? 'selected' : '' }}>Domestic Packages</option>
                                        <option value="International Tours (Europe, SE Asia, Middle East)" {{ old('core_specialization', $agent->core_specialization) == 'International Tours (Europe, SE Asia, Middle East)' ? 'selected' : '' }}>International Tours</option>
                                        <option value="Corporate Travel (MICE)" {{ old('core_specialization', $agent->core_specialization) == 'Corporate Travel (MICE)' ? 'selected' : '' }}>Corporate Travel (MICE)</option>
                                        <option value="Visa & Documentation" {{ old('core_specialization', $agent->core_specialization) == 'Visa & Documentation' ? 'selected' : '' }}>Visa & Documentation</option>
                                        <option value="Cruise Liners" {{ old('core_specialization', $agent->core_specialization) == 'Cruise Liners' ? 'selected' : '' }}>Cruise Liners</option>
                                        <option value="Customized Honeymoons" {{ old('core_specialization', $agent->core_specialization) == 'Customized Honeymoons' ? 'selected' : '' }}>Customized Honeymoons</option>
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label>Secondary Specialization</label>
                                    <select name="secondary_specialization" class="form-control">
                                        <option value="">Select Secondary (Optional)</option>
                                        <option value="Domestic Packages" {{ old('secondary_specialization', $agent->secondary_specialization) == 'Domestic Packages' ? 'selected' : '' }}>Domestic Packages</option>
                                        <option value="International Tours" {{ old('secondary_specialization', $agent->secondary_specialization) == 'International Tours' ? 'selected' : '' }}>International Tours</option>
                                        <option value="Corporate Travel" {{ old('secondary_specialization', $agent->secondary_specialization) == 'Corporate Travel' ? 'selected' : '' }}>Corporate Travel</option>
                                        <option value="Visa & Documentation" {{ old('secondary_specialization', $agent->secondary_specialization) == 'Visa & Documentation' ? 'selected' : '' }}>Visa & Documentation</option>
                                        <option value="Cruise Liners" {{ old('secondary_specialization', $agent->secondary_specialization) == 'Cruise Liners' ? 'selected' : '' }}>Cruise Liners</option>
                                        <option value="Honeymoon Packages" {{ old('secondary_specialization', $agent->secondary_specialization) == 'Honeymoon Packages' ? 'selected' : '' }}>Honeymoon Packages</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label>GDS/CRS Systems Known</label>
                                    <div class="row">
                                        @php 
                                            $gdsSystems = explode(',', old('gds_systems', $agent->gds_systems ?? ''));
                                        @endphp
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gds_systems[]" value="Amadeus" {{ in_array('Amadeus', $gdsSystems) ? 'checked' : '' }}>
                                                <label class="form-check-label">Amadeus</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gds_systems[]" value="Galileo" {{ in_array('Galileo', $gdsSystems) ? 'checked' : '' }}>
                                                <label class="form-check-label">Galileo</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gds_systems[]" value="Sabre" {{ in_array('Sabre', $gdsSystems) ? 'checked' : '' }}>
                                                <label class="form-check-label">Sabre</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gds_systems[]" value="Travelport" {{ in_array('Travelport', $gdsSystems) ? 'checked' : '' }}>
                                                <label class="form-check-label">Travelport</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label>Languages Spoken Fluently</label>
                                    <input type="text" name="languages_spoken" class="form-control" value="{{ old('languages_spoken', $agent->languages_spoken) }}" placeholder="e.g., English, Hindi, Tamil, Spanish">
                                </div>

                                <div class="col-6">
                                    <label>Years in Travel Industry</label>
                                    <input type="number" name="experience_years" class="form-control" value="{{ old('experience_years', $agent->experience_years) }}" min="0" step="0.5">
                                </div>

                                <!-- SECTION 4: KYC & STATUTORY COMPLIANCE -->
                                <h6 class="text-primary mt-3">KYC & Statutory Compliance</h6>

                                <div class="col-6">
                                    <label>PAN Card Number <span class="text-danger">*</span></label>
                                    <input type="text" name="pan_number" class="form-control" value="{{ old('pan_number', $agent->pan_number) }}" placeholder="ABCDE1234F" required>
                                </div>

                                <div class="col-6">
                                    <label>PAN Card Upload</label>
                                    <input type="file" name="pan_card_upload" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                    @if($agent->pan_card_upload)
                                        <small class="text-muted d-block mt-1">Current: {{ basename($agent->pan_card_upload) }}</small>
                                    @endif
                                </div>

                                <div class="col-6">
                                    <label>Aadhaar Number <span class="text-danger">*</span></label>
                                    <input type="text" name="aadhaar_number" class="form-control" value="{{ old('aadhaar_number', $agent->aadhaar_number) }}" placeholder="XXXX XXXX XXXX" required>
                                </div>

                                <div class="col-6">
                                    <label>Aadhaar Card Upload</label>
                                    <input type="file" name="aadhaar_upload" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                    @if($agent->aadhaar_upload)
                                        <small class="text-muted d-block mt-1">Current: {{ basename($agent->aadhaar_upload) }}</small>
                                    @endif
                                </div>

                                <!-- EMERGENCY CONTACT -->
                                <div class="col-4">
                                    <label>Emergency Contact Name</label>
                                    <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $agent->emergency_contact_name) }}">
                                </div>

                                <div class="col-4">
                                    <label>Emergency Contact Relation</label>
                                    <input type="text" name="emergency_contact_relation" class="form-control" value="{{ old('emergency_contact_relation', $agent->emergency_contact_relation) }}">
                                </div>

                                <div class="col-4">
                                    <label>Emergency Contact Phone</label>
                                    <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $agent->emergency_contact_phone) }}">
                                </div>

                                <!-- ADDRESS -->
                                <h6 class="text-primary mt-3">Residential Address</h6>

                                <div class="col-12">
                                    <label>Street Address</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address', $agent->address) }}</textarea>
                                </div>

                                <div class="col-6">
                                    <label>Country</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $val)
                                        <option value="{{$val->id}}" {{ old('country_id', $agent->country_id) == $val->id ? 'selected' : '' }}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label>State</label>
                                    <select name="state_id" id="state_id" class="form-control">
                                        <option value="">Select State</option>
                                        @foreach($states as $val)
                                        <option value="{{$val->id}}" {{ old('state_id', $agent->state_id) == $val->id ? 'selected' : '' }}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label>City</label>
                                    <select name="city_id" id="city_id" class="form-control">
                                        <option value="">Select City</option>
                                        @foreach($cities as $val)
                                        <option value="{{$val->id}}" {{ old('city_id', $agent->city_id) == $val->id ? 'selected' : '' }}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label>Pincode</label>
                                    <input type="text" name="pin_code" class="form-control" value="{{ old('pin_code', $agent->pin_code) }}">
                                </div>

                                <!-- SECTION 5: BANK DETAILS (For Salary Credit) -->
                                <h6 class="text-primary mt-3">Bank Details (Salary Account)</h6>

                                <div class="col-6">
                                    <label>Account Holder Name <span class="text-danger">*</span></label>
                                    <input type="text" name="account_holder" class="form-control" value="{{ old('account_holder', $agent->account_holder) }}" required>
                                </div>

                                <div class="col-6">
                                    <label>Account Number <span class="text-danger">*</span></label>
                                    <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $agent->account_number) }}" required>
                                </div>

                                <div class="col-4">
                                    <label>Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $agent->bank_name) }}">
                                </div>

                                <div class="col-4">
                                    <label>Branch Name</label>
                                    <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch', $agent->bank_branch) }}">
                                </div>

                                <div class="col-4">
                                    <label>IFSC Code <span class="text-danger">*</span></label>
                                    <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code', $agent->ifsc_code) }}" required>
                                </div>

                                <!-- SECTION 6: COMMISSION & EARNINGS -->
                                <h6 class="text-primary mt-3">Commission & Earnings Setup</h6>

                                <div class="col-4">
                                    <label>Commission Type</label>
                                    <select name="commission_type" class="form-control">
                                        <option value="fixed" {{ old('commission_type', $agent->commission_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount per Booking</option>
                                        <option value="percentage" {{ old('commission_type', $agent->commission_type) == 'percentage' ? 'selected' : '' }}>Percentage of Booking Value</option>
                                        <option value="tiered" {{ old('commission_type', $agent->commission_type) == 'tiered' ? 'selected' : '' }}>Tiered (Based on Targets)</option>
                                        <option value="salary_only" {{ old('commission_type', $agent->commission_type) == 'salary_only' ? 'selected' : '' }}>Salary Only</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label>Commission Value</label>
                                    <input type="text" name="commission_value" class="form-control" value="{{ old('commission_value', $agent->commission_value) }}" placeholder="e.g., 5% or ₹500">
                                </div>

                                <div class="col-4">
                                    <label>Monthly Target (₹)</label>
                                    <input type="text" name="monthly_target" class="form-control" value="{{ old('monthly_target', $agent->monthly_target) }}" placeholder="Booking value target">
                                </div>

                                <!-- SECTION 7: IT & SYSTEM ACCESS CHECKLIST -->
                                <h6 class="text-primary mt-3">IT & System Access</h6>

                                <div class="col-12">
                                    <label>Access to be Provisioned</label>
                                    <div class="row">
                                        @php 
                                            $systemAccess = explode(',', old('system_access', $agent->system_access ?? ''));
                                        @endphp
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="system_access[]" value="crm" {{ in_array('crm', $systemAccess) ? 'checked' : '' }}>
                                                <label class="form-check-label">PhoenixCRM Access</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="system_access[]" value="b2b_portal" {{ in_array('b2b_portal', $systemAccess) ? 'checked' : '' }}>
                                                <label class="form-check-label">B2B Flight/Hotel Portal</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="system_access[]" value="whatsapp_business" {{ in_array('whatsapp_business', $systemAccess) ? 'checked' : '' }}>
                                                <label class="form-check-label">WhatsApp Business API</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="system_access[]" value="email_group" {{ in_array('email_group', $systemAccess) ? 'checked' : '' }}>
                                                <label class="form-check-label">Sales Email Group</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 8: ASSET & BRANDING ISSUANCE -->
                                <h6 class="text-primary mt-3">Asset & Branding Issuance</h6>

                                <div class="col-3">
                                    <label>Laptop Serial Number</label>
                                    <input type="text" name="laptop_serial" class="form-control" value="{{ old('laptop_serial', $agent->laptop_serial) }}">
                                </div>

                                <div class="col-3">
                                    <label>SIM Card/Dongle Number</label>
                                    <input type="text" name="sim_number" class="form-control" value="{{ old('sim_number', $agent->sim_number) }}">
                                </div>

                                <div class="col-3">
                                    <label>ID Card Number</label>
                                    <input type="text" name="id_card_number" class="form-control" value="{{ old('id_card_number', $agent->id_card_number) }}">
                                </div>

                                <div class="col-3">
                                    <label>Uniform/Blazer Size</label>
                                    <select name="uniform_size" class="form-control">
                                        <option value="">Select Size</option>
                                        <option value="S" {{ old('uniform_size', $agent->uniform_size) == 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ old('uniform_size', $agent->uniform_size) == 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ old('uniform_size', $agent->uniform_size) == 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ old('uniform_size', $agent->uniform_size) == 'XL' ? 'selected' : '' }}>XL</option>
                                        <option value="XXL" {{ old('uniform_size', $agent->uniform_size) == 'XXL' ? 'selected' : '' }}>XXL</option>
                                        <option value="Custom Measurement" {{ old('uniform_size', $agent->uniform_size) == 'Custom Measurement' ? 'selected' : '' }}>Custom Measurement</option>
                                    </select>
                                </div>

                                <!-- SECTION 9: REFERRAL INFO -->
                                <h6 class="text-primary mt-3">Referral Information</h6>

                                <div class="col-6">
                                    <label>Referral Code (If any)</label>
                                    <input type="text" name="referral_code" class="form-control" value="{{ old('referral_code', $agent->referral_code) }}">
                                </div>

                                <div class="col-6">
                                    <label>Referred By (Agent Name/ID)</label>
                                    <input type="text" name="referred_by" class="form-control" value="{{ old('referred_by', $agent->referred_by) }}">
                                </div>

                                <!-- SECTION 10: ACCOUNT SETUP -->
                                <h6 class="text-primary mt-3">Account Setup</h6>

                                <div class="col-6">
                                    <label>New Password (Leave blank to keep current)</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter new password">
                                </div>

                                <div class="col-6">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                                </div>

                                <!-- SECTION 11: POLICY ACKNOWLEDGMENTS -->
                                <h6 class="text-primary mt-3">Policy Acknowledgments</h6>

                                <div class="col-12">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_handbook" value="1" id="handbook" {{ old('acknowledge_handbook', $agent->acknowledge_handbook) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="handbook">
                                            I acknowledge receipt of the <strong>Phoenix Tours & Travels Agent Handbook</strong>.
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_data_privacy" value="1" id="privacy" {{ old('acknowledge_data_privacy', $agent->acknowledge_data_privacy) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="privacy">
                                            I agree to the <strong>Data Privacy Policy</strong> (Client Passport/Visa Data Handling).
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_fraud_policy" value="1" id="fraud" {{ old('acknowledge_fraud_policy', $agent->acknowledge_fraud_policy) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fraud">
                                            I understand the <strong>Fraud & Cash Handling Policy</strong>.
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_non_compete" value="1" id="noncompete" {{ old('acknowledge_non_compete', $agent->acknowledge_non_compete) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="noncompete">
                                            I have signed the <strong>Non-Compete & Confidentiality Agreement</strong>.
                                        </label>
                                    </div>
                                </div>

                                <!-- STATUS -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status" value="1" id="status" {{ old('status', $agent->status) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            <strong>Active</strong> (Uncheck to deactivate agent)
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <br>

                            <div class="d-flex gap-2">
                                <button type="submit" id="submit" class="btn btn-primary px-4">Update Agent</button>
                                <a href="{{ route('list.agents') }}" class="btn btn-secondary">Cancel</a>
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
    $(document).ready(function() {
        // Country, State, City dynamic dropdowns
        $(document).on('change', '#country_id', function() {
            let country_id = $(this).val();
            if (country_id) {
                $.ajax({
                    method: 'post',
                    url: "{{ route('get.state.name') }}",
                    data: {
                        country_id: country_id,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(res) {
                        if (res != '') {
                            $('#state_id').html(res);
                            let selectedState = "{{ old('state_id', $agent->state_id) }}";
                            if (selectedState) {
                                $('#state_id').val(selectedState);
                            }
                        }
                    },
                    error: function() {
                        toastr.error('Error loading states');
                    }
                });
            }
        });

        $(document).on('change', '#state_id', function() {
            let state_id = $(this).val();
            let country_id = $('#country_id').val();
            if (state_id && country_id) {
                $.ajax({
                    method: 'post',
                    url: "{{ route('get.city.name') }}",
                    data: {
                        country_id: country_id,
                        state_id: state_id,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(res) {
                        if (res != '') {
                            $('#city_id').html(res);
                            let selectedCity = "{{ old('city_id', $agent->city_id) }}";
                            if (selectedCity) {
                                $('#city_id').val(selectedCity);
                            }
                        }
                    },
                    error: function() {
                        toastr.error('Error loading cities');
                    }
                });
            }
        });

        // Form submission with AJAX and loader
        $("#edit_agent").submit(function(event) {
            event.preventDefault();
            
            // Disable submit button and show loader
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
            
            var formData = new FormData(this);
            formData.append('_method', 'PUT');

            axios.post("{{ route('update.agents', $agent->id) }}", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(function(response) {
                var data = response.data;
                
                // Reset button
                $("#submit").prop('disabled', false);
                $("#submit").html('Update Agent');
                
                if (data.success === true) {
                    // Use the existing notify function like in your create form
                    notify(null, data.message || 'Agent updated successfully!', 'top right', 'success', true, "{{ route('list.agents') }}", 2000);
                } else {
                    // Show error message
                    if (data.message) {
                        if (typeof data.message === 'object') {
                            for (var a in data.message) {
                                notify(null, data.message[a][0], 'bottom left');
                            }
                        } else {
                            notify(null, data.message, 'top right', 'error');
                        }
                    } else {
                        notify(null, 'Something went wrong!', 'top right', 'error');
                    }
                }
            }).catch(function(error) {
                // Reset button
                $("#submit").prop('disabled', false);
                $("#submit").html('Update Agent');
                
                let errorMessage = 'Something went wrong. Please try again.';
                
                if (error.response && error.response.data) {
                    if (error.response.data.message) {
                        if (typeof error.response.data.message === 'object') {
                            let messages = Object.values(error.response.data.message);
                            errorMessage = messages.join('\n');
                        } else {
                            errorMessage = error.response.data.message;
                        }
                    }
                    
                    // Handle validation errors
                    if (error.response.status === 422 && error.response.data.errors) {
                        let errors = error.response.data.errors;
                        let errorList = [];
                        Object.keys(errors).forEach(function(key) {
                            errorList.push(errors[key][0]);
                        });
                        errorMessage = errorList.join('\n');
                    }
                }
                
                notify(null, errorMessage, 'top right', 'error');
                console.error('Error details:', error);
            });
        });

        // File input preview
        $(document).on('change', 'input[type="file"]', function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var fileName = input.files[0].name;
                    var fileSize = (input.files[0].size / 1024 / 1024).toFixed(2) + ' MB';
                    $(input).closest('.col-6, .col-12').find('.file-info').remove();
                    $(input).after('<small class="file-info text-muted d-block">Selected: ' + fileName +
                        ' (' + fileSize + ')</small>');
                }
                reader.readAsDataURL(input.files[0]);
            }
        });

        // Password strength indicator
        $(document).on('input', 'input[name="password"]', function() {
            var password = $(this).val();
            var strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;

            var strengthText = '';
            var strengthClass = '';
            switch (strength) {
                case 0:
                case 1:
                    strengthText = 'Weak';
                    strengthClass = 'text-danger';
                    break;
                case 2:
                    strengthText = 'Fair';
                    strengthClass = 'text-warning';
                    break;
                case 3:
                    strengthText = 'Good';
                    strengthClass = 'text-info';
                    break;
                case 4:
                case 5:
                    strengthText = 'Strong';
                    strengthClass = 'text-success';
                    break;
            }

            $('.password-strength').remove();
            if (password.length > 0) {
                $(this).after('<small class="password-strength ' + strengthClass + '">Password Strength: ' +
                    strengthText + '</small>');
            }
        });
    });
</script>
@stop