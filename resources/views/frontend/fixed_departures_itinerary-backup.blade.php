@extends('layouts.web')
@section('title', 'Itinerary Phoenix Tours & Travels')
@section('css')
<style>
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }
    .otp-input {
        width: 50px;
        height: 50px;
        text-align: center;
        font-size: 20px;
        margin: 0 5px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
    }
    .otp-input:focus {
        border-color: #0d6efd;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
</style>
@endsection

@section('content')
<div class="main-container">
    <!-- Breadcrumb Bar -->
    <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">{{$tour->package_title}}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="isax isax-home5"></i></a></li>
                            <li class="breadcrumb-item">Fixed Departures</li>
                            <li class="breadcrumb-item active" aria-current="page">Itinerary</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-3"></div>
        <div class="col-xl-6">
            <div class="bg-light-200 card-bg-light mb-4" id="Itinerary">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fs-18 mb-0">{{$tour->package_title}} Itinerary</h5>
                    <!-- Download Button -->
                    <button type="button" class="btn btn-primary" onclick="openDownloadModal()">
                        <i class="isax isax-document-download me-2"></i>Download Itinerary
                    </button>
                </div>
                
                <div class="accordion faq-accordion" id="itineraryAccordion">
                    @foreach($package_itinerary as $key => $val)
                    <div class="accordion-item mb-3">
                        <div class="accordion-header">
                            <button class="accordion-button fw-medium {{ $key !== 0 ? 'collapsed' : '' }}" 
                                type="button" 
                                data-bs-toggle="collapse"
                                data-bs-target="#faq-collapse_{{ $key + 1 }}" 
                                aria-expanded="{{ $key === 0 ? 'true' : 'false' }}"
                                aria-controls="faq-collapse_{{ $key + 1 }}">
                                <span class="me-2-day">Day {{ $key + 1 }}</span>&nbsp;&nbsp;{{ $val->question }}
                            </button>
                        </div>
                        <div id="faq-collapse_{{ $key + 1 }}" 
                            class="accordion-collapse collapse {{ $key === 0 ? 'show' : '' }}"
                            data-bs-parent="#itineraryAccordion">
                            <div class="accordion-body">
                                <p class="mb-3">{{ $val->answer }}</p>
                                
                                @if(!empty($val->image) || !empty($val->image_two))
                                <div class="row mt-3">
                                    @if(!empty($val->image))
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <img src="{{ asset($val->image) }}" 
                                             alt="Itinerary Image" 
                                             style="height: 160px; width: 100%; border-radius: 13px; object-fit: cover;">
                                    </div>
                                    @endif
                                    @if(!empty($val->image_two))
                                    <div class="col-md-6">
                                        <img src="{{ asset($val->image_two) }}" 
                                             alt="Itinerary Image Two" 
                                             style="height: 160px; width: 100%; border-radius: 13px; object-fit: cover;">
                                    </div>
                                    @endif
                                </div>
                                @endif
                                
                                    @if(!empty($itinerary_image) && count($itinerary_image) > 0)
                                            <div class="bg-light-200 card-bg-light mb-4">
                                                <div class="tour-gallery-slider owl-carousel">
                                                    @foreach($itinerary_image as $key => $row)
                                                    @if($val->id == $row->itinerary_id)
                                                    <a class="galley-wrap" data-fancybox="gallery"
                                                        href="{{ asset('uploads/itinerary/' . $row->image) }}">
                                                        <img src="{{ asset('uploads/itinerary/' . $row->image) }}" alt="img">
                                                    </a>
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                    @endif
                                    
                                            <div class="d-flex align-items-center justify-content-between border-top pt-3">
                                                <div class="w-100">
                                                    @php
                                                        $amenities = explode('##', $val->short_description);
                                                    @endphp
                                            
                                                    <div class="row">
                                                        @foreach($amenities as $item)
                                                            @if(trim($item) !== '')
                                                                <div class="col-md-4 col-4 mb-2">
                                                                    <li>{{ trim($item) }}</li>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-xl-3"></div>
    </div>
</div>

<!-- Download Form Modal -->
<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadModalLabel">Download Itinerary PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="downloadForm" onsubmit="return false;">
                    @csrf
                    <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                    
                    <!-- Step 1: User Details -->
                    <div id="step1">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required placeholder="Enter your full name">
                            <div class="invalid-feedback" id="full_name_error"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email address">
                            <div class="invalid-feedback" id="email_error"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" required placeholder="Enter your phone number">
                            <div class="invalid-feedback" id="phone_error"></div>
                        </div>
                        
                        <button type="button" class="btn btn-primary w-100" onclick="sendOTP()">
                            <i class="isax isax-sms me-2"></i>Send OTP
                        </button>
                    </div>
                    
                    <!-- Step 2: OTP Verification -->
                    <div id="step2" style="display: none;">
                        <p class="text-center mb-3">Enter the OTP sent to your email</p>
                        <div class="d-flex justify-content-center mb-3">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this)" onkeydown="moveToPrev(event, this)">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this)" onkeydown="moveToPrev(event, this)">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this)" onkeydown="moveToPrev(event, this)">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this)" onkeydown="moveToPrev(event, this)">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this)" onkeydown="moveToPrev(event, this)">
                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this)" onkeydown="moveToPrev(event, this)">
                        </div>
                        <input type="hidden" name="otp" id="fullOtp">
                        <div class="invalid-feedback text-center mb-2" id="otp_error"></div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" onclick="resendOTP()">
                                <i class="isax isax-refresh me-1"></i>Resend OTP
                            </button>
                            <button type="button" class="btn btn-primary" onclick="verifyOTP()">
                                <i class="isax isax-shield-tick me-1"></i>Verify & Download
                            </button>
                        </div>
                    </div>
                    
                    <!-- Loading State -->
                    <div id="loadingState" style="display: none;" class="text-center py-3">
                        <div class="spinner-border text-primary mb-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p id="loadingMessage">Processing...</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="isax isax-tick-circle text-success" style="font-size: 60px;"></i>
                </div>
                <h4 class="mb-2">OTP Verified Successfully!</h4>
                <p class="text-muted mb-0">Your itinerary PDF download will start automatically.</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let downloadModal;
    
    document.addEventListener('DOMContentLoaded', function() {
        downloadModal = new bootstrap.Modal(document.getElementById('downloadModal'));
    });
    
    function openDownloadModal() {
        // Reset form
        document.getElementById('step1').style.display = 'block';
        document.getElementById('step2').style.display = 'none';
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('downloadForm').reset();
        document.getElementById('fullOtp').value = '';
        document.querySelectorAll('.otp-input').forEach(input => input.value = '');
        
        // Clear errors
        clearErrors();
        
        downloadModal.show();
    }
    
    function clearErrors() {
        ['full_name', 'email', 'phone', 'otp'].forEach(field => {
            const input = document.getElementById(field);
            const errorDiv = document.getElementById(field + '_error');
            if (input) input.classList.remove('is-invalid');
            if (errorDiv) errorDiv.textContent = '';
            if (errorDiv) errorDiv.style.display = 'none';
        });
    }
    
    function showError(fieldId, message) {
        const input = document.getElementById(fieldId);
        const errorDiv = document.getElementById(fieldId + '_error');
        
        if (input) input.classList.add('is-invalid');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
    }
    
    function sendOTP() {
        clearErrors();
        
        const fullName = document.getElementById('full_name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const tourId = document.querySelector('input[name="tour_id"]').value;
        
        // Basic validation
        let isValid = true;
        
        if (!fullName) {
            showError('full_name', 'Full name is required');
            isValid = false;
        }
        
        if (!email) {
            showError('email', 'Email is required');
            isValid = false;
        } else if (!isValidEmail(email)) {
            showError('email', 'Please enter a valid email address');
            isValid = false;
        }
        
        if (!phone) {
            showError('phone', 'Phone number is required');
            isValid = false;
        } else if (phone.length < 10) {
            showError('phone', 'Please enter a valid phone number');
            isValid = false;
        }
        
        if (!isValid) return;
        
        // Show loading
        showLoading('Sending OTP...');
        
        // Send OTP request
        fetch('{{ route("send.otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                full_name: fullName,
                email: email,
                phone: phone,
                tour_id: tourId
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                // Switch to OTP step
                document.getElementById('step1').style.display = 'none';
                document.getElementById('step2').style.display = 'block';
                
                // Focus on first OTP input
                setTimeout(() => {
                    document.querySelector('.otp-input').focus();
                }, 100);
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'OTP Sent!',
                    text: data.message || 'OTP has been sent to your email',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // If debug OTP is available, show it
                if (data.debug_otp) {
                    console.log('Debug OTP:', data.debug_otp);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to send OTP'
                });
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.'
            });
        });
    }
    
    function verifyOTP() {
        // Get OTP from individual inputs
        const otpInputs = document.querySelectorAll('.otp-input');
        let otp = '';
        
        otpInputs.forEach(input => {
            otp += input.value;
        });
        
        document.getElementById('fullOtp').value = otp;
        
        if (otp.length !== 6) {
            showError('otp', 'Please enter complete 6-digit OTP');
            return;
        }
        
        clearErrors();
        
        const fullName = document.getElementById('full_name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const tourId = document.querySelector('input[name="tour_id"]').value;
        
        // Show loading
        showLoading('Verifying OTP...');
        
        // Verify OTP
        fetch('{{ route("verify.otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                full_name: fullName,
                email: email,
                phone: phone,
                tour_id: tourId,
                otp: otp
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                hideLoading();
                
                // Close download modal
                downloadModal.hide();
                
                // Show success modal briefly
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
                
                // Auto-start download after short delay
                setTimeout(() => {
                    // Trigger PDF download by redirecting to download URL
                    window.location.href = data.download_url;
                    
                    // Close success modal after download starts
                    setTimeout(() => {
                        successModal.hide();
                    }, 1000);
                }, 1500);
                
            } else {
                hideLoading();
                showError('otp', data.message || 'Invalid OTP');
                
                // Clear OTP inputs on error
                document.querySelectorAll('.otp-input').forEach(input => input.value = '');
                document.querySelector('.otp-input').focus();
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.'
            });
        });
    }
    
    function resendOTP() {
        const fullName = document.getElementById('full_name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const tourId = document.querySelector('input[name="tour_id"]').value;
        
        showLoading('Resending OTP...');
        
        fetch('{{ route("send.otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                full_name: fullName,
                email: email,
                phone: phone,
                tour_id: tourId
            })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                // Clear OTP inputs
                document.querySelectorAll('.otp-input').forEach(input => input.value = '');
                document.getElementById('fullOtp').value = '';
                
                // Focus on first OTP input
                document.querySelector('.otp-input').focus();
                
                Swal.fire({
                    icon: 'success',
                    title: 'OTP Resent!',
                    text: data.message || 'New OTP has been sent to your email',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                if (data.debug_otp) {
                    console.log('Debug OTP:', data.debug_otp);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to resend OTP'
                });
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to resend OTP'
            });
        });
    }
    
    // OTP Input handlers
    function moveToNext(input) {
        if (input.value.length === 1) {
            const nextInput = input.nextElementSibling;
            if (nextInput && nextInput.classList.contains('otp-input')) {
                nextInput.focus();
            }
        }
        // Auto-submit when all 6 digits are entered
        checkAutoSubmit();
    }
    
    function moveToPrev(event, input) {
        if (event.key === 'Backspace' && input.value.length === 0) {
            const prevInput = input.previousElementSibling;
            if (prevInput && prevInput.classList.contains('otp-input')) {
                prevInput.focus();
            }
        }
    }
    
    function checkAutoSubmit() {
        const otpInputs = document.querySelectorAll('.otp-input');
        let allFilled = true;
        
        otpInputs.forEach(input => {
            if (input.value.length === 0) {
                allFilled = false;
            }
        });
        
        // Optional: Auto-submit when all 6 digits are entered
        // Uncomment the line below if you want auto-submit
        // if (allFilled) verifyOTP();
    }
    
    // Utility functions
    function showLoading(message) {
        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('loadingMessage').textContent = message;
        document.getElementById('step1').style.display = 'none';
        document.getElementById('step2').style.display = 'none';
    }
    
    function hideLoading() {
        document.getElementById('loadingState').style.display = 'none';
    }
    
    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
</script>
@endsection