@extends('layouts.web')
@section('title', 'Phoenix Tours & Travels')
@section('css')
<style>
    /* Loader styles - keeping it minimal */
    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .loader-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Pagination styles - matching your design */
    .pagination-container {
        margin-top: 40px;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .pagination-btn {
        padding: 8px 16px;
        border: 1px solid #ddd;
        background: #fff;
        color: #333;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
    }
    
    .pagination-btn:hover:not(:disabled) {
        background: #3498db;
        color: #fff;
        border-color: #3498db;
    }
    
    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .pagination-btn.active {
        background: #3498db;
        color: #fff;
        border-color: #3498db;
    }
    
    .pagination-ellipsis {
        padding: 8px 5px;
        color: #666;
    }
    
    .page-info {
        margin-top: 15px;
        text-align: center;
        color: #666;
        font-size: 14px;
    }
    
    /* Initial loading state */
    .initial-loader {
        text-align: center;
        padding: 60px 20px;
    }
</style>
@endsection

@section('content')
<!-- Loader Overlay -->
<div class="loader-overlay" id="loader">
    <div class="loader-spinner"></div>
</div>

<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Blog</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="isax isax-home5"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Blog</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container">
        <!-- Blog Posts Container - Exact same structure -->
        <div class="row justify-content-center" id="blog-container">
            <!-- Initial loading state -->
            <div class="col-12">
                <div class="initial-loader">
                    <div class="loader-spinner" style="margin: 0 auto 15px;"></div>
                    <p>Loading blog posts...</p>
                </div>
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
    let perPage = 9;
    
    // Load blog posts on page load
    loadBlogPosts(currentPage);
    
    function loadBlogPosts(page) {
        showLoader();
        
        $.ajax({
            url: "{{ route('blog.data') }}",
            method: 'GET',
            data: {
                page: page,
                per_page: perPage
            },
            success: function(response) {
                if (response.success) {
                    renderBlogPosts(response.data);
                    renderPagination(response);
                    updatePageInfo(response);
                    
                    // Re-initialize WOW if used
                    if (typeof WOW !== 'undefined') {
                        new WOW().init();
                    }
                } else {
                    showError('Failed to load blog posts');
                }
            },
            error: function() {
                showError('Error loading blog posts');
            },
            complete: function() {
                hideLoader();
            }
        });
    }
    
    function renderBlogPosts(posts) {
        const container = $('#blog-container');
        container.empty();
        
        if (!posts || posts.length === 0) {
            container.html(`
                <div class="col-12 text-center">
                    <p>No blog posts found.</p>
                </div>
            `);
            return;
        }
        
        // Render exact same HTML structure as original
        posts.forEach(function(post, index) {
            const delay = (index % 3) * 0.1 + 0.2;
            
            const blogCard = `
                <div class="col-xl-4 col-md-6">
                    <div class="blog-item mb-4 wow fadeInUp" data-wow-delay="${delay}s">
                        <a href="${post.blog_details_url}" class="blog-img" style="height: 245px;">
                            <img src="${post.image}" alt="img">
                        </a>
                        <div class="blog-info text-center">
                            <div class="d-inline-flex align-items-center justify-content-center">
                                <p class="text-white mb-2"><i class="isax isax-calendar-2 me-2"></i>${post.created_at}</p>
                            </div>
                            <h5><a href="${post.blog_details_url}">${post.title}</a></h5>
                        </div>
                    </div>
                </div>
            `;
            container.append(blogCard);
        });
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
                Previous
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
                Next
            </button>
        `);
        
        // Bind click events
        $('.pagination-btn').on('click', function() {
            const page = $(this).data('page');
            if (page && !$(this).prop('disabled')) {
                currentPage = page;
                loadBlogPosts(page);
                
                // Scroll to top of blog section
                $('html, body').animate({
                    scrollTop: $('.content').offset().top - 80
                }, 300);
            }
        });
    }
    
    function updatePageInfo(data) {
        if (data.total === 0) {
            $('#page-info').html('');
            return;
        }
        
        const start = (data.current_page - 1) * data.per_page + 1;
        const end = Math.min(data.current_page * data.per_page, data.total);
        
        $('#page-info').html(`Showing ${start} - ${end} of ${data.total} posts`);
    }
    
    function showLoader() {
        $('#loader').css('display', 'flex').fadeIn(200);
    }
    
    function hideLoader() {
        $('#loader').fadeOut(200);
    }
    
    function showError(message) {
        const container = $('#blog-container');
        container.html(`
            <div class="col-12 text-center">
                <p class="text-danger">${message}</p>
                <button class="btn btn-primary mt-2" onclick="location.reload()">Retry</button>
            </div>
        `);
        $('#pagination').empty();
        $('#page-info').empty();
    }
});
</script>
@endsection