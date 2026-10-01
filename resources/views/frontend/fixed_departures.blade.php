@extends('layouts.web')
@section('title', $tour->meta_title.' | Phoenix Tours & Travels')

@section('css')
<!-- Add Razorpay checkout script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- Add Toastr for notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<style>
    :root {
        --primary: #20b1d7;
        --primary-dark: #1a8fa8;
        --secondary: #2c3e50;
        --success: #27ae60;
        --warning: #f39c12;
        --danger: #e74c3c;
        --light: #f8f9fa;
        --border: #e1e5eb;
        --text: #333;
        --text-light: #666;
        --shadow: 0 2px 20px rgba(0,0,0,0.08);
        --available-green: #4CAF50;
        --available-light: #e8f5e9;
    }

    .main-container {
        /*max-width: 1400px;*/
        /*margin: 0 auto;*/
        /*padding: 20px;*/
    }

    .breadcrumb-wrapper {
        background: #fff;
        padding: 20px 30px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
    }

    .breadcrumb-nav {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .breadcrumb-nav li {
        color: var(--text-light);
    }

    .breadcrumb-nav li a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .breadcrumb-nav li.active {
        color: var(--text);
        font-weight: 600;
    }

    .booking-layout {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 25px;
        align-items: start;
        margin-bottom: 40px;
    }

    .left-panel {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .calendar-card {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: var(--shadow);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f2f5;
    }

    .calendar-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .calendar-nav {
        display: flex;
        gap: 8px;
    }

    .calendar-nav button {
        width: 40px;
        height: 40px;
        border: 1px solid var(--border);
        background: #fff;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 18px;
        color: var(--text);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .calendar-nav button:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }

    .calendar-day-header {
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        color: #999;
        padding: 12px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 15px;
        font-weight: 600;
        color: var(--text);
        position: relative;
        border: 2px solid transparent;
        min-height: 60px;
    }

    .calendar-day:hover:not(.disabled):not(.empty):not(.sold-out) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }

    .calendar-day.selected {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
        box-shadow: 0 4px 15px rgba(32,177,215,0.4);
    }

    .calendar-day.today:not(.selected) {
        border-color: var(--primary);
        border-style: dashed;
    }

    .calendar-day.today.available:not(.selected) {
        background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
        border-color: var(--available-green);
        border-style: solid;
        color: #2e7d32;
    }

    .calendar-day.available:not(.selected):not(.sold-out) {
        background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
        border-color: var(--available-green);
        border-style: solid;
        color: #2e7d32;
    }

    .calendar-day.available:hover:not(.selected) {
        background: #c8e6c9;
        border-color: #388e3c;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
    }

    .calendar-day.disabled {
        color: #ccc;
        cursor: not-allowed;
        opacity: 0.4;
        background: #f5f5f5;
    }

    .calendar-day.sold-out {
        background: #fff5f5;
        color: #c53030;
        cursor: not-allowed;
        border-color: #feb2b2;
    }

    .calendar-day.empty {
        cursor: default;
        background: transparent;
        border: none;
    }

    .calendar-day .price-tag {
        font-size: 10px;
        font-weight: 600;
        margin-top: 4px;
        padding: 2px 6px;
        border-radius: 10px;
    }

    .calendar-day.available .price-tag {
        color: #2e7d32;
        background: rgba(76, 175, 80, 0.15);
    }

    .calendar-day.selected .price-tag {
        color: rgba(255,255,255,0.95);
        background: rgba(255,255,255,0.2);
    }

    .calendar-day.sold-out .price-tag {
        color: #c53030;
        background: rgba(197,48,48,0.1);
    }

    .available-slots-badge {
        font-size: 10px;
        font-weight: 700;
        margin-top: 2px;
    }

    .calendar-day.available .available-slots-badge {
        color: #2e7d32;
    }

    .calendar-legend {
        display: flex;
        gap: 25px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #f0f2f5;
        font-size: 13px;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 4px;
    }

    .selected-date-display {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        display: none;
        color: #fff;
    }

    .selected-date-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .selected-date-label {
        font-size: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #fff;
    }

    .selected-date-value {
        font-size: 18px;
        font-weight: 700;
    }

    .change-date-btn {
        background: rgba(255,255,255,0.2);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.3);
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
        backdrop-filter: blur(10px);
        margin-left: 10px;
    }

    .change-date-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-1px);
    }

    .seat-selector-card {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: var(--shadow);
        animation: slideIn 0.4s ease;
        border: 2px solid var(--available-green);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .seats-available-info {
        margin-bottom: 25px;
        padding: 18px;
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        border-radius: 12px;
        color: #155724;
        font-size: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .seats-grid-container {
        background: #f8fafb;
        border-radius: 16px;
        padding: 25px;
        border: 2px solid #e8ecf1;
    }

    .seats-grid-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 20px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .seats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
        gap: 12px;
        justify-items: center;
    }

    .seat-item {
        width: 70px;
        height: 70px;
        border: 2px solid var(--available-green);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--available-light);
        color: var(--available-green);
        font-weight: 700;
        font-size: 18px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        cursor: pointer;
    }

    .seat-item:hover:not(.booked) {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(76,175,80,0.3);
    }

    .seat-item.booked {
        border-color: #e0e0e0;
        background: #f5f5f5;
        color: #bdbdbd;
        cursor: not-allowed;
        position: relative;
    }

    .seat-item.booked::after {
        content: '✕';
        position: absolute;
        font-size: 14px;
        color: #999;
    }

    .seat-item.selected {
        border-color: #2196F3;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        box-shadow: 0 8px 25px rgba(33,150,243,0.4);
        animation: pulse 0.4s ease;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .seat-item .seat-icon {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--available-green);
        color: #fff;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        display: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .seat-item.selected .seat-icon {
        display: flex;
        background: #fff;
        color: #2196F3;
    }

    .selected-seats-summary {
        margin-top: 25px;
        padding: 20px;
        background: #f0f7ff;
        border: 2px solid #2196F3;
        border-radius: 12px;
        display: none;
    }

    .selected-seats-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 12px;
    }

    .seat-chip {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 10px rgba(102,126,234,0.3);
    }

    .remove-seat-btn {
        cursor: pointer;
        font-weight: bold;
        font-size: 18px;
        opacity: 0.8;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
    }

    .remove-seat-btn:hover {
        opacity: 1;
        background: rgba(255,255,255,0.4);
    }

    .seats-legend {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .seats-legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--text-light);
    }

    .seats-legend-box {
        width: 20px;
        height: 20px;
        border-radius: 4px;
        border: 2px solid;
    }

    .total-seats-count {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        font-weight: 600;
        color: var(--text);
        padding: 15px;
        background: #f8fafb;
        border-radius: 10px;
        margin-top: 15px;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(2px);
    }

    .loading-spinner {
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .right-panel {
        position: sticky;
        top: 30px;
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .tour-info-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .tour-image-container {
        position: relative;
    }

    .tour-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .tour-rating-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #fff;
        color: var(--text);
        padding: 8px 14px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .tour-info-content {
        padding: 25px;
    }

    .tour-name {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 20px;
        line-height: 1.4;
    }

    .tour-features {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: var(--text-light);
    }

    .feature-icon {
        width: 38px;
        height: 38px;
        background: #f0f8ff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 18px;
    }

    .divider {
        border: none;
        border-top: 1px solid #f0f2f5;
        margin: 15px 0;
    }

    .price-card {
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: var(--shadow);
        border: 2px solid transparent;
        transition: all 0.3s;
    }

    .price-card.has-selection {
        border-color: var(--available-green);
    }

    .price-card:hover {
        border-color: var(--primary);
        box-shadow: 0 10px 40px rgba(32,177,215,0.15);
    }

    .price-label {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .price-value {
        font-size: 40px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 20px;
    }

    .currency {
        font-size: 24px;
    }

    .price-breakdown {
        padding: 20px 0;
        border-top: 1px solid #f0f2f5;
        border-bottom: 1px solid #f0f2f5;
    }

    .breakdown-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
        color: var(--text-light);
    }

    .total-amount {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #f0f2f5;
        font-weight: 700;
        font-size: 18px;
        color: var(--text);
    }

    .book-now-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
        padding: 18px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 17px;
        width: 100%;
        margin-top: 25px;
        cursor: pointer;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        overflow: hidden;
    }

    .book-now-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: 0.5s;
    }

    .book-now-btn:hover:not(:disabled)::before {
        left: 100%;
    }

    .book-now-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(102,126,234,0.4);
    }

    .book-now-btn:disabled {
        background: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%);
        cursor: not-allowed;
        transform: none;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 14px;
        color: var(--text);
    }

    .form-group input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s;
        background: #f8fafb;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(32,177,215,0.1);
        background: #fff;
    }

    .form-group input.error {
        border-color: var(--danger);
        background: #fff5f5;
    }

    .error-message {
        color: var(--danger);
        font-size: 12px;
        margin-top: 5px;
        display: none;
    }

    .secure-payment-text {
        text-align: center;
        margin-top: 15px;
        font-size: 12px;
        color: #999;
    }

    @media (max-width: 1200px) {
        .booking-layout {
            grid-template-columns: 1fr;
        }
        .right-panel {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .calendar-grid {
            gap: 2px;
        }
        .calendar-day {
            font-size: 13px;
            min-height: 50px;
        }
        .seats-grid {
            grid-template-columns: repeat(auto-fill, minmax(55px, 1fr));
            gap: 8px;
        }
        .seat-item {
            width: 55px;
            height: 55px;
            font-size: 15px;
        }
        .calendar-legend {
            gap: 15px;
        }
        .main-container {
            padding: 10px;
        }
    }
</style>
@endsection

@section('content')

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner">
        <div class="spinner"></div>
        <p style="margin-top: 15px; font-weight: 600; color: var(--text);">Processing your booking...</p>
    </div>
</div>

<div class="main-container">
    <!-- Breadcrumb Bar -->
    <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">{{ $fixed_departure->package_name  }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="isax isax-home5"></i></a></li>
                            <li class="breadcrumb-item">Tours</li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $fixed_departure->package_name  }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Breadcrumb -->
    <div class="breadcrumb-wrapper">
        <ul class="breadcrumb-nav">
            <li><a href="{{ route('home') }}"><i class="isax isax-home5"></i> Home</a></li>
            <li><i class="isax isax-arrow-right"></i></li>
            <li>Tours</li>
            <li><i class="isax isax-arrow-right"></i></li>
            <li class="active">{{ $fixed_departure->package_name  }}</li>
        </ul>
    </div>

    <!-- Booking Layout -->
    <div class="booking-layout">
        
        <!-- Left Panel -->
        <div class="left-panel">
            <!-- Calendar Section -->
            <div class="calendar-card">
                <div class="calendar-header">
                    <h3 class="calendar-title">
                        <i class="isax isax-calendar-2"></i>
                        <span id="month-year">Loading...</span>
                    </h3>
                    <div class="calendar-nav">
                        <button onclick="changeMonth(-1)" title="Previous Month">
                            <i class="isax isax-arrow-left"></i>
                        </button>
                        <button onclick="changeMonth(1)" title="Next Month">
                            <i class="isax isax-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <div class="calendar-grid" id="calendar-grid">
                    <!-- Calendar rendered by JavaScript -->
                </div>
                
                <div class="calendar-legend">
                    <div class="legend-item">
                        <div class="legend-dot" style="background: #e8f5e9; border: 2px solid var(--available-green);"></div>
                        <span>Available Dates</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background: var(--primary);"></div>
                        <span>Selected Date</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background: #fff5f5; border: 2px solid #feb2b2;"></div>
                        <span>Sold Out</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background: #f5f5f5; border: 2px solid #ddd;"></div>
                        <span>Past Date</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="border: 2px dashed var(--primary); background: #fff;"></div>
                        <span>Today</span>
                    </div>
                </div>

                <!-- Selected Date Display -->
                <div class="selected-date-display" id="selected-date-section">
                    <div class="selected-date-info">
                        <div class="selected-date-label">
                            <i class="isax isax-calendar-tick"></i>
                            Selected Date:
                        </div>
                        <div>
                            <span class="selected-date-value" id="selected-date-text"></span>
                            <button class="change-date-btn" onclick="changeDate()">
                                <i class="isax isax-edit"></i> Change Date
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seat Selector Section -->
            <div class="seat-selector-card" id="seat-selector-container" style="display: none;">
                <h3 class="calendar-title" style="margin-bottom: 10px;">
                    <i class="isax isax-people"></i>
                    Select Your Slots
                </h3>
                
                <div class="seats-available-info" id="seats-available-info">
                    <i class="isax isax-info-circle"></i>
                    <span id="available-seats-count">0</span> Slots available for 
                    <span id="selected-date-label"></span>
                </div>

                <div class="seats-grid-container">
                    <div class="seats-grid-title">
                        <i class="isax isax-mouse-circle"></i> Click on slots to select/deselect
                    </div>
                    <div class="seats-grid" id="seats-grid">
                        <!-- Seats rendered here -->
                    </div>
                    
                    <div class="total-seats-count">
                        <i class="isax isax-ticket"></i>
                        Total Selected: 
                        <span id="total-selected-display" style="color: var(--primary); font-size: 20px;">0</span> 
                        Slots
                    </div>
                    
                    <div class="seats-legend">
                        <div class="seats-legend-item">
                            <div class="seats-legend-box" style="background: var(--available-light); border-color: var(--available-green);"></div>
                            <span>Available</span>
                        </div>
                        <div class="seats-legend-item">
                            <div class="seats-legend-box" style="background: linear-gradient(135deg, #667eea, #764ba2); border-color: #2196F3;"></div>
                            <span>Selected</span>
                        </div>
                        <div class="seats-legend-item">
                            <div class="seats-legend-box" style="background: #f5f5f5; border-color: #e0e0e0;"></div>
                            <span>Booked</span>
                        </div>
                    </div>
                </div>

                <div class="selected-seats-summary" id="selected-seats-summary">
                    <strong style="color: var(--text);">
                        <i class="isax isax-tick-circle"></i> Selected Slots:
                    </strong>
                    <div class="selected-seats-chips" id="selected-seats-chips"></div>
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            
            <!-- Tour Info Card -->
            <div class="tour-info-card">
                <div class="tour-image-container">
                    @php
                        $imagePath = $fixed_departure->package_image ?? null;
                        if (!$imagePath) {
                            $imagePath = $tour->image ?? 'https://placehold.co/400x250/4f46e5/ffffff?text=Tour+Image';
                        }
                    @endphp
                    <img src="{{ asset($imagePath) }}" 
                         alt="{{ $fixed_departure->package_name ?? $tour->tour_name }}" 
                         class="tour-image"
                         onerror="this.onerror=null; this.src='https://placehold.co/400x250/4f46e5/ffffff?text=Tour+Image';">
                    <div class="tour-rating-badge">
                        <i class="isax isax-star" style="color: #f39c12;"></i>
                        4.8/5
                    </div>
                </div>
                
                <div class="tour-info-content">
                    <h3 class="tour-name">{{ $fixed_departure->package_name  }}</h3>
                    
                    <div class="tour-features">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="isax isax-clock"></i></div>
                            <div>
                                <strong>{{ $fixed_departure->nights_count ?? 2 }} N - {{ $fixed_departure->days_count ?? 3 }} D</strong>
                                <small style="display: block; color: #999;">Duration</small>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="isax isax-people"></i></div>
                            <div>
                                <strong id="group-size-display">{{ $fixed_departure->group_size ?? 20 }}</strong>
                                <small style="display: block; color: #999;">Group Size</small>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="isax isax-location"></i></div>
                            <div>
                                <strong>{{ $fixed_departure->meeting_point ?? 'Central' }}</strong>
                                <small style="display: block; color: #999;">Meeting Point</small>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="isax isax-language-circle"></i></div>
                            <div>
                                <strong>English/Hindi/Telugu</strong>
                                <small style="display: block; color: #999;">Language</small>
                            </div>
                        </div>
                    </div>

                    <hr class="divider">

                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <span style="background: #f0f8ff; padding: 6px 12px; border-radius: 20px; font-size: 12px; color: var(--primary);">
                            <i class="isax isax-wifi"></i> WiFi
                        </span>
                        <span style="background: #f0f8ff; padding: 6px 12px; border-radius: 20px; font-size: 12px; color: var(--primary);">
                            <i class="isax isax-coffee"></i> Meals
                        </span>
                        <span style="background: #f0f8ff; padding: 6px 12px; border-radius: 20px; font-size: 12px; color: var(--primary);">
                            <i class="isax isax-camera"></i> Guide
                        </span>
                        <span style="background: #f0f8ff; padding: 6px 12px; border-radius: 20px; font-size: 12px; color: var(--primary);">
                            <i class="isax isax-shield"></i> Insurance
                        </span>
                    </div>
                </div>
            </div>

            <!-- Price Card -->
            <div class="price-card" id="price-card">
                <div class="price-label">Price per Slots</div>
                <div class="price-value">
                    <span class="currency">₹</span><span id="price-display">{{ number_format($fixed_departure->price ?? 0, 0) }}</span>
                </div>
                
                <div class="price-breakdown">
                    <div class="breakdown-item">
                        <span>Base Price (per slot)</span>
                        <span>₹<span id="base-price">0</span></span>
                    </div>
                    <div class="breakdown-item">
                        <span>GST (5%)</span>
                        <span>₹<span id="gst-amount">0</span></span>
                    </div>
                    <div class="breakdown-item">
                        <span>Service Fee</span>
                        <span>₹<span id="service-fee">0</span></span>
                    </div>
                    <div class="breakdown-item">
                        <span>Total Slots</span>
                        <span><span id="total-seats-count">0</span></span>
                    </div>
                    <div class="total-amount">
                        <span>Total Amount</span>
                        <span style="color: var(--primary);">₹<span id="total-amount">0</span></span>
                    </div>
                </div>

                <!-- Booking Form -->
                <form id="booking-form" style="display: none;">
                    @csrf
                    <input type="hidden" name="fixed_departure_id" id="fixed-departure-id" value="{{ $fixed_departure->id }}">
                    <input type="hidden" name="travel_date" id="form-travel-date">
                    <input type="hidden" name="seat_numbers" id="form-seat-numbers">
                    <input type="hidden" name="total_seats" id="form-total-seats">
                    <input type="hidden" name="total_amount" id="form-total-amount">
                    <input type="hidden" name="price_per_seat" id="form-price-per-seat">
                    
                    <div class="form-group">
                        <label><i class="isax isax-user"></i> Full Name *</label>
                        <input type="text" name="name" id="customer-name" required placeholder="Enter your full name">
                        <div class="error-message" id="name-error">Please enter your full name</div>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="isax isax-sms"></i> Email Address *</label>
                        <input type="email" name="email" id="customer-email" required placeholder="Enter your email">
                        <div class="error-message" id="email-error">Please enter a valid email</div>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="isax isax-call"></i> Phone Number *</label>
                        <input type="tel" name="phone" id="customer-phone" required placeholder="Enter your phone number">
                        <div class="error-message" id="phone-error">Please enter a valid phone number</div>
                    </div>
                </form>

                <button class="book-now-btn" id="book-now-btn" disabled onclick="proceedToPayment()">
                    <i class="isax isax-ticket"></i> Select Dates & Slots to Book
                </button>
                
                <p class="secure-payment-text">
                    <i class="isax isax-shield-tick" style="color: #4CAF50;"></i>
                    Secure payment powered by Razorpay
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
// Initialize data from PHP
window.tourData = {
    price: {{ $fixed_departure->price ?? 0 }},
    groupSize: {{ $fixed_departure->group_size ?? 20 }},
    departureId: {{ $fixed_departure->id ?? 0 }},
    csrfToken: '{{ csrf_token() }}'
};

