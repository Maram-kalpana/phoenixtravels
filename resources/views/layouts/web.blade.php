<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Phoenix Tours & Travels | Tour Packages & Travel Agency Hyderabad</title>
<meta name="description" content="Phoenix Tours & Travels offers affordable India & international tour packages from Hyderabad, including family, honeymoon, group and customized holidays.">
<meta name="keywords" content="Phoenix Tours and Travels,travel agency in Hyderabad,best travel agency in Hyderabad,tour operator in Hyderabad,tour packages from Hyderabad,holiday packages from Hyderabad,travel agency Hyderabad,domestic tour packages,customized tour packages,group tour packages,affordable tour packages,honeymoon tour packages,family tour packages,budget tour packages,luxury tour packages,weekend tour packages,Phoenix Tours and Travels online travel agency ">
<meta name="author" content="">
<meta name="robots" content="index, follow">
<link rel="apple-touch-icon" sizes="180x180" href="{{asset('frontend/img/logo1.png')}}">
<link rel="icon" href="{{asset('frontend/img/logo1.png')}}" type="image/x-icon">
<link rel="shortcut icon" href="{{asset('frontend/img/logo1.png')}}" type="image/x-icon">

<!-- Font Awesome 6 (CDN - Most Reliable) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Font Awesome 5 Fallback -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- CSS Files -->
<link rel="stylesheet" href="{{asset('frontend/css/animate.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/meanmenu.css')}}">
<link rel="stylesheet" href="{{asset('frontend/plugins/tabler-icons/tabler-icons.css')}}">
<link rel="stylesheet" href="{{asset('frontend/plugins/fontawesome/css/fontawesome.min.css')}}">
<link rel="stylesheet" href="{{asset('frontend/plugins/fontawesome/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('frontend/plugins/owlcarousel/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('frontend/plugins/slick/slick.css')}}">
<link rel="stylesheet" href="{{asset('frontend/plugins/fancybox/jquery.fancybox.min.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/iconsax.css')}}">
<link rel="stylesheet" href="{{asset('frontend/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/bootstrap-datetimepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('frontend/plugins/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/style.css')}}">
<link rel="stylesheet" href="{{asset('frontend/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}">
<link rel="stylesheet" href="{{asset('frontend/plugins/ion-rangeslider/css/ion.rangeSlider.min.css')}}">

 
<style>
 .offer-card {
    background: #f2f2f2;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    flex-direction: column;
    padding: 12px 16px;
    display: flex;
 }
 .card-style {
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: #71bbd9;
    color: #fff !important;
    border-color: #71bbd9;
    border-radius: 6px;

 }
    /* Ensure Font Awesome icons display properly */
    .fa, .fas, .far, .fal, .fab, .fa-solid, .fa-regular, .fa-brands {
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
    }
    
    /* Fix for specific icon classes */
    .fa-chevron-left:before,
    .fa-chevron-right:before,
    .fa-arrow-left:before,
    .fa-arrow-right:before,
    .fa-solid:before {
        font-family: "Font Awesome 6 Free" !important;
        font-weight: 900 !important;
    }
    
    /* Fallback for older icons */
    .fa {
        font-family: "Font Awesome 6 Free" !important;
        font-weight: 900 !important;
    }
    
</style>
<style>
.amenities-container {
    /*width: 100%;*/
}

.amenities-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0px 25px !important; /* row gap | column gap */
    margin: 0;
    padding: 0;
    width: 100%;
}
.color_organ{
     color: #ff9d5b !important;
}
.color_blue a{
    color: #1d9cd0 !important;
}
.tour_title a{
    color: #ff9d5b !important;
}

.amenities-grid .amenity-item {
    display: flex;
    align-items: center;
    /*padding: 6px 0;*/
    list-style: none;
}

.amenities-grid .amenity-item li {
    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 12px;
    color: #2fa2d1;
    display: flex;
    align-items: center;
    background-color: #fff !important;
}

.amenities-grid .amenity-item li:before {
    content: "✓";
    color: #28a745;
    font-weight: bold;
    margin-right: 8px;
    font-size: 12px;
}

.amenities-grid .amenity-items li {
    margin: 0;
    padding: 0;
    list-style: none;
    font-size: 12px;
    color: #555;
    display: flex;
    align-items: center;
}

