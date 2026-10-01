@extends('layouts.app')
@section('title', 'Add Agents')
@section('content')
<div class="page-content">

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">

                        <h6 class="mb-0 text-uppercase">Phoenix Tours & Travels - Agent Onboarding</h6>
                        <hr>

                        <form method="post" id="add_agent" action="" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <!-- SECTION 1: PERSONAL & CONTACT INFORMATION -->
                                <h6 class="text-primary">Personal & Contact Information</h6>

                                <div class="col-6">
                                    <label>Full Legal Name <span class="text-danger">*</span> (as on PAN)</label>
                                    <input type="text" name="full_legal_name" class="form-control" required>
                                </div>

                                <div class="col-6">
                                    <label>Preferred Name (for email/nametag)</label>
                                    <input type="text" name="preferred_name" class="form-control">
                                </div>

                                <div class="col-6">
                                    <label>Personal Mobile <span class="text-danger">*</span></label>
                                    <input type="text" name="personal_mobile" class="form-control" required>
                                </div>

                                <div class="col-6">
                                    <label>Personal Email <span class="text-danger">*</span></label>
                                    <input type="email" name="personal_email" class="form-control" required>
                                </div>

                                <div class="col-6">
                                    <label>Date of Birth</label>
                                    <input type="date" name="dob" class="form-control">
                                </div>

                                <div class="col-6">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="">Select</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label>Profile Image</label>
                                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                                </div>

                                <!-- SECTION 2: EMPLOYMENT & ROLE SPECIFICS -->
                                <h6 class="text-primary mt-3">Employment & Role Specifics</h6>

                                <div class="col-4">
                                    <label>Employee ID</label>
                                    <input type="text" name="employee_id" class="form-control" placeholder="Auto-generated or Manual">
                                </div>

                                <div class="col-4">
                                    <label>Official Phoenix Email</label>
                                    <input type="email" name="phoenix_email" class="form-control" placeholder="name@phoenixtours.com">
                                </div>

                                <div class="col-4">
                                    <label>Date of Joining <span class="text-danger">*</span></label>
                                    <input type="date" name="date_of_joining" class="form-control" required>
                                </div>

                                <div class="col-4">
                                    <label>Designation <span class="text-danger">*</span></label>
                                    <select name="designation" class="form-control" required>
                                        <option value="">Select Designation</option>
                                        <option>Travel Consultant</option>
                                        <option>Senior Holiday Specialist</option>
                                        <option>Corporate Sales Manager</option>
                                        <option>Visa Executive</option>
                                        <option>Air Ticketing Executive</option>
                                        <option>Branch Manager</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label>Department <span class="text-danger">*</span></label>
                                    <select name="department" class="form-control" required>
                                        <option value="">Select Department</option>
                                        <option>Outbound Tours</option>
                                        <option>Domestic Packages</option>
                                        <option>Air Ticketing</option>
                                        <option>Visa Services</option>
                                        <option>Corporate Travel (MICE)</option>
                                        <option>Cruise Division</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label>Reporting Manager</label>
                                    <input type="text" name="reporting_manager" class="form-control">
                                </div>

                                <div class="col-4">
                                    <label>Work Location</label>
                                    <select name="work_location" class="form-control">
                                        <option value="">Select Location</option>
                                        <option>Head Office</option>
                                        <option>Branch - Delhi</option>
                                        <option>Branch - Mumbai</option>
                                        <option>Branch - Bangalore</option>
                                        <option>Branch - Kolkata</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label>Work Type</label>
                                    <select name="work_type" class="form-control">
                                        <option value="">Select Type</option>
                                        <option>On-Field Sales</option>
                                        <option>In-Office Desk</option>
                                        <option>Hybrid</option>
                                    </select>
                                </div>

                                <!-- SECTION 3: TRAVEL INDUSTRY EXPERTISE -->
                                <h6 class="text-primary mt-3">Travel Industry Expertise & Specialization</h6>

                                <div class="col-6">
                                    <label>Core Specialization</label>
                                    <select name="core_specialization" class="form-control">
                                        <option value="">Select Primary Specialization</option>
                                        <option>Domestic Packages (Kashmir, Andaman, Rajasthan, etc.)</option>
                                        <option>International Tours (Europe, SE Asia, Middle East)</option>
                                        <option>Corporate Travel (MICE)</option>
                                        <option>Visa & Documentation</option>
                                        <option>Cruise Liners</option>
                                        <option>Customized Honeymoons</option>
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label>Secondary Specialization</label>
                                    <select name="secondary_specialization" class="form-control">
                                        <option value="">Select Secondary (Optional)</option>
                                        <option>Domestic Packages</option>
                                        <option>International Tours</option>
                                        <option>Corporate Travel</option>
                                        <option>Visa & Documentation</option>
                                        <option>Cruise Liners</option>
                                        <option>Honeymoon Packages</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label>GDS/CRS Systems Known</label>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gds_systems[]" value="Amadeus">
                                                <label class="form-check-label">Amadeus</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gds_systems[]" value="Galileo">
                                                <label class="form-check-label">Galileo</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gds_systems[]" value="Sabre">
                                                <label class="form-check-label">Sabre</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gds_systems[]" value="Travelport">
                                                <label class="form-check-label">Travelport</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <label>Languages Spoken Fluently</label>
                                    <input type="text" name="languages_spoken" class="form-control" placeholder="e.g., English, Hindi, Tamil, Spanish">
                                </div>

                                <div class="col-6">
                                    <label>Years in Travel Industry</label>
                                    <input type="number" name="experience_years" class="form-control" min="0" step="0.5">
                                </div>

                                <!-- SECTION 4: KYC & STATUTORY COMPLIANCE -->
                                <h6 class="text-primary mt-3">KYC & Statutory Compliance</h6>

                                <div class="col-6">
                                    <label>PAN Card Number <span class="text-danger">*</span></label>
                                    <input type="text" name="pan_number" class="form-control" placeholder="ABCDE1234F" required>
                                </div>

                                <div class="col-6">
                                    <label>PAN Card Upload</label>
                                    <input type="file" name="pan_card_upload" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                </div>

                                <div class="col-6">
                                    <label>Aadhaar Number <span class="text-danger">*</span></label>
                                    <input type="text" name="aadhaar_number" class="form-control" placeholder="XXXX XXXX XXXX" required>
                                </div>

                                <div class="col-6">
                                    <label>Aadhaar Card Upload</label>
                                    <input type="file" name="aadhaar_upload" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                </div>

                                <!-- EMERGENCY CONTACT -->
                                <div class="col-4">
                                    <label>Emergency Contact Name</label>
                                    <input type="text" name="emergency_contact_name" class="form-control">
                                </div>

                                <div class="col-4">
                                    <label>Emergency Contact Relation</label>
                                    <input type="text" name="emergency_contact_relation" class="form-control">
                                </div>

                                <div class="col-4">
                                    <label>Emergency Contact Phone</label>
                                    <input type="text" name="emergency_contact_phone" class="form-control">
                                </div>

                                <!-- ADDRESS -->
                                <h6 class="text-primary mt-3">Residential Address</h6>

                                <div class="col-12">
                                    <label>Street Address</label>
                                    <textarea name="address" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="col-6">
                                    <label>Country</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                        <option value="">Select Country</option>
                                        @foreach($country as $val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label>State</label>
                                    <select name="state_id" id="state_id" class="form-control"></select>
                                </div>

                                <div class="col-6">
                                    <label>City</label>
                                    <select name="city_id" id="city_id" class="form-control"></select>
                                </div>

                                <div class="col-6">
                                    <label>Pincode</label>
                                    <input type="text" name="pin_code" class="form-control">
                                </div>

                                <!-- SECTION 5: BANK DETAILS (For Salary Credit) -->
                                <h6 class="text-primary mt-3">Bank Details (Salary Account)</h6>

                                <div class="col-6">
                                    <label>Account Holder Name <span class="text-danger">*</span></label>
                                    <input type="text" name="account_holder" class="form-control" required>
                                </div>

                                <div class="col-6">
                                    <label>Account Number <span class="text-danger">*</span></label>
                                    <input type="text" name="account_number" class="form-control" required>
                                </div>

                                <div class="col-4">
                                    <label>Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control">
                                </div>

                                <div class="col-4">
                                    <label>Branch Name</label>
                                    <input type="text" name="bank_branch" class="form-control">
                                </div>

                                <div class="col-4">
                                    <label>IFSC Code <span class="text-danger">*</span></label>
                                    <input type="text" name="ifsc_code" class="form-control" required>
                                </div>

                                <!-- SECTION 6: COMMISSION & EARNINGS -->
                                <h6 class="text-primary mt-3">Commission & Earnings Setup</h6>

                                <div class="col-4">
                                    <label>Commission Type</label>
                                    <select name="commission_type" class="form-control">
                                        <option value="fixed">Fixed Amount per Booking</option>
                                        <option value="percentage">Percentage of Booking Value</option>
                                        <option value="tiered">Tiered (Based on Targets)</option>
                                        <option value="salary_only">Salary Only</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label>Commission Value</label>
                                    <input type="text" name="commission_value" class="form-control" placeholder="e.g., 5% or ₹500">
                                </div>

                                <div class="col-4">
                                    <label>Monthly Target (₹)</label>
                                    <input type="text" name="monthly_target" class="form-control" placeholder="Booking value target">
                                </div>

                                <!-- SECTION 7: IT & SYSTEM ACCESS CHECKLIST -->
                                <h6 class="text-primary mt-3">IT & System Access</h6>

                                <div class="col-12">
                                    <label>Access to be Provisioned</label>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="system_access[]" value="crm">
                                                <label class="form-check-label">PhoenixCRM Access</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="system_access[]" value="b2b_portal">
                                                <label class="form-check-label">B2B Flight/Hotel Portal</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="system_access[]" value="whatsapp_business">
                                                <label class="form-check-label">WhatsApp Business API</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="system_access[]" value="email_group">
                                                <label class="form-check-label">Sales Email Group</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 8: ASSET & BRANDING ISSUANCE -->
                                <h6 class="text-primary mt-3">Asset & Branding Issuance</h6>

                                <div class="col-3">
                                    <label>Laptop Serial Number</label>
                                    <input type="text" name="laptop_serial" class="form-control">
                                </div>

                                <div class="col-3">
                                    <label>SIM Card/Dongle Number</label>
                                    <input type="text" name="sim_number" class="form-control">
                                </div>

                                <div class="col-3">
                                    <label>ID Card Number</label>
                                    <input type="text" name="id_card_number" class="form-control">
                                </div>

                                <div class="col-3">
                                    <label>Uniform/Blazer Size</label>
                                    <select name="uniform_size" class="form-control">
                                        <option value="">Select Size</option>
                                        <option>S</option>
                                        <option>M</option>
                                        <option>L</option>
                                        <option>XL</option>
                                        <option>XXL</option>
                                        <option>Custom Measurement</option>
                                    </select>
                                </div>

                                <!-- SECTION 9: REFERRAL INFO -->
                                <h6 class="text-primary mt-3">Referral Information</h6>

                                <div class="col-6">
                                    <label>Referral Code (If any)</label>
                                    <input type="text" name="referral_code" class="form-control">
                                </div>

                                <div class="col-6">
                                    <label>Referred By (Agent Name/ID)</label>
                                    <input type="text" name="referred_by" class="form-control">
                                </div>

                                <!-- SECTION 10: ACCOUNT SETUP -->
                                <h6 class="text-primary mt-3">Login Account Setup</h6>

                                <div class="col-6">
                                    <label>Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                <div class="col-6">
                                    <label>Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>

                                <!-- SECTION 11: POLICY ACKNOWLEDGMENTS -->
                                <h6 class="text-primary mt-3">Policy Acknowledgments</h6>

                                <div class="col-12">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_handbook" value="1" id="handbook" required>
                                        <label class="form-check-label" for="handbook">
                                            I acknowledge receipt of the <strong>Phoenix Tours & Travels Agent Handbook</strong>.
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_data_privacy" value="1" id="privacy" required>
                                        <label class="form-check-label" for="privacy">
                                            I agree to the <strong>Data Privacy Policy</strong> (Client Passport/Visa Data Handling).
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_fraud_policy" value="1" id="fraud" required>
                                        <label class="form-check-label" for="fraud">
                                            I understand the <strong>Fraud & Cash Handling Policy</strong>.
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="acknowledge_non_compete" value="1" id="noncompete" required>
                                        <label class="form-check-label" for="noncompete">
                                            I have signed the <strong>Non-Compete & Confidentiality Agreement</strong>.
                                        </label>
                                    </div>
                                </div>

                                <!-- AGENT PROFILE SUMMARY CARD (Hidden/Display Fields) -->
                                <div class="col-12 mt-3">
                                    <div class="alert alert-info">
                                        <strong>Agent Profile Summary</strong><br>
                                        <small>This information will appear in the CRM as: <span id="summary_preview">[Agent Name] | Phoenix ID: PTT[Auto] | Dept: [Selected]</span></small>
                                    </div>
                                </div>

                            </div>

                            <br>

                            <div class="d-flex gap-2">
                                <button type="submit" id="submit" class="btn btn-primary px-4">Onboard Agent</button>
                                <button type="reset" class="btn btn-secondary">Reset Form</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Initialize CKEditor if needed
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('editor');
    }

    $(document).ready(function() {
        // Country, State, City dropdowns
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
                            $('#city_id').html('<option value="">Select City</option>');
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
                        }
                    },
                    error: function() {
                        toastr.error('Error loading cities');
                    }
                });
            }
        });

        // Form submission with AJAX and loader
        $('#add_agent').submit(function(event) {
            event.preventDefault();

            // Disable submit button and show loader
            $('#submit').prop('disabled', true);
            $('#submit').html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
                );

            // Create FormData object for file uploads
            var formData = new FormData(this);

            // Send AJAX request
            axios.post("{{ route('add.agents') }}", formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    var data = response.data;
                    if (data.success) {
                        // Success notification with SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message.success[0] || 'Agent onboarded successfully!',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: true
                        }).then(() => {
                            // Redirect to agent list or reset form
                            window.location.href = "{{ route('list.agents') }}";
                        });

                        // Reset form
                        $('#add_agent')[0].reset();
                        $('#state_id').html('<option value="">Select State</option>');
                        $('#city_id').html('<option value="">Select City</option>');

                    } else {
                        // Error notification
                        if (data.message && data.message.error) {
                            let errorMessages = '';
                            if (typeof data.message.error === 'object') {
                                Object.keys(data.message.error).forEach(function(key) {
                                    errorMessages += data.message.error[key][0] + '\n';
                                });
                            } else {
                                errorMessages = data.message.error;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: errorMessages,
                                timer: 5000,
                                timerProgressBar: true
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong. Please try again.',
                                timer: 3000
                            });
                        }
                    }
                })
                .catch(error => {
                    // Error handling
                    let errorMessage = 'Something went wrong. Please try again.';

                    if (error.response) {
                        // Server responded with error
                        if (error.response.data && error.response.data.message) {
                            if (typeof error.response.data.message === 'object') {
                                let messages = Object.values(error.response.data.message);
                                errorMessage = messages.join('\n');
                            } else {
                                errorMessage = error.response.data.message;
                            }
                        } else if (error.response.status === 422) {
                            // Validation errors
                            let errors = error.response.data.errors || {};
                            let errorList = [];
                            Object.keys(errors).forEach(function(key) {
                                errorList.push(errors[key][0]);
                            });
                            errorMessage = errorList.join('\n');
                        }
                    } else if (error.request) {
                        // Request made but no response
                        errorMessage = 'No response from server. Please check your connection.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        timer: 5000
                    });

                    console.error('Error details:', error);
                })
                .finally(() => {
                    // Re-enable submit button and restore text
                    $('#submit').prop('disabled', false);
                    $('#submit').html('Onboard Agent');
                });
        });

        // Live preview update
        $(document).on('input', 'input[name="full_legal_name"], select[name="department"]', function() {
            var name = $('input[name="full_legal_name"]').val() || 'Agent Name';
            var dept = $('select[name="department"]').val() || 'Dept';
            var employeeId = 'PTT' + new Date().getFullYear() + 'XXXX';
            $('#summary_preview').html(name + ' | Phoenix ID: ' + employeeId + ' | Dept: ' + dept);
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
]@stop