window.availableDates = @json($availableDates ?? []);

// Global state variables
window.currentDate = new Date();
window.selectedDate = null;
window.selectedSeats = [];
window.maxAvailableSeats = 0;
window.allSeatsData = [];
window.currentGroupSize = window.tourData.groupSize;

// Main initialization
document.addEventListener('DOMContentLoaded', function() {
    // Set the initial date to today at midnight
    window.currentDate = new Date();
    window.currentDate.setHours(0, 0, 0, 0);
    
    // Initialize calendar
    renderCalendar();
    
    // Set initial group size display
    updateGroupSizeDisplay(window.tourData.groupSize);
    
    // Toastr configuration
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000
    };
});

// Show/Hide loading overlay
function showLoading() {
    document.getElementById('loading-overlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loading-overlay').style.display = 'none';
}

/**
 * Get today's date as YYYY-MM-DD string without timezone issues
 */
function getTodayString() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

/**
 * Parse a YYYY-MM-DD string into a Date object using local time
 */
function parseLocalDate(dateStr) {
    const [year, month, day] = dateStr.split('-').map(Number);
    return new Date(year, month - 1, day);
}

/**
 * Normalize a Date object to midnight for comparison
 */
function normalizeDate(date) {
    const normalized = new Date(date);
    normalized.setHours(0, 0, 0, 0);
    return normalized;
}

