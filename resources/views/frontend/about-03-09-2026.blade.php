@extends('layouts.web')
@section('title', 'About us |  Phoenix Tours & Travels')
@section('content')
<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">About us</h2>
            </div>
        </div>
    </div>
</div>




    <!-- About Section -->
    <section class="section about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-image mb-4 mb-lg-0">
                        <div class="about-img">
                            <img src="{{asset('frontend/img/common/abour_right.png')}}" alt="about">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content mb-4">
                        <h6 class="text-primary fs-14 fw-medium mb-2">About us</h6>
                        <h2 class="display-6 mb-2">We Are The Best Travel Agency Company Since 2016</h2>
                        <p class="mb-4">Phoenix Tours and Travels (since 2016) is whole Proprietorship company, managed by Mr. Prasad M. Registered in Telangana , Miyapur, Hyderabad.</p>
                        <p class="md-4">We are a leading tour operator providing Flight Tickets, Domestic tour packages, and Hotel Reservations in all over India. Our primary concern is to provide the quality service to the costumers. We have been organizing Inbound and Outbound Holiday packages & we specialize in Honeymoon Packages, Education Packages, Group tour packages, Wild Life Sanctuaries, Adventure Tours, Spiritual Tours, Historical Tours, and Customized Tours in all over India.  Being a full-fledged tour operator we deal all kinds of travel and tourism products. Our main focus has always been to remain fair and honest in dealing and delivering for what we promise and combining professionalism to quality services.</p>
                    </div>
                </div>
                <p class="md-4">We offer best services at affordable cost in all over India on our tour packages. We provide a wide range of holiday tour packages for all the domestic destinations across the India.</p>
                <p class="md-4">We offer best packages for all the business and leisure travelers across the India. Mainly our aim is to satisfy the client’s requirements with ultimate transparency and cost-effectiveness. We assure to offer round the clock support and assistance at any point of your travel.</p>
                <p class="md-4">Our Team supports all our customers by 24/7 hours. We keep our focus on customer satisfaction, and best tour plans with affordable prices. We believe in maintaining better quality and services for all our customers.</p>
            </div>
            <div class="about-bg d-none d-md-block">
                <img src="{{asset('frontend/img/bg/about-bg.png')}}" alt="img" class="about-bg-01">
            </div>
            <div class="about-bg-1 d-none d-md-block">
                <img src="{{asset('frontend/img/bg/about-arrow.png')}}" alt="img" class="about-bg-01">
            </div>
        </div>
    </section>
    <!-- /About Section -->

    <!-- Why Choose Us -->
    <section class="section choose-us-section">
        <div class="container">
            <div class="choose-title">
                <h2>Why Choose <span class="text-primary">Us?</span></h2>
            </div>
            <div class="row g-4">
                <div class="col-lg-3">
                    <div class="choose-card">
                        <div class="choose-icon mb-3">
                            <span class="rounded-circle d-flex align-items-center justify-content-center"><i
                                    class="isax isax-archive-tick text-white"></i></span>
                        </div>
                        <div class="card-content">
                            <h6 class="mb-2">Best services</h6>
                            <p class="fs-16 text-gray-6">Phoenix Tours provides transparent, affordable travel services nationwide, ensuring customer satisfaction with 24/7 support and quality tour packages.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="choose-card">
                        <div class="choose-icon mb-3">
                            <span class="rounded-circle d-flex align-items-center justify-content-center"><i
                                    class="isax isax-dollar-square text-white"></i></span>
                        </div>
                        <div class="card-content">
                            <h6 class="mb-2">Trusted payment</h6>
                            <p class="fs-16 text-gray-6">Secure transactions guaranteed: Trusted payment methods ensure safety and peace of mind for all your financial transactions with us</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="choose-card">
                        <div class="choose-icon mb-3">
                            <span class="rounded-circle d-flex align-items-center justify-content-center"><i
                                    class="isax isax-headphone text-white"></i></span>
                        </div>
                        <div class="card-content">
                            <h6 class="mb-2">Top facility</h6>
                            <p class="fs-16 text-gray-6">Enjoy top-notch amenities: We provide exceptional facilities ensuring comfort and convenience throughout your travel experience with us.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="choose-card">
                        <div class="choose-icon mb-3">
                            <span class="rounded-circle d-flex align-items-center justify-content-center"><i
                                    class="isax isax-map text-white"></i></span>
                        </div>
                        <div class="card-content">
                            <h6 class="mb-2">Awesome deals</h6>
                            <p class="fs-16 text-gray-6">Discover incredible savings: Our awesome deals offer unbeatable prices on flights, tours, and accommodations, making your travel dreams affordable.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="choose-bg-01">
                <img src="{{asset('frontend/img/bg/about-bg-stars-bottom.png')}}" alt="image" class="img-fluid">
            </div>
            <div class="choose-bg-01">
                <img src="{{asset('frontend/img/bg/about-bg-stars-bottom.png')}}" alt="image" class="img-fluid">
            </div>
            <div class="choose-bg-02">
                <img src="{{asset('frontend/img/bg/about-bg-stars-top.png')}}" alt="image" class="img-fluid">
            </div>
        </div>
    </section>
    <!-- /Why Choose Us -->
<br>
   

    <!-- user Section -->
    <section class="section user-section pt-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-10 text-center wow fadeInUp" data-wow-delay="0.2s">
                    <div class="section-header text-center">
                        <h2 class="mb-2">What’s Our <span class="text-primary  text-decoration-underline">User</span>
                            Says</h2>
                        <p class="sub-title">DreamsTour, a tour operator specializing in dream destinations, offers a
                            variety of benefits for travelers.</p>
                    </div>
                </div>
            </div>
            <div class="owl-carousel testimonial-slider">

                <!-- Testimonial Item-->
                 @foreach($testimonials as $key => $val)
                <div class="card border-white wow fadeInUp" data-wow-delay="0.2s">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between text-center flex-column">
                            <div class="rating d-flex align-items-center me-2 mb-3">
                                <i class="fa-solid fa-star filled  text-primary me-1 fs-14"></i>
                                <i class="fa-solid fa-star filled  text-primary me-1 fs-14"></i>
                                <i class="fa-solid fa-star filled  text-primary me-1 fs-14"></i>
                                <i class="fa-solid fa-star filled  text-primary me-1 fs-14"></i>
                                <i class="fa-solid fa-star filled  text-primary me-1 fs-14"></i>
                                <span class="text-gray-9 fs-14 ms-1">5.0</span>
                            </div>
                            <h6 class="mb-2">Awesome service</h6>
                            <p class="mb-3">{{ $val->message }}</p>
                            <div class="d-flex align-items-center">
                                <div class="ms-2 d-flex align-items-center">
                                    <h6 class="fs-16 fw-medium"><a href="#">{{$val->name}}</a></h6>
                                    <span class="city-type">{{$val->role}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 @endforeach
                <!-- /Testimonial Item-->


            </div>
        </div>
        <div class="testimonial-bg">
            <img src="{{asset('frontend/img/bg/testimonial-bg-01.svg')}}" alt="img">
        </div>
    </section>
    
@endsection