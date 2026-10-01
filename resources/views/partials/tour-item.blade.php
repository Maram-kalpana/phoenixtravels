@foreach($packages as $key => $val)
<div class="pt-1 col-xxl-5 col-md-5 d-flex">
    <div class="place-item mb-4 flex-fill">
        <div class="place-img">
            @php
                $package_images = get_images_package($val->id);
            @endphp

            @if($package_images && count($package_images) > 0)
                <div class="img-slider image-slide owl-carousel nav-center">
                    @foreach($package_images as $image)
                        <div class="slide-images">
                            <a href="{{ route('domestic.tour.detail', $val->slug) }}">
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
                        <a href="{{ route('domestic.tour.detail', $val->slug) }}">
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
            <h5 class="mb-1 text-truncate color_blue fs-16"><a href="{{ route('domestic.tour.detail', $val->slug) }}">{{ $val->package_title }}</a></h5>
            <p class="d-flex align-items-center mb-3" style="font-size: 12px;"><i class="isax isax-location5 me-2"></i>{{$val->pack_location_cover}}</p>
           <div class="d-flex align-items-center justify-content-between border-top pt-3">
                <div class="d-flex flex-wrap align-items-center me-2 w-100">
                    <div class="amenities-container w-100">
                        <div class="amenities-grid">
                            <div class="amenity-items">
                                <div class="package-flight-wrapper">
                                    <!-- Package Options -->
                                    @php 
                                    $buget_price = get_dynamic_tour_cal('Budget', 2, $val->tour_id,$val->days_count,$val->nights_count);
                                    $standard_price = get_dynamic_tour_cal('Standard', 2, $val->tour_id,$val->days_count,$val->nights_count);
                                    $premium_price = get_dynamic_tour_cal('Premium', 2, $val->tour_id,$val->days_count,$val->nights_count);
                                    $luxury_price = get_dynamic_tour_cal('Luxury', 2, $val->tour_id,$val->days_count,$val->nights_count);
                                    @endphp
                                    <div class="package-group">
                                        <!-- Standard Package -->
                                        <!--<label class="package-option">-->
                                        <!--    <input class="form-check-input package-type-radio" type="radio" -->
                                        <!--           name="package_type_{{ $val->id }}" -->
                                        <!--           value="budget"-->
                                        <!--           data-id="{{ $val->id }}"-->
                                        <!--           data-type="budget"-->
                                        <!--           data-url="{{ route('domestic.tour.detail', $val->slug) }}?type=budget&price={{ $buget_price }}">-->
                                        <!--    <span class="package-content">-->
                                        <!--        <span class="package-name" style="font-size: 14px;">Budget:</span>-->
                                        <!--        <span class="package-price" style="font-size: 14px;color: #ff9d5b;">₹ {{ number_format($buget_price) }}</span>-->
                                        <!--    </span>-->
                                        <!--</label>-->
                                        
                                        <!-- Standard Package -->
                                        <label class="package-option">
                                            <input class="form-check-input package-type-radio" type="radio" 
                                                   name="package_type_{{ $val->id }}" 
                                                   value="standard"
                                                   data-id="{{ $val->id }}"
                                                   data-type="standard"
                                                   data-url="{{ route('domestic.tour.detail', $val->slug) }}?type=standard&price={{ $standard_price }}">
                                            <span class="package-content">
                                                <span class="package-name" style="font-size: 14px;">Standard:</span>
                                                <span class="package-price" style="font-size: 14px;color: #ff9d5b;">₹ {{ number_format($standard_price) }}</span>
                                            </span>
                                        </label>
            
                                        <!-- Premium Package -->
                                        <label class="package-option">
                                            <input class="form-check-input package-type-radio" type="radio" 
                                                   name="package_type_{{ $val->id }}" 
                                                   value="premium"
                                                   data-id="{{ $val->id }}"
                                                   data-type="premium"
                                                   data-url="{{ route('domestic.tour.detail', $val->slug) }}?type=premium&price={{ $premium_price }}">
                                            <span class="package-content">
                                                <span class="package-name" style="font-size: 14px;">Premium:</span>
                                                <span class="package-price" style="font-size: 14px;color: #ff9d5b;">₹ {{ number_format($premium_price) }}</span>
                                            </span>
                                        </label>
            
                                        <!-- Luxury Package -->
                                        <label class="package-option">
                                            <input class="form-check-input package-type-radio" type="radio" 
                                                   name="package_type_{{ $val->id }}" 
                                                   value="luxury"
                                                   data-id="{{ $val->id }}"
                                                   data-type="luxury"
                                                   data-url="{{ route('domestic.tour.detail', $val->slug) }}?type=luxury&price={{ $luxury_price }}">
                                            <span class="package-content">
                                                <span class="package-name" style="font-size: 14px;">Luxury:</span>
                                                <span class="package-price" style="font-size: 14px;color: #ff9d5b;">₹ {{ number_format($luxury_price) }}</span>
                                            </span>
                                        </label>
                                    </div><br>
            
                                    <!-- Flight Options -->
                                    
                                        <div class="flight-group">
                                            <div class=row>
                                                <div class="col-6">
                                                <label class="flight-option">
                                                    <input class="form-check-input" type="radio" name="flight_option_{{ $val->id }}" value="without_flight" checked>
                                                    <span class="flight-label" style="font-size: 14px;color: #26add5;">Without Flight</span>
                                                </label>
                                                </div> 
                                                <div class="col-6">
                                                <label class="flight-option">
                                                    <input class="form-check-input" type="radio" name="flight_option_{{ $val->id }}" value="with_flight">
                                                    <span class="flight-label" style="font-size: 14px;color: #26add5;">With Flight</span>
                                                </label>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            
             <div class="mb-3 offer-card">
                <h6 class="d-flex align-items-center text-gray-6 fs-13 fw-normal w-100" style="font-size: 14px;">
                    {{$val->offer_text}}
            
                    <div class="ms-auto">
                        <span class="fs-18 fw-semibold" style="color: #ff9d5b;">
                            ₹{{ $buget_price }}/</span><span>Person</span>&nbsp;</br>
                        <p class="text-end"><span>Total Price</span><span> ₹{{$buget_price * 2}} </span></p>
                    </div>
                </h6>
            </div>
        </div>
    </div>
</div>
@endforeach
@section('script')
<script>
$(document).ready(function() {
    $('.package-type-radio').on('change', function() {
        
        if ($(this).is(':checked')) {
            var url = $(this).data('url');
            window.location.href = url;
        }
    });
});
</script>
@endsection