/**
 * Update Group Size Display
 */
function updateGroupSizeDisplay(totalSeats) {
    const groupSizeDisplay = document.getElementById('group-size-display');
    if (groupSizeDisplay) {
        if (window.selectedDate && window.allSeatsData && window.allSeatsData.length > 0) {
            // When date is selected, show total seats for that date
            groupSizeDisplay.textContent = totalSeats || window.allSeatsData.length;
        } else {
            // When no date selected, show default group size
            groupSizeDisplay.textContent = window.tourData.groupSize || totalSeats;
        }
    }
}

// Render Calendar with green highlights for available dates
function renderCalendar() {
    const year = window.currentDate.getFullYear();
    const month = window.currentDate.getMonth();
    
    document.getElementById('month-year').textContent = new Date(year, month).toLocaleString('default', { 
        month: 'long', 
        year: 'numeric' 
    });
    
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    
    // Get today's date normalized
    const todayStr = getTodayString();
    const today = parseLocalDate(todayStr);
    
    let html = '';
    const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    dayHeaders.forEach(day => {
        html += `<div class="calendar-day-header">${day}</div>`;
    });
    
    // Empty cells before first day of month
    for (let i = 0; i < firstDay; i++) {
        html += '<div class="calendar-day empty"></div>';
    }
    
    // Calendar days
    for (let day = 1; day <= daysInMonth; day++) {
        // Create date object for this calendar day
        const date = new Date(year, month, day);
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        // Check if this is today by comparing date strings
        const isToday = dateStr === todayStr;
        const isPast = date < today;
        const isSelected = window.selectedDate === dateStr;
        const dateInfo = window.availableDates[dateStr];
        const hasAvailability = dateInfo && dateInfo.hasAvailability === true;
        const isSoldOut = dateInfo && dateInfo.seatsAvailable === 0 && !isPast;
        
        let classes = ['calendar-day'];
        
        if (isToday && !isPast) classes.push('today');
        if (isPast) classes.push('disabled');
        if (isSelected) classes.push('selected');
        if (hasAvailability && !isSelected && !isPast) classes.push('available');
        if (isSoldOut && !isSelected) classes.push('sold-out');
        
        let clickHandler = '';
        if (!isPast && dateInfo && !isSoldOut) {
            clickHandler = `onclick="selectDate('${dateStr}')"`;
        }
        
        // Build content for each calendar day
        let priceHTML = '';
        let slotsHTML = '';
        
        if (!isPast && dateInfo) {
            if (isSelected) {
                priceHTML = '<span class="price-tag">Selected ✓</span>';
            } else if (isSoldOut) {
                priceHTML = '<span class="price-tag">Sold Out</span>';
            } else if (hasAvailability) {
                priceHTML = `<span class="price-tag">₹${dateInfo.price.toLocaleString()}</span>`;
                slotsHTML = `<span class="available-slots-badge">${dateInfo.seatsAvailable} slots</span>`;
            }
        }
        
        html += `<div class="${classes.join(' ')}" data-date="${dateStr}" ${clickHandler}>
            <span>${day}</span>
            ${priceHTML}
            ${slotsHTML}
        </div>`;
    }
    
    document.getElementById('calendar-grid').innerHTML = html;
    updateSelectedDateDisplay();
}

