@section('css')
<style>
    .package-flight-wrapper {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 15px;
        width: 100%;
        padding: 5px 0;
    }
    
    .package-group {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        flex: 1;
    }
    
    .flight-group {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        padding-left: 15px;
        border-left: 2px solid #e8edf2;
    }
    
    .package-option {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border: 2px solid #e8edf2;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #ffffff;
        gap: 4px;
        margin: 0;
    }
    
    .package-option:hover {
        border-color: #279fd1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 159, 209, 0.12);
    }
    
    .package-option input[type="radio"] {
        margin: 0 6px 0 0;
        width: 16px;
        height: 16px;
        accent-color: #279fd1;
        cursor: pointer;
        flex-shrink: 0;
    }
    
    .package-option .package-name {
        font-weight: 600;
        color: #279fd1;
        font-size: 13px;
    }
    
    .package-option .package-price {
        color: #555;
        font-weight: 600;
        font-size: 14px;
    }
    
    .package-option:has(input:checked) {
        border-color: #279fd1;
        background: #f0f8fe;
        box-shadow: 0 0 0 3px rgba(39, 159, 209, 0.12);
    }
    
    .package-option:has(input:checked) .package-price {
        color: #279fd1;
    }
    
    /* Flight Options */
    .flight-option {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border: 2px solid #e8edf2;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #ffffff;
        gap: 6px;
        margin: 0;
    }
    
    .flight-option:hover {
        border-color: #279fd1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 159, 209, 0.12);
    }
    
    .flight-option input[type="radio"] {
        margin: 0 6px 0 0;
        width: 16px;
        height: 16px;
        accent-color: #279fd1;
        cursor: pointer;
        flex-shrink: 0;
    }
    
    .flight-option .flight-label {
        font-size: 13px;
        color: #555;
        font-weight: 500;
    }
    
    .flight-option:has(input:checked) {
        border-color: #279fd1;
        background: #f0f8fe;
        box-shadow: 0 0 0 3px rgba(39, 159, 209, 0.12);
    }
    
    .flight-option:has(input:checked) .flight-label {
        color: #279fd1;
        font-weight: 600;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .package-flight-wrapper {
            flex-direction: column;
            align-items: stretch;
        }
        
        .flight-group {
            border-left: none;
            padding-left: 0;
            border-top: 2px solid #e8edf2;
            padding-top: 12px;
        }
        
        .package-option,
        .flight-option {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endsection
@foreach($packages as $key => $val)
<div class="col-xxl-5 col-md-6 d-flex">
    <div class="place-item mb-4 flex-fill">
        <div class="place-img">
            @php
                $package_images = get_images_package($val->id);
            @endphp

            @if($package_images && count($package_images) > 0)
                <div class="img-slider image-slide owl-carousel nav-center">
                    @foreach($package_images as $image)
                        <div class="slide-images">
                            <a href="{{ route('international.tour.detail', $val->slug) }}">
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
                        <a href="{{ route('international.tour.detail', $val->slug) }}">
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
            <h5 class="mb-1 text-truncate color_blue fs-16"><a href="{{ route('international.tour.detail', $val->slug) }}">{{ $val->package_title }}</a></h5>
            <p class="d-flex align-items-center mb-3" style="font-size: 12px;"><i class="isax isax-location5 me-2"></i>{{$val->pack_location_cover}}</p>
            <hr>
            <div class="d-flex align-items-center justify-content-between border-top pt-3">
                <div class="d-flex flex-wrap align-items-center me-2 w-100">
                    <div class="amenities-container w-100">
                        <div class="amenities-grid">
                            <div class="amenity-items">
                                <div class="package-flight-wrapper">
                                    <!-- Package Options -->
                                    <div class="package-group">
                                        <!-- Standard Package -->
                                        <label class="package-option">
                                            <input class="form-check-input package-type-radio" type="radio" 
                                                   name="package_type_{{ $val->id }}" 
                                                   value="standard"
                                                   data-id="{{ $val->id }}"
                                                   data-type="standard"
                                                   data-url="{{ route('international.tour.detail', $val->slug) }}?type=standard&price={{ $val->s_individual }}">
                                            <span class="package-content">
                                                <span class="package-name" style="font-size: 14px;">Standard</span>
                                                <span class="package-price" style="font-size: 14px;">₹ {{ number_format($val->s_individual) }}</span>
                                            </span>
                                        </label>
            
                                        <!-- Premium Package -->
                                        <label class="package-option">
                                            <input class="form-check-input package-type-radio" type="radio" 
                                                   name="package_type_{{ $val->id }}" 
                                                   value="premium"
                                                   data-id="{{ $val->id }}"
                                                   data-type="premium"
                                                   data-url="{{ route('international.tour.detail', $val->slug) }}?type=premium&price={{ $val->d_individual }}">
                                            <span class="package-content">
                                                <span class="package-name" style="font-size: 14px;">Premium</span>
                                                <span class="package-price" style="font-size: 14px;">₹ {{ number_format($val->d_individual) }}</span>
                                            </span>
                                        </label>
            
                                        <!-- Luxury Package -->
                                        <label class="package-option">
                                            <input class="form-check-input package-type-radio" type="radio" 
                                                   name="package_type_{{ $val->id }}" 
                                                   value="luxury"
                                                   data-id="{{ $val->id }}"
                                                   data-type="luxury"
                                                   data-url="{{ route('international.tour.detail', $val->slug) }}?type=luxury&price={{ $val->p_individual }}">
                                            <span class="package-content">
                                                <span class="package-name" style="font-size: 14px;">Luxury</span>
                                                <span class="package-price" style="font-size: 14px;">₹ {{ number_format($val->p_individual) }}</span>
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