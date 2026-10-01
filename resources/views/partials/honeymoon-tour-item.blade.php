@foreach($packages as $key => $val)
<div class="col-xxl-5 col-md-5 d-flex">
    <div class="place-item mb-4 flex-fill">
        <div class="place-img">
            @php
                $package_images = get_images_package($val->id);
            @endphp

            @if($package_images && count($package_images) > 0)
                <div class="img-slider image-slide owl-carousel nav-center">
                    @foreach($package_images as $image)
                        <div class="slide-images">
                            <a href="{{ route('honeymoon.tour.detail', $val->slug) }}">
                                <img src="{{ asset('uploads/package/' . $image->images) }}" 
                                    class="img-fluid" 
                                    alt="{{ $image->alt_text ?? 'Package Image' }}" style="height: 200px;">
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="img-slider image-slide owl-carousel nav-center">
                    <div class="slide-images">
                        <a href="{{ route('honeymoon.tour.detail', $val->slug) }}">
                            <img src="{{ asset('uploads/default-placeholder.jpg') }}" 
                                class="img-fluid" 
                                alt="No Image Available">
                        </a>
                    </div>
                </div>
            @endif
           
        </div>
        <div class="place-content">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <div class="d-flex flex-wrap align-items-center">
                    <span class="me-2 fw-bold">Tour Code:</span>
                    <p class="fs-12 text-gray-9">{{$val->package_id}}</p>
                </div>
                <div class="d-flex flex-wrap align-items-center">
                    <span class="me-1"><i class="ti ti-receipt text-primary"></i></span>
                    <p class="fs-12 text-gray-9">{{$val->destination_type}}</p>
                </div>
                <span class="d-inline-block border vertical-splits">
                    <span class="bglight text-light d-flex align-items-center justify-content-center"></span>
                </span>
                <div class="d-flex align-items-center flex-wrap">
                    <span class="me-1"><i class="isax isax-calendar-tick text-gray-6"></i></span>
                    <p class="fs-12 text-gray-9 card-style"> {{$val->nights_count}}N - {{$val->days_count}}D </p>
                </div>
            </div>
            <h5 class="mb-1 text-truncate color_blue fs-16"><a href="{{ route('honeymoon.tour.detail', $val->slug) }}">{{ $val->package_title }}</a></h5>
            <p class="d-flex align-items-center mb-3" style="font-size: 12px;"><i class="isax isax-location5 me-2"></i>{{$val->pack_location_cover}}</p>
            <div class="mb-3 offer-card">
                <h6 class="d-flex align-items-center text-gray-6 fs-14 fw-normal w-100" style="font-size: 14px;">
                    {{$val->offer_text}}
            
                    <div class="ms-auto">
                        <span class="fs-18 fw-semibold" style="color: #ff9d5b;">
                            ₹{{ $val->new_price }}/</span><span>Person</span>
                        </span>
                        <p class="text-end"><span>Total Price</span><span> ₹{{$val->new_price * 2}} </span></p>
                    </div>
                </h6>
            </div>
            <div class="d-flex align-items-center justify-content-between border-top pt-3">
                <div class="d-flex flex-wrap align-items-center me-2 w-100">
                    <div class="amenities-container w-100">
                        @php
                            $amenities = explode('##', $val->amenities);
                        @endphp
                        
                        <div class="amenities-grid">
                            @foreach($amenities as $item)
                                @if(trim($item) !== '')
                                    <div class="amenity-items">
                                        <li>{{ trim($item) }}</li>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex align-items-center justify-content-between border-top pt-3">
                <div class="d-flex flex-wrap align-items-center me-2 w-100">
                    <div class="amenities-container w-100">
                        @php
                            $visit_places = explode('##', $val->visit_places);
                        @endphp
                        <div class="amenities-grid">
                            @foreach($visit_places as $item)
                                @if(trim($item) !== '')
                                    <div class="amenity-item">
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
</div>
@endforeach