// Update selected date display at the top of calendar
function updateSelectedDateDisplay() {
    const section = document.getElementById('selected-date-section');
    const text = document.getElementById('selected-date-text');
    
    if (section && text) {
        if (window.selectedDate) {
            section.style.display = 'block';
            text.textContent = formatDate(window.selectedDate);
        } else {
            section.style.display = 'none';
        }
    }
}

// Select Date and show slots
async function selectDate(dateStr) {
    console.log('Selected date:', dateStr);
    
    const dateInfo = window.availableDates[dateStr];
    if (!dateInfo) {
        toastr.warning('No information available for this date');
        return;
    }
    
    if (dateInfo.seatsAvailable === 0 && window.selectedDate !== dateStr) {
        toastr.warning('This date is completely booked. Please select another date.');
        return;
    }
    
    // If clicking the same date, deselect it
    if (window.selectedDate === dateStr) {
        window.selectedDate = null;
        window.selectedSeats = [];
        window.maxAvailableSeats = 0;
        window.allSeatsData = [];
        hideSeatSelector();
        updatePriceSummary();
        updateGroupSizeDisplay(window.tourData.groupSize); // Reset to default group size
        renderCalendar();
        return;
    }
    
    showLoading();
    
    try {
        // Fetch fresh data from server
        const response = await fetch('{{ route("booking.date-info") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.tourData.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                date: dateStr,
                departure_id: window.tourData.departureId
            })
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const result = await response.json();
        
        if (result.success && result.data) {
            window.selectedDate = dateStr;
            window.selectedSeats = [];
            window.allSeatsData = result.data.seats || [];
            window.maxAvailableSeats = result.data.seatsAvailable || 0;
            window.currentGroupSize = result.data.totalSeats || window.allSeatsData.length;
            
            // Update price
            window.tourData.price = result.data.price || dateInfo.price;
            document.getElementById('price-display').textContent = 
                window.tourData.price.toLocaleString();
            
            // Update group size display with the actual total seats for this date
            updateGroupSizeDisplay(window.currentGroupSize);
            
            showSeatSelector();
            updateAvailableSeatsInfo();
            renderAllSeats();
            updateSelectedSeatsSummary();
            updatePriceSummary();
            renderCalendar();
            
            // Scroll to seat selector
            setTimeout(() => {
                const seatContainer = document.getElementById('seat-selector-container');
                if (seatContainer) {
                    seatContainer.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
                
                toastr.success(
                    `${window.maxAvailableSeats} slots available for ${formatDate(dateStr)}`
                );
            }, 300);
            
        } else {
            toastr.error(result.message || 'Failed to load date information');
        }
    } catch (error) {
        console.error('Error fetching date info:', error);
        
        // Fallback to cached data
        if (window.availableDates[dateStr] && window.availableDates[dateStr].hasAvailability) {
            const cachedData = window.availableDates[dateStr];
            window.selectedDate = dateStr;
            window.selectedSeats = [];
            
            // Use seats data from cache if available
            if (cachedData.seats && cachedData.seats.length > 0) {
                window.allSeatsData = cachedData.seats;
            } else {
                // Generate basic seats data from cache
                const seats = [];
                for (let i = 1; i <= cachedData.totalSeats; i++) {
                    seats.push({
                        seatNumber: i,
                        status: cachedData.bookedSeats && cachedData.bookedSeats.includes(i) ? 'booked' : 'available'
                    });
                }
                window.allSeatsData = seats;
            }
            
            window.maxAvailableSeats = cachedData.seatsAvailable;
            window.currentGroupSize = cachedData.totalSeats || window.allSeatsData.length;
            
            // Update group size display
            updateGroupSizeDisplay(window.currentGroupSize);
            
            showSeatSelector();
            updateAvailableSeatsInfo();
            renderAllSeats();
            updateSelectedSeatsSummary();
            updatePriceSummary();
            renderCalendar();
        } else {
            toastr.error('Failed to load date information. Please try again.');
        }
    } finally {
        hideLoading();
    }
}

