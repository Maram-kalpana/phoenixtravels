<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation - Phoenix Tours & Travels</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #1d9cd0;
        }
        .header h1 {
            color: #1d9cd0;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .content {
            padding: 20px 0;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .booking-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #1d9cd0;
        }
        .booking-details h3 {
            margin: 0 0 15px 0;
            color: #1d9cd0;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 130px;
        }
        .detail-value {
            display: inline-block;
        }
        .tour-info {
            margin: 20px 0;
            padding: 15px;
            background: #e8f4f8;
            border-radius: 8px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #999;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #1d9cd0;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
        .btn:hover {
            background: #1583b0;
        }
        .text-success {
            color: #28a745;
            font-weight: bold;
        }
        .text-primary {
            color: #1d9cd0;
        }
        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }
            .detail-label {
                display: block;
                width: 100%;
                margin-bottom: 5px;
            }
            .detail-value {
                display: block;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmed! 🎉</h1>
            <p>Phoenix Tours & Travels</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                <p>Dear <strong>{{ $customer_name ?? 'Valued Customer' }}</strong>,</p>
                <p>Thank you for choosing Phoenix Tours & Travels. Your booking has been <span class="text-success">confirmed successfully</span>.</p>
            </div>
            
            <div class="booking-details">
                <h3>📋 Booking Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value"><strong>{{ $booking_id ?? 'N/A' }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Booking Date:</span>
                    <span class="detail-value">{{ date('d M Y, h:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value text-primary"><strong>₹{{ number_format($total_amount ?? 0, 2) }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Status:</span>
                    <span class="detail-value text-success">Paid ✓</span>
                </div>
                @if(isset($payment) && $payment->coupon_code)
                <div class="detail-row">
                    <span class="detail-label">Coupon Applied:</span>
                    <span class="detail-value">{{ $payment->coupon_code }}</span>
                </div>
                @endif
            </div>
            
            @if(isset($invoice) && $invoice)
            <div class="tour-info">
                <h3>✈️ Tour Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Package:</span>
                    <span class="detail-value">{{ $invoice->package_name ?? 'Tour Package' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Departure Date:</span>
                    <span class="detail-value">{{ $invoice->departure_date ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Return Date:</span>
                    <span class="detail-value">{{ $invoice->return_date ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Travelers:</span>
                    <span class="detail-value">{{ $invoice->adults_count ?? 0 }} Adults, {{ $invoice->children_count ?? 0 }} Children</span>
                </div>
            </div>
            @endif
            
            <p style="margin-top: 20px;">Please find attached your invoice for this booking.</p>
            
            
            <div style="margin-top: 30px; padding: 15px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107;">
                <p style="margin: 0; font-size: 13px; color: #856404;">
                    <strong>📌 Important Notes:</strong><br>
                    • Please carry a valid ID proof for all travelers.<br>
                    • Reporting time is 2 hours before departure.<br>
                    • For any changes or cancellations, please contact our support team.
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Phoenix Tours & Travels</strong><br>
            Email: support@phoenixtoursandtravels.in | Phone: +91 8790006059, +91 8790006069</p>
            <p>&copy; {{ date('Y') }} Phoenix Tours & Travels. All rights reserved.</p>
            <p style="font-size: 11px;">This is a system generated email, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>