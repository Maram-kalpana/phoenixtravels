@extends('layouts.web')
@section('title', 'Phoenix Tours & Travels - Bus Search')
@section('content')
    <!-- Page Loader (Shows on page load for 30 seconds) -->
    <div id="initialPageLoader" class="initial-page-loader">
        <div class="loader-content">
            <div class="loader-logo">
                <img src="{{ asset('frontend/img/logo1.png') }}" alt="Phoenix Tours" style="height: 80px;">
            </div>
            <div class="loader-spinners mt-4">
                <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem; animation-duration: 0.8s;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-success" style="width: 2.5rem; height: 2.5rem; animation-duration: 1s; animation-delay: 0.2s;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow text-info" style="width: 2rem; height: 2rem; animation-duration: 1.2s; animation-delay: 0.4s;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <h5 class="mt-3 fw-bold text-primary">Welcome to Phoenix Tours</h5>
            <p class="text-muted" id="initialLoaderMessage">Loading bus search platform...</p>
            <div class="loader-progress mt-3" style="max-width: 300px; margin: 0 auto;">
                <div class="progress" style="height: 6px; border-radius: 10px; background: #e9ecef;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="initialProgress" role="progressbar" style="width: 0%; background: linear-gradient(90deg, #0d6efd, #0a58ca);"></div>
                </div>
            </div>
            <div class="loader-status mt-3">
                <span class="badge bg-light text-dark" id="initialStatusBadge">Initializing...</span>
            </div>
            <div class="loader-timer mt-2">
                <small class="text-muted" id="loaderTimer">Please wait...</small>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="mainContent" style="display: none;">
        <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <h2 class="breadcrumb-title mb-2">Bus</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center mb-0">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="isax isax-home5"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Bus List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Wrapper -->
        <div class="content">
            <div class="container">

                <!-- Bus Search -->
                <div class="card">
                    <div class="card-body">
                        <div class="banner-form">
                            <form id="busSearchForm">
                                @csrf
                                <div class="normal-trip">
                                    <div class="d-lg-flex">
                                        <div class="d-flex form-info">
                                            <!-- From -->
                                            <div class="form-item dropdown">
                                                <label class="form-label fs-14 text-default mb-1">From</label>
                                                <div class="dropdown">
                                                    <button class="form-control text-start dropdown-toggle w-100" type="button" id="originDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background: white; text-align: left !important; height: 46px;">
                                                        <span id="originDisplay">Select origin city</span>
                                                    </button>
                                                    <ul class="dropdown-menu w-100 p-0" style="max-height: 300px; overflow-y: auto;">
                                                        <div class="input-search p-2 border-bottom">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control origin-search" placeholder="Search Location...">
                                                                <span class="input-group-text px-2 border-start-0 bg-white"><i class="isax isax-search-normal"></i></span>
                                                            </div>
                                                        </div>
                                                        <ul class="location-list" id="originCityList">
                                                            <li class="text-center py-3 text-muted city-loading">
                                                                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                                                Loading cities...
                                                            </li>
                                                        </ul>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="origin" name="origin" value="">
                                                <input type="hidden" id="origin_code" name="origin_code" value="">
                                            </div>

                                            <!-- To -->
                                            <div class="form-item dropdown ps-2 ps-sm-3">
                                                <label class="form-label fs-14 text-default mb-1">To</label>
                                                <div class="dropdown">
                                                    <button class="form-control text-start dropdown-toggle w-100" type="button" id="destinationDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background: white; text-align: left !important; height: 46px;">
                                                        <span id="destinationDisplay">Select destination city</span>
                                                    </button>
                                                    <span class="way-icon badge badge-primary rounded-pill translate-middle">
                                                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                                    </span>
                                                    <ul class="dropdown-menu w-100 p-0" style="max-height: 300px; overflow-y: auto;">
                                                        <div class="input-search p-2 border-bottom">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control destination-search" placeholder="Search Location...">
                                                                <span class="input-group-text px-2 border-start-0 bg-white"><i class="isax isax-search-normal"></i></span>
                                                            </div>
                                                        </div>
                                                        <ul class="location-list" id="destinationCityList">
                                                            <li class="text-center py-3 text-muted city-loading">
                                                                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                                                Loading cities...
                                                            </li>
                                                        </ul>
                                                    </ul>
                                                </div>
                                                <input type="hidden" id="destination" name="destination" value="">
                                                <input type="hidden" id="destination_code" name="destination_code" value="">
                                            </div>

                                            <!-- Departure Date -->
                                            <div class="form-item">
                                                <label class="form-label fs-14 text-default mb-1">Departure</label>
                                                <input type="text" class="form-control datetimepicker" id="departure_date" name="departure_date" value="{{ date('d-m-Y', strtotime('+1 day')) }}">
                                                <p class="fs-12 mb-0" id="departure_day">{{ date('l', strtotime('+1 day')) }}</p>
                                            </div>

                                            <!-- Travelers -->
                                            <div class="form-item dropdown">
                                                <label class="form-label fs-14 text-default mb-1">Travellers</label>
                                                <div class="dropdown">
                                                    <button class="form-control text-start dropdown-toggle w-100" type="button" id="travelerDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background: white; text-align: left !important; height: 46px;">
                                                        <span id="travelerSummary">1 Person</span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 320px;">
                                                        <h5 class="mb-3">Select Travelers</h5>
                                                        <div class="mb-3 border br-10 info-item pb-1">
                                                            <h6 class="fs-16 fw-medium mb-2">Travellers</h6>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <label class="form-label text-gray-9 mb-2">Adults <span class="text-default fw-normal">(12+)</span></label>
                                                                        <div class="input-group">
                                                                            <button type="button" class="btn btn-outline-secondary btn-number" data-type="minus" data-field="adults">-</button>
                                                                            <input type="text" name="adults" class="form-control text-center traveler-input" value="1" id="adults" min="1" max="10" readonly>
                                                                            <button type="button" class="btn btn-outline-secondary btn-number" data-type="plus" data-field="adults">+</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <label class="form-label text-gray-9 mb-2">Children <span class="text-default fw-normal">(2-12)</span></label>
                                                                        <div class="input-group">
                                                                            <button type="button" class="btn btn-outline-secondary btn-number" data-type="minus" data-field="children">-</button>
                                                                            <input type="text" name="children" class="form-control text-center traveler-input" value="0" id="children" min="0" max="5" readonly>
                                                                            <button type="button" class="btn btn-outline-secondary btn-number" data-type="plus" data-field="children">+</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <label class="form-label text-gray-9 mb-2">Infants <span class="text-default fw-normal">(0-2)</span></label>
                                                                        <div class="input-group">
                                                                            <button type="button" class="btn btn-outline-secondary btn-number" data-type="minus" data-field="infants">-</button>
                                                                            <input type="text" name="infants" class="form-control text-center traveler-input" value="0" id="infants" min="0" max="3" readonly>
                                                                            <button type="button" class="btn btn-outline-secondary btn-number" data-type="plus" data-field="infants">+</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 border br-10 info-item pb-1">
                                                            <h6 class="fs-16 fw-medium mb-2">Bus Type</h6>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" value="all" name="bus_type" id="all_types" checked>
                                                                    <label class="form-check-label" for="all_types">All</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" value="seater" name="bus_type" id="seater">
                                                                    <label class="form-check-label" for="seater">Seater</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" value="sleeper" name="bus_type" id="sleeper">
                                                                    <label class="form-check-label" for="sleeper">Sleeper</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" value="ac_sleeper" name="bus_type" id="ac_sleeper">
                                                                    <label class="form-check-label" for="ac_sleeper">AC Sleeper</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <a href="#" class="btn btn-light btn-sm" data-bs-dismiss="dropdown">Cancel</a>
                                                            <button type="button" class="btn btn-primary btn-sm apply-travelers">Apply</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="fs-12 mb-0" id="travelerDetail">1 Adult</p>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary search-btn rounded" id="searchBtn">
                                            <i class="isax isax-search-normal me-1"></i>Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /Bus Search -->

                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <h6 class="mb-3" id="resultCount">
                                <i class="isax isax-ticket me-2"></i>
                                <span id="resultCountText">Search for buses to see results</span>
                            </h6>
                            <div class="d-flex align-items-center flex-wrap">
                                <div class="dropdown mb-3">
                                    <a href="#" class="dropdown-toggle py-2" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="fw-medium text-gray-9">Sort By : </span><span id="sortLabel">Recommended</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-sm">
                                        <form>
                                            <h6 class="fw-medium fs-16 mb-3">Sort By</h6>
                                            <div class="form-check d-flex align-items-center ps-0 mb-2">
                                                <input class="form-check-input ms-0 mt-0 sort-option" type="radio" name="sort" value="recommended" id="sort_recommended" checked>
                                                <label class="form-check-label ms-2" for="sort_recommended">Recommended</label>
                                            </div>
                                            <div class="form-check d-flex align-items-center ps-0 mb-2">
                                                <input class="form-check-input ms-0 mt-0 sort-option" type="radio" name="sort" value="price_low" id="sort_price_low">
                                                <label class="form-check-label ms-2" for="sort_price_low">Price: low to high</label>
                                            </div>
                                            <div class="form-check d-flex align-items-center ps-0 mb-2">
                                                <input class="form-check-input ms-0 mt-0 sort-option" type="radio" name="sort" value="price_high" id="sort_price_high">
                                                <label class="form-check-label ms-2" for="sort_price_high">Price: high to low</label>
                                            </div>
                                            <div class="form-check d-flex align-items-center ps-0 mb-2">
                                                <input class="form-check-input ms-0 mt-0 sort-option" type="radio" name="sort" value="rating" id="sort_rating">
                                                <label class="form-check-label ms-2" for="sort_rating">Ratings</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-end border-top pt-3 mt-3">
                                                <a href="#" class="btn btn-light btn-sm me-2">Reset</a>
                                                <button type="button" class="btn btn-primary btn-sm apply-sort">Apply</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Info Banner -->
                        @php
                            use App\Models\CustomerOtpModel;
                            use App\Models\CustomerModel;
                            $customer_id = \Session::get('customer_id');
                            $customer_details = CustomerOtpModel::select('*')->where('customer_id', $customer_id)->orderBy('id', 'desc')->first();
                            $customer_detail = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
                        @endphp
                        @if(!empty($customer_id) && empty($customer_details->customer_id))
                        @else
                        <div class="bg-info br-10 p-3 pb-2 mb-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <p class="fs-14 fw-medium mb-2 d-inline-flex align-items-center">
                                    <i class="isax isax-info-circle me-2"></i>Save an average of 15% on thousands of buses when you're signed in
                                </p>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#login-modal" class="btn btn-white btn-sm mb-2">Sign In</a>
                            </div>
                        </div>
                        @endif

                        <!-- Bus List Container -->
                        <div class="bus-list" id="busListContainer">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <!-- Search Loader -->
                                    <div id="pageLoader" style="display: none;" class="page-loader-overlay">
                                        <div class="page-loader-content">
                                            <div class="loader-spinners">
                                                <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem; animation-duration: 0.8s;" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <div class="spinner-grow text-success" style="width: 2.5rem; height: 2.5rem; animation-duration: 1s; animation-delay: 0.2s;" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <div class="spinner-grow text-info" style="width: 2rem; height: 2rem; animation-duration: 1.2s; animation-delay: 0.4s;" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                            <h5 class="mt-3 fw-bold text-primary">Searching for Buses</h5>
                                            <p class="text-muted" id="loaderMessage">Please wait while we find the best options for you...</p>
                                            <div class="loader-progress mt-3" style="max-width: 300px; margin: 0 auto;">
                                                <div class="progress" style="height: 8px; border-radius: 10px; background: #e9ecef;">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="searchProgress" role="progressbar" style="width: 0%; background: linear-gradient(90deg, #0d6efd, #0a58ca);"></div>
                                                </div>
                                            </div>
                                            <div class="loader-status mt-3">
                                                <span class="badge bg-light text-dark" id="loaderStatusBadge">Searching...</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- No Results Message -->
                                    <div id="noResults" style="display: none;" class="text-center py-5">
                                        <i class="isax isax-search-normal text-muted" style="font-size: 48px;"></i>
                                        <h5 class="mt-3">No buses found</h5>
                                        <p class="text-muted">Try adjusting your search criteria.</p>
                                    </div>

                                    <!-- Bus Results -->
                                    <div id="busResults"></div>

                                    <!-- Pagination -->
                                    <div id="paginationContainer" style="display: none;" class="mt-4">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination justify-content-center" id="paginationList"></ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
@endsection

@section('script')
<script>
$(document).ready(function() {
    // =============================================
    // INITIAL PAGE LOADER (30 seconds)
    // =============================================
    var initialLoaderInterval = null;
    var initialProgress = 0;
    var startTime = Date.now();
    var totalDuration = 30; // 30 seconds

    // Status messages for initial loader
    var initialStatusMessages = [
        { progress: 10, message: 'Initializing system...' },
        { progress: 25, message: 'Loading cities database...' },
        { progress: 40, message: 'Connecting to bus API...' },
        { progress: 55, message: 'Fetching available routes...' },
        { progress: 70, message: 'Preparing search interface...' },
        { progress: 85, message: 'Almost ready...' },
        { progress: 95, message: 'Loading your experience...' }
    ];
    var currentMsgIndex = 0;

    // Start initial loader
    function startInitialLoader() {
        initialLoaderInterval = setInterval(function() {
            var elapsed = (Date.now() - startTime) / 1000;
            initialProgress = Math.min((elapsed / totalDuration) * 100, 99);
            
            // Update progress bar
            $('#initialProgress').css('width', initialProgress + '%');
            
            // Update timer display
            var remaining = Math.max(0, Math.ceil(totalDuration - elapsed));
            $('#loaderTimer').text('Loading... ' + remaining + 's remaining');
            
            // Update status messages
            for (var i = currentMsgIndex; i < initialStatusMessages.length; i++) {
                if (initialProgress >= initialStatusMessages[i].progress) {
                    $('#initialLoaderMessage').text(initialStatusMessages[i].message);
                    $('#initialStatusBadge').text('Loading... ' + Math.round(initialProgress) + '%');
                    currentMsgIndex = i + 1;
                }
            }
            
            // Show completion after 30 seconds
            if (elapsed >= totalDuration) {
                completeInitialLoader();
            }
        }, 100);
    }

    // Complete initial loader
    function completeInitialLoader() {
        clearInterval(initialLoaderInterval);
        $('#initialProgress').css('width', '100%');
        $('#initialLoaderMessage').text('Ready!');
        $('#initialStatusBadge').text('Complete!');
        $('#loaderTimer').text('Loading complete!');
        
        setTimeout(function() {
            // Hide initial loader
            $('#initialPageLoader').fadeOut(500, function() {
                // Show main content
                $('#mainContent').fadeIn(500);
                
                // Initialize everything after page shows
                initializePage();
            });
        }, 500);
    }

    // =============================================
    // PAGE INITIALIZATION (After loader)
    // =============================================
    function initializePage() {
        // Pagination variables
        var currentPage = 1;
        var perPage = 10;
        var totalBuses = 0;
        var searchTimeout = null;
        var isSearching = false;
        var citiesLoaded = false;
        var progressInterval = null;

        // Initialize datepicker
        $('.datetimepicker').datetimepicker({
            format: 'DD-MM-YYYY',
            minDate: moment().add(1, 'day'),
            useCurrent: false,
            ignoreReadonly: true
        });

        // Update day when date changes
        $('.datetimepicker').on('dp.change', function(e) {
            var day = e.date.format('dddd');
            if ($(this).attr('id') === 'departure_date') {
                $('#departure_day').text(day);
                triggerSearch();
            }
        });

        // Load cities from localStorage or API
        function loadCities() {
            var cachedCities = localStorage.getItem('etravos_cities');
            if (cachedCities) {
                try {
                    var cities = JSON.parse(cachedCities);
                    if (cities && cities.length > 0) {
                        populateCityDropdowns(cities);
                        fetchCitiesInBackground();
                        return;
                    }
                } catch(e) {
                    console.warn('Failed to parse cached cities');
                }
            }

            $('.location-list').html(`
                <li class="text-center py-3 text-muted city-loading">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    Loading cities...
                </li>
            `);
            
            fetchCitiesFromAPI();
        }

        function fetchCitiesInBackground() {
            $.ajax({
                url: '{{ route("bus.cities") }}',
                type: 'GET',
                timeout: 10000,
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        localStorage.setItem('etravos_cities', JSON.stringify(response.data));
                    }
                }
            });
        }

        function fetchCitiesFromAPI() {
            $.ajax({
                url: '{{ route("bus.cities") }}',
                type: 'GET',
                timeout: 8000,
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        localStorage.setItem('etravos_cities', JSON.stringify(response.data));
                        populateCityDropdowns(response.data);
                    } else {
                        useDefaultCities();
                    }
                },
                error: function() {
                    useDefaultCities();
                }
            });
        }

        function useDefaultCities() {
            var defaultCities = getDefaultCities();
            localStorage.setItem('etravos_cities', JSON.stringify(defaultCities));
            populateCityDropdowns(defaultCities);
        }

        function populateCityDropdowns(cities) {
            var fromList = $('#originCityList');
            var toList = $('#destinationCityList');
            
            fromList.empty();
            toList.empty();

            if (!cities || cities.length === 0) {
                fromList.append('<li class="text-center py-3 text-muted">No cities available</li>');
                toList.append('<li class="text-center py-3 text-muted">No cities available</li>');
                return;
            }

            cities.forEach(function(city) {
                var cityHtml = `
                    <li class="border-bottom city-item">
                        <a class="dropdown-item city-option" href="#" 
                           data-location="${escapeHtml(city.name)}" 
                           data-city-id="${escapeHtml(city.id)}" 
                           data-code="${escapeHtml(city.code || city.id)}"
                           data-state="${escapeHtml(city.state || '')}">
                            <h6 class="fs-16 fw-medium mb-1">${escapeHtml(city.name)}</h6>
                            <p class="fs-12 text-muted mb-0">${escapeHtml(city.state || city.country || 'India')}</p>
                        </a>
                    </li>
                `;
                fromList.append(cityHtml);
                toList.append($(cityHtml).clone());
            });

            // Bind click events for origin
            $('#originCityList .city-option').off('click').on('click', function(e) {
                e.preventDefault();
                var location = $(this).data('location');
                var cityId = $(this).data('city-id');
                var code = $(this).data('code');
                var state = $(this).data('state');
                
                $('#originDisplay').text(location);
                $('#origin').val(location);
                $('#origin_code').val(code);
                $('#origin').data('city-id', cityId);
                $('#origin').data('code', code);
                $('#originState').text(state || 'City');
                
                $('#originDropdown').dropdown('toggle');
                triggerSearchWithLoader();
            });

            // Bind click events for destination
            $('#destinationCityList .city-option').off('click').on('click', function(e) {
                e.preventDefault();
                var location = $(this).data('location');
                var cityId = $(this).data('city-id');
                var code = $(this).data('code');
                var state = $(this).data('state');
                
                $('#destinationDisplay').text(location);
                $('#destination').val(location);
                $('#destination_code').val(code);
                $('#destination').data('city-id', cityId);
                $('#destination').data('code', code);
                $('#destinationState').text(state || 'City');
                
                $('#destinationDropdown').dropdown('toggle');
                triggerSearchWithLoader();
            });

            // Set default values
            if (!$('#origin').val() && cities.length > 0) {
                var fromCity = cities.find(c => c.code === '8466') || cities[0];
                $('#originDisplay').text(fromCity.name);
                $('#origin').val(fromCity.name);
                $('#origin_code').val(fromCity.code || fromCity.id);
                $('#origin').data('city-id', fromCity.id);
                $('#origin').data('code', fromCity.code || fromCity.id);
                $('#originState').text(fromCity.state || 'City');
            }
            
            if (!$('#destination').val() && cities.length > 1) {
                var toCity = cities.find(c => c.code === '2863') || cities[1];
                $('#destinationDisplay').text(toCity.name);
                $('#destination').val(toCity.name);
                $('#destination_code').val(toCity.code || toCity.id);
                $('#destination').data('city-id', toCity.id);
                $('#destination').data('code', toCity.code || toCity.id);
                $('#destinationState').text(toCity.state || 'City');
            }

            citiesLoaded = true;

            // Auto search after cities loaded
            setTimeout(function() {
                if ($('#origin').val() && $('#destination').val()) {
                    triggerSearchWithLoader();
                }
            }, 300);
        }

        function escapeHtml(text) {
            if (!text) return '';
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function getDefaultCities() {
            return [
                {id: '8466', name: 'Vijayawada', state: 'Andhra Pradesh', country: 'India', code: '8466'},
                {id: '2863', name: 'Hyderabad', state: 'Telangana', country: 'India', code: '2863'},
                {id: '1780', name: 'Delhi', state: 'Delhi', country: 'India', code: '1780'},
                {id: '1361', name: 'Chandigarh', state: 'Chandigarh', country: 'India', code: '1361'},
                {id: 'JPR', name: 'Jaipur', state: 'Rajasthan', country: 'India', code: 'JPR'},
            ];
        }

        function initCitySearch() {
            $('.origin-search').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase().trim();
                var parentList = $('#originCityList');
                
                var visibleCount = 0;
                parentList.find('.city-item').each(function() {
                    var cityName = $(this).find('h6').text().toLowerCase();
                    var cityState = $(this).find('p').text().toLowerCase();
                    
                    if (searchTerm === '' || cityName.includes(searchTerm) || cityState.includes(searchTerm)) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                var noResultsItem = parentList.find('.no-results-item');
                if (visibleCount === 0 && searchTerm !== '') {
                    if (noResultsItem.length === 0) {
                        parentList.append(`
                            <li class="text-center py-3 text-muted no-results-item">
                                No cities found matching "${escapeHtml(searchTerm)}"
                            </li>
                        `);
                    }
                } else {
                    noResultsItem.remove();
                }
            });

            $('.destination-search').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase().trim();
                var parentList = $('#destinationCityList');
                
                var visibleCount = 0;
                parentList.find('.city-item').each(function() {
                    var cityName = $(this).find('h6').text().toLowerCase();
                    var cityState = $(this).find('p').text().toLowerCase();
                    
                    if (searchTerm === '' || cityName.includes(searchTerm) || cityState.includes(searchTerm)) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                var noResultsItem = parentList.find('.no-results-item');
                if (visibleCount === 0 && searchTerm !== '') {
                    if (noResultsItem.length === 0) {
                        parentList.append(`
                            <li class="text-center py-3 text-muted no-results-item">
                                No cities found matching "${escapeHtml(searchTerm)}"
                            </li>
                        `);
                    }
                } else {
                    noResultsItem.remove();
                }
            });
        }

        // Traveler counter
        $('.btn-number').click(function(e) {
            e.preventDefault();
            var field = $(this).data('field');
            var input = $('#' + field);
            var currentVal = parseInt(input.val()) || 0;
            var max = parseInt(input.attr('max')) || 10;
            var min = parseInt(input.attr('min')) || 0;
            
            if ($(this).data('type') === 'plus') {
                if (currentVal < max) {
                    input.val(currentVal + 1);
                }
            } else {
                if (currentVal > min) {
                    input.val(currentVal - 1);
                }
            }
            updateTravelerSummary();
        });

        $('.apply-travelers').click(function() {
            updateTravelerSummary();
            $(this).closest('.dropdown-menu').prev().dropdown('toggle');
            triggerSearchWithLoader();
        });

        function updateTravelerSummary() {
            var adults = parseInt($('#adults').val()) || 0;
            var children = parseInt($('#children').val()) || 0;
            var infants = parseInt($('#infants').val()) || 0;
            var total = adults + children + infants;
            
            var summary = total + ' ' + (total === 1 ? 'Person' : 'Persons');
            $('#travelerSummary').text(summary);
            
            var detail = '';
            if (adults > 0) detail += adults + ' Adult' + (adults > 1 ? 's' : '');
            if (children > 0) detail += (detail ? ', ' : '') + children + ' Child' + (children > 1 ? 'ren' : '');
            if (infants > 0) detail += (detail ? ', ' : '') + infants + ' Infant' + (infants > 1 ? 's' : '');
            $('#travelerDetail').text(detail || '0 Adult');
        }

        // Trigger search with loader
        function triggerSearchWithLoader() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                currentPage = 1;
                searchBuses();
            }, 300);
        }

        // Search form submission
        $('#busSearchForm').submit(function(e) {
            e.preventDefault();
            clearTimeout(searchTimeout);
            currentPage = 1;
            searchBuses();
        });

        $('#searchBtn').click(function(e) {
            e.preventDefault();
            clearTimeout(searchTimeout);
            currentPage = 1;
            searchBuses();
        });

        $('.sort-option').on('change', function() {
            var label = $(this).siblings('label').text();
            $('#sortLabel').text(label);
        });

        $('.apply-sort').click(function() {
            clearTimeout(searchTimeout);
            currentPage = 1;
            searchBuses();
            $(this).closest('.dropdown-menu').prev().dropdown('toggle');
        });

        // Enhanced Search with Page Loader
        function searchBuses() {
            if (isSearching) return;
            
            var origin = $('#origin').val();
            var destination = $('#destination').val();
            var originCode = $('#origin_code').val();
            var destinationCode = $('#destination_code').val();
            
            if (!origin || !destination) {
                showNoResults('Please select both origin and destination.');
                return;
            }

            var formData = $('#busSearchForm').serializeArray();
            var searchData = {};
            
            $.each(formData, function(i, field) {
                searchData[field.name] = field.value;
            });
            
            searchData.origin = origin;
            searchData.destination = destination;
            searchData.origin_code = originCode;
            searchData.destination_code = destinationCode;
            searchData.sort_by = $('input[name="sort"]:checked').val();
            searchData.page = currentPage;
            searchData.per_page = perPage;
            searchData.trip_type = 'oneway';

            // Show page loader
            showPageLoader(true);
            isSearching = true;

            // Animate progress bar
            var progress = 0;
            var statusMessages = [
                { progress: 10, message: 'Searching for available buses...' },
                { progress: 30, message: 'Checking seat availability...' },
                { progress: 50, message: 'Fetching best options for you...' },
                { progress: 70, message: 'Almost there...' },
                { progress: 85, message: 'Finalizing results...' }
            ];
            var currentMsgIndex = 0;

            progressInterval = setInterval(function() {
                progress += Math.random() * 8;
                if (progress > 95) {
                    progress = 95;
                    clearInterval(progressInterval);
                }
                $('#searchProgress').css('width', progress + '%');

                for (var i = currentMsgIndex; i < statusMessages.length; i++) {
                    if (progress >= statusMessages[i].progress) {
                        $('#loaderMessage').text(statusMessages[i].message);
                        $('#loaderStatusBadge').text('Loading... ' + Math.round(progress) + '%');
                        currentMsgIndex = i + 1;
                    }
                }
            }, 400);

            $.ajax({
                url: '{{ route("bus.search") }}',
                type: 'POST',
                data: searchData,
                timeout: 30000,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    clearInterval(progressInterval);
                    $('#searchProgress').css('width', '100%');
                    $('#loaderMessage').text('Results found!');
                    $('#loaderStatusBadge').text('Complete!');
                    
                    setTimeout(function() {
                        isSearching = false;
                        showPageLoader(false);
                        if (response.success) {
                            totalBuses = response.total;
                            displayBusResults(response.data);
                            updatePagination(response);
                            $('#resultCountText').text(response.total + ' Buses Found on Your Search');
                        } else {
                            showNoResults(response.message || 'No buses found for your search criteria.');
                        }
                    }, 500);
                },
                error: function(xhr) {
                    clearInterval(progressInterval);
                    isSearching = false;
                    showPageLoader(false);
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Please fix the following errors:\n';
                        $.each(errors, function(key, value) {
                            errorMessage += '- ' + value[0] + '\n';
                        });
                        alert(errorMessage);
                    } else if (xhr.status === 404) {
                        showNoResults('No buses found for the selected route. Please try different cities or date.');
                    } else {
                        showNoResults('Search failed. Please try again.');
                    }
                }
            });
        }

        // Page Loader Functions
        function showPageLoader(show) {
            if (show) {
                $('#pageLoader').fadeIn(300);
                $('#busResults').hide();
                $('#noResults').hide();
                $('#paginationContainer').hide();
                $('#searchBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Searching...');
                $('#searchProgress').css('width', '0%');
                $('#loaderMessage').text('Please wait while we find the best options for you...');
                $('#loaderStatusBadge').text('Searching...');
            } else {
                $('#pageLoader').fadeOut(300);
                $('#busResults').show();
                $('#searchBtn').prop('disabled', false).html('<i class="isax isax-search-normal me-1"></i>Search');
            }
        }

        function displayBusResults(buses) {
            var container = $('#busResults');
            container.empty();

            if (!buses || buses.length === 0) {
                showNoResults('No buses available for your search criteria.');
                return;
            }

            $('#noResults').hide();

            window.busData = {};
            $.each(buses, function(index, bus) {
                window.busData[bus.id] = bus;
                var busCard = createBusCard(bus);
                container.append(busCard);
            });
        }

        function createBusCard(bus) {
            var amenities = '';
            if (bus.amenities && bus.amenities.length > 0) {
                $.each(bus.amenities.slice(0, 3), function(i, amenity) {
                    amenities += '<span><i class="isax isax-' + amenity.icon + ' text-gray-6 me-1"></i></span>';
                });
            }

            var ratingHtml = '';
            if (bus.rating) {
                var fullStars = Math.floor(bus.rating);
                for (var i = 0; i < fullStars; i++) {
                    ratingHtml += '<i class="fas fa-star filled text-primary me-1"></i>';
                }
                if (bus.rating - fullStars > 0) {
                    ratingHtml += '<i class="fas fa-star-half-alt filled text-primary me-1"></i>';
                }
                ratingHtml += ' <span class="ms-1">(' + (bus.reviews_count || 0) + ')</span>';
            }

            var imageUrl = bus.images && bus.images.length > 0 ? bus.images[0] : '{{ asset("frontend/img/bus/bus-img-01.jpg") }}';

            var busTypeBadge = '';
            if (bus.ac) {
                busTypeBadge += '<span class="badge bg-success me-1">AC</span>';
            }
            if (bus.non_ac) {
                busTypeBadge += '<span class="badge bg-warning me-1">Non-AC</span>';
            }
            if (bus.seater) {
                busTypeBadge += '<span class="badge bg-info me-1">Seater</span>';
            }
            if (bus.sleeper) {
                busTypeBadge += '<span class="badge bg-primary me-1">Sleeper</span>';
            }

            return `
                <div class="place-item p-4 mb-4 bus-card" data-bus-id="${bus.id}">
                    <div class="place-img">
                        <a href="javascript:void(0)">
                            <img src="${imageUrl}" class="img-fluid" alt="${bus.name}">
                        </a>
                        <div class="fav-item">
                            <div class="d-flex align-items-center">
                                <a href="#" class="fav-icon me-2" onclick="toggleFavorite(this)">
                                    <i class="isax isax-heart5"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="place-content">
                        <div class="bus-content">
                            <div class="bus-title-item">
                                <h5 class="text-truncate mb-1">${bus.name}</h5>
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="avatar avatar-sm me-2">
                                        <img src="{{ asset('frontend/img/bus/bus-logo-01.svg') }}" class="rounded-circle" alt="icon">
                                    </span>
                                    <p class="fs-14 mb-0 me-2">${bus.operator}</p>
                                    ${busTypeBadge}
                                    ${ratingHtml}
                                </div>
                            </div>
                            <div class="bus-title">
                                <div class="bus-title-item">
                                    <h5 class="text-truncate mb-1">${bus.origin}</h5>
                                    <p>${bus.departure_time}</p>
                                </div>
                                <div class="dot-line">
                                    <span><small class="text-muted">${bus.duration}</small></span>
                                </div>
                                <div class="bus-title-item">
                                    <h5 class="text-truncate mb-1">${bus.destination}</h5>
                                    <p>${bus.arrival_time}</p>
                                </div>
                            </div>
                            <div>
                                <h6 class="d-flex align-items-center text-gray-6 fs-14 fw-normal">
                                    From
                                    <span class="ms-1 fs-18 fw-semibold text-primary">₹${bus.fare}</span>
                                    <span class="ms-2 fs-12 text-success">${bus.available_seats} seats left</span>
                                </h6>
                            </div>
                        </div>
                        <div class="bus-footer">
                            <div class="d-flex align-items-center justify-content-between me-2">
                                <div class="me-2">
                                    <span class="badge bg-light rounded-pill">${bus.bus_type}</span>
                                </div>
                                <div class="bus-footer-dot">
                                    <span></span>
                                </div>
                                <div>
                                    ${amenities}
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-success btn-md search-btn" type="button" onclick="viewSeats('${bus.id}')">
                                    <i class="isax isax-eye me-1"></i>View Seats
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function updatePagination(response) {
            var container = $('#paginationContainer');
            var list = $('#paginationList');
            list.empty();

            if (!response || response.total <= response.per_page) {
                container.hide();
                return;
            }

            container.show();

            var totalPages = response.last_page || 1;
            var currentPage = response.current_page || 1;

            var prevDisabled = currentPage === 1 ? 'disabled' : '';
            list.append(`
                <li class="page-item ${prevDisabled}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}" ${prevDisabled ? '' : 'onclick="goToPage(event, this)"'}>
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                </li>
            `);

            var startPage = Math.max(1, currentPage - 2);
            var endPage = Math.min(totalPages, currentPage + 2);

            if (startPage > 1) {
                list.append(`<li class="page-item"><a class="page-link" href="#" data-page="1" onclick="goToPage(event, this)">1</a></li>`);
                if (startPage > 2) {
                    list.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                }
            }

            for (var i = startPage; i <= endPage; i++) {
                var active = i === currentPage ? 'active' : '';
                list.append(`
                    <li class="page-item ${active}">
                        <a class="page-link" href="#" data-page="${i}" onclick="goToPage(event, this)">${i}</a>
                    </li>
                `);
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    list.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                }
                list.append(`<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}" onclick="goToPage(event, this)">${totalPages}</a></li>`);
            }

            var nextDisabled = currentPage === totalPages ? 'disabled' : '';
            list.append(`
                <li class="page-item ${nextDisabled}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}" ${nextDisabled ? '' : 'onclick="goToPage(event, this)"'}>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
            `);
        }

        window.goToPage = function(event, element) {
            event.preventDefault();
            var page = $(element).data('page');
            if (page && !$(element).closest('.page-item').hasClass('disabled')) {
                currentPage = page;
                clearTimeout(searchTimeout);
                searchBuses();
                $('html, body').animate({
                    scrollTop: $('#busListContainer').offset().top - 100
                }, 300);
            }
        };

        function showNoResults(message) {
            $('#pageLoader').hide();
            $('#busResults').hide();
            $('#paginationContainer').hide();
            $('#noResults').show();
            $('#noResults p').text(message || 'No buses found for your search criteria.');
            $('#searchBtn').prop('disabled', false).html('<i class="isax isax-search-normal me-1"></i>Search');
        }

        // View Seats
        window.viewSeats = function(busId) {
            if (window.busData && window.busData[busId]) {
                var bus = window.busData[busId];
                sessionStorage.setItem('selectedBus', JSON.stringify(bus));
                sessionStorage.setItem('searchParams', JSON.stringify({
                    origin: $('#origin').val(),
                    destination: $('#destination').val(),
                    origin_code: $('#origin_code').val(),
                    destination_code: $('#destination_code').val(),
                    departure_date: $('#departure_date').val(),
                    travelers: $('#travelerSummary').text().trim()
                }));
                window.location.href = '/bus/seats/' + encodeURIComponent(busId);
            } else {
                $.ajax({
                    url: '/bus/details/' + busId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var bus = response.data;
                            sessionStorage.setItem('selectedBus', JSON.stringify(bus));
                            sessionStorage.setItem('searchParams', JSON.stringify({
                                origin: $('#origin').val(),
                                destination: $('#destination').val(),
                                origin_code: $('#origin_code').val(),
                                destination_code: $('#destination_code').val(),
                                departure_date: $('#departure_date').val(),
                                travelers: $('#travelerSummary').text().trim()
                            }));
                            window.location.href = '/bus/seats/' + busId;
                        } else {
                            alert('Failed to load bus details. Please try again.');
                        }
                    },
                    error: function() {
                        alert('Failed to load bus details. Please try again.');
                    }
                });
            }
        };

        window.toggleFavorite = function(element) {
            $(element).toggleClass('selected');
            return false;
        };

        // Handle Enter key
        $('input, select').keypress(function(e) {
            if (e.which === 13) {
                e.preventDefault();
                clearTimeout(searchTimeout);
                currentPage = 1;
                searchBuses();
            }
        });

        // Initialize
        initCitySearch();
        loadCities();

        // Auto search on page load
        setTimeout(function() {
            if ($('#origin').val() && $('#destination').val()) {
                triggerSearchWithLoader();
            }
        }, 500);
    }

    // =============================================
    // START THE INITIAL LOADER
    // =============================================
    startInitialLoader();
});
</script>