// Show seat selector
function showSeatSelector() {
    const container = document.getElementById('seat-selector-container');
    if (container) {
        container.style.display = 'block';
        container.style.animation = 'none';
        container.offsetHeight; // Trigger reflow
        container.style.animation = 'slideIn 0.4s ease';
    }
}

// Hide seat selector
function hideSeatSelector() {
    document.getElementById('seat-selector-container').style.display = 'none';
    document.getElementById('booking-form').style.display = 'none';
    document.getElementById('price-card').classList.remove('has-selection');
}

// Update available seats info
function updateAvailableSeatsInfo() {
    document.getElementById('available-seats-count').textContent = window.maxAvailableSeats;
    document.getElementById('selected-date-label').textContent = formatDate(window.selectedDate);
}

// Toggle seat selection
function toggleSeat(seatNumber) {
    const seatData = window.allSeatsData.find(s => s.seatNumber === seatNumber);
    if (!seatData || seatData.status === 'booked') {
        toastr.warning('This slot is already booked');
        return;
    }
    
    const index = window.selectedSeats.indexOf(seatNumber);
    
    if (index > -1) {
        window.selectedSeats.splice(index, 1);
    } else {
        window.selectedSeats.push(seatNumber);
    }
    
    window.selectedSeats.sort((a, b) => a - b);
    
    renderAllSeats();
    updateSelectedSeatsSummary();
    updatePriceSummary();
}