.amenities-grid .amenity-items li:before {
    content: "•"; /* better bullet */
    color: #24262b;
    font-weight: bold;
    margin-right: 8px;
    font-size: 18px; /* increase size for stronger look */
}

/* Responsive */
@media (max-width: 768px) {
    .amenities-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
}

/* Alternative bullet styles */
.amenities-grid .amenity-item li.bullet-dot:before {
    content: "•";
    color: #007bff;
    font-size: 13px;
}

.amenities-grid .amenity-item li.bullet-circle:before {
    content: "○";
    color: #ffc107;
}

.amenities-grid .amenity-item li.bullet-star:before {
    content: "★";
    color: #ffc107;
}

.amenities-list {
    list-style-position: inside;   /* keeps bullets aligned with text */
    padding: 0;
    margin: 0;
    text-align: center;            /* center text + bullets */
}

.amenity-item {
    margin-bottom: 4px;
    margin-left: 10px;
}
.image-rounded {
    border-radius: 12px !important;
}
</style>
 @yield('css')
</head>
<body>
<div class="main-header">
@include('partials.header')
</div>

<!-- Hero Section -->
@yield('content')
<!-- /Hero Section -->

@include('partials.footer')

<!-- JavaScript Files -->
<script src="{{asset('frontend/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('frontend/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('frontend/js/wow.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery.meanmenu.min.js')}}"></script>
<script src="{{asset('frontend/plugins/owlcarousel/owl.carousel.min.js')}}"></script>
<script src="{{asset('frontend/plugins/theia-sticky-sidebar/ResizeSensor.js')}}"></script>
<script src="{{asset('frontend/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js')}}"></script>
<script src="{{asset('frontend/plugins/fancybox/jquery.fancybox.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery.counterup.min.js')}}"></script>
<script src="{{asset('frontend/plugins/slick/slick.min.js')}}"></script>
<script src="{{asset('frontend/js/jquery.waypoints.min.js')}}"></script>

<!-- Rangeslider JS -->
<script src="{{asset('frontend/plugins/ion-rangeslider/js/ion.rangeSlider.js')}}"></script>
<script src="{{asset('frontend/plugins/ion-rangeslider/js/custom-rangeslider.js')}}"></script>
<script src="{{asset('frontend/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>

<!-- Date & Time Pickers -->
<script src="{{asset('frontend/plugins/moment/moment.js')}}"></script>
<script src="{{asset('frontend/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{asset('frontend/plugins/daterangepicker/daterangepicker.js')}}"></script>

<!-- Custom Scripts -->
<script src="{{asset('frontend/js/theme-script.js')}}"></script>
<script src="{{asset('frontend/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{asset('frontend/js/cursor.js')}}"></script>
<script src="{{asset('frontend/js/script.js')}}"></script>

<!-- Additional Libraries -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{asset('backend/js/notify.min.js')}}"></script>
<script src="{{asset('backend/sweetalerts/sweetalert.min.js')}}"></script>
<script src="{{asset('backend/plugins/notify/js/notifIt.js')}}"></script>

<script>
// Notify function
function notify(id = null, message = 'Something went wrong', position = 'top right', type = 'error', willReload = false, url = null, timeout = 3000) {
    if (id != null && id != 'input[name=error]' && id != 'input[name=error]') {
        $(id).notify(message, {
            position: position,
            className: type
        });
    } else {
        $.notify(message, {
            position: 'top right',
            className: type
        });
    }
    if (willReload) {
        setTimeout(function() {
            if (url == null) {
                window.location.reload();
            } else {
                window.location.href = url;
            }
        }, timeout);
    }
}

// Check if lightbox is defined before using it
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lightbox !== 'undefined' && lightbox !== null) {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        });
    } else {
        console.warn('Lightbox library is not loaded');
    }
});

