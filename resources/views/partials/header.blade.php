@php
use App\Models\ToursModel;
use App\Models\InternationalToursModel;
use App\Models\HoneymoonToursModel;
use Illuminate\Support\Facades\DB;
@endphp
<div class="header-topbar text-center bg-transparent">
            <div class="container">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <p class="d-flex align-items-center fw-medium fs-14 mb-2"><i class="isax isax-call5 me-2"></i>Customer Support : +91 8790006059/8790006069</p>
                    <div class="d-flex align-items-center">
                        <p class="mb-2 me-3 d-flex align-items-center fw-medium fs-14"><i
                                class="isax isax-message-text-15 me-2"></i>Email : <a href="mailto:support@phoenixtoursandtravels.in" style="color: #fff;">support@phoenixtoursandtravels.in</a></p>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- /Header Topbar-->

        <!-- Header -->
        <header>
            <div class="container">
                <div class="offcanvas-info">
                    <div class="offcanvas-wrap">
                        <div class="offcanvas-detail">
                            <div class="offcanvas-head">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <a href="{{route('home')}}" class="black-logo-responsive">
                                        <img src="{{asset('frontend/img/logo-1.png')}}" alt="logo-img" style="height: 75px;">
                                    </a>
                                    <a href="{{route('home')}}" class="white-logo-responsive">
                                        <img src="{{asset('frontend/img/logo-1.png')}}" alt="logo-img" style="height: 75px;">
                                    </a>
                                    <div class="offcanvas-close">
                                        <i class="ti ti-x"></i>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="mobile-menu fix mb-3"></div>
                            <div class="offcanvas__contact">
                                <div class="mt-4">
                                    <div class="header-dropdown d-flex flex-fill">
                                        <div class="w-100">
                                        </div>
                                    </div>
                                     @php
                                use App\Models\CustomerOtpModel;
                                use App\Models\CustomerModel;
                                $customer_id = \Session::get('customer_id');
                                $customer_details =  CustomerOtpModel::select('*')->where('customer_id', $customer_id)->orderBy('id', 'desc')->first();
                                $customer_detail =  CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
                                @endphp
                                @if(!empty($customer_id) && empty($customer_details->customer_id))
                            <div><a href="{{route('user.dashboard')}}" class="btn btn-white me-3">Dashboard</a></div>
                            @else
                                   <div><a href="#" class="text-white btn btn-dark w-100 mb-3" data-bs-toggle="modal"
                                            data-bs-target="#login-modal">Sign In</a></div>
                            @endif       
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="offcanvas-overlay"></div>
                <div class="header-nav">
                    <div class="main-menu-wrapper">
                        <div class="navbar-logo">
                            <a class="logo-white header-logo" href="{{route('home')}}">
                                <img src="{{asset('frontend/img/logo-1.png')}}" class="logo" alt="Logo" style="height: 75px;border-radius: 10%;">
                            </a>
                            <a class="logo-dark header-logo" href="{{route('home')}}">
                                <img src="{{asset('frontend/img/logo1.png')}}" class="logo" alt="Logo" style="height: 75px;">
                            </a>
                        </div>
                        <nav id="mobile-menu">
                            <ul class="main-nav">
                                <li>
                                    <a href="{{route('flight.list')}}">Flight</a>
                                </li>
                                <li>
                                    <a href="{{route('hotel.list')}}">Hotel</a>
                                   
                                </li>
                                 <li class="has-submenu megamenutab active">
                                    <a href="#">Holidays<i class="fa-solid fa-angle-down"></i></a>
                                    <ul class="submenu mega-submenu">
                                        <li>
                                            <div class="megamenu-wrapper">
                                                <div class="row">
                                                    <!-- Left Side Tabs -->
                                                    
                                                    <!-- Right Side Content -->
                                                    <div class="col-lg-12">
                                                        <div class="tab-content tab-menulist" id="menu-tabContent">
                                                            <div class="tab-pane fade show active" id="activity" role="tabpanel">
                                                                <div class="row">
                                                                        <div class="row">
                                                                            @php
                                                                                $tours = ToursModel::where('status', 1)->where('tour_category_id', 1)->select('id', 'tour_name','slug')->orderBy('tour_name', 'asc')->get();
                                                                                $chunks = $tours->chunk(6); // Split into chunks of 6 items each
                                                                            @endphp
                                                                            
                                                                            @foreach($chunks as $chunk)
                                                                            <div class="col-lg-4">
                                                                                <ul>
                                                                                    @foreach($chunk as $tour)
                                                                                        <li>
                                                                                            <a href="{{ route('tour', $tour->slug) }}" class="text-decoration-none">
                                                                                                {{ $tour->tour_name }}
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                </div>
                                                            </div>
                                                           
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li class="has-submenu megamenutab active">
                                    <a href="#">International<i class="fa-solid fa-angle-down"></i></a>
                                    <ul class="submenu mega-submenu">
                                        <li>
                                            <div class="megamenu-wrapper">
                                                <div class="row">
                                                    <!-- Right Side Content -->
                                                    <div class="col-lg-12">
                                                        <div class="tab-content tab-menulist" id="menu-tabContent">
                                                            <div class="tab-pane fade show active" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="row">
                                                                            @php
                                                                                $tours = InternationalToursModel::where('status', 1)->where('tour_category_id', 2)->select('id', 'tour_name','slug')->orderBy('tour_name', 'asc')->get();
                                                                                $chunks = $tours->chunk(6); // Split into chunks of 6 items each
                                                                            @endphp
                                                                            
                                                                            @foreach($chunks as $chunk)
                                                                            <div class="col-lg-4">
                                                                                <ul>
                                                                                    @foreach($chunk as $tour)
                                                                                        <li>
                                                                                            <a href="{{ route('international.tour', $tour->slug) }}" class="text-decoration-none">
                                                                                                {{ $tour->tour_name }}
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li class="has-submenu megamenutab active">
                                    <a href="#">Honeymoon<i class="fa-solid fa-angle-down"></i></a>
                                    <ul class="submenu mega-submenu">
                                        <li>
                                            <div class="megamenu-wrapper">
                                                <div class="row">
                                                    <!-- Left Side Tabs -->
                                                    <div class="col-lg-3">
                                                        <div class="nav flex-column nav-pills menu-tab" id="menu-tab"
                                                            role="tablist" aria-orientation="vertical">
                                                            <ul>
                                                                <li>
                                                                    <a class="nav-link active" id="activity-tab"
                                                                        data-bs-toggle="tab" href="#hactivity"
                                                                        role="tab">
                                                                        India
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="nav-link" id="visa-tab"
                                                                        data-bs-toggle="tab" href="#hvisa" role="tab">
                                                                        International
                                                                    </a>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <!-- Right Side Content -->
                                                    <div class="col-lg-9">
                                                        <div class="tab-content tab-menulist" id="menu-tabContent">
                                                            <div class="tab-pane fade show active" id="hactivity" role="tabpanel">
                                                                <div class="row">
                                                                        <div class="row">
                                                                            @php
                                                                                $tours = HoneymoonToursModel::where('status', 1)->where('tour_category_id', 1)->select('id', 'tour_name','slug')->orderBy('tour_name', 'asc')->get();
                                                                                $chunks = $tours->chunk(6); // Split into chunks of 6 items each
                                                                            @endphp
                                                                            
                                                                            @foreach($chunks as $chunk)
                                                                            <div class="col-lg-4">
                                                                                <ul>
                                                                                    @foreach($chunk as $tour)
                                                                                        <li>
                                                                                            <a href="{{ route('honeymoon.tour', $tour->slug) }}" class="text-decoration-none">
                                                                                                {{ $tour->tour_name }}
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="hvisa" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="row">
                                                                            @php
                                                                                $tours = HoneymoonToursModel::where('status', 1)->where('tour_category_id', 2)->select('id', 'tour_name','slug')->orderBy('tour_name', 'asc')->get();
                                                                                $chunks = $tours->chunk(6); // Split into chunks of 6 items each
                                                                            @endphp
                                                                            
                                                                            @foreach($chunks as $chunk)
                                                                            <div class="col-lg-4">
                                                                                <ul>
                                                                                    @foreach($chunk as $tour)
                                                                                        <li>
                                                                                            <a href="{{ route('honeymoon.tour', $tour->slug) }}" class="text-decoration-none">
                                                                                                {{ $tour->tour_name }}
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{route('bus.list')}}">Bus</a>
                                </li>
                                <li class="has-submenu megamenu">
                                    <a href="#">Car</a>
                                    
                                </li>
                                <li class="has-submenu megamenu">
                                    <a href="#">Cruise</a>
                                    
                                </li>
                                <li class="has-submenu megamenu">
                                    <a href="#">Travel Insurance</a>
                                   
                                </li>
                                <li class="has-submenu megamenutab active">
                                    <a href="#">Fixed Departures<i class="fa-solid fa-angle-down"></i></a>
                                    <ul class="submenu mega-submenu">
                                        <li>
                                            <div class="megamenu-wrapper">
                                                <div class="row">
                                                    <!-- Left Side Tabs -->
                                                    <div class="col-lg-3">
                                                        <div class="nav flex-column nav-pills menu-tab" id="menu-tab"
                                                            role="tablist" aria-orientation="vertical">
                                                            <ul>
                                                                <li>
                                                                    <a class="nav-link active" id="activity-tab"
                                                                        data-bs-toggle="tab" href="#Fixed_activity"
                                                                        role="tab">
                                                                        India
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="nav-link" id="visa-tab"
                                                                        data-bs-toggle="tab" href="#Fixed_visa" role="tab">
                                                                        International
                                                                    </a>
                                                                </li>
                                                                
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <!-- Right Side Content -->
                                                    <div class="col-lg-9">
                                                        <div class="tab-content tab-menulist" id="menu-tabContent">
                                                            <div class="tab-pane fade show active" id="Fixed_activity" role="tabpanel">
                                                                <div class="row">
                                                                        <div class="row">
                                                                            @php
                                                                               $tours = DB::table('tour')->join('fixed_departures', 'tour.id', '=', 'fixed_departures.tour_id')->where('tour.status', 1)->where('tour.tour_category_id', 1)->select('tour.id', 'tour.tour_name', 'tour.slug')->groupBy('tour.id', 'tour.tour_name', 'tour.slug')->orderBy('tour.tour_name', 'asc')->get();
                                                                                $chunks = $tours->chunk(6); // Split into chunks of 6 items each
                                                                            @endphp
                                                                            
                                                                            @foreach($chunks as $chunk)
                                                                            <div class="col-lg-4">
                                                                                <ul>
                                                                                    @foreach($chunk as $tour)
                                                                                        <li>
                                                                                            <a href="{{ route('fixed.departures.india', $tour->slug) }}" class="text-decoration-none">
                                                                                                {{ $tour->tour_name }}
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="Fixed_visa" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="row">
                                                                            @php
                                                                            $tours = DB::table('tour')->join('fixed_departures', 'tour.id', '=', 'fixed_departures.tour_id')->where('tour.status', 1)->where('tour.tour_category_id', 2)->select('tour.id', 'tour.tour_name', 'tour.slug')->groupBy('tour.id', 'tour.tour_name', 'tour.slug')->orderBy('tour.tour_name', 'asc')->get();
                                                                                $chunks = $tours->chunk(6); // Split into chunks of 6 items each
                                                                            @endphp
                                                                            
                                                                            @foreach($chunks as $chunk)
                                                                            <div class="col-lg-4">
                                                                                <ul>
                                                                                    @foreach($chunk as $tour)
                                                                                        <li>
                                                                                            <a href="{{ route('fixed.departures.international', $tour->slug) }}" class="text-decoration-none">
                                                                                                {{ $tour->tour_name }}
                                                                                            </a>
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                            @endforeach
                                                                        </div>
                                                                </div>
                                                            </div>
                                                            
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li class="has-submenu active">
                                    <a href="#">Group Tours<i class="fa-solid fa-angle-down"></i></a>
                                    <ul class="submenu">
                                        <li>
                                            <li><a href="{{route('school.tours')}}">School Excursion</a></li>
                                            <li><a href="{{route('college.tours')}}">College Excursion</a></li>
                                            <li><a href="{{route('corporate.tours')}}">Corporate Tours</a></li>
                                        </li>
                                    </ul>
                                </li>
                                
                                <!-- <li>-->
                                <!--    <a href="{{route('gallery')}}">Gallery</a>-->
                                <!--</li>-->
                                <!--<li>-->
                                <!--    <a href="{{route('blog')}}">Blog</a>-->
                                <!--</li>-->
                                <!--<li>-->
                                <!--    <a href="{{route('contact')}}">Contact us</a>-->
                                <!--</li>-->

                            </ul>
                        </nav>
                        <div class="header-btn d-flex align-items-center">
                            @php
                             
                                $customer_id = \Session::get('customer_id');
                                $customer_details =  CustomerOtpModel::select('*')->where('customer_id', $customer_id)->orderBy('id', 'desc')->first();
                                $customer_detail =  CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
                                @endphp
                                @if(!empty($customer_id) && empty($customer_details->customer_id))
                            <div><a href="{{route('user.dashboard')}}" class="btn btn-white me-3">Dashboard</a></div>
                            @else
                            <div><a href="#" class="btn btn-org me-3" data-bs-toggle="modal"
                                    data-bs-target="#login-modal" style="background: #ff9d5b;border-color: #ff9d5b;color: #FFF;">Sign In</a></div>
                            @endif
                            <div class="header__hamburger d-xl-none my-auto">
                                <div class="sidebar-menu">
                                    <i class="isax isax-menu5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- /Header -->