// Render all seats
function renderAllSeats() {
    const seatsGrid = document.getElementById('seats-grid');
    if (!seatsGrid) return;
    
    if (!window.allSeatsData || window.allSeatsData.length === 0) {
        seatsGrid.innerHTML = `
            <p style="text-align: center; grid-column: 1/-1; padding: 40px; color: #999;">
                <i class="isax isax-info-circle" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                No slots available for this date
            </p>`;
        return;
    }
    
    let html = '';
    
    window.allSeatsData.forEach(seat => {
        const isBooked = seat.status === 'booked';
        const isSelected = window.selectedSeats.includes(seat.seatNumber);
        
        let seatClass = 'seat-item';
        let onclick = '';
        let content = '';
        
        if (isBooked) {
            seatClass += ' booked';
            content = `<span class="seat-number" style="text-decoration: line-through;">${seat.seatNumber}</span>`;
        } else if (isSelected) {
            seatClass += ' selected';
            onclick = `onclick="toggleSeat(${seat.seatNumber})"`;
            content = `<span class="seat-number">${seat.seatNumber}</span>
                       <span class="seat-icon">✓</span>`;
        } else {
            onclick = `onclick="toggleSeat(${seat.seatNumber})"`;
            content = `<span class="seat-number">${seat.seatNumber}</span>`;
        }
        
        html += `<div class="${seatClass}" ${onclick}>${content}</div>`;
    });
    
    seatsGrid.innerHTML = html;
    document.getElementById('total-selected-display').textContent = window.selectedSeats.length;
}

