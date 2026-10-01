
    <!-- Footer -->
    <footer>
        <div class="container-fluid">
            <div class="footer-top">
                <div class="row row-cols-lg-5 row-cols-md-3 row-cols-sm-2 row-cols-1">
                    <div class="col">
                        <div class="footer-widget">
                            <h5 style="color: #fff !important;">Company</h5>
                            <ul class="footer-menu">
                                <li><a href="{{route('home')}}">Home</a></li>
                                <li><a href="{{route('about')}}">About Us</a></li>
                                <li><a href="">India</a></li>
                                <li><a href="{{route('gallery')}}">Gallery</a></li>
                                <li><a href="{{route('blog')}}">Blog</a></li>
                                <li><a href="{{route('contact')}}">Contact Us</a></li>
                                <li><a href="{{route('privacy_policy')}}">Privacy Policy</a></li>
                                <li><a href="{{route('term_conditions')}}">Terms & Conditions</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col">
                        <div class="footer-widget">
                            <h5 style="color: #fff !important;">Top Destinations</h5>
                            <ul class="footer-menu">
                                @php
                                    $result1 = DB::table('tour')->select('id', 'tour_name','slug')->where('tour_category_id', '1')->where('status', '1')->orderBy('tour_name', 'desc')->limit(9)->get();
                                @endphp
                                @foreach($result1 as $key => $rrr)
                                    <li><a href="{{route('tour',$rrr->slug)}}">{{ $rrr->tour_name; }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                   <div class="col">
                        <div class="footer-widget">
                            <h5 style="color: #fff !important;">International  Packages</h5>
                            <ul class="footer-menu">
                               @php 
                                $tours = DB::table('tour')->select('id', 'tour_name','slug')->where('tour_category_id', 2)->where('status', 1)->orderBy('tour_name', 'desc')->limit(9)->get();
                                @endphp
                                @foreach($tours as $key => $val)
                                <li><a href="{{ route('tour', $val->slug) }}">{{ $val->tour_name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col">
                        <div class="footer-widget">
                            <h5 style="color: #fff !important;">Honeymoon Packages</h5>
                            <ul class="footer-menu">
                               @php 
                                $tours = DB::table('tour')->select('id', 'tour_name','slug')->where('tour_category_id', 3)->where('status', 1)->orderBy('tour_name', 'desc')->limit(9)->get();
                                @endphp
                                @foreach($tours as $key => $val)
                                <li><a href="{{ route('tour', $val->slug) }}">{{ $val->tour_name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col">
                        <div class="footer-widget">
                            <h5 style="color: #fff !important;">Cruise Packages</h5>
                            <ul class="footer-menu">
                               @php 
                                $tours = DB::table('tour')->select('id', 'tour_name','slug')->where('tour_category_id', 4)->where('status', 1)->orderBy('tour_name', 'desc')->limit(9)->get();
                                @endphp
                                @foreach($tours as $key => $val)
                                <li><a href="{{ route('tour', $val->slug) }}">{{ $val->tour_name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                </div>
                <div class="footer-wrap bg-white"  style="--bs-gutter-x: 0rem;">
                    <div class="row align-items-center">
                        <div class="col-lg-4 col-xl-4 col-xxl-4">
                            <div class="d-flex align-items-center justify-content-center flex-wrap">
                                <h6 class="fs-14 fw-medium me-2 mb-0">Available on :</h6>
                                <a href="#" class="d-block me-2">
                                    <img src="{{asset('frontend/img/icons/googleplay.svg')}}" alt="Google Play">
                                </a>
                                <a href="#" class="d-block">
                                    <img src="{{asset('frontend/img/icons/appstore.svg')}}" alt="App Store">
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-8 col-xl-8 col-xxl-8">
                            <div class="d-sm-flex align-items-center justify-content-center justify-content-xl-end">
                                <div class="d-flex align-items-center justify-content-center justify-content-sm-start me-sm-3 pe-sm-3 border-end-sm mb-3">
                                    <span class="avatar avatar-lg bg-primary rounded-circle flex-shrink-0">
                                        <i class="ti ti-headphones-filled fs-24"></i>
                                    </span>
                                    <div class="ms-2">
                                        <p class="mb-1">Customer Support</p>
                                        <p class="fw-medium text-dark mb-0">
                                            <a href="tel:+8790006059">+91 8790006059</a>
                                        </p>
                                        <p class="fw-medium text-dark mb-0">
                                            <a href="tel:+8790006069">+91 8790006069</a>
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center justify-content-sm-start mb-3">
                                    <span class="avatar avatar-lg bg-secondary rounded-circle flex-shrink-0">
                                        <i class="ti ti-message fs-24 text-gray-9"></i>
                                    </span>
                                    <div class="ms-2">
                                        <p class="mb-1">Drop Us an Email</p>
                                        <p class="fw-medium text-dark mb-0">
                                            <a href="mailto:support@phoenixtoursandtravels.in">support@phoenixtoursandtravels.in</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <p class="fs-14">Copyright © 2024 - {{date('Y')}} Phoenix Tours & Travels All Rights Reserved | Developed By <a href="https://innoplus.in/" target="_blank">Innoplus Technologies</a></p>
                            <div class="d-flex align-items-center">
                                <ul class="social-icon">
                                    <li>
                                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa-brands fa-pinterest"></i></a>
                                    </li>
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Footer Bottom -->

    </footer>
    <!-- /Footer -->

   <!-- Login Modal -->
<div class="modal fade" id="login-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center justify-content-end pb-0 border-0">
                <a href="#" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x fs-20"></i></a>
            </div>
            <div class="modal-body p-4 pt-0">
                <!-- Login Form -->
                <form id="login_submit" method="post">
                    @csrf
                    <div class="text-center mb-3">
                        <h5 class="mb-1">Welcome Back</h5>
                        <p>Login using your mobile number</p>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Mobile Number</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="isax isax-message"></i>
                            </span>
                            <input type="text" name="phone" id="phone" class="form-control form-control-lg" placeholder="Enter Mobile Number">
                        </div>
                        <input type="hidden" class="form-control" name="referral_by" id="referral_by" value="{{ request()->get('ref') }}">
                    </div>
                    <div class="mb-3">
                        <button type="submit" id="login_btn" class="btn btn-xl btn-primary d-flex align-items-center justify-content-center w-100">
                            Login<i class="isax isax-arrow-right-3 ms-2"></i>
                        </button>
                    </div>
                </form>

                <!-- OTP Verification Form (Hidden Initially) -->
                <form id="otp_submit" method="post" style="display: none;">
                    @csrf
                    <div class="text-center mb-3">
                        <h5 class="mb-1">Verify OTP</h5>
                        <p>Enter OTP sent to your mobile</p>
                    </div>
                    <input type="hidden" name="phone" id="otp_phone">
                    <div class="mb-2">
                        <label class="form-label">OTP</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="isax isax-key"></i>
                            </span>
                            <input type="text" name="otp" id="otp" class="form-control form-control-lg" placeholder="Enter OTP">
                        </div>
                    </div>
                    <div class="mb-2">
                        <button type="submit" id="verify_btn" class="btn btn-xl btn-primary d-flex align-items-center justify-content-center w-100">
                            Verify OTP<i class="isax isax-arrow-right-3 ms-2"></i>
                        </button>
                    </div>
                    <div class="text-center">
                        <a href="#" id="resend_otp" class="text-muted">Resend OTP</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Login Modal -->

   
  
    <!-- Cursor -->
    <div class="xb-cursor tx-js-cursor">
        <div class="xb-cursor-wrapper">
            <div class="xb-cursor--follower xb-js-follower"></div>
        </div>
    </div>
    <!-- /Cursor -->

    <div class="back-to-top">
        <a class="back-to-top-icon align-items-center justify-content-center d-flex" href="#top"><i
                class="fa-solid fa-arrow-up"></i></a>
    </div>