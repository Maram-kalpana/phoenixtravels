@extends('layouts.web')
@section('title', $tour->meta_title.' | Phoenix Tours & Travels')
@section('css')
<style>
  .departure-wrap {
    background: #f8fafc;
    border-radius: 16px;
    padding: 16px;
    margin-top: 12px !important;
}

.departure-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
    font-weight: 600;
    color: #1f2937;
    padding-bottom: 8px;
    border-bottom: 2px solid #e2e8f0;
}

.departure-title i {
    color: #2563eb;
    font-size: 18px;
}

.departure-list {
    display: flex;
    flex-wrap: nowrap;
    gap: 12px;
    margin-top: 12px;
}

.departure-box {
    min-width: 66px;
    padding: 2px 10px;
    background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.departure-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.05), transparent);
    transition: left 0.5s;
}

.departure-box:hover {
    border-color: #2563eb;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.12);
}

.departure-box:hover::before {
    left: 100%;
}

.departure-day {
    font-size: 24px;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
    letter-spacing: -0.5px;
}

.departure-month {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    margin-top: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.departure-year {
    font-size: 10px;
    font-weight: 500;
    color: #9ca3af;
    margin-top: 2px;
}

.departure-more {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-color: #bfdbfe;
    min-width: 85px;
}

.departure-more i {
    font-size: 20px;
    color: #2563eb;
    margin-bottom: 4px;
}

.more-count {
    font-size: 18px;
    font-weight: 800;
    color: #2563eb;
    line-height: 1;
}

.more-text {
    font-size: 10px;
    font-weight: 600;
    color: #2563eb;
    margin-top: 4px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* Optional: Add a badge for today's date */
.departure-box.today {
    border: 2px solid #2563eb;
    background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
    position: relative;
}

.departure-box.today::after {
    content: 'TODAY';
    position: absolute;
    top: -8px;
    right: -8px;
    background: #2563eb;
    color: white;
    font-size: 8px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 20px;
    letter-spacing: 0.5px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .departure-wrap {
        padding: 12px;
    }
    
    .departure-box {
        min-width: 65px;
        padding: 8px 10px;
    }
    
    .departure-day {
        font-size: 20px;
    }
    
    .departure-month {
        font-size: 10px;
    }
}
</style>
<style>
    /* Loader Styles */
    #loader {
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* Transition effects */
    #toursContainer {
        transition: opacity 0.3s ease;
    }

    .place-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .place-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    /* Amenities grid */
    .amenities-grid {
        display: flex;
        flex-wrap: wrap;
       
    }

    .amenity-item li {
        font-size: 11px;
        list-style: none;
        background: #f5f5f5;
        /*padding: 2px 8px;*/
        border-radius: 4px;
        margin: 2px;
    }
</style>
@endsection

@section('content')
<div class="breadcrumb-bar text-center"
     style="background: url('{{ asset($tour->image) }}') no-repeat center center; background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">{{ $type_name }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">
                                <i class="isax isax-home5"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $type_name }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
      
    <div class="content">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                
                
                <div class="col-xl-3 col-lg-4 theiaStickySidebar">

                    <div class="card filter-sidebar mb-4 mb-lg-0">
                        @if($tour->tour_category_id == 1)
                        <h2 class="card-header d-flex align-items-center"><a href="{{ route('tours.list') }}" style="color: #ff9d5b;text-decoration: underline;font-size: 14px;text-decoration-color: #1d9cd0;text-decoration-thickness: 1px;text-underline-offset: 6px;">View All Package</a></h2>
                        @else
                        <h2 class="card-header d-flex align-items-center"><a href="{{ route('international.tours.list') }}" style="color: #ff9d5b;text-decoration: underline;font-size: 14px;text-decoration-color: #1d9cd0;text-decoration-thickness: 1px;text-underline-offset: 6px;">View All Package</a></h2>
                        @endif
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5>Filters</h5>
                            <a href="#" class="fs-14 link-primary" id="resetFilters">Reset</a>
                        </div>
                        <div class="card-body p-0">
                            <form id="filterForm">
                                <div class="p-3 border-bottom">
                                    <label class="form-label fs-16">Search by Tour Type</label>
                                    <div class="input-icon">
                                        <span class="input-icon-addon">
                                            <i class="isax isax-search-normal"></i>
                                        </span>
                                        <input type="text" class="form-control" name="search_tour_type" placeholder="Search by Tour Type">
                                    </div>
                                </div>
                                <div class="accordion accordion-list">
                                    <div class="accordion-item border-bottom p-3">
                                        <div class="accordion-header">
                                            <div class="accordion-button p-0" data-bs-toggle="collapse"
                                                data-bs-target="#accordion-populars" aria-expanded="true"
                                                aria-controls="accordion-populars" role="button">
                                                <i class="isax isax-ranking me-2 text-primary"></i>Popular
                                            </div>
                                        </div>
                                        <div id="accordion-populars" class="accordion-collapse collapse show">
                                            <div class="accordion-body pt-2">
                                                @foreach($tour_state as $key => $val)
                                                <div class="form-checkbox form-check form-check-inline d-inline-flex align-items-center mt-2 me-2">
                                                    <input class="form-check-input ms-0 mt-0 filter-checkbox" 
                                                        name="tour_types[]" 
                                                        type="checkbox" 
                                                        value="{{ $val->id }}"  
                                                        id="popular{{ $val->id }}">
                                                    <label class="form-check-label ms-2 fs-13" for="popular{{ $val->id }}">
                                                        {{ $val->tour_name }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item border-bottom p-3">
                                        <div class="accordion-header">
                                            <div class="accordion-button p-0" data-bs-toggle="collapse"
                                                data-bs-target="#accordion-popular" aria-expanded="true"
                                                aria-controls="accordion-popular" role="button">
                                                <i class="isax isax-coin me-2 text-primary"></i>Budget (per person)
                                            </div>
                                        </div>
                                        <div id="accordion-popular" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <div class="filter-range">
                                                    <input type="text" id="range_03" name="price_range">
                                                </div>
                                                <div class="filter-range-amount">
                                                    <p class="fs-14">Range : <span class="text-gray-9 fw-medium">₹<span id="min_price">0</span> - ₹<span id="max_price">50000</span></span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="accordion-item border-bottom p-3">
                                        <div class="accordion-header">
                                            <div class="accordion-button p-0" data-bs-toggle="collapse"
                                                data-bs-target="#accordion-brand" aria-expanded="true"
                                                aria-controls="accordion-brand" role="button">
                                                <i class="isax isax-discount-shape me-2 text-primary"></i>Reviews
                                            </div>
                                        </div>
                                        <div id="accordion-brand" class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <div class="form-check d-flex align-items-center ps-0 mb-2">
                                                    <label class="form-check-label ms-2" for="review1">
                                                        <span class="rating d-flex align-items-center">
                                                            <i class="fas fa-star filled text-primary me-1"></i>
                                                            <i class="fas fa-star filled text-primary me-1"></i>
                                                            <i class="fas fa-star filled text-primary me-1"></i>
                                                            <i class="fas fa-star filled text-primary me-1"></i>
                                                            <i class="fas fa-star filled text-primary"></i>
                                                            <span class="ms-2">5 Star</span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check d-flex align-items-center ps-0 mb-2">
                                                    <label class="form-check-label ms-2" for="review2">
                                                        <span class="rating d-flex align-items-center">
                                                            <i class="fas fa-star filled text-primary me-1"></i>
                                                            <i class="fas fa-star filled text-primary me-1"></i>
                                                            <i class="fas fa-star filled text-primary me-1"></i>
                                                            <i class="fas fa-star filled text-primary"></i>
                                                            <span class="ms-2">4 Star</span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check d-flex align-items-center ps-0 mb-2">
                                                    <label class="form-check-label ms-2" for="review3">
                                                        <span class="rating d-flex align-items-center">
                                                            <i class="fas fa-star filled text-primary me-1"></i>
                                                            <i class="fas fa-star filled text-primary me-1"></i>
                                                            <i class="fas fa-star filled text-primary"></i>
                                                            <span class="ms-2">3 Star</span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check d-flex align-items-center ps-0 mb-2">
                                                    <label class="form-check-label ms-2" for="review4">
                                                        <span class="rating d-flex align-items-center">
                                                            <i class="fas fa-star filled text-primary me-1"></i>
                                                            <i class="fas fa-star filled text-primary"></i>
                                                            <span class="ms-2">2 Star</span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check d-flex align-items-center ps-0 mb-0">
                                                    <label class="form-check-label ms-2" for="review5">
                                                        <span class="rating d-flex align-items-center">
                                                            <i class="fas fa-star filled text-primary"></i>
                                                            <span class="ms-2">1 Star</span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /Sidebar -->

                <div class="col-xl-9 col-lg-8 theiaStickySidebar">
                    <!-- Loader -->
                    
                    <div id="loader" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading packages...</p>
                    </div>
                    
                    <!-- Tours Container -->
                    <div class="row" id="toursContainer">
                        @include('partials.fixed-departures-tour-item', ['packages' => $packages])
                    </div>
                    
                    <!-- Pagination Container -->
                    <div id="paginationContainer">
                        @include('partials.pagination', ['packages' => $packages])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Get min and max price from existing packages
    var minPrice = 0;
    var maxPrice = 50000;
    
    // Initialize range slider
    var priceRange = $("#range_03").ionRangeSlider({
        type: "double",
        grid: true,
        min: minPrice,
        max: maxPrice,
        from: minPrice,
        to: maxPrice,
        prefix: "₹",
        onFinish: function(data) {
            $('#min_price').text(data.from);
            $('#max_price').text(data.to);
            applyFilters();
        }
    });
    
    // Variables
    let currentPage = 1;
    let debounceTimer;
    
    // Function to apply filters
    function applyFilters(page = 1) {
        currentPage = page;
        showLoader();
        
        // Get all filter values
        let tourTypes = [];
        $('input[name="tour_types[]"]:checked').each(function() {
            tourTypes.push($(this).val());
        });
        
        let reviews = [];
        $('input[name="reviews[]"]:checked').each(function() {
            reviews.push($(this).val());
        });
        
        // Get price range
        let priceRangeValue = $('#range_03').val();
        let minPriceVal = null;
        let maxPriceVal = null;
        
        if(priceRangeValue && priceRangeValue !== '') {
            let priceValues = priceRangeValue.split(';');
            if(priceValues[0] && priceValues[1]) {
                minPriceVal = priceValues[0];
                maxPriceVal = priceValues[1];
            }
        }
        
        // Get search value
        let searchValue = $('input[name="search_tour_type"]').val();
        if(searchValue && searchValue.trim() === '') {
            searchValue = null;
        }
        
        let formData = {
            page: page,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        // Only add parameters if they have values
        if(tourTypes.length > 0) {
            formData.tour_types = tourTypes;
        }
        
        if(reviews.length > 0) {
            formData.reviews = reviews;
        }
        
        if(minPriceVal !== null && maxPriceVal !== null && minPriceVal != maxPriceVal) {
            formData.min_price = minPriceVal;
            formData.max_price = maxPriceVal;
        }
        
        if(searchValue !== null) {
            formData.search_tour_type = searchValue;
        }
        
        console.log('Sending filters:', formData);
        
        $.ajax({
            url: window.location.href,
            type: 'GET',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Response received:', response);
                if(response.success) {
                    $('#toursContainer').html(response.data);
                    $('#paginationContainer').html(response.pagination);
                    reinitializeCarousels();
                } else {
                    showError(response.message || 'An error occurred');
                }
                hideLoader();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);
                hideLoader();
                showError('An error occurred while filtering tours. Please try again.');
            }
        });
    }
    
    // Function to show loader
    function showLoader() {
        $('#loader').fadeIn(200);
        $('#toursContainer').fadeOut(200);
        $('#paginationContainer').fadeOut(200);
    }
    
    // Function to hide loader
    function hideLoader() {
        $('#loader').fadeOut(200);
        $('#toursContainer').fadeIn(200);
        $('#paginationContainer').fadeIn(200);
    }
    
    // Function to show error message
    function showError(message) {
        $('#toursContainer').html(`
            <div class="col-12">
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-triangle"></i> ${message}
                </div>
            </div>
        `);
        $('#toursContainer').fadeIn(200);
        $('#paginationContainer').html('');
    }
    
    // Function to reinitialize carousels
    function reinitializeCarousels() {
        if($('.image-slide').length) {
            $('.image-slide').each(function() {
                if($(this).hasClass('owl-loaded')) {
                    $(this).trigger('destroy.owl.carousel');
                }
                $(this).owlCarousel({
                    items: 1,
                    loop: true,
                    nav: true,
                    dots: false,
                    autoplay: true,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                    navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>']
                });
            });
        }
    }
    
    // Event listeners with debouncing
    function debounce(func, wait) {
        return function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(func, wait);
        };
    }
    
    // Filter change events
    $('.filter-checkbox, .review-checkbox').on('change', function() {
        console.log('Filter changed');
        debounce(() => applyFilters(1), 300)();
    });
    
    // Search input with debounce
    $('input[name="search_tour_type"]').on('keyup', function() {
        console.log('Search input changed');
        debounce(() => applyFilters(1), 500)();
    });
    
    // Reset filters
    $('#resetFilters').on('click', function(e) {
        e.preventDefault();
        
        console.log('Resetting filters');
        
        // Reset checkboxes
        $('.filter-checkbox, .review-checkbox').prop('checked', false);
        
        // Reset search input
        $('input[name="search_tour_type"]').val('');
        
        // Reset price range
        var slider = $("#range_03").data("ionRangeSlider");
        if(slider) {
            slider.update({
                from: minPrice,
                to: maxPrice
            });
        }
        $('#min_price').text(minPrice);
        $('#max_price').text(maxPrice);
        
        // Apply reset with no filters
        applyFilters(1);
    });
    
    // Pagination click event
    $(document).on('click', '.pagination-nav .page-link', function(e) {
        e.preventDefault();
        let page = $(this).data('page');
        if(page && !isNaN(page)) {
            console.log('Pagination clicked:', page);
            applyFilters(page);
            // Scroll to top of tours container
            $('html, body').animate({
                scrollTop: $('#toursContainer').offset().top - 100
            }, 500);
        }
    });
    
    // Initial load - reinitialize carousels for existing content
    reinitializeCarousels();
});
</script>
@endsection