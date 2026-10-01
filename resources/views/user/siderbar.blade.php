<div class="card user-sidebar mb-4">
                        <div class="card-header user-sidebar-header">
                            <div class="profile-content rounded-pill">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class=" d-flex align-items-center justify-content-center">
                                        <img src="https://ui-avatars.com/api/?name=<?=$customer_details->full_name?>" alt="image"
                                            class="img-fluid avatar avatar-lg rounded-circle me-1">
                                        <div>
                                            <h6 class="fs-16"><?=$customer_details->full_name?></h6>
                                            <span class="fs-14 text-gray-6"><?=$customer_details->phone?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <a href="{{route('user.update.profile')}}"
                                                class="p-1 rounded-circle btn btn-light d-flex align-items-center justify-content-center"><i
                                                    class="isax isax-edit-2 fs-14"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body user-sidebar-body">
                            <ul>
                                <li>
                                    <span class="fs-14 text-gray-3 fw-medium mb-2">Main</span>
                                </li>
                                <li>
                                    <a href="{{route('user.dashboard')}}" class="d-flex align-items-center {{ request()->segment(3) == '' ? 'active' : '' }}">
                                        <i class="isax isax-grid-55"></i> Dashboard
                                    </a>
                                </li>
                                <li class="submenu">
                                    <a href="#" class="d-block"><i class="isax isax-calendar-tick5"></i><span>My
                                            Bookings</span><span class="menu-arrow"></span></a>
                                    <ul>
                                        <li>
                                            <a href="{{route('user.flight.booking')}}"
                                                class="fs-14 d-inline-flex align-items-center {{ request()->segment(3) == 'customer-flight-booking' ? 'active' : '' }}">Flights</a>
                                        </li>
                                        <li>
                                            <a href="{{route('user.hotel.booking')}}"
                                                class="fs-14 d-inline-flex align-items-center {{ request()->segment(3) == 'customer-hotel-booking' ? 'active' : '' }}">Hotels</a>
                                        </li>
                                        <li>
                                            <a href="{{route('user.car.booking')}}"
                                                class="fs-14 d-inline-flex align-items-center {{ request()->segment(3) == 'customer-car-booking' ? 'active' : '' }}">Cars</a>
                                        </li>
                                        <li>
                                            <a href="{{route('user.cruise.booking')}}"
                                                class="fs-14 d-inline-flex align-items-center {{ request()->segment(3) == 'customer-cruise-booking' ? 'active' : '' }}">Cruise</a>
                                        </li>
                                        <li>
                                            <a href="{{route('user.tour.booking')}}"
                                                class="fs-14 d-inline-flex align-items-center {{ request()->segment(3) == 'customer-tour-booking' ? 'active' : '' }}">Tour</a>
                                        </li>
                                        <li>
                                            <a href="{{route('user.bus.booking')}}"
                                                class="fs-14 d-inline-flex align-items-center {{ request()->segment(3) == 'customer-bus-booking' ? 'active' : '' }}">Bus</a>
                                        </li>
                                    </ul>
                                </li>
                                
                                
                                <li>
                                    <span class="fs-14 text-gray-3 fw-medium mb-2">Finance</span>
                                </li>
                                <li>
                                    <a href="{{route('user.refer')}}" class="d-flex align-items-center {{ request()->segment(3) == 'refer-and-earn' ? 'active' : '' }}">
                                        <i class="fas fa-share-alt me-2"></i> Refer & Earn
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('user.wallet')}}" class="d-flex align-items-center {{ request()->segment(3) == 'wallet-history' ? 'active' : '' }}">
                                        <i class="isax isax-wallet-add-15"></i> Wallet
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{route('user.payment')}}" class="d-flex align-items-center {{ request()->segment(3) == 'payment-history' ? 'active' : '' }}">
                                        <i class="isax isax-money-recive5"></i> Payments
                                    </a>
                                </li>
                                <li>
                                    <span class="fs-14 text-gray-3 fw-medium mb-2">Account</span>
                                </li>
                                <li>
                                    <a href="{{route('user.support')}}" class="d-flex align-items-center {{ request()->segment(3) == 'support' ? 'active' : '' }}">
                                        <i class="fas fa-headset me-2"></i> Support Tickets
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('user.update.profile')}}" class="d-flex align-items-center {{ request()->segment(3) == 'update-profile' ? 'active' : '' }}">
                                        <i class="isax isax-profile-tick5"></i> My Profile
                                    </a>
                                </li>
                                <li>
                                    <div class="message-content">
                                        <a href="{{route('user.notifications')}}" class="d-flex align-items-center {{ request()->segment(3) == 'notifications' ? 'active' : '' }}">
                                            <i class="isax isax-notification-bing5"></i> Notifications
                                        </a>
                                       
                                    </div>
                                </li>
                               
                                <li>
                                    <a href="{{ route('user.logout') }}"  onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="list-group-item list-group-item-action text-danger d-flex align-items-center pb-0" >
                                        <i class="isax isax-logout-15"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
@csrf
</form>
                                </li>
                            </ul>
                        </div>
                    </div>