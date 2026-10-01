@extends('layouts.web')
@section('title','Tours List | Phoenix Tours & Travels')

@section('css')
<style>

/* ===== LOADER CENTER FIX ===== */
.loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.9);
    display: none;
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.loader-spinner {
    width: 55px;
    height: 55px;
    border: 5px solid #eee;
    border-top: 5px solid #1e9dcf;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    100% { transform: rotate(360deg); }
}

/* ===== CARD ===== */
.tour-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.tour-img img {
    width: 100%;
    height: 100%;
    min-height: 260px;
    object-fit: cover;
}

.price {
    color: #1e9dcf;
    font-size: 20px;
    font-weight: bold;
}

.border-start {
    border-left: 1px solid #eee !important;
}

/* ===== PAGINATION ===== */
.pagination-nav {
    margin-top: 40px;
}

.pagination {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    justify-content: center;
}

.pagination .page-item .page-link {
    padding: 10px 16px;
    border: 1px solid #ddd;
    background: #fff;
    color: #333;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
}

.pagination .page-item .page-link:hover {
    background: #1e9dcf;
    color: #fff;
    border-color: #1e9dcf;
}

.pagination .page-item.active .page-link {
    background: #1e9dcf;
    color: #fff;
    border-color: #1e9dcf;
}

.pagination .page-item.disabled .page-link {
    opacity: 0.5;
    pointer-events: none;
    cursor: not-allowed;
}

/* ===== PAGE INFO ===== */
.page-info {
    text-align: center;
    margin-top: 15px;
    color: #666;
    font-size: 14px;
}

/* ===== SEARCH BAR ===== */
.search-wrapper {
    margin-bottom: 30px;
}

.search-box {
    position: relative;
    max-width: 500px;
    margin: 0 auto;
}

.search-box input {
    width: 100%;
    padding: 12px 50px 12px 20px;
    border: 1px solid #ddd;
    border-radius: 30px;
    font-size: 14px;
    outline: none;
    transition: all 0.3s;
}

.search-box input:focus {
    border-color: #1e9dcf;
    box-shadow: 0 0 0 3px rgba(253, 120, 113, 0.1);
}

.search-box button {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background: #1e9dcf;
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s;
}

.search-box button:hover {
    background: #e56760;
}

.search-info {
    margin-top: 10px;
    color: #666;
    font-size: 14px;
    text-align: center;
}

.clear-search {
    color: #1e9dcf;
    cursor: pointer;
    margin-left: 10px;
    text-decoration: underline;
}

/* ===== TABLE STYLES ===== */
.table {
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
    padding: 10px 8px;
}

.table td {
    text-align: center;
    padding: 8px;
    white-space: nowrap;
}

.table td:first-child {
    text-align: left;
    background-color: #fafafa;
}

