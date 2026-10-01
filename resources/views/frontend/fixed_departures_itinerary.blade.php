@extends('layouts.web')
@section('title', $package_details->meta_title.' |  Phoenix Tours & Travels')
@section('css')
<style>
    .sub-menu-new{
       text-align: center;
        background-color: #1fb1d7;
        margin-bottom: 30px;
        padding: 11px 0px;
    }
    .sub-menu-new li{
        display: inline-block;
    }
    .sub-menu-new li a{
        padding: 14px 10px;
        color: #FFF;
    }
    .me-2-day {
            width: 60px;
            height: 26px;
            text-align: center;
            line-height: 26px;
            background: #4c82ff24;
            margin-right: 15px;
            font-weight: 400;
            font-size: 11px;
            border-radius: 10%;
            font-weight: bold;
    }
    /* Fix for Tour Details Sidebar */
    .details-info {
        border-bottom: 1px solid #e9ecef;
        padding: 6px 0;
    }
    .details-info:last-of-type {
        border-bottom: none;
    }
    .details-info h6 {
        min-width: 120px;
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 0;
    }
    .details-info p {
        margin-bottom: 0;
        color: #212529;
        font-weight: 500;
    }
    .places-covered-text {
        color: #ff9d5b !important;
        font-weight: 600;
        padding: 8px 0 0 0;
        line-height: 1.5;
    }
    .tour-detail-header {
        display: flex;
        align-items: center;
        /*margin-bottom: 16px;*/
    }
    .tour-detail-header .avatar {
        margin-right: 10px;
    }
    
   .col-xl-35{
    flex:0 0 29.166667%;
    max-width:29.166667%;
}

.custom-mb {
    margin-bottom: 2.5rem !important;
}
</style>