// Update selected seats summary
function updateSelectedSeatsSummary() {
    const summaryDiv = document.getElementById('selected-seats-summary');
    const chipsDiv = document.getElementById('selected-seats-chips');
    
    if (!summaryDiv || !chipsDiv) return;
    
    if (window.selectedSeats.length === 0) {
        summaryDiv.style.display = 'none';
    } else {
        summaryDiv.style.display = 'block';
        chipsDiv.innerHTML = window.selectedSeats.map(seatNum => `
            <span class="seat-chip">
                <i class="isax isax-tick-circle"></i>
                Slot ${seatNum}
                <span class="remove-seat-btn" 
                      onclick="event.stopPropagation(); toggleSeat(${seatNum})" 
                      title="Remove slot">
                    ×
                </span>
            </span>
        `).join('');
    }
}

// Change date
function changeDate() {
    window.selectedDate = null;
    window.selectedSeats = [];
    window.maxAvailableSeats = 0;
    window.allSeatsData = [];
    window.currentGroupSize = window.tourData.groupSize;
    hideSeatSelector();
    updatePriceSummary();
    updateSelectedDateDisplay();
    updateGroupSizeDisplay(window.tourData.groupSize); // Reset to default group size
    renderCalendar();
    document.getElementById('price-card').classList.remove('has-selection');
    
    // Scroll back to calendar
    document.querySelector('.calendar-card').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
}

// Update price summary
function updatePriceSummary() {
    const seatPrice = window.tourData.price;
    const totalSeats = window.selectedSeats.length;
    const basePrice = seatPrice * totalSeats;
    const gst = Math.round(basePrice * 0.05);
    const serviceFee = totalSeats > 0 ? 50 : 0;
    const total = basePrice + gst + serviceFee;
    
    document.getElementById('total-seats-count').textContent = totalSeats;
    document.getElementById('base-price').textContent = basePrice.toLocaleString();
    document.getElementById('gst-amount').textContent = gst.toLocaleString();
    document.getElementById('service-fee').textContent = serviceFee.toLocaleString();
    document.getElementById('total-amount').textContent = total.toLocaleString();
    
    const bookingForm = document.getElementById('booking-form');
    const bookNowBtn = document.getElementById('book-now-btn');
    const priceCard = document.getElementById('price-card');
    
    if (totalSeats > 0 && window.selectedDate) {
        bookingForm.style.display = 'block';
        priceCard.classList.add('has-selection');
        
        document.getElementById('form-travel-date').value = window.selectedDate;
        document.getElementById('form-seat-numbers').value = window.selectedSeats.join(',');
        document.getElementById('form-total-seats').value = totalSeats;
        document.getElementById('form-total-amount').value = total;
        document.getElementById('form-price-per-seat').value = seatPrice;
        
        bookNowBtn.disabled = false;
        bookNowBtn.innerHTML = `<i class="isax isax-ticket"></i> Pay ₹${total.toLocaleString()} - Book Now`;
    } else {
        bookingForm.style.display = 'none';
        bookNowBtn.disabled = true;
        priceCard.classList.remove('has-selection');
        bookNowBtn.innerHTML = '<i class="isax isax-ticket"></i> Select Dates & Slots to Book';
    }
}

