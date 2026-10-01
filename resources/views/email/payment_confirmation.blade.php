{{-- resources/views/email/payment_confirmation.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f5f5;
        }
        
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            padding: 40px 30px;
            text-align: center;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: #ffffff;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .checkmark {
            display: inline-block;
            width: 30px;
            height: 15px;
            border-left: 4px solid #28a745;
            border-bottom: 4px solid #28a745;
            transform: rotate(-45deg);
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -12px;
            margin-left: -15px;
        }
        
        .header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header p {
            color: #ffffff;
            font-size: 16px;
            opacity: 0.95;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 25px;
            font-weight: 500;
        }
        
        .greeting strong {
            color: #1a202c;
        }
        
        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
        }
        
        .info-card h3 {
            color: #2d3748;
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #718096;
            font-size: 14px;
            font-weight: 500;
        }
        
        .info-value {
            color: #2d3748;
            font-size: 14px;
            font-weight: 600;
            text-align: right;
        }
        
        .booking-number {
            background: #ebf4ff;
            color: #3182ce;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .status-badge {
            display: inline-block;
            background: #c6f6d5;
            color: #22543d;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .payment-details {
            background: #ffffff;
            border: 2px solid #c6f6d5;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        
        .payment-amount {
            font-size: 36px;
            font-weight: 700;
            color: #28a745;
            margin: 10px 0;
        }
        
        .payment-amount .currency {
            font-size: 20px;
            vertical-align: super;
        }
        
        .payment-method {
            color: #718096;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .tour-details {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            color: #ffffff;
        }
        
        .tour-details h3 {
            font-size: 18px;
            margin-bottom: 15px;
            opacity: 0.95;
        }
        
        .tour-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .tour-date {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .important-note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        
        .important-note h4 {
            color: #856404;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .important-note p {
            color: #856404;
            font-size: 13px;
            margin: 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 15px 35px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .footer h4 {
            color: #2d3748;
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        .contact-info {
            color: #718096;
            font-size: 14px;
            line-height: 1.8;
        }
        
        .contact-info a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #718096;
            text-decoration: none;
        }
        
        .copyright {
            color: #a0aec0;
            font-size: 12px;
            margin-top: 20px;
        }
        
        .divider {
            border-top: 1px solid #e9ecef;
            margin: 20px 0;
        }
        
        .seats-info {
            display: inline-block;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
        }
        
        @media only screen and (max-width: 600px) {
            .header {
                padding: 30px 20px;
            }
            
            .content {
                padding: 25px 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .payment-amount {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="header">
            <h1>Payment Confirmed! 🎉</h1>
            <p>Your booking is now confirmed</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Dear <strong>{{ $booking->name }}</strong>,
            </div>
            
            <p style="margin-bottom: 25px; color: #4a5568; font-size: 15px;">
                Great news! Your payment has been successfully processed and your booking is now confirmed. 
                We're excited to have you on board for this amazing journey!
            </p>
            
            <!-- Payment Success Card -->
            <div class="payment-details">
                <div style="color: #28a745; font-size: 14px; font-weight: 600; margin-bottom: 5px;">
                    ✓ PAYMENT SUCCESSFUL
                </div>
                <div class="payment-amount">
                    <span class="currency">₹</span>{{ number_format($booking->total_amount, 2) }}
                </div>
                <div class="payment-method">
                    Paid via {{ ucfirst($booking->payment_method) }} • {{ $booking->payment_date ? date('d M Y, h:i A', strtotime($booking->payment_date)) : '' }}
                </div>
            </div>
            
            <!-- Booking Details -->
            <div class="info-card">
                <h3>📋 Booking Details</h3>
                
                <div class="info-row">
                    <span class="info-label">Booking Number</span>
                    <span class="booking-number">{{ $booking->booking_number }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="status-badge">{{ ucfirst($booking->status) }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Travel Date</span>
                    <span class="info-value">{{ date('d M Y', strtotime($booking->travel_date)) }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Total Seats</span>
                    <span class="info-value">{{ $booking->total_seats }} Seat(s)</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Seat Numbers</span>
                    <span class="seats-info">{{ $booking->seat_numbers }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Price Per Seat</span>
                    <span class="info-value">₹{{ number_format($booking->price_per_seat, 2) }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Payment Status</span>
                    <span class="info-value" style="color: #28a745;">✓ Paid</span>
                </div>
            </div>
            
            <!-- Tour Details -->
            @if(isset($tour) && $tour)
            <div class="tour-details">
                <h3>🧳 Tour Information</h3>
                <div class="tour-name">{{ $tour->tour_name ?? $tour->package_name ?? 'Tour Package' }}</div>
                @if($booking->fixedDeparture)
                <div class="tour-date">
                    <!--Duration: {{ $booking->fixedDeparture->duration ?? 'N/A' }} • -->
                    <!--Group Size: {{ $booking->fixedDeparture->group_size ?? 'N/A' }}-->
                </div>
                @endif
            </div>
            @endif
            
            <!-- Important Notes -->
            <div class="important-note">
                <h4>📋 Important Information</h4>
                <p>
                    Please keep this email for your records. Your booking number <strong>{{ $booking->booking_number }}</strong> 
                    will be required for any future correspondence. Our team will reach out to you before the travel date 
                    with detailed itinerary and pickup information.
                </p>
            </div>
            
            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ route('booking.success', $booking->booking_number) }}" class="cta-button" style="color:#fff">
                    View Booking Details →
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <h4>Need Help?</h4>
            <div class="contact-info">
                <div>📞 <a href="tel:+918790006059">+91 8790006059</a></div>
                <div>📞 <a href="tel:+918790006069">+91 8790006069</a></div>
                <div>✉️ <a href="mailto:support@phoenixtoursandtravels.in">support@phoenixtoursandtravels.in</a></div>
                <div>🌐 <a href="https://phoenixtoursandtravels.in">www.phoenixtoursandtravels.in</a></div>
            </div>
            
            <div class="social-links">
                <a href="#" title="Facebook">Facebook</a> •
                <a href="#" title="Instagram">Instagram</a> •
                <a href="#" title="Twitter">Twitter</a>
            </div>
            
            <div class="divider"></div>
            
            <div class="copyright">
                © {{ date('Y') }} Phoenix Tours & Travels. All rights reserved.<br>
                This is an automated email, please do not reply directly.
            </div>
        </div>
    </div>
</body>
</html>