@endsection
@section('content')
    <!-- Breadcrumb -->
    <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">{{ $package_details->package_title }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item"><a href=""><i class="isax isax-home5"></i></a></li>
                            <li class="breadcrumb-item">Tours</li>
                            <li class="breadcrumb-item active" aria-current="page">Tour Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="content">
        <div class="container">
                   <!--<div class="activity-wrap mb-4">-->
                   <!--     <div class="row g-2">-->
                   <!--         <div class="col-lg-4">-->
                   <!--             <div class="h-100">-->
                   <!--                 <a href="#" class="d-block h-100">-->
                   <!--                     <img src="https://phoenixtoursandtravels.in/uploads/package/1774344673_340_Simhachalam-1.png"-->
                   <!--                          class="rounded w-100 image-rounded"-->
                   <!--                          alt="Img"-->
                   <!--                          style="height: 300px; object-fit: cover;">-->
                   <!--                 </a>-->
                   <!--             </div>-->
                   <!--         </div>-->
                    
                   <!--         <div class="col-lg-3">-->
                   <!--             <div class="activitys-img mb-2">-->
                   <!--                 <a class="galley-wrap" data-fancybox="gallery"-->
                   <!--                    href="https://phoenixtoursandtravels.in/uploads/package/1774344673_254_Papikondalu-4.jpg">-->
                   <!--                     <img src="https://phoenixtoursandtravels.in/uploads/package/1774344673_254_Papikondalu-4.jpg"-->
                   <!--                          class="w-100 rounded image-rounded"-->
                   <!--                          alt="img"-->
                   <!--                          style="height: 146px; object-fit: cover;">-->
                   <!--                 </a>-->
                   <!--             </div>-->
                    
                   <!--             <div class="activitys-img mb-0">-->
                   <!--                 <a class="galley-wrap" data-fancybox="gallery"-->
                   <!--                    href="https://phoenixtoursandtravels.in/uploads/package/1772624080_112_X1.jpg">-->
                   <!--                     <img src="https://phoenixtoursandtravels.in/uploads/package/1772624080_112_X1.jpg"-->
                   <!--                          class="w-100 rounded image-rounded"-->
                   <!--                          alt="img"-->
                   <!--                          style="height: 146px; object-fit: cover;">-->
                   <!--                 </a>-->
                   <!--             </div>-->
                   <!--         </div>-->
                   <!--         <div class="col-lg-2">-->
                   <!--             <div class="activitys-img mb-2">-->
                   <!--                 <a class="galley-wrap" data-fancybox="gallery"-->
                   <!--                    href="https://phoenixtoursandtravels.in/uploads/package/1772624080_779_X2.jpg">-->
                   <!--                     <img src="https://phoenixtoursandtravels.in/uploads/package/1772624080_779_X2.jpg"-->
                   <!--                          class="w-100 rounded image-rounded"-->
                   <!--                          alt="img"-->
                   <!--                          style="height: 146px; object-fit: cover;">-->
                   <!--                 </a>-->
                   <!--             </div>-->
                    
                   <!--             <div class="activitys-img mb-0">-->
                   <!--                 <a class="galley-wrap" data-fancybox="gallery"-->
                   <!--                    href="https://phoenixtoursandtravels.in/uploads/package/1772624080_499_X4.png">-->
                   <!--                     <img src="https://phoenixtoursandtravels.in/uploads/package/1772624080_499_X4.png"-->
                   <!--                          class="w-100 rounded image-rounded"-->
                   <!--                          alt="img"-->
                   <!--                          style="height: 146px; object-fit: cover;">-->
                   <!--                 </a>-->
                   <!--             </div>-->
                   <!--         </div>-->
                    
                   <!--             <div class="col-lg-3">-->
                   <!--                 <div class="h-100">-->
                   <!--                     <a href="#" class="d-block h-100">-->
                   <!--                         <img src="https://phoenixtoursandtravels.in/uploads/package/1773736082_908_20.jpg"-->
                   <!--                              class="rounded w-100 image-rounded"-->
                   <!--                              alt="Img"-->
                   <!--                              style="height: 300px; object-fit: cover;">-->
                   <!--                     </a>-->
                   <!--                 </div>-->
                   <!--             </div>-->
                   <!--     </div>-->
                   <!-- </div>-->
            <div class="row">
                   
                <div class="col-xl-8">
                     <div>
                        <div class="service-wrap slider-wrap-four mb-4">
                            <!--<div class="d-flex align-items-center justify-content-between mb-2">-->
                            <!--    <div class="mb-2">-->
                            <!--        <h4 class="mb-1 d-flex align-items-center flex-wrap">Coral Cruiser<span-->
                            <!--                class="badge badge-xs bg-success rounded-pill ms-2"><i-->
                            <!--                    class="isax isax-ticket-star me-1"></i>Verified</span></h4>-->
                            <!--        <div class="d-flex align-items-center flex-wrap">-->
                            <!--            <p class="fs-14 mb-2 me-3 pe-3 border-end"><i-->
                            <!--                    class="isax isax-ship me-2"></i>Luxury Cruise</p>-->
                            <!--            <p class="fs-14 mb-2 me-3 pe-3 border-end"><i-->
                            <!--                    class="isax isax-location5 me-2"></i>Ciutat Vella, Barcelona<a-->
                            <!--                    href="#location"-->
                            <!--                    class="link-primary text-decoration-underline fw-medium ms-2">View-->
                            <!--                    Location</a></p>-->
                            <!--            <div class="d-flex align-items-center mb-2">-->
                            <!--                <span-->
                            <!--                    class="badge badge-warning badge-xs text-gray-9 fs-13 fw-medium me-2">5.0</span>-->
                            <!--                <p class="fs-14"><a href="#reviews">(400 Reviews)</a></p>-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--    <div class="d-flex align-items-center mb-3">-->
                            <!--        <a href="#"-->
                            <!--            class="btn btn-outline-light btn-icon btn-sm d-flex align-items-center justify-content-center me-2"><i-->
                            <!--                class="isax isax-share"></i></a>-->
                            <!--        <a href="#" class="btn btn-outline-light btn-sm d-inline-flex align-items-center"><i-->
                            <!--                class="isax isax-heart5 text-danger me-1"></i>Save</a>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="service-wrap">
                                <div class="slider-wrap custom-mb">
                                    <div class="owl-carousel service-carousel nav-center mb-4" id="large-img">
                                         @foreach($package_images as $key => $img)
                                        <div class="service-img">
                                            <img src="{{ asset('uploads/package/' . $img->images) }}" class="img-fluid"
                                                alt="Slider Img" style="height:300px">
                                        </div>
                                         @endforeach
                                    </div>
                                </div>
                                <div class="owl-carousel slider-nav-thumbnails nav-center" id="small-img">
                                     @foreach($package_images as $key => $img)
                                    <div><img src="{{ asset('uploads/package/' . $img->images) }}" class="img-fluid"
                                            alt="Slider Img" style="height: 100px;"></div>
                                     @endforeach
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="sub-menu-new">
                              <ul>
                                  <li><a class="scroll" href="#over-01">OVERVIEW </a></li>
                                  <li><a class="scroll" href="#Itinerary">ITINERARY </a></li>
                                  <li><a class="scroll" href="#Inclusions">INCLUSIONS</a></li>
                              </ul>                                
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="mb-2">
                                <h4 class="mb-1 d-flex align-items-center flex-wrap mb-2" style="font-size: 24px;">{{$package_details->package_title}}</h4>
                                 
                                <div class="d-flex align-items-center flex-wrap">
                                    <p class="fs-14 mb-2 me-3 pe-3 border-end"><i
                                            class="isax isax-receipt text-primary me-2"></i>{{$package_details->destination_type}}</p>
                                    <p class="fs-14 mb-2 me-3 pe-3 border-end"><i
                                            class="isax isax-location5 me-2"></i>{{$package_details->pack_location_cover}}
                                    </p>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="d-flex align-items-center flex-wrap">
                                            <span class="me-1"><i class="isax isax-calendar-tick text-gray-6"></i></span>
                                            <p class="fs-14 text-gray-9">{{$package_details->nights_count}} Night - {{$package_details->days_count}} Day</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Slider -->
                    <!--<div>-->
                    <!--    <div class="service-wrap mb-4">-->
                    <!--        <div class="slider-wrap vertical-slider tour-vertical-slide d-flex align-items-center">-->
                    <!--            <div class="slider-for nav-center" id="large-img">-->
                    <!--                @foreach($package_images as $key => $img)-->
                    <!--                <div class="service-img">-->
                    <!--                    <img src="{{ asset('uploads/package/' . $img->images) }}" class="img-fluid"-->
                    <!--                        alt="Slider Img" style="width:100%;height: 430px;">-->
                    <!--                </div>-->
                    <!--                @endforeach-->
                    <!--            </div>-->
                    <!--            <div class="slider-nav nav-center" id="small-img">-->
                    <!--                @foreach($package_images as $key => $img)-->
                    <!--                <div><img src="{{ asset('uploads/package/' . $img->images) }}" class="img-fluid"-->
                    <!--                        alt="Slider Img" style="width:75px;height: 75px;"></div>-->
                    <!--                @endforeach-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    <div class="row">-->
                    <!--        <div class="col-md-12">-->
                    <!--            <div class="sub-menu-new">-->
                    <!--          <ul>-->
                    <!--              <li><a class="scroll" href="#over-01">OVERVIEW </a></li>-->
                    <!--              <li><a class="scroll" href="#Itinerary">ITINERARY </a></li>-->
                    <!--              <li><a class="scroll" href="#Inclusions">INCLUSIONS</a></li>-->
                    <!--          </ul>                                -->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    <div class="d-flex align-items-center justify-content-between mb-2">-->
                    <!--        <div class="mb-2">-->
                    <!--            <h4 class="mb-1 d-flex align-items-center flex-wrap mb-2" style="font-size: 24px;">{{$package_details->package_title}}</h4>-->
                                 
                    <!--            <div class="d-flex align-items-center flex-wrap">-->
                    <!--                <p class="fs-14 mb-2 me-3 pe-3 border-end"><i-->
                    <!--                        class="isax isax-receipt text-primary me-2"></i>{{$package_details->destination_type}}</p>-->
                    <!--                <p class="fs-14 mb-2 me-3 pe-3 border-end"><i-->
                    <!--                        class="isax isax-location5 me-2"></i>{{$package_details->pack_location_cover}}-->
                    <!--                </p>-->
                    <!--                <div class="d-flex align-items-center mb-2">-->
                    <!--                    <div class="d-flex align-items-center flex-wrap">-->
                    <!--                        <span class="me-1"><i class="isax isax-calendar-tick text-gray-6"></i></span>-->
                    <!--                        <p class="fs-14 text-gray-9">{{$package_details->nights_count}} Night - {{$package_details->days_count}} Day</p>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!-- /Slider -->

                    <!-- Description -->
                    <div class="bg-light-200 card-bg-light mb-2" id="over-01">
                        <h5 class="fs-18 mb-3">Overview</h5>
                        <div class="mb-2">
                            <p style="font-size: 15px;">{{$package_details->overview}}
                            </p>
                        </div>
                    </div>
                    <!-- /Description -->

                    <!-- Highlights -->
                    <div class="bg-light-200 card-bg-light mb-4">
                        <!--<h5 class="fs-14 mb-3">Notes</h5>-->
                        <!--<div class="fs-13">-->
                        <!--    <div class="d-flex align-items-center mb-2">-->
                        <!--        <span class="avatar avatar-md bg-primary-transparent rounded-circle me-2">-->
                        <!--            <i class="isax isax-send-sqaure-2 fs-16"></i>-->
                        <!--        </span>-->
                        <!--        <p>Buffet breakfast as per the Itinerary</p>-->
                        <!--    </div>-->
                        <!--    <div class="d-flex align-items-center mb-2">-->
                        <!--        <span class="avatar avatar-md bg-primary-transparent rounded-circle me-2">-->
                        <!--            <i class="isax isax-send-sqaure-2 fs-16"></i>-->
                        <!--        </span>-->
                        <!--        <p>Visit eight villages showcasing Polynesian culture</p>-->
                        <!--    </div>-->
                        <!--    <div class="d-flex align-items-center mb-2">-->
                        <!--        <span class="avatar avatar-md bg-primary-transparent rounded-circle me-2">-->
                        <!--            <i class="isax isax-send-sqaure-2 fs-16"></i>-->
                        <!--        </span>-->
                        <!--        <p>Complimentary Camel safari, Bonfire,</p>-->
                        <!--    </div>-->
                        <!--    <div class="d-flex align-items-center mb-2">-->
                        <!--        <span class="avatar avatar-md bg-primary-transparent rounded-circle me-2">-->
                        <!--            <i class="isax isax-send-sqaure-2 fs-16"></i>-->
                        <!--        </span>-->
                        <!--        <p>All toll tax, parking, fuel, and driver allowances</p>-->
                        <!--    </div>-->
                        <!--    <div class="d-flex align-items-center mb-2">-->
                        <!--        <span class="avatar avatar-md bg-primary-transparent rounded-circle me-2">-->
                        <!--            <i class="isax isax-send-sqaure-2 fs-16"></i>-->
                        <!--        </span>-->
                        <!--        <p>Comfortable and hygienic vehicle</p>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                    <!-- /Highlights -->
                    @if(!empty($vehicle_images) && count($vehicle_images) > 0)
                    <!-- Gallery -->
                    <div class="bg-light-200 card-bg-light mb-4">
                        <h5 class="fs-18 mb-3">Vehicle</h5>
                        <div class="tour-gallery-slider owl-carousel">
                            @foreach($vehicle_images as $key => $val)
                            <a class="galley-wrap" data-fancybox="gallery"
                                href="{{ asset('uploads/package/' . $val->images) }}">
                                <img src="{{ asset('uploads/package/' . $val->images) }}" alt="img">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- /Gallery -->
                    @endif
                    <!-- Gallery -->
                    @if(!empty($hotels_images) && count($hotels_images) > 0)
                    <div class="bg-light-200 card-bg-light mb-4">
                        <h5 class="fs-18 mb-3">Hotels</h5>
                        <div class="tour-gallery-slider owl-carousel">
                            @foreach($hotels_images as $key => $val)
                            <a class="galley-wrap" data-fancybox="gallery"
                                href="{{ asset('uploads/package/' . $val->images) }}">
                                <img src="{{ asset('uploads/package/' . $val->images) }}" alt="img">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <!-- /Gallery -->
                    <div class="bg-light-200 card-bg-light mb-4" id="Itinerary">
                        <h5 class="fs-18 mb-3">Itinerary</h5>
                        
                        <!-- Static Transport Design -->
                        
                        <!-- End Static Transport Design -->
                    
                        <div class="accordion faq-accordion" id="itineraryAccordion">
                            
                        <div class="transport-card mb-4" style="background: #f8f9fa; border-radius: 12px; padding: 20px;">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div class="d-flex align-items-center">
                                    <div class="transport-icon me-3" style="background: #1fb1d7; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-plane-departure" style="color: white; font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center flex-wrap">
                                            <span class="badge me-2" style="background: #4c82ff24; color: #1fb1d7; padding: 5px 12px; border-radius: 20px; font-weight: bold;">0 days</span>
                                            <span style="font-weight: 600; font-size: 16px;">{{$package_flight?->start_place}}</span>
                                            <i class="isax isax-arrow-right mx-2" style="color: #6c757d;"></i>
                                            <span style="font-weight: 600; font-size: 16px;">{{$package_flight?->end_place}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="transport-time d-flex align-items-center mt-2 mt-sm-0">
                                    <div class="me-3 text-center">
                                        <span style="font-size: 14px; color: #6c757d;">Departure</span>
                                        <span style="display: block; font-weight: 600;">{{$package_flight?->from_time}}</span>
                                        <span style="font-size: 12px; color: #6c757d;">{{ \Carbon\Carbon::parse($package_flight?->form_date)->format('D, d M') }}</span>
                                    </div>
                                    <i class="isax isax-arrow-right mx-2" style="color: #1fb1d7;"></i>
                                    <div class="text-center">
                                        <span style="font-size: 14px; color: #6c757d;">Arrival</span>
                                        <span style="display: block; font-weight: 600;">{{$package_flight?->to_time}}</span>
                                        <span style="font-size: 12px; color: #6c757d;">{{ \Carbon\Carbon::parse($package_flight?->to_date)->format('D, d M') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="transport-details mt-2 pt-2" style="border-top: 1px dashed #dee2e6;">
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="me-3" style="font-size: 13px; color: #6c757d;"><i class="isax isax-ticket me-1"></i>Cabin: {{$package_flight?->cabin_place}}</span>
                                    <span style="font-size: 13px; color: #6c757d;"><i class="isax isax-clock me-1"></i>Check-in: {{$package_flight?->check_in}}</span>
                                </div>
                            </div>
                        </div>
                            @foreach($package_itinerary as $key => $val)
                            <div class="accordion-item mb-3">
                                <div class="accordion-header">
                                    <button class="accordion-button fw-medium {{ $key !== 0 ? 'collapsed' : '' }}" 
                                        type="button" 
                                        data-bs-toggle="collapse"
                                        data-bs-target="#faq-collapse_{{ $key + 1 }}" 
                                        aria-expanded="{{ $key === 0 ? 'true' : 'false' }}"
                                        aria-controls="faq-collapse_{{ $key + 1 }}">
                                        <span class="me-2-day">Day {{ $key + 1 }}</span> {{ $val->question }}
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
                                                     style="height: 180px; width: 100%; border-radius: 13px; object-fit: cover;">
                                            </div>
                                            @endif
                                            @if(!empty($val->image_two))
                                            <div class="col-md-6">
                                                <img src="{{ asset($val->image_two) }}" 
                                                     alt="Itinerary Image Two" 
                                                     style="height: 180px; width: 100%; border-radius: 13px; object-fit: cover;">
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
                                            
                                                    <div class="row ms-2 fs-12">
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
                                          
                                        
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Itinerary -->
                    
                    

                    <!-- Includes & Excludes -->
                    <div class="bg-light-200 card-bg-light mb-4" id="Inclusions">
                        <h5 class="fs-16 mb-3">Includes & Excludes</h5>
                        <div class="row gy-2 fs-13">
                            <div class="col-md-6">
                                @php 
                                $inclusion = explode('##', $package_details->inclusions); 
                                $exclusion = explode('##', $package_details->exclusions);
                                @endphp
                                @foreach($inclusion as $item)
                                        @if(trim($item) !== '')
                                            <p class="d-flex align-items-center mb-2">
                                                <i class="isax isax-tick-square5 text-success me-2"></i> {{ trim($item) }}
                                            </p>
                                        @endif
                                @endforeach
                                
                            </div>
                            <div class="col-md-6">
                                @foreach($exclusion as $item)
                                  @if(trim($item) !== '')
                                    <p class="d-flex align-items-center mb-2">
                                        <i class="isax isax-close-square5 text-danger me-2"></i> {{ trim($item) }}
                                    </p>
                                  @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- /Includes & Excludes -->

                    <!-- Gallery -->
                    <div class="bg-light-200 card-bg-light mb-6">
                        <h5 class="fs-18 mb-3">Gallery</h5>
                        <div class="tour-gallery-slider owl-carousel">
                            @foreach($package_images as $key => $val)
                            <a class="galley-wrap" data-fancybox="gallery"
                                href="{{ asset('uploads/package/' . $val->images) }}">
                                <img src="{{ asset('uploads/package/' . $val->images) }}" alt="img">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- /Gallery -->
                </div>
                <!-- /Reviews -->

                <!-- Tour Sidebar -->
                <div class="col-xl-4 theiaStickySidebar">
                    <div class="card bg-light-200">
                        <div class="card-body">
                            <div class="tour-detail-header">
                                <span class="avatar avatar-md rounded-circle bg-primary me-2">
                                    <i class="isax isax-signpost5"></i>
                                </span>
                                <h5 class="fs-14 mb-0 fw-semibold">Tour Details</h5>
                            </div>
                            <div>
                                <div class="d-flex align-items-center justify-content-between details-info">
                                    <h6 class="fw-medium">Tour Name</h6>
                                    <p class="fs-15 flex-fill text-end">{{ $package_details->package_title }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between details-info">
                                    <h6 class="fw-medium">Tour Code</h6>
                                    <p class="fs-15 flex-fill text-end">{{ $package_details->package_id }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between details-info">
                                    <h6 class="fw-medium">Duration</h6>
                                    <p class="fs-15 flex-fill text-end">{{$package_details->days_count}} Days, {{$package_details->nights_count}} Nights</p>
                                </div>
                                <div class="details-info pb-2">
                                    <h6 class="fw-medium mb-2">Places Covered</h6>
                                    <p class="fs-14 places-covered-text">{{ $package_details->pack_location_cover }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="card shadow-none">
                        <div class="card-body">
                            <h5 class="fs-14 mb-3">Why Book With Us</h5>
                            <div class="fs-13">
                                <p class="d-flex align-items-center mb-3"><i
                                        class="isax isax-medal-star text-primary me-2"></i>Expertise and Experience</p>
                                <p class="d-flex align-items-center mb-3"><i
                                        class="isax isax-menu text-primary me-2"></i>Tailored Services</p>
                                <p class="d-flex align-items-center mb-3"><i
                                        class="isax isax-message-minus text-primary me-2"></i>Comprehensive Planning</p>
                                <p class="d-flex align-items-center mb-3"><i
                                        class="isax isax-user-add text-primary me-2"></i>Client Satisfaction</p>
                                <p class="d-flex align-items-center"><i
                                        class="isax isax-grammerly text-primary me-2"></i>24/7 Support</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Tour Sidebar -->
                 <div class="col-xl-2"></div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
function getValue(val) {
    for(let i = 1; i <= 6; i++) {
        $(`#age${i}`).hide();
        $(`#child_age${i}`).prop('required', false);
    }
    for(let i = 1; i <= val; i++) {
        $(`#age${i}`).show();
        $(`#child_age${i}`).prop('required', true);
    }
}
</script>
<script>
$(function() {
  $('input[name="from_date"]').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    autoApply:true,
     locale: {
      format: 'DD-MM-YYYY'
    }
    
  });
   
});


function change_date(date){
    var package_id = {{ $package_details->id }};
    $.ajax({
        type: "POST",
        url: "{{ route('change.date') }}",
        data: {
            date: date,
            package_id: package_id,
            _token: "{{ csrf_token() }}"
        },
        success: function(data){
            $("#result_date").val(data);
            $("#result_date_two").val(data);
        }
    });
}


function setvalue(package_type){
    var package_id = {{ $package_details->id }};

    $.ajax({
        type: "POST",
        url: "{{ route('get.price') }}",
        data: {
            package_type: package_type,
            package_id: package_id,
            _token: "{{ csrf_token() }}"
        },
        cache: false,
        success: function(data){
            $("#result").html(data);
            $("#result_two").val(data);
        }
    });
}



</script>
    <script>
        $("#news_comment_form").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('pay.submit') }}", new FormData($("#news_comment_form")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true, "{{ route('submit.info') }}", 1000);
                else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Submit');
                    for (var a in data['error']['message']) {
                        notify(null, data['error']['message'][a][0], 'botton left');
                        if (a == 'success' | a == 'error') notify(null, data['error']['message'][a][0],'botton left');
                    }
                }
            }).catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('Submit');
                notify(null, 'Something went wrong', 'top right');
                console.log(error);
            });
        });
    </script>
 @stop