// Fix for Font Awesome icons if they don't load properly
$(document).ready(function() {
    // Check if Font Awesome is loaded
    if (typeof FontAwesome === 'undefined') {
        // Force reload Font Awesome if missing
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css';
        document.head.appendChild(link);
    }
});
</script>
  <script>
    // Login Form Submission
    $("#login_submit").submit(function(event) {
        event.preventDefault();
        
        var phone = $("#phone").val();
        if (!phone) {
            notify(null, 'Please enter mobile number', 'top right', 'error');
            return false;
        }
        
        if (phone.length != 10) {
            notify(null, 'Please enter valid 10-digit mobile number', 'top right', 'error');
            return false;
        }
        
        $("#login_btn").prop('disabled', true);
        $("#login_btn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending OTP...');
        
        axios.post("{{ route('user.login') }}", new FormData($("#login_submit")[0]))
            .then(response => {
                var data = response.data;
                if (data.success) {
                    // Hide login form, show OTP form
                    $("#login_submit").hide();
                    $("#otp_submit").show();
                    $("#otp_phone").val(phone);
                    
                    notify(null, 'OTP has been sent to your mobile number', 'top right', 'success');
                } else {
                    $("#login_btn").prop('disabled', false);
                    $("#login_btn").html('Login <i class="isax isax-arrow-right-3 ms-2"></i>');
                    
                    if (data.error && data.error.message) {
                        for (var a in data.error.message) {
                            notify(null, data.error.message[a][0], 'top right', 'error');
                        }
                    } else {
                        notify(null, 'Failed to send OTP', 'top right', 'error');
                    }
                }
            })
            .catch(error => {
                $("#login_btn").prop('disabled', false);
                $("#login_btn").html('Login <i class="isax isax-arrow-right-3 ms-2"></i>');
                notify(null, 'Something went wrong. Please try again.', 'top right', 'error');
                console.log(error);
            });
    });
    
    // OTP Verification
    $("#otp_submit").submit(function(event) {
        event.preventDefault();
        
        var otp = $("#otp").val();
        if (!otp) {
            notify(null, 'Please enter OTP', 'top right', 'error');
            return false;
        }
        
        if (otp.length != 6) {
            notify(null, 'Please enter valid 6-digit OTP', 'top right', 'error');
            return false;
        }
        
        $("#verify_btn").prop('disabled', true);
        $("#verify_btn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verifying...');
        
        axios.post("{{ route('user.otp') }}", new FormData($("#otp_submit")[0]))
            .then(response => {
                var data = response.data;
                if (data.success) {
                    notify(null, 'Login successful!', 'top right', 'success');
                    // Close modal after successful login
                    setTimeout(function() {
                        $('#login-modal').modal('hide');
                        // Reset forms
                        $("#login_submit")[0].reset();
                        $("#otp_submit")[0].reset();
                        $("#login_submit").show();
                        $("#otp_submit").hide();
                        // Reload page or redirect
                        location.reload(); // or window.location.href = '/dashboard';
                    }, 1000);
                } else {
                    $("#verify_btn").prop('disabled', false);
                    $("#verify_btn").html('Verify OTP <i class="isax isax-arrow-right-3 ms-2"></i>');
                    notify(null, data.message || 'Invalid OTP', 'top right', 'error');
                }
            })
            .catch(error => {
                $("#verify_btn").prop('disabled', false);
                $("#verify_btn").html('Verify OTP <i class="isax isax-arrow-right-3 ms-2"></i>');
                notify(null, 'Something went wrong. Please try again.', 'top right', 'error');
                console.log(error);
            });
    });
    
    // Resend OTP
    $("#resend_otp").click(function(event) {
        event.preventDefault();
        
        var phone = $("#otp_phone").val();
        
        axios.post("{{ route('user.login') }}", {
            phone: phone,
            _token: '{{ csrf_token() }}'
        })
        .then(response => {
            var data = response.data;
            if (data.success) {
                notify(null, 'OTP resent successfully!', 'top right', 'success');
            } else {
                notify(null, 'Failed to resend OTP', 'top right', 'error');
            }
        })
        .catch(error => {
            notify(null, 'Something went wrong', 'top right', 'error');
        });
    });
    
    // Reset modal when closed
    $('#login-modal').on('hidden.bs.modal', function () {
        $("#login_submit")[0].reset();
        $("#otp_submit")[0].reset();
        $("#login_submit").show();
        $("#otp_submit").hide();
        $("#login_btn").prop('disabled', false);
        $("#login_btn").html('Login <i class="isax isax-arrow-right-3 ms-2"></i>');
        $("#verify_btn").prop('disabled', false);
        $("#verify_btn").html('Verify OTP <i class="isax isax-arrow-right-3 ms-2"></i>');
    });
</script>
@yield('script')

</body>
</html>