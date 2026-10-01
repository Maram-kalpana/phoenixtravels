
@foreach($packages as $key => $val)
<div class="col-xxl-5 col-md-5 d-flex">
    <div class="place-item mb-4 flex-fill package-card">

        <div class="place-img">
            <div class="img-slider image-slide owl-carousel nav-center">
                <div class="slide-images">
                    <a href="{{ route('fixed.departures.details', $val->slug) }}">
                        <img src="{{ asset($val->package_image) }}"
                             class="img-fluid package-image"
                             alt="Package Image" style="height: 240px;">
                    </a>
                </div>
            </div>
        </div>

        <div class="place-content">

            <div class="d-flex align-items-center justify-content-between mb-1">
                <div class="d-flex flex-wrap align-items-center">
                    <span class="me-2 fw-bold">Tour Code:</span>
                    <p class="fs-12 text-gray-9 mb-0">{{ $val->package_id }}</p>
                </div>

                <span class="d-inline-block border vertical-splits">
                    <span class="bglight text-light d-flex align-items-center justify-content-center"></span>
                </span>

                <div class="d-flex align-items-center flex-wrap">
                    <span class="me-1">
                        <i class="isax isax-calendar-tick text-gray-6"></i>
                    </span>
                   <p class="fs-12 text-gray-9 card-style"> {{$val->nights_count}}N - {{$val->days_count}}D </p>
                </div>
            </div>

            <h5 class="mb-1 text-truncate color_blue fs-16">
                <a href="{{ route('fixed.departures.details', $val->slug) }}">
                    {{ $val->package_name }}
                </a>
            </h5>

            <p class="d-flex align-items-center mb-3 package-location fs-12">
                <i class="isax isax-location5 me-2"></i>
                {{ $val->pack_location_cover }}
            </p>
                @php
                    $departures_date = \DB::table('departure_dates')
                        ->select('price')
                        ->where('fixed_departure_id', $val->id)
                        ->first();
                @endphp
            <div class="mb-3 offer-card">
                <h6 class="d-flex align-items-center text-gray-6 fs-14 fw-normal w-100" style="font-size: 14px;">
                    Group Cost
            
                    <div class="ms-auto">
                        <span class="fs-18 fw-semibold" style="color: #ff9d5b;">
                            ₹{{ $val->price }}/
                        </span>
                        <p><span>Per Person</span></p>
                    </div>
                </h6>
            </div>                 
            <!-- Static Departure Dates -->
            <div class="departure-wrap mt-3">
                <div class="departure-title mb-3">
                    <i class="isax isax-calendar-2"></i>
                    <span>Departure Dates</span>
                </div>
            
                <div class="departure-list">
                    <!-- Individual date boxes with enhanced design -->
                     @php
                    $departures_date = \DB::table('departure_dates')
                        ->select('departure_date')
                        ->where('fixed_departure_id', $val->id)->limit(4)
                        ->get();
                    @endphp
                    @foreach($departures_date as $key => $row)
                        <a href="{{ route('fixed.departures.details', $val->slug) }}">
                            <div class="departure-box">
                                <span class="departure-day">{{ date('d', strtotime($row->departure_date)) }}</span>
                                <span class="departure-month">{{ \Carbon\Carbon::parse($row->departure_date)->format('M') }}</span>
                                <span class="departure-year">{{ date('Y', strtotime($row->departure_date)) }}</span>
                            </div>
                        </a>
                    @endforeach
                     
                     <!--<a href="{{ route('fixed.departures.details', $val->slug) }}">-->
                     <!--   <div class="departure-box departure-more">-->
                     <!--       <i class="isax isax-calendar-add"></i>-->
                     <!--       <span class="more-count">+31</span>-->
                     <!--       <span class="more-text">More Dates</span>-->
                     <!--   </div>-->
                     <!--</a>-->
                </div>
            </div>

            <!-- Itinerary Button -->
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('fixed.departures.itinerary', ['country' => request()->segment(2) ?? 'default-country','slug' => $val->itinerary_id]) }}"
                   target="_blank"
                   class="btn btn-primary btn-sm itinerary-btn">
                    <i class="isax isax-document-text me-1"></i>
                    View Itinerary
                </a>
            </div>

        </div>
    </div>
</div>
@endforeach