.table-responsive {
    overflow-x: auto;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.empty-state i {
    font-size: 48px;
    color: #ddd;
    margin-bottom: 20px;
}

/* ===== BADGE STYLES ===== */
.badge.bg-primary {
    background-color: #1e9dcf !important;
}

.btn-primary {
    background-color: #1e9dcf;
    border-color: #1e9dcf;
}
.table thead tr th {
    font-weight: 600;
    border-color: #E7EDF2;
    background: #F3EFF9;
    font-size: 14px;
    color: #111827;
}
.btn-primary:hover {
    background-color: #e56760;
    border-color: #e56760;
}

.text-primary {
    color: #1e9dcf !important;
}

/* ===== RESPONSIVE ===== */
@media(max-width:768px){
    .border-start {
        border-left: none !important;
        border-top: 1px solid #eee;
    }
    
    .tour-img img {
        min-height: 180px;
    }
    
    .table {
        font-size: 12px;
    }
    
    .table th,
    .table td {
        padding: 6px 4px;
    }
    
    .pagination .page-item .page-link {
        padding: 8px 12px;
        font-size: 13px;
    }
}

@media(max-width:576px){
    .pagination {
        gap: 4px;
    }
    
    .pagination .page-item .page-link {
        padding: 6px 10px;
        font-size: 12px;
    }
}



.tour-card {
    border-radius: 12px;
    overflow: hidden;
    transition: 0.3s;
}

.tour-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.tour-img img {
    height: 100%;
    object-fit: cover;
}

.price {
    font-size: 20px;
    font-weight: bold;
}


@media (max-width: 576px) {

    /* Title + Code in one line */
    .tour-header-mobile {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 6px;
    }

    .tour-header-mobile h5 {
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 65%;
    }

    .tour-header-mobile .badge {
        font-size: 10px;
        white-space: nowrap;
    }
    .tour-location-mobile {
        font-size: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

   
}
</style>
@endsection


@section('content')

<!-- Loader Overlay -->
<div class="loader-overlay" id="loader">
    <div class="loader-spinner"></div>
</div>

<!-- Breadcrumb -->
<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">All Packages</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="isax isax-home5"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Packages</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="content">
    <div class="container">
        <!-- Search Bar -->
        <div class="search-wrapper">
            <div class="search-box">
                <input type="text" id="search-input" placeholder="Search by package name, tour code...">
                <button id="search-btn">
                    <i class="isax isax-search-normal-1"></i>
                </button>
            </div>
            <div class="search-info" id="search-info"></div>
        </div>
        
        <!-- Tours Container -->
        <div id="tours-container"></div>

        <!-- Pagination -->
        <nav class="pagination-nav">
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>

        <!-- Page Info -->
        <div class="page-info" id="page-info"></div>
    </div>
</div>

@endsection


@section('script')
<script>
$(document).ready(function(){

    let currentPage = 1;
    let perPage = 5;
    let currentSearch = '';
    let searchTimeout = null;

    // Initial load
    loadTours(currentPage, currentSearch);

    // Search functionality
    $('#search-input').on('keyup', function() {
        const searchTerm = $(this).val().trim();
        
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        searchTimeout = setTimeout(function() {
            currentSearch = searchTerm;
            currentPage = 1;
            loadTours(currentPage, currentSearch);
        }, 500);
    });
    
    $('#search-btn').on('click', function() {
        const searchTerm = $('#search-input').val().trim();
        currentSearch = searchTerm;
        currentPage = 1;
        loadTours(currentPage, currentSearch);
    });
    
    $('#search-input').on('keypress', function(e) {
        if (e.which === 13) {
            const searchTerm = $(this).val().trim();
            currentSearch = searchTerm;
            currentPage = 1;
            loadTours(currentPage, currentSearch);
        }
    });

    function loadTours(page, search = ''){
        showLoader();

        $.ajax({
            url: "{{ route('packages.ajax') }}",
            method: 'GET',
            data: {
                page: page,
                per_page: perPage,
                search: search
            },
            success: function(res){
                if (res.success) {
                    renderTours(res.data);
                    renderPagination(res);
                    updatePageInfo(res);
                    updateSearchInfo(res, search);
                } else {
                    showError('Failed to load packages');
                }
            },
            error: function(){
                showError('Error loading packages');
            },
            complete: function(){
                hideLoader();
            }
        });
    }

    function renderTours(packages){

        let html = '';

        if(!packages || packages.length === 0){
            html = `
                <div class="empty-state">
                    <i class="isax isax-location5"></i>
                    <h4>No Packages Found</h4>
                    <p>${currentSearch ? 'No packages match your search criteria.' : 'Please check back later for new tour packages.'}</p>
                    ${currentSearch ? '<button class="btn btn-outline-primary mt-3" onclick="clearSearch()">Clear Search</button>' : ''}
                </div>
            `;
            $('#tours-container').html(html);
            return;
        }

        packages.forEach(pkg => {
            
            let s = pkg.pricing.standard;
            let d = pkg.pricing.deluxe;
            let p = pkg.pricing.premium;
            
            // Get first image or placeholder
            let firstImage = pkg.images && pkg.images.length > 0 
                ? pkg.images[0] 
                : (pkg.package_homepage_image || '{{ asset('images/placeholder.jpg') }}');

            // Convert amenities string into array
let amenitiesArr = pkg.amenities ? pkg.amenities.split('##') : [];

let amenitiesHTML = '';
amenitiesArr.forEach(item => {
    if(item.trim() !== ''){
        amenitiesHTML += `<li class="amenity-item">${item.trim()}</li>`;
    }
});

html += `
<div class="tour-card card mb-4 shadow-sm border-0">
    <div class="row g-0">

        <!-- Image -->
        <div class="col-md-3">
            <div class="tour-img position-relative">
                <img src="${firstImage}" 
                     class="img-fluid rounded-start h-100 w-100 object-fit-cover" 
                     alt="${pkg.package_title}">
            </div>
        </div>

        <!-- Details -->
        <div class="col-md-6 p-2">

            <!-- Title -->
            <h5 class="fw-bold text-primary mb-1">
                ${pkg.package_title}
            </h5>

            <!-- Tour Code + Days -->
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                <span class="badge bg-light text-dark border">
                    Tour Code: ${pkg.package_id}
                </span>

                <span class="badge bg-primary text-white">
                    <i class="isax isax-calendar-tick me-1"></i>
                    ${pkg.nights_count} N / ${pkg.days_count} D
                </span>
            </div>

            <!-- Location -->
            <div class="text-muted mb-2 tour-location-mobile">
                <i class="isax isax-location5 me-1"></i>
                ${pkg.pack_location_cover || 'Location not specified'}
            </div>

            <!-- Price Table -->
            <div class="table-responsive small">
                <table class="table table-sm table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>2 Pax</th>
                            <th>3 Pax</th>
                            <th>4 Pax</th>
                            <th>5 Pax</th>
                            <th>6 Pax</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            <td class="fw-semibold">Standard</td>
                            <td>${s[2] || '--'}</td>
                            <td>${s[3] || '--'}</td>
                            <td>${s[4] || '--'}</td>
                            <td>${s[5] || '--'}</td>
                            <td>${s[6] || '--'}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Premium</td>
                            <td>${d[2] || '--'}</td>
                            <td>${d[3] || '--'}</td>
                            <td>${d[4] || '--'}</td>
                            <td>${d[5] || '--'}</td>
                            <td>${d[6] || '--'}</td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Luxury</td>
                            <td>${p[2] || '--'}</td>
                            <td>${p[3] || '--'}</td>
                            <td>${p[4] || '--'}</td>
                            <td>${p[5] || '--'}</td>
                            <td>${p[6] || '--'}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Price + CTA -->
        <div class="col-md-3 p-3 border-start d-flex flex-column justify-content-between bg-light">

            <!-- Price -->
            
            <!-- Amenities -->
            <div class="amenities-container w-100 p-3">
                <div class="amenity-item">
                    ${amenitiesHTML}
                </div>
            </div>

            <!-- Button -->
            <div class="mt-auto">
                <a href="${pkg.detail_url}" class="btn btn-primary w-100">
                    <i class="isax isax-eye me-1"></i> View Details
                </a>
            </div>

        </div>

    </div>
</div>
`;
        });

        $('#tours-container').html(html);
    }

    function renderPagination(data){

        let html = '';

        // PREVIOUS button
        html += `
            <li class="page-item ${data.current_page == 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${data.current_page - 1}">
                    <i class="fa fa-chevron-left"></i>
                </a>
            </li>
        `;

        // PAGE NUMBERS
        let start = Math.max(1, data.current_page - 2);
        let end = Math.min(data.last_page, data.current_page + 2);

        // First page
        if (start > 1) {
            html += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="1">1</a>
                </li>
            `;
            if (start > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
        }

        // Page numbers
        for(let i = start; i <= end; i++){
            html += `
                <li class="page-item ${i == data.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }

        // Last page
        if (end < data.last_page) {
            if (end < data.last_page - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            html += `
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${data.last_page}">${data.last_page}</a>
                </li>
            `;
        }

        // NEXT button
        html += `
            <li class="page-item ${data.current_page == data.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${data.current_page + 1}">
                    <i class="fa fa-chevron-right"></i>
                </a>
            </li>
        `;

        $('#pagination').html(html);

        // Bind click events
        $('.page-link').click(function(e){
            e.preventDefault();
            let page = $(this).data('page');

            if(page && !$(this).parent().hasClass('disabled')){
                currentPage = page;
                loadTours(page, currentSearch);

                $('html, body').animate({
                    scrollTop: $('.content').offset().top - 80
                }, 300);
            }
        });
    }

    function updatePageInfo(data){
        if (data.total === 0) {
            $('#page-info').html('');
            return;
        }
        
        $('#page-info').html(
            `Showing ${data.from} - ${data.to} of ${data.total} packages`
        );
    }
    
    function updateSearchInfo(data, search) {
        if (search && data.total > 0) {
            $('#search-info').html(`
                Found ${data.total} result${data.total !== 1 ? 's' : ''} for "${search}"
                <span class="clear-search" onclick="clearSearch()">Clear</span>
            `);
        } else if (search && data.total === 0) {
            $('#search-info').html(`
                No results found for "${search}"
                <span class="clear-search" onclick="clearSearch()">Clear</span>
            `);
        } else {
            $('#search-info').html('');
        }
    }

    function showLoader(){
        $('#loader').css('display', 'flex').fadeIn(200);
    }

    function hideLoader(){
        $('#loader').fadeOut(200);
    }
    
    function showError(message) {
        $('#tours-container').html(`
            <div class="empty-state">
                <i class="isax isax-info-circle"></i>
                <h4>Error</h4>
                <p class="text-danger">${message}</p>
                <button class="btn btn-primary mt-3" onclick="location.reload()">
                    <i class="isax isax-refresh me-1"></i> Retry
                </button>
            </div>
        `);
        $('#pagination').empty();
        $('#page-info').empty();
        $('#search-info').empty();
    }

    // Global function for clear search
    window.clearSearch = function() {
        $('#search-input').val('');
        currentSearch = '';
        currentPage = 1;
        loadTours(currentPage, currentSearch);
    };

});
</script>
@endsection