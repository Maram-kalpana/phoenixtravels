<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            @if (!empty(asset(getLogo())))
            <a href="{{ route('dashboard') }}"> <img src="{{asset(getLogo())}}"
                class="logo-icon" alt="logo icon"></a>
            @endif
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('dashboard') }}">
                <div class="parent-icon">
                    <ion-icon name="home-outline"></ion-icon>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
       
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-travel"></i></div>
                </div>
                <div class="menu-title">Tours</div>
            </a>
            <ul>
                <li><a href="{{ route('list.country') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Country</div></a></li>
                <li><a href="{{route('list.state')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> State </div></a></li>
                <li><a href="{{route('list.city')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> City </div></a></li>
                <li><a href="{{route('list.home.page.section')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Home Page Section </div></a></li>
                <li><a href="{{route('list.tours')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Domestic Packages </div></a></li>
                <li><a href="{{route('list.international.tours')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> International Packages </div></a></li>
                <li><a href="{{route('list.honeymoon.tours')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Honeymoon Packages </div></a></li>
                <li><a href="{{route('list.fixed.departures')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Fixed Departures </div></a></li>
                
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-dinner"></i></div>
                </div>
                <div class="menu-title">Menu</div>
            </a>
            <ul>
                <li><a href="{{ route('list.category',['module_id' => 9999]) }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Breakfast Menu</div></a></li>
                <li><a href="{{route('list.category',['module_id' => 9988])}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Lunch Menu </div></a></li>
                <li><a href="{{route('list.category',['module_id' => 9977])}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Dinner Menu </div></a></li>
                <li><a href="{{route('list.category',['module_id' => 9966])}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Conference - Morning Snacks </div></a></li>
                <li><a href="{{route('list.category',['module_id' => 9955])}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Conference - Evening Snacks </div></a></li>
                <li><a href="{{route('list.category',['module_id' => 9944])}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Drinks Type</div></a></li>
                <li><a href="{{route('list.category',['module_id' => 9933])}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Starters</div></a></li>
                <li><a href="{{route('list.category',['module_id' => 9922])}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Ammunities</div></a></li>
                <li><a href="{{route('list.category',['module_id' => 9911])}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Seeting Type</div></a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <div class="font-22"><i class="bx bx-user-plus"></i></div>
                </div>
                <div class="menu-title">Users</div>
            </a>
            <ul>
                <li><a href="{{ route('list.users')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Users</div></a></li>
                <!--<li><a href="{{route('list.payments')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Payment List </div></a></li>-->
                <li><a href="{{route('list.wallet')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Users Wallets </div></a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <div class="font-22"><i class="bx bx-calendar-plus"></i></div>
                </div>
                <div class="menu-title">Users Booking</div>
            </a>
             <ul>
                <li><a href="{{ route('fixed.departures.booking.list') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Fixed Departures</div></a></li>
                <li><a href="{{ route('holidays.booking.list') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Holidays</div></a></li>
                <li><a href="{{ route('flight.booking.list') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Flight </div></a></li>
                <li><a href="{{ route('hotel.booking.list') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Hotel </div></a></li>
                <li><a href="{{ route('bus.booking.list') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Bus </div></a></li>
                <li><a href="{{ route('car.booking.list') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Car </div></a></li>
                <li><a href="{{ route('travel.insurance.booking.list') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Travel Insurance </div></a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <div class="font-22"><i class="bx bx-calendar-event"></i></div>
                </div>
                <div class="menu-title">Group Tours</div>
            </a>
             <ul>
                <li><a href="{{ route('school.excursion.booking.list') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> School Excursion</div></a></li>
                <li><a href="{{ route('college.excursion.booking.list') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>  College Excursion </div></a></li>
                <li><a href="{{ route('corporate.tours.booking.list') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Corporate Tours </div></a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-agenda"></i></div>
                </div>
                <div class="menu-title">Agents</div>
            </a>
            <ul>
                <li><a href="{{ route('add.agents') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Add Agents</div></a></li>
                <li><a href="{{route('list.agents')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> List Agents </div></a></li>
            </ul>
        </li>
       <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-consulting"></i></div>
                </div>
                <div class="menu-title">Vendor</div>
            </a>
            <ul>
                <li><a href="{{ route('add.vendor') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Add Vendor</div></a></li>
                <li><a href="{{route('list.vendor')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> List Vendor </div></a></li>
            </ul>
        </li>
        
        
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-grow"></i></div>
                </div>
                <div class="menu-title">Enquiries</div>
            </a>
            <ul>
                <li><a href="{{ route('list.booking.enquiry') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Bookings</div></a></li>
                <li><a href="{{route('list.quick.enquiry')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Quick Enquiries </div></a></li>
                <li><a href="{{route('list.enquiry')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Contact Enquiries </div></a></li>
            </ul>
        </li>
        
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <div class="font-22"><i class="bx bxs-coupon"></i></div>
                </div>
                <div class="menu-title">Coupons</div>
            </a>
            <ul>
                <li><a href="{{ route('list.coupon') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Coupons List</div></a></li>
                <li><a href="{{route('list.coupon.history')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Coupons History </div></a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-grow"></i></div>
                </div>
                <div class="menu-title">Domestic Dynamic Pricing</div>
            </a>
            <ul>
                <li><a href="{{ route('list.dynamic.price') }}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Room Pricing</div></a></li>
                <li><a href="{{route('room.index')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Bulk Upload Room Price</div></a></li>
                <li><a href="{{route('list.car.dynamic.price')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Transport / Car Pricing </div></a></li>
                <li><a href="{{route('car.bulk.index')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i>Bulk Upload Transport / Car Pricing </div></a></li>
            </ul>
        </li>
        
        <li>
            <a href="{{ route('list.support.tickets') }}">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-comments-reply"></i></div>
                </div>
                <div class="menu-title">Support Tickets</div>
            </a>
        </li>
        <li>
            <a href="{{ route('list.testimonials') }}">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-comments-reply"></i></div>
                </div>
                <div class="menu-title">Testimonials</div>
            </a>
        </li>
        
       
         <li>
            <a href="{{ route('list.gallery') }}">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-image"></i></div>
                </div>
                <div class="menu-title">Gallery</div>
            </a>
        </li>
        <li>
            <a href="{{ route('list.faq') }}">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-question-circle"></i></div>
                </div>
                <div class="menu-title">FAQ's</div>
            </a>
        </li>
        <li>
            <a href="{{ route('list.notification') }}">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-alarm"></i></div>
                </div>
                <div class="menu-title">Notifications</div>
            </a>
        </li>
        <li>
            <a href="{{ route('list.blog') }}">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-blogger"></i></div>
                </div>
                <div class="menu-title">Blog</div>
            </a>
        </li>
        <li>
            <a href="{{ route('list.banner') }}">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-react"></i></div>
                </div>
                <div class="menu-title">Banner</div>
            </a>
        </li>
        <li>
            <a href="{{ route('list.pages') }}">
                <div class="parent-icon">
                    <div class="font-22"><i class="lni lni-page-break"></i></div>
                </div>
                <div class="menu-title">Pages</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <ion-icon name="cog-outline"></ion-icon>
                </div>
                <div class="menu-title">Settings</div>
            </a>
            <ul>
                <li><a href="{{route('index')}}"><div class="font-18"><i class="bx bx-globe"></i> General Settings</div></a></li>
                <li><a href="{{route('package.percentage')}}"><div class="font-18"><i class="bx bx-right-arrow-alt"></i> Package Percentage</div></a></li>
                <li><a href="{{route('smtp.settings')}}"><div class="font-18"><i class="lni lni-mailchimp"></i> SMTP Settings</div></a></li>
                <li><a href="{{route('social.settings')}}"><div class="font-18"><i class="lni lni-network"></i> Social Settings</div></a></li>
                <li><a href="{{route('sms.settings')}}"><div class="font-18"><i class="bx bx-message-rounded-dots"></i> Sms Settings</div></a></li>
                <li><a href="{{route('payment.settings')}}"><div class="font-18"><i class="lni lni-amazon"></i> Payment Settings</div></a></li>
            </ul>
        </li>
        
        <li>
            <a href="{{route('logout')}}">
                <div class="parent-icon">
                    <div class="font-22"><i class="bx bx-power-off"></i></div>
                </div>
                <div class="menu-title">Logout</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</aside>
