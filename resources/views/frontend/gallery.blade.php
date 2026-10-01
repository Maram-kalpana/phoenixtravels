@extends('layouts.web')
@section('title', 'Phoenix Tours & Travels - Gallery')
@section('css')
<style>
    /* Loader styles - Centered */
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loader-content {
        text-align: center;
        padding: 30px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    }
    
    .loader-spinner {
        width: 60px;
        height: 60px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-right: 5px solid #2ecc71;
        border-bottom: 5px solid #e74c3c;
        border-left: 5px solid #f39c12;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .loader-text {
        color: #333;
        font-size: 16px;
        font-weight: 500;
        margin-top: 10px;
    }
    
    /* Alternative simple loader - centered */
    .loader-simple {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loader-circle {
        width: 70px;
        height: 70px;
        border: 6px solid #e0e0e0;
        border-top: 6px solid #3498db;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    /* Pagination styles */
    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .pagination-btn {
        padding: 10px 18px;
        border: 1px solid #ddd;
        background: #fff;
        color: #333;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
        min-width: 45px;
    }
    
    .pagination-btn:hover:not(:disabled) {
        background: #3498db;
        color: #fff;
        border-color: #3498db;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
    }
    
    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .pagination-btn.active {
        background: #3498db;
        color: #fff;
        border-color: #3498db;
        box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
    }
    
    .pagination-ellipsis {
        padding: 10px 5px;
        color: #666;
        font-weight: bold;
    }
    
    .page-info {
        margin-top: 20px;
        text-align: center;
        color: #666;
        font-size: 14px;
    }
    
    /* Gallery card enhancements */
    .gallery-card {
        transition: transform 0.3s;
    }
    
    .gallery-card:hover {
        transform: translateY(-5px);
    }
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #666;
    }
    
    .empty-state i {
        font-size: 48px;
        color: #ccc;
        margin-bottom: 20px;
    }
    
    /* Alert styles */
    .alert-custom {
        padding: 15px 20px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .alert-danger-custom {
        background: #fee;
        border: 1px solid #fcc;
        color: #c33;
    }
</style>
@endsection

@section('content')
<!-- Centered Loader Overlay -->
<div class="loader-simple" id="loader">
    <div class="loader-circle"></div>
</div>

<!-- Alternative: Loader with text (you can use either one) -->
<!-- 
<div class="loader-overlay" id="loader-with-text">
    <div class="loader-content">
        <div class="loader-spinner"></div>
        <div class="loader-text">Loading Gallery...</div>
    </div>
</div>
-->

<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Gallery</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="isax isax-home5"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container">
        <!-- Gallery Details -->
        <div class="row row-gap-4" id="gallery-container">
            <!-- Gallery items will be loaded here via AJAX -->
            <div class="col-12 text-center">
                <div class="loader-circle" style="margin: 50px auto;"></div>
                <p class="mt-3">Loading gallery...</p>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-container">
            <div class="pagination" id="pagination"></div>
        </div>
        
        <!-- Page Info -->
        <div class="page-info" id="page-info"></div>
    </div>
</div>  
@endsection

@section('script')
<script>
$(document).ready(function() {
    let currentPage = 1;
    let perPage = 12;
    
    // Load gallery on page load
    loadGallery(currentPage);
    
    function loadGallery(page) {
        showLoader();
        
        $.ajax({
            url: "{{ route('gallery.data') }}", // Update with your route name
            method: 'GET',
            data: {
                page: page,
                per_page: perPage
            },
            timeout: 30000, // 30 seconds timeout
            success: function(response) {
                if (response.success) {
                    renderGallery(response.data);
                    renderPagination(response);
                    updatePageInfo(response);
                } else {
                    showError('Failed to load gallery: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Error loading gallery';
                
                if (status === 'timeout') {
                    errorMessage = 'Request timeout. Please try again.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (error) {
                    errorMessage = error;
                }
                
                showError(errorMessage);
            },
            complete: function() {
                hideLoader();
            }
        });
    }
    
    function renderGallery(items) {
        const container = $('#gallery-container');
        container.empty();
        
        if (!items || items.length === 0) {
            container.html(`
                <div class="col-12">
                    <div class="empty-state">
                        <i class="ti ti-image"></i>
                        <h4>No Gallery Images Found</h4>
                        <p>Please check back later for updates.</p>
                    </div>
                </div>
            `);
            return;
        }
        
        items.forEach(function(item) {
            const galleryCard = `
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="gallery-card">
                        <div class="gallery-body">
                            <div class="img-wrap" style="position: relative; overflow: hidden; border-radius: 8px;">
                                <img src="${item.image_url}" 
                                     alt="${item.alt}" 
                                     title="${item.title}" 
                                     style="height: 200px; width: 100%; object-fit: cover; transition: transform 0.3s;"
                                     onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                <div class="img-overlay-1" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.3s; display: flex; align-items: center; justify-content: center;">
                                    <a class="gallery-img" 
                                       data-fancybox="gallery" 
                                       href="${item.image_url}" 
                                       title="${item.title}"
                                       data-caption="${item.title} - ${item.created_at}">
                                        <span class="gallery-eye-icon rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="ti ti-eye text-white" style="font-size: 20px;"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            `;
            container.append(galleryCard);
        });
        
        // Add hover effect
        $('.img-wrap').hover(
            function() {
                $(this).find('img').css('transform', 'scale(1.1)');
                $(this).find('.img-overlay-1').css('opacity', '1');
            },
            function() {
                $(this).find('img').css('transform', 'scale(1)');
                $(this).find('.img-overlay-1').css('opacity', '0');
            }
        );
        
        // Initialize Fancybox
        if (typeof $.fancybox !== 'undefined') {
            $('[data-fancybox="gallery"]').fancybox({
                buttons: [
                    "zoom",
                    "slideShow",
                    "fullScreen",
                    "download",
                    "thumbs",
                    "close"
                ],
                loop: true,
                protect: true,
                animationEffect: "zoom",
                transitionEffect: "fade",
                thumbs: {
                    autoStart: true,
                    axis: 'x'
                }
            });
        }
    }
    
    function renderPagination(data) {
        const pagination = $('#pagination');
        pagination.empty();
        
        if (data.total === 0 || data.last_page === 1) {
            return;
        }
        
        const prevDisabled = data.current_page === 1 ? 'disabled' : '';
        const nextDisabled = !data.has_more_pages ? 'disabled' : '';
        
        // Previous button
        pagination.append(`
            <button class="pagination-btn" ${prevDisabled} data-page="${data.current_page - 1}">
                <i class="ti ti-chevron-left"></i> Prev
            </button>
        `);
        
        // Page numbers
        const startPage = Math.max(1, data.current_page - 2);
        const endPage = Math.min(data.last_page, data.current_page + 2);
        
        if (startPage > 1) {
            pagination.append(`<button class="pagination-btn" data-page="1">1</button>`);
            if (startPage > 2) {
                pagination.append(`<span class="pagination-ellipsis">...</span>`);
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const activeClass = i === data.current_page ? 'active' : '';
            pagination.append(`
                <button class="pagination-btn ${activeClass}" data-page="${i}">${i}</button>
            `);
        }
        
        if (endPage < data.last_page) {
            if (endPage < data.last_page - 1) {
                pagination.append(`<span class="pagination-ellipsis">...</span>`);
            }
            pagination.append(`<button class="pagination-btn" data-page="${data.last_page}">${data.last_page}</button>`);
        }
        
        // Next button
        pagination.append(`
            <button class="pagination-btn" ${nextDisabled} data-page="${data.current_page + 1}">
                Next <i class="ti ti-chevron-right"></i>
            </button>
        `);
        
        // Bind click events
        $('.pagination-btn').on('click', function() {
            const page = $(this).data('page');
            if (page && !$(this).prop('disabled')) {
                currentPage = page;
                loadGallery(page);
                
                // Smooth scroll to gallery section
                $('html, body').animate({
                    scrollTop: $('.content').offset().top - 80
                }, 400);
            }
        });
    }
    
    function updatePageInfo(data) {
        if (data.total === 0) {
            $('#page-info').html('No images available');
            return;
        }
        
        const start = (data.current_page - 1) * data.per_page + 1;
        const end = Math.min(data.current_page * data.per_page, data.total);
        
        $('#page-info').html(`
            <i class="ti ti-photo"></i> Showing ${start} - ${end} of ${data.total} images
        `);
    }
    
    function showLoader() {
        $('#loader').css({
            'display': 'flex',
            'justify-content': 'center',
            'align-items': 'center'
        }).fadeIn(200);
    }
    
    function hideLoader() {
        $('#loader').fadeOut(200);
    }
    
    function showError(message) {
        const container = $('#gallery-container');
        container.html(`
            <div class="col-12">
                <div class="alert-custom alert-danger-custom">
                    <i class="ti ti-alert-circle"></i> 
                    <strong>Error:</strong> ${message}
                    <br>
                    <button class="btn btn-sm btn-outline-danger mt-3" onclick="location.reload()">
                        <i class="ti ti-refresh"></i> Retry
                    </button>
                </div>
            </div>
        `);
        $('#pagination').empty();
        $('#page-info').empty();
    }
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.page) {
            loadGallery(event.state.page);
        }
    });
    
    // Update URL when changing pages (optional)
    function updateUrl(page) {
        const url = new URL(window.location);
        url.searchParams.set('page', page);
        window.history.pushState({page: page}, '', url);
    }
});
</script>
@endsection