<style>
/* =============================================
   INITIAL PAGE LOADER
   ============================================= */
.initial-page-loader {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeInLoader 0.5s ease-in;
}

@keyframes fadeInLoader {
    from { opacity: 0; }
    to { opacity: 1; }
}

.loader-content {
    text-align: center;
    padding: 50px 60px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 30px 80px rgba(0,0,0,0.15);
    min-width: 380px;
    animation: scaleInLoader 0.6s ease-out;
}

@keyframes scaleInLoader {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.loader-logo img {
    animation: pulseLogo 2s ease-in-out infinite;
}

@keyframes pulseLogo {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.loader-spinners {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.loader-spinners .spinner-grow {
    display: inline-block;
}

.loader-progress .progress {
    background: #f0f0f0;
    border-radius: 10px;
    overflow: hidden;
}

.loader-progress .progress-bar {
    transition: width 0.3s ease;
    background: linear-gradient(90deg, #0d6efd, #0a58ca, #0d6efd);
    background-size: 200% 100%;
    animation: progressGradient 2s ease-in-out infinite;
}

@keyframes progressGradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.loader-status .badge {
    font-size: 14px;
    padding: 6px 18px;
    border-radius: 20px;
}

.loader-timer small {
    font-size: 13px;
    color: #6c757d;
}

/* =============================================
   PAGE LOADER OVERLAY (Search)
   ============================================= */
.page-loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.95);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(8px);
    animation: fadeInOverlay 0.3s ease-in;
}

@keyframes fadeInOverlay {
    from { opacity: 0; }
    to { opacity: 1; }
}

.page-loader-content {
    text-align: center;
    padding: 50px 60px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 30px 80px rgba(0,0,0,0.15);
    min-width: 380px;
    animation: scaleIn 0.4s ease-out;
}

@keyframes scaleIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* =============================================
   BUS CARD ANIMATIONS
   ============================================= */
.bus-card {
    animation: fadeIn 0.2s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* =============================================
   FAVORITE ICON
   ============================================= */
.fav-icon.selected i {
    color: #ff4d4d;
}

/* =============================================
   CITY DROPDOWNS
   ============================================= */
.location-list {
    max-height: 300px;
    overflow-y: auto;
    padding: 0;
    margin: 0;
}

.location-list li {
    cursor: pointer;
    list-style: none;
}

.location-list li:hover {
    background-color: #f8f9fa;
}

.location-list .dropdown-item {
    padding: 8px 16px;
    display: block;
}

.location-list .dropdown-item h6 {
    margin-bottom: 2px;
    font-size: 16px;
    font-weight: 500;
}

.location-list .dropdown-item p {
    margin-bottom: 0;
    font-size: 12px;
    color: #6c757d;
}

.input-search .input-group-text {
    background-color: #fff;
}

.no-results-item {
    color: #6c757d;
    font-style: italic;
    text-align: center;
    padding: 10px;
}

.city-loading {
    text-align: center;
    padding: 20px;
    color: #6c757d;
}

.city-loading .spinner-border {
    width: 1.2rem;
    height: 1.2rem;
}

/* =============================================
   PAGINATION
   ============================================= */
.pagination .page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.pagination .page-link {
    color: #0d6efd;
    cursor: pointer;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    cursor: not-allowed;
}

#searchBtn .spinner-border {
    width: 1rem;
    height: 1rem;
}

/* =============================================
   DROPDOWN FIXES
   ============================================= */
.dropdown-toggle::after {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
}

.form-item .dropdown {
    position: relative;
}

.way-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) !important;
    z-index: 5;
    pointer-events: none;
}

/* =============================================
   RESPONSIVE
   ============================================= */
@media (max-width: 768px) {
    .loader-content {
        min-width: auto;
        padding: 30px 25px;
        margin: 20px;
    }
    
    .loader-spinners .spinner-grow {
        width: 2.5rem !important;
        height: 2.5rem !important;
    }
    
    .page-loader-content {
        min-width: auto;
        padding: 30px 25px;
        margin: 20px;
    }
}
</style>
@endsection