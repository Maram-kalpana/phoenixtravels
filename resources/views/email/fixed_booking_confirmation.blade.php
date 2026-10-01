<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - {{ $booking->booking_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f7f9;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        
        .email-wrapper {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        /* Header */
        .email-header {
            background: linear-gradient(135deg, #20b1d7 0%, #1a8fa8 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .email-logo {
            position: relative;
            z-index: 1;
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -1px;
            margin-bottom: 10px;
        }
        
        .email-logo span {
            color: #ffd700;
        }
        
        .email-header-icon {
            position: relative;
            z-index: 1;
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            border: 3px solid rgba(255,255,255,0.3);
        }
        
        .email-header-icon span {
            font-size: 40px;
        }
        
        .email-header h1 {
            position: relative;
            z-index: 1;
            color: #fff;
            font-size: 28px;
            margin: 10px 0 5px;
            font-weight: 700;
        }
        
        .email-header p {
            position: relative;
            z-index: 1;
            color: rgba(255,255,255,0.9);
            font-size: 14px;
            margin: 0;
        }
        
        /* Content */
        .email-content {
            padding: 40px 30px;
        }
        
        .booking-number-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 15px 25px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .info-item {
            background: #f8fafb;
            padding: 15px 20px;
            border-radius: 10px;
            border: 1px solid #e8ecf1;
        }
        
        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #999;
            letter-spacing: 1px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #333;
        }
        
        .info-value.highlight {
            color: #20b1d7;
        }
        
        /* Seats Display */
        .seats-display {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 5px;
        }
        
        .seat-tag {
            background: linear-gradient(135deg, #20b1d7, #1a8fa8);
            color: #fff;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }
        
        /* CTA Button */
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #20b1d7 0%, #1a8fa8 100%);
            color: #fff;
            text-decoration: none;
            padding: 15px 35px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 15px rgba(32,177,215,0.3);
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(32,177,215,0.4);
        }
        
        /* Invoice Summary */
        .invoice-summary {
            background: #f8fafb;
            border: 1px solid #e1e5eb;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .invoice-summary h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }
        
        .invoice-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            color: #666;
            border-bottom: 1px solid #e1e5eb;
        }
        
        .invoice-row:last-child {
            border-bottom: none;
        }
        
        .invoice-row.total {
            font-weight: 700;
            font-size: 16px;
            color: #20b1d7;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #20b1d7;
        }
        
        /* Footer */
        .email-footer {
            background: #2c3e50;
            padding: 30px;
            text-align: center;
            color: #fff;
        }
        
        .email-footer .contact-info {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 15px;
            flex-wrap: wrap;
            font-size: 13px;
        }
        
        .email-footer .social-links {
            margin: 15px 0;
        }
        
        .email-footer .social-links a {
            display: inline-block;
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            margin: 0 5px;
            line-height: 35px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }
        
        .email-footer .copyright {
            font-size: 11px;
            color: rgba(255,255,255,0.6);
            margin-top: 15px;
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .email-header h1 {
                font-size: 22px;
            }
            .email-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        
        <!-- Header -->
        <div class="email-header">
            <div class="email-logo">PHOENIX <span>TOURS</span></div>
            <div class="email-header-icon">
                <span>🎉</span>
            </div>
            <h1>Booking Confirmed!</h1>
            <p>Your adventure awaits! Here are your booking details.</p>
        </div>
        
        <!-- Content -->
        <div class="email-content">
            
            <!-- Booking Number -->
            <div class="booking-number-box">
                📋 Booking #{{ $booking->booking_number }}
            </div>
            
            <!-- Greeting -->
            <p style="font-size: 16px; margin-bottom: 5px;">Dear <strong>{{ $booking->name }}</strong>,</p>
            <p style="color: #666; font-size: 14px; margin-bottom: 25px;">
                Thank you for choosing Phoenix Tours & Travels! Your booking has been confirmed. 
                Please find your booking details and invoice attached.
            </p>
            
            <!-- Booking Details Grid -->
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Travel Date</div>
                    <div class="info-value highlight">📅 {{ $booking->travel_date->format('d M Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Seats</div>
                    <div class="info-value">🪑 {{ $booking->total_seats }} Seats</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Seat Numbers</div>
                    <div class="seats-display">
                        @foreach(explode(',', $booking->seat_numbers) as $seat)
                            <span class="seat-tag">{{ $seat }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value highlight">✅ {{ ucfirst($booking->status) }}</div>
                </div>
            </div>
            
            <!-- Tour Info -->
            @if(isset($tour))
            <div style="background: #e8f4fd; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                <strong style="color: #20b1d7;">🎯 Tour Package:</strong> 
                {{ $tour->tour_name ?? 'Amazing Tour Package' }}
            </div>
            @endif
            
            <!-- Invoice Summary -->
            <div class="invoice-summary">
                <h3>💰 Payment Summary</h3>
                <div class="invoice-row">
                    <span>Amount per person × {{ $booking->total_seats }}</span>
                    <span>₹{{ number_format($invoice->subtotal, 0) }}</span>
                </div>
                <div class="invoice-row">
                    <span>GST ({{ $invoice->gst_percentage }}%)</span>
                    <span>₹{{ number_format($invoice->gst_amount, 0) }}</span>
                </div>
                @if($invoice->service_fee > 0)
                <div class="invoice-row">
                    <span>Service Fee</span>
                    <span>₹{{ number_format($invoice->service_fee, 0) }}</span>
                </div>
                @endif
                <div class="invoice-row total">
                    <span>Total Amount</span>
                    <span>₹{{ number_format($invoice->total_amount, 0) }}</span>
                </div>
            </div>
            
            <!-- CTA -->
            <div style="text-align: center;">
                <a href="{{ route('booking.success', $booking->booking_number) }}" class="cta-button">
                    View Booking Details →
                </a>
            </div>
            
            <!-- Support Message -->
            <div style="margin-top: 25px; padding: 20px; background: #fff8e1; border-radius: 10px; border-left: 4px solid #ffc107;">
                <strong style="color: #f57c00;">📞 Need Help?</strong>
                <p style="margin: 5px 0 0; font-size: 13px; color: #666;">
                    Our support team is available 24/7. Contact us at 
                    <strong>+91 99667 68227</strong> or 
                    <strong>support@phoenixtoursandtravels.in</strong>
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="contact-info">
                <span>📞 +91 99667 68227</span>
                <span>✉️ support@phoenixtoursandtravels.in</span>
                <span>🌐 www.phoenixtoursandtravels.in</span>
            </div>
            <div class="social-links">
                <a href="#">f</a>
                <a href="#">in</a>
                <a href="#">📷</a>
                <a href="#">▶</a>
            </div>
            <div class="copyright">
                © {{ date('Y') }} Phoenix Tours & Travels. All rights reserved.<br>
                This email was sent to {{ $booking->email }} regarding your booking.
            </div>
        </div>
        
    </div>
</body>
</html>