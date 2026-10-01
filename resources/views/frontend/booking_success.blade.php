@extends('layouts.web')
@section('title', 'Booking Confirmed | Phoenix Tours & Travels')
@section('css')
<style>
    :root {
        --primary: #20b1d7;
        --primary-dark: #1a8fa8;
        --success: #27ae60;
        --warning: #f39c12;
        --danger: #e74c3c;
        --text: #333;
        --text-light: #666;
        --border: #e1e5eb;
        --shadow: 0 2px 20px rgba(0,0,0,0.08);
        --shadow-lg: 0 10px 40px rgba(0,0,0,0.12);
    }

    /* Success Page Container */
    .success-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 100px 20px;
    }

    /* Success Header */
    .success-header {
        text-align: center;
        margin-bottom: 40px;
        animation: slideDown 0.6s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-icon-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 25px;
    }

    .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 10px 30px rgba(39, 174, 96, 0.3);
        animation: scaleIn 0.5s ease;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    .success-icon i {
        font-size: 50px;
        color: #fff;
    }

    .confetti-particle {
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        animation: confetti 1.5s ease infinite;
    }

    @keyframes confetti {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(-100px) rotate(720deg);
            opacity: 0;
        }
    }

    .success-title {
        font-size: 36px;
        font-weight: 800;
        color: #27ae60;
        margin-bottom: 10px;
    }

    .success-subtitle {
        font-size: 16px;
        color: var(--text-light);
        margin-bottom: 5px;
    }

    .booking-number {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 10px 25px;
        border-radius: 30px;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-top: 15px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    /* Content Grid */
    .success-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
        margin-top: 40px;
    }

    /* Booking Details Card */
    .details-card {
        background: #fff;
        border-radius: 20px;
        padding: 35px;
        box-shadow: var(--shadow-lg);
        animation: fadeInUp 0.6s ease 0.2s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f2f5;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: var(--primary);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .detail-item {
        padding: 20px;
        background: #f8fafb;
        border-radius: 12px;
        border: 1px solid #e8ecf1;
        transition: all 0.3s;
    }

    .detail-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-color: var(--primary);
    }

    .detail-label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
    }

    .detail-value.highlight {
        color: var(--primary);
    }

    .detail-value.success {
        color: #27ae60;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }

    .status-badge.confirmed {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(39, 174, 96, 0.4);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(39, 174, 96, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(39, 174, 96, 0);
        }
    }

    /* Seats Display */
    .seats-display {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 5px;
    }

    .seat-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    /* Tour Info Sidebar */
    .tour-sidebar {
        animation: fadeInUp 0.6s ease 0.4s both;
    }

    .tour-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        margin-bottom: 20px;
    }

    .tour-card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .tour-card-content {
        padding: 25px;
    }

    .tour-card-name {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--text);
    }

    .tour-feature {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f2f5;
        font-size: 14px;
        color: var(--text-light);
    }

    .tour-feature:last-child {
        border-bottom: none;
    }

    .tour-feature i {
        color: var(--primary);
        width: 20px;
    }

    /* Action Buttons */
    .action-card {
        background: #fff;
        border-radius: 20px;
        padding: 25px;
        box-shadow: var(--shadow-lg);
        text-align: center;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px 30px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s;
        cursor: pointer;
        border: none;
        text-decoration: none;
        width: 100%;
        margin-bottom: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-outline {
        background: #fff;
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    .btn-outline:hover {
        background: var(--primary);
        color: #fff;
    }

    .btn-success {
        background: #25D366;
        color: #fff;
    }

    .btn-success:hover {
        background: #128C7E;
        transform: translateY(-2px);
    }

    /* Invoice/Receipt Section */
    .receipt-section {
        margin-top: 30px;
        padding: 25px;
        background: #f8fafb;
        border-radius: 12px;
        border: 1px dashed #d1d5db;
    }

    .receipt-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .price-breakdown {
        margin-top: 15px;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
        color: var(--text-light);
    }

    .price-row.total {
        border-top: 2px solid #d1d5db;
        margin-top: 10px;
        padding-top: 15px;
        font-weight: 700;
        font-size: 18px;
        color: var(--text);
    }

    /* Alert Messages */
    .alert {
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        align-items: flex-start;
        gap: 15px;
        animation: fadeInUp 0.5s ease;
    }

    .alert-info {
        background: #e8f4fd;
        border: 1px solid #bee5f5;
        color: #0c5460;
    }

    .alert-warning {
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
    }

    .alert-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-icon {
        font-size: 24px;
        flex-shrink: 0;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 30px;
        margin: 20px 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #d1d5db;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -26px;
        top: 0;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--primary);
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px var(--primary);
    }

    .timeline-dot.completed {
        background: #27ae60;
        box-shadow: 0 0 0 2px #27ae60;
    }

    .timeline-content {
        font-size: 14px;
        color: var(--text-light);
    }

    .timeline-content strong {
        color: var(--text);
    }

    .timeline-time {
        font-size: 12px;
        color: #999;
        margin-top: 4px;
    }

    /* Print Styles */
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            background: #fff;
        }

        .success-container {
            padding: 0;
        }

        .details-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }
    }

    /* Responsive */
    @media (max-width: 992px) {
        .success-grid {
            grid-template-columns: 1fr;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .success-title {
            font-size: 28px;
        }

        .success-icon {
            width: 80px;
            height: 80px;
        }

        .success-icon i {
            font-size: 40px;
        }

        .details-card {
            padding: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">Booking Status</h2>
                    
                </div>
            </div>
        </div>
    </div>
<div class="success-container">
    
    <!-- Payment Pending Alert (if applicable) -->
    @if($booking->payment_status !== 'paid')
    <div class="alert alert-warning no-print">
        <div class="alert-icon">
            <i class="isax isax-warning-2" style="font-size: 30px;"></i>
        </div>
        <div>
            <strong style="font-size: 16px;">Payment Pending</strong>
            <p style="margin: 5px 0 0 0; font-size: 14px;">
                Your payment is not yet completed. Please complete the payment to confirm your booking.
                <br>Our team will contact you shortly at <strong>{{ $booking->phone }}</strong> for payment assistance.
            </p>
        </div>
    </div>
    @endif

    <!-- Success Header -->
    <div class="success-header">
        <div class="success-icon-wrapper">
            <div class="success-icon">
                <i class="isax isax-tick-circle"></i>
            </div>
            <!-- Confetti particles -->
            <div class="confetti-particle" style="background: #ff6b6b; top: 0; left: 20px; animation-delay: 0s;"></div>
            <div class="confetti-particle" style="background: #4ecdc4; top: 10px; right: 20px; animation-delay: 0.3s;"></div>
            <div class="confetti-particle" style="background: #45b7d1; bottom: 20px; left: 0; animation-delay: 0.6s;"></div>
            <div class="confetti-particle" style="background: #f9ca24; bottom: 0; right: 10px; animation-delay: 0.9s;"></div>
            <div class="confetti-particle" style="background: #a55eea; top: 50%; left: -10px; animation-delay: 0.4s;"></div>
            <div class="confetti-particle" style="background: #26de81; top: 50%; right: -10px; animation-delay: 0.7s;"></div>
        </div>
        
        <h1 class="success-title">
            {{ $booking->status === 'confirmed' ? 'Booking Confirmed!' : 'Booking Received!' }}
        </h1>
        <p class="success-subtitle">
            {{ $booking->status === 'confirmed' 
                ? 'Your adventure is now booked. Get ready for an amazing experience!'
                : 'We have received your booking request. Please complete the payment to confirm.' 
            }}
        </p>
        <div class="booking-number">
            <i class="isax isax-document-text"></i>
            Booking #{{ $booking->booking_number }}
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="success-grid">
        
        <!-- Left Column - Booking Details -->
        <div>
            <!-- Status Timeline -->
            <div class="details-card">
                <h3 class="card-title">
                    <i class="isax isax-status-up"></i>
                    Booking Status
                </h3>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot completed"></div>
                        <div class="timeline-content">
                            <strong>Booking Received</strong>
                            <p>Your booking has been received and a confirmation email will be sent to <strong>{{ $booking->email }}</strong></p>
                            <div class="timeline-time">{{ $booking->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                    </div>
                    
                    @if($booking->payment_status === 'paid')
                    <div class="timeline-item">
                        <div class="timeline-dot completed"></div>
                        <div class="timeline-content">
                            <strong>Payment Confirmed</strong>
                            <p>Payment of ₹{{ number_format($booking->total_amount, 0) }} has been received</p>
                            <div class="timeline-time">{{ $booking->payment_date ? $booking->payment_date->format('d M Y, h:i A') : '' }}</div>
                        </div>
                    </div>
                    @else
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <strong>Payment Pending</strong>
                            <p>Please complete the payment to confirm your seats</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <strong>Travel Date</strong>
                            <p>Your journey begins on <strong>{{ $booking->travel_date->format('d M Y') }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="details-card" style="margin-top: 30px;">
                <h3 class="card-title">
                    <i class="isax isax-info-circle"></i>
                    Booking Details
                </h3>
                
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Passenger Name</div>
                        <div class="detail-value">{{ $booking->name }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Email Address</div>
                        <div class="detail-value">{{ $booking->email }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Phone Number</div>
                        <div class="detail-value">{{ $booking->phone }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Travel Date</div>
                        <div class="detail-value highlight">
                            <i class="isax isax-calendar-2"></i>
                            {{ $booking->travel_date->format('d M Y') }}
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Total Seats Booked</div>
                        <div class="detail-value highlight">{{ $booking->total_seats }}</div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Seat Numbers</div>
                        <div class="seats-display">
                            @foreach(explode(',', $booking->seat_numbers) as $seat)
                                <span class="seat-badge">Seat {{ $seat }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Booking Status</div>
                        <div class="detail-value">
                            <span class="status-badge {{ $booking->status === 'confirmed' ? 'confirmed' : 'pending' }}">
                                <span class="pulse-dot"></span>
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Payment Status</div>
                        <div class="detail-value {{ $booking->payment_status === 'paid' ? 'success' : '' }}">
                            {{ $booking->payment_status ? ucfirst($booking->payment_status) : 'Pending' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Receipt -->
            <div class="details-card" style="margin-top: 30px;">
                <div class="receipt-section">
                    <div class="receipt-title">
                        <i class="isax isax-receipt"></i>
                        Payment Receipt
                    </div>
                    
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Price per person × {{ $booking->total_seats }} seats</span>
                            <span>₹{{ number_format($booking->price_per_seat * $booking->total_seats, 0) }}</span>
                        </div>
                        <div class="price-row">
                            <span>GST (5%)</span>
                            <span>₹{{ number_format(($booking->price_per_seat * $booking->total_seats) * 0.05, 0) }}</span>
                        </div>
                        <div class="price-row">
                            <span>Service Fee</span>
                            <span>₹50</span>
                        </div>
                        <div class="price-row total">
                            <span>Total Amount</span>
                            <span style="color: var(--primary);">₹{{ number_format($booking->total_amount, 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Tour Info & Actions -->
        <div class="tour-sidebar">
            
            <!-- Tour Information -->
            <div class="tour-card">
                @if(isset($tour) && $tour->image)
                <img src="{{ asset($tour->image) }}" alt="{{ $tour->tour_name }}" class="tour-card-image">
                @else
                <img src="https://placehold.co/400x200/20b1d7/ffffff?text=Tour+Image" alt="Tour" class="tour-card-image">
                @endif
                
                <div class="tour-card-content">
                    <h4 class="tour-card-name">
                        {{ $tour->tour_name ?? 'Amazing Tour Package' }}
                    </h4>
                    
                    <div class="tour-feature">
                        <i class="isax isax-calendar-2"></i>
                        <span><strong>Travel Date:</strong> {{ $booking->travel_date->format('d M Y') }}</span>
                    </div>
                    
                    <div class="tour-feature">
                        <i class="isax isax-people"></i>
                        <span><strong>Seats:</strong> {{ $booking->total_seats }} ({{ $booking->seat_numbers }})</span>
                    </div>
                    
                    <div class="tour-feature">
                        <i class="isax isax-clock"></i>
                        <span><strong>Duration:</strong> {{ $booking->fixedDeparture->nights_count ?? 2 }}N - {{ $booking->fixedDeparture->days_count ?? 3 }}D</span>
                    </div>
                    
                    <div class="tour-feature">
                        <i class="isax isax-location"></i>
                        <span><strong>Meeting Point:</strong> {{ $booking->fixedDeparture->meeting_point ?? 'To be announced' }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-card no-print">
                @if($booking->payment_status !== 'paid')
                <button class="btn btn-primary" onclick="retryPayment()">
                    <i class="isax isax-card"></i>
                    Complete Payment Now
                </button>
                @endif
                
                <button class="btn btn-outline" onclick="window.print()">
                    <i class="isax isax-printer"></i>
                    Print Booking Details
                </button>
                
                <button class="btn btn-success" onclick="shareOnWhatsApp()">
                    <i class="isax isax-whatsapp"></i>
                    Share on WhatsApp
                </button>
                
                <a href="{{ route('home') }}" class="btn btn-outline" style="text-decoration: none;">
                    <i class="isax isax-home5"></i>
                    Back to Home
                </a>
            </div>

            <!-- Need Help Card -->
            <div class="tour-card" style="text-align: center;">
                <div class="tour-card-content">
                    <h4 style="margin-bottom: 15px;">
                        <i class="isax isax-headphone"></i>
                        Need Help?
                    </h4>
                    <p style="font-size: 14px; color: var(--text-light); margin-bottom: 15px;">
                        Our support team is available 24/7 to assist you with any queries.
                    </p>
                    <a href="tel:+918790006059" style="color: var(--primary); font-weight: 700; font-size: 18px; text-decoration: none;">
                        <i class="isax isax-call"></i>
                        +918790006059
                    </a><br>
                    <a href="tel:+918790006069" style="color: var(--primary); font-weight: 700; font-size: 18px; text-decoration: none;">
                        <i class="isax isax-call"></i>
                        +918790006069
                    </a>
                    <br>
                    <a href="mailto:support@phoenixtoursandtravels.in" style="color: var(--text-light); font-size: 14px; text-decoration: none;">
                        support@phoenixtoursandtravels.in
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    // Toastr configuration
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000
    };

    // Retry payment function
    function retryPayment() {
        @if($booking->payment_status !== 'paid')
        // You can implement payment retry logic here
        // This could reinitialize Razorpay with the same order
        alert('Payment gateway is being set up. Our team will contact you at {{ $booking->phone }} for payment assistance.');
        @endif
    }

    // Share on WhatsApp
    function shareOnWhatsApp() {
        const bookingDetails = `🎉 *Booking Confirmed!*\n\n` +
            `📋 *Booking #:* {{ $booking->booking_number }}\n` +
            `👤 *Name:* {{ $booking->name }}\n` +
            `📅 *Date:* {{ $booking->travel_date->format('d M Y') }}\n` +
            `🪑 *Seats:* {{ $booking->total_seats }} ({{ $booking->seat_numbers }})\n` +
            `💰 *Amount:* ₹{{ number_format($booking->total_amount, 0) }}\n\n` +
            `Thank you for booking with Phoenix Tours & Travels! 🌟`;
        
        const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(bookingDetails)}`;
        window.open(whatsappUrl, '_blank');
    }

    // Auto-print option (optional)
    // Uncomment to auto-trigger print dialog
    // setTimeout(() => {
    //     if (confirm('Would you like to print your booking details?')) {
    //         window.print();
    //     }
    // }, 2000);

    // Send confirmation email notification
    @if($booking->status === 'confirmed')
    toastr.success('Confirmation email sent to {{ $booking->email }}');
    @endif

    // Console log for debugging
    console.log('Booking Details:', {
        bookingNumber: '{{ $booking->booking_number }}',
        status: '{{ $booking->status }}',
        date: '{{ $booking->travel_date->format('Y-m-d') }}',
        seats: '{{ $booking->seat_numbers }}',
        amount: '{{ $booking->total_amount }}'
    });
</script>

<!-- Add print-specific styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .success-container, .success-container * {
            visibility: visible;
        }
        .success-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            /*padding: 20px;*/
        }
        .no-print, .no-print * {
            display: none !important;
        }
        .details-card {
            break-inside: avoid;
            page-break-inside: avoid;
        }
    }
</style>
@endsection