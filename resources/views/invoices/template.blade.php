<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }} | Phoenix Tours & Travels</title>
    <style>
        @page {
            size: A4;
            margin: 8mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Helvetica', Arial, sans-serif;
            font-size: 13px;
            color: #1a1a2e;
            margin: 0;
            padding: 10px;
            line-height: 1.6;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .main-table td {
            vertical-align: top;
        }
        
        /* Header Styles */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .header-table td {
            vertical-align: middle;
            padding: 10px;
        }
        
        .logo-cell {
            width: 100px;
            text-align: center;
        }
        
        .header-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #20b1d7;
            padding: 4px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #0a1628;
            margin-bottom: 5px;
        }
        
        .company-name span {
            color: #20b1d7;
        }
        
        .company-tagline {
            font-size: 13px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: bold;
            margin-bottom: 6px;
        }
        
        .company-address {
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }
        
        .invoice-info-cell {
            text-align: right;
        }
        
        .invoice-label {
            display: inline-block;
            background: #20b1d7;
            color: #fff;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
            margin-bottom: 12px;
        }
        
        .invoice-date {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            line-height: 1.8;
        }
        
        .invoice-date strong {
            color: #1a1a2e;
            font-size: 14px;
        }
        
        /* Divider */
        .divider-row td {
            border-top: 3px solid #20b1d7;
            padding: 8px 0;
        }
        
        .divider-thin-row td {
            border-top: 1px solid #e2e8f0;
            padding: 5px 0;
        }
        
        /* Section Title */
        .section-title-row td {
            background: #0a1628;
            color: #ffffff;
            padding: 12px 20px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        
        /* Customer & Booking Info */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .info-table td {
            width: 50%;
            vertical-align: top;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            line-height: 1.8;
        }
        
        .info-section-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #20b1d7;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .info-content {
            font-size: 14px;
        }
        
        .info-content strong {
            color: #1a1a2e;
            font-size: 15px;
        }
        
        .info-row-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-row-table td {
            padding: 4px 0;
            font-size: 13px;
            background: transparent;
            border: none;
        }
        
        .info-row-table .lbl {
            color: #64748b;
            font-size: 12px;
        }
        
        .info-row-table .val {
            font-weight: bold;
            color: #1a1a2e;
            font-size: 13px;
            text-align: right;
        }
        
        .info-row-table .val-highlight {
            color: #20b1d7;
            font-weight: bold;
            font-size: 13px;
            text-align: right;
        }
        
        /* Status Badge */
        .status-paid {
            color: #065f46;
            font-weight: bold;
            font-size: 13px;
        }
        
        .status-pending {
            color: #92400e;
            font-weight: bold;
            font-size: 13px;
        }
        
        /* Travel Details */
        .travel-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: #f0f9ff;
            border-left: 5px solid #20b1d7;
        }
        
        .travel-table td {
            padding: 12px 18px;
            font-size: 13px;
            line-height: 1.8;
            vertical-align: top;
        }
        
        .travel-section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #0c4a6e;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .travel-table strong {
            font-size: 15px;
            color: #1a1a2e;
        }
        
        .travel-table span {
            font-size: 13px;
            color: #475569;
        }
        
        /* Product Table */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 13px;
        }
        
        .product-table thead th {
            background: #f1f5f9;
            font-weight: bold;
            text-align: center;
            padding: 12px 10px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        
        .product-table tbody td {
            padding: 12px 10px;
            border: 1px solid #e2e8f0;
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .service-name {
            font-weight: bold;
            color: #1a1a2e;
            font-size: 13px;
        }
        
        .service-desc {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 3px;
        }
        
        /* Totals Section */
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .totals-table td {
            padding: 8px 15px;
            font-size: 14px;
        }
        
        .totals-label {
            text-align: right;
            color: #64748b;
            font-weight: 500;
            font-size: 14px;
            width: 70%;
        }
        
        .totals-amount {
            text-align: right;
            font-weight: bold;
            color: #1a1a2e;
            width: 30%;
            font-size: 14px;
        }
        
        .total-row td {
            font-size: 18px;
            font-weight: bold;
            color: #20b1d7;
            border-top: 2px solid #e2e8f0;
            padding-top: 12px;
        }
        
        .balance-row td {
            padding-top: 12px;
        }
        
        .balance-label {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: #1a1a2e;
            padding-right: 15px;
            width: 70%;
        }
        
        .balance-amount {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            color: #ffffff;
            background: #1a2a4a;
            padding: 12px 18px;
            width: 30%;
        }
        
        .amount-words {
            font-size: 12px;
            color: #94a3b8;
            font-style: italic;
            text-align: right;
            padding: 8px 15px;
        }
        
        .amount-words strong {
            color: #64748b;
            font-weight: bold;
            font-size: 13px;
        }
        
        /* Footer */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        
        .footer-table td {
            vertical-align: top;
            padding: 12px;
        }
        
        .terms-text {
            font-size: 12px;
            color: #64748b;
            line-height: 1.9;
        }
        
        .terms-text strong {
            color: #1a1a2e;
            font-size: 14px;
        }
        
        .signature-cell {
            text-align: right;
            padding-right: 25px;
        }
        
        .signature-img {
            width: 120px;
            height: auto;
        }
        
        .signature-line {
            border-top: 2px solid #1a1a2e;
            width: 160px;
            margin-top: 50px;
        }
        
        .signature-label {
            font-size: 12px;
            color: #64748b;
            margin-top: 8px;
            font-weight: 500;
        }
        
        .signature-company {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 5px;
        }
        
        .thank-you-row td {
            text-align: center;
            color: #20b1d7;
            font-size: 14px;
            font-weight: bold;
            padding: 15px;
            border-top: 2px solid #e2e8f0;
            margin-top: 15px;
        }
        
        .text-green {
            color: #10b981;
        }
    </style>
</head>
<body>

<!-- MAIN CONTAINER TABLE -->
<table class="main-table">
    
    <!-- HEADER SECTION -->
    <tr>
        <td>
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        @php
                            $logoPath = public_path('frontend/img/logo-1.png');
                            $logoExists = file_exists($logoPath);
                        @endphp
                        @if($logoExists)
                            <img src="{{ $logoPath }}" class="header-logo" alt="Phoenix Tours Logo">
                        @else
                            <table>
                                <tr>
                                    <td style="width: 80px; height: 80px; border-radius: 50%; background: #20b1d7; text-align: center; vertical-align: middle;">
                                        <span style="color: #fff; font-size: 32px; font-weight: bold;">PT</span>
                                    </td>
                                </tr>
                            </table>
                        @endif
                    </td>
                    
                    <td>
                        <table>
                            <tr>
                                <td class="company-name">PHOENIX <span>TOURS</span> &amp; TRAVELS</td>
                            </tr>
                            <tr>
                                <td class="company-tagline">Your Trusted Travel Partner Since 2015</td>
                            </tr>
                            <tr>
                                <td class="company-address">
                                    📍 Kukatpally, Hyderabad, Telangana - 500085<br>
                                    📞 +91 8790006059 &nbsp;|&nbsp;📞 +91 8790006069<br>
                                     ️ ✉️ support@phoenixtoursandtravels.in &nbsp;|&nbsp; 🌐 www.phoenixtoursandtravels.in
                                </td>
                            </tr>
                        </table>
                    </td>
                    
                    <td class="invoice-info-cell">
                        <table style="float: right;">
                            <tr>
                                <td class="invoice-label">Tax Invoice</td>
                            </tr>
                            <tr>
                                <td class="invoice-date">
                                    <strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('d M Y') }}<br>
                                    <strong>Invoice No:</strong> {{ $invoice->invoice_number }}<br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    <!-- DIVIDER -->
    <tr class="divider-row">
        <td></td>
    </tr>
    
    <!-- SECTION TITLE -->
    <tr class="section-title-row">
        <td>🏕️ TOUR PACKAGE INVOICE</td>
    </tr>
    
    <!-- CUSTOMER & BOOKING INFO -->
    <tr>
        <td>
            <table class="info-table">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="info-section-title">👤 TRAVELER DETAILS</td>
                            </tr>
                            <tr>
                                <td class="info-content">
                                    <strong>{{ $invoice->customer_name }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="info-content">📧 {{ $invoice->customer_email }}</td>
                            </tr>
                            <tr>
                                <td class="info-content">📱 {{ $invoice->customer_phone }}</td>
                            </tr>
                        </table>
                    </td>
                    
                    <td>
                        <table style="width: 100%;">
                            <tr>
                                <td class="info-section-title">🎫 BOOKING INFORMATION</td>
                            </tr>
                            <tr>
                                <td>
                                    <table class="info-row-table">
                                        <tr>
                                            <td class="lbl">Booking ID</td>
                                            <td class="val-highlight">{{ $booking->booking_number }}</td>
                                        </tr>
                                        <tr>
                                            <td class="lbl">Booking Date</td>
                                            <td class="val">{{ $booking->created_at->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="lbl">Package</td>
                                            <td class="val">{{ $booking->fixedDeparture->package_name ?? 'Tour Package' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="lbl">Agent</td>
                                            <td class="val">Phoenix Tours & Travels</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    <!-- TRAVEL DETAILS -->
    <tr>
        <td>
            <table class="travel-table">
                <tr>
                    <td width="33%">
                        <table>
                            <tr>
                                <td class="travel-section-title">✈️ Travel Date</td>
                            </tr>
                            <tr>
                                <td><strong>{{ $booking->travel_date->format('D, d M Y') }}</strong></td>
                            </tr>
                        </table>
                    </td>
                    
                    <td width="33%">
                        <table>
                            <tr>
                                <td class="travel-section-title">⏱️ Duration</td>
                            </tr>
                            <tr>
                                <td><strong>{{ $booking->fixedDeparture->nights_count ?? 2 }} Nights / {{ $booking->fixedDeparture->days_count ?? 3 }} Days</strong></td>
                            </tr>
                        </table>
                    </td>
                    
                    <td width="34%">
                        <table>
                            <tr>
                                <td class="travel-section-title">👥 Group Size</td>
                            </tr>
                            <tr>
                                <td><strong>Max {{ $booking->fixedDeparture->group_size ?? '30' }} Persons</strong></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td class="travel-section-title">📍 Meeting Point</td>
                            </tr>
                            <tr>
                                <td><span>{{ $booking->fixedDeparture->meeting_point ?? 'To Be Announced' }}</span></td>
                            </tr>
                        </table>
                    </td>
                    
                    <td>
                        <table>
                            <tr>
                                <td class="travel-section-title">🪑 Seats Booked</td>
                            </tr>
                            <tr>
                                <td><strong>{{ $booking->total_seats }} Seat(s)</strong></td>
                            </tr>
                        </table>
                    </td>
                    
                    <td>
                        <table>
                            <tr>
                                <td class="travel-section-title">🗣️ Language</td>
                            </tr>
                            <tr>
                                <td><span>English / Hindi / Telugu</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    <!-- PRODUCT TABLE -->
    <tr>
        <td>
            <table class="product-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="20%">Service</th>
                        <th width="28%">Description</th>
                        <th width="12%">Date</th>
                        <th width="8%">Qty</th>
                        <th width="13%">Price (₹)</th>
                        <th width="14%">Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>
                            <table>
                                <tr>
                                    <td class="service-name">Tour Package</td>
                                </tr>
                                <tr>
                                    <td class="service-desc">Per Person Charges</td>
                                </tr>
                            </table>
                        </td>
                        <td>{{ $booking->fixedDeparture->package_name ?? 'Tour Package' }}</td>
                        <td class="text-center">{{ $booking->travel_date->format('d M Y') }}</td>
                        <td class="text-center">{{ $booking->total_seats }}</td>
                        <td class="text-right">₹{{ number_format($booking->price_per_seat, 0) }}</td>
                        <td class="text-right">₹{{ number_format($invoice->subtotal, 0) }}</td>
                    </tr>
                    
                    <tr>
                        <td class="text-center">2</td>
                        <td>
                            <table>
                                <tr>
                                    <td class="service-name">GST</td>
                                </tr>
                                <tr>
                                    <td class="service-desc">Goods & Services Tax</td>
                                </tr>
                            </table>
                        </td>
                        <td>GST @ {{ $invoice->gst_percentage }}%</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-right">-</td>
                        <td class="text-right">₹{{ number_format($invoice->gst_amount, 0) }}</td>
                    </tr>
                    
                    @if($invoice->service_fee > 0)
                    <tr>
                        <td class="text-center">3</td>
                        <td>
                            <table>
                                <tr>
                                    <td class="service-name">Service Fee</td>
                                </tr>
                                <tr>
                                    <td class="service-desc">Booking & Processing</td>
                                </tr>
                            </table>
                        </td>
                        <td>Administrative Charges</td>
                        <td class="text-center">-</td>
                        <td class="text-center">-</td>
                        <td class="text-right">-</td>
                        <td class="text-right">₹{{ number_format($invoice->service_fee, 0) }}</td>
                    </tr>
                    @endif
                    
                    <tr>
                        <td class="text-center">{{ $invoice->service_fee > 0 ? '4' : '3' }}</td>
                        <td>
                            <table>
                                <tr>
                                    <td class="service-name">Seat Reservation</td>
                                </tr>
                                <tr>
                                    <td class="service-desc">Selected Seats: {{ $booking->seat_numbers }}</td>
                                </tr>
                            </table>
                        </td>
                        <td>Reserved Seats</td>
                        <td class="text-center">{{ $booking->travel_date->format('d M Y') }}</td>
                        <td class="text-center">{{ $booking->total_seats }}</td>
                        <td class="text-right">Included</td>
                        <td class="text-right">₹0.00</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    
    <!-- TOTALS SECTION -->
    <tr>
        <td>
            <table class="totals-table">
                <tr>
                    <td class="totals-label">Subtotal:</td>
                    <td class="totals-amount">₹{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="totals-label">GST ({{ $invoice->gst_percentage }}%):</td>
                    <td class="totals-amount">₹{{ number_format($invoice->gst_amount, 2) }}</td>
                </tr>
                @if($invoice->service_fee > 0)
                <tr>
                    <td class="totals-label">Service Fee:</td>
                    <td class="totals-amount">₹{{ number_format($invoice->service_fee, 2) }}</td>
                </tr>
                @endif
                
                @php
                    $advancePayment = $booking->payment_status === 'paid' ? $invoice->total_amount : 0;
                    $balanceDue = $invoice->total_amount - $advancePayment;
                @endphp
                
                @if($advancePayment > 0)
                <tr>
                    <td class="totals-label" style="color: #10b981;">Advance Payment:</td>
                    <td class="totals-amount" style="color: #10b981;">- ₹{{ number_format($advancePayment, 2) }}</td>
                </tr>
                @endif
                
                <tr class="divider-thin-row">
                    <td colspan="2"></td>
                </tr>
                
                <tr class="total-row">
                    <td class="totals-label">Total Package Cost:</td>
                    <td class="totals-amount">₹{{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
                
                @if($balanceDue > 0)
                <tr class="balance-row">
                    <td colspan="2">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td class="balance-label">Balance Due:</td>
                                <td class="balance-amount">₹{{ number_format($balanceDue, 2) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif
                
                <tr>
                    <td colspan="2" class="amount-words">
                        Amount in Words: <strong>{{ ucwords(\NumberFormatter::create('en_IN', \NumberFormatter::SPELLOUT)->format($invoice->total_amount)) }} Rupees Only</strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    <!-- DIVIDER -->
    <tr class="divider-row">
        <td></td>
    </tr>
    
    <!-- FOOTER -->
    <tr>
        <td>
            <table class="footer-table">
                <tr>
                    <td width="62%">
                        <table class="terms-text">
                            <tr>
                                <td><strong>Terms &amp; Conditions:</strong></td>
                            </tr>
                            <tr>
                                <td>• This is a computer-generated invoice and does not require a physical signature.</td>
                            </tr>
                            <tr>
                                <td>• All prices are in Indian Rupees (₹).</td>
                            </tr>
                            <tr>
                                <td>• 50% cancellation charges apply if cancelled within 7 days of travel date.</td>
                            </tr>
                            <tr>
                                <td>• Carry valid ID proof during travel.</td>
                            </tr>
                            <tr>
                                <td>• Check-in: 12:00 PM | Check-out: 10:00 AM (as per hotel policy).</td>
                            </tr>
                            <tr>
                                <td>• All disputes subject to Mumbai jurisdiction.</td>
                            </tr>
                            <tr>
                                <td>• Payment is due within 7 days from the invoice date.</td>
                            </tr>
                        </table>
                    </td>
                    
                    <td width="38%" class="signature-cell">
                        @php
                            $signaturePath = public_path('frontend/img/Signature-1-removebg-preview.png');
                            $signatureExists = file_exists($signaturePath);
                        @endphp
                        
                        @if($signatureExists)
                        <table style="float: right;">
                            <tr>
                                <td style="text-align: right;">
                                    <img src="{{ $signaturePath }}" class="signature-img" alt="Authorized Signature">
                                </td>
                            </tr>
                        </table>
                        @endif
                        
                        <table style="float: right; margin-top: 20px;">
                            <tr>
                                <td style="text-align: right;">
                                    <table>
                                        <tr>
                                            <td class="signature-line"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="signature-label">Authorized Signatory</td>
                            </tr>
                            <tr>
                                <td class="signature-company">Phoenix Tours &amp; Travels</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    <!-- THANK YOU -->
    <tr class="thank-you-row">
        <td>✈️ Thank you for choosing Phoenix Tours & Travels! We wish you a wonderful journey filled with unforgettable memories. 🌍✨</td>
    </tr>
    
</table>

</body>
</html>