// Proceed to payment
async function proceedToPayment() {
    const name = document.getElementById('customer-name').value.trim();
    const email = document.getElementById('customer-email').value.trim();
    const phone = document.getElementById('customer-phone').value.trim();
    
    // Clear previous errors
    document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.form-group input').forEach(el => el.classList.remove('error'));
    
    let hasError = false;
    
    if (!name) {
        document.getElementById('name-error').style.display = 'block';
        document.getElementById('customer-name').classList.add('error');
        hasError = true;
    }
    
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        document.getElementById('email-error').style.display = 'block';
        document.getElementById('customer-email').classList.add('error');
        hasError = true;
    }
    
    if (!phone || phone.length < 10) {
        document.getElementById('phone-error').style.display = 'block';
        document.getElementById('customer-phone').classList.add('error');
        hasError = true;
    }
    
    if (hasError) {
        toastr.error('Please fill all required fields correctly');
        const firstError = document.querySelector('.form-group input.error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
        }
        return;
    }
    
    if (window.selectedSeats.length === 0) {
        toastr.error('Please select at least one slot');
        return;
    }
    
    showLoading();
    
    try {
        const formData = {
            fixed_departure_id: window.tourData.departureId,
            travel_date: window.selectedDate,
            seat_numbers: window.selectedSeats.join(','),
            total_seats: window.selectedSeats.length,
            total_amount: parseInt(
                document.getElementById('total-amount').textContent.replace(/,/g, '')
            ),
            price_per_seat: window.tourData.price,
            name: name,
            email: email,
            phone: phone,
            _token: window.tourData.csrfToken
        };
        
        const response = await fetch('{{ route("booking.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.tourData.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            const options = {
                key: result.key,
                amount: result.amount,
                currency: 'INR',
                name: result.name || 'Phoenix Tours & Travels',
                description: `Booking ${result.booking_number} - ${window.selectedSeats.length} slots`,
                order_id: result.razorpay_order_id,
                handler: function(response) {
                    verifyPayment(response, result.booking_id);
                },
                prefill: {
                    name: name,
                    email: email,
                    contact: phone
                },
                theme: {
                    color: '#20b1d7'
                },
                modal: {
                    ondismiss: function() {
                        hideLoading();
                        toastr.warning('Payment cancelled. Your booking is pending.');
                    }
                }
            };
            
            const razorpay = new Razorpay(options);
            
            razorpay.on('payment.failed', function(response) {
                hideLoading();
                toastr.error('Payment failed. Please try again.');
                console.error('Payment failed:', response.error);
            });
            
            razorpay.open();
        } else {
            toastr.error(result.message || 'Failed to create booking');
        }
    } catch (error) {
        console.error('Error:', error);
        toastr.error('Something went wrong. Please try again.');
    } finally {
        hideLoading();
    }
}

// Verify payment
async function verifyPayment(paymentResponse, bookingId) {
    showLoading();
    
    try {
        const response = await fetch('{{ route("booking.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.tourData.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                razorpay_payment_id: paymentResponse.razorpay_payment_id,
                razorpay_order_id: paymentResponse.razorpay_order_id,
                razorpay_signature: paymentResponse.razorpay_signature,
                booking_id: bookingId
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            toastr.success(result.message || 'Payment successful!');
            setTimeout(() => {
                window.location.href = result.redirect_url;
            }, 1500);
        } else {
            toastr.error(result.message || 'Payment verification failed');
        }
    } catch (error) {
        console.error('Verification error:', error);
        toastr.error('Payment verification failed. Please contact support.');
    } finally {
        hideLoading();
    }
}

// Navigate calendar
function changeMonth(delta) {
    const newDate = new Date(window.currentDate);
    newDate.setMonth(newDate.getMonth() + delta);
    
    // Get the first day of the current month for comparison
    const today = new Date();
    today.setDate(1);
    today.setHours(0, 0, 0, 0);
    
    // Don't allow going to past months
    if (newDate < today && delta < 0) {
        toastr.info('Cannot view past months');
        return;
    }
    
    // Limit to 6 months in the future
    const maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 6);
    maxDate.setDate(1);
    
    if (newDate > maxDate && delta > 0) {
        toastr.info('Cannot view beyond 6 months');
        return;
    }
    
    window.currentDate = newDate;
    renderCalendar();
}

/**
 * Format date - Parse manually to avoid timezone offset issues
 * Returns date in format: "Monday, January 1, 2024"
 */
function formatDate(dateStr) {
    // Parse the date string manually to avoid timezone issues
    const parts = dateStr.split('-');
    const year = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1; // Month is 0-indexed in JavaScript
    const day = parseInt(parts[2], 10);
    
    // Create date using local time (not UTC)
    const date = new Date(year, month, day);
    
    const monthNames = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    
    const dayNames = [
        'Sunday', 'Monday', 'Tuesday', 'Wednesday', 
        'Thursday', 'Friday', 'Saturday'
    ];
    
    const dayName = dayNames[date.getDay()];
    const monthName = monthNames[month];
    
    return `${dayName}, ${monthName} ${day}, ${year}`;
}
</script>
@endsection