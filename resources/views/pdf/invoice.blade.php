<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->booking_id ?? 'N/A' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .invoice-box {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            border: 1px solid #e0e0e0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1d9cd0;
        }
        .header h1 {
            color: #1d9cd0;
            margin: 0 0 5px;
            font-size: 32px;
        }
        .header p {
            margin: 0;
            color: #666;
        }
        .company-info {
            float: left;
            width: 50%;
            margin-bottom: 20px;
        }
        .invoice-info {
            float: right;
            width: 50%;
            text-align: right;
            margin-bottom: 20px;
        }
        .clearfix {
            clear: both;
        }
        .customer-info {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .customer-info h3 {
            margin: 0 0 10px;
            color: #1d9cd0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th {
            background: #1d9cd0;
            color: #fff;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        table tr:last-child td {
            border-bottom: none;
        }
        .totals {
            float: right;
            width: 350px;
            margin-top: 20px;
        }
        .totals table {
            width: 100%;
        }
        .totals table td {
            border: none;
            padding: 8px;
        }
        .totals table tr:last-child td {
            font-weight: bold;
            border-top: 2px solid #333;
            font-size: 16px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #999;
        }
        .text-right {
            text-align: right;
        }
        .text-success {
            color: #28a745;
        }
        .text-primary {
            color: #1d9cd0;
        }
        .section-title {
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        @media print {
            body {
                padding: 0;
            }
            .invoice-box {
                padding: 15px;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <img src="{{ public_path('frontend/img/logo-1.png') }}" alt="Logo" style="max-width: 150px; margin-bottom: 15px;">
            <h1>TAX INVOICE</h1>
            <p>Phoenix Tours & Travels</p>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="company-info">
            <strong>Phoenix Tours & Travels</strong><br>
            18 & 19 1, Gullapallis Movva Nest Complex, 1st Floor, Plot. No. 17, Nizampet  <br>Rd, opp. Vijetha Super Market, Hyder Nagar, Kukatpally, Hyderabad, Telangana 500085<br>
            Email: support@phoenixtoursandtravels.in<br>
            Phone: +91 8790006059 /<br> +91 8790006069
        </div>
        
        <div class="invoice-info">
            <strong>INVOICE DETAILS</strong><br>
            Invoice No: {{ $invoice->invoice_number ?? 'INV-' . ($invoice->id ?? 'N/A') }}<br>
            Invoice Date: {{ $invoice->invoice_date ?? date('d M Y') }}<br>
            Booking ID: {{ $invoice->booking_id ?? 'N/A' }}<br>
            Payment Status: <span class="text-success">{{ $invoice->payment_status ?? 'Paid' }}</span>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="customer-info">
            <h3>BILL TO</h3>
            <strong>{{ $invoice->customer_name ?? 'N/A' }}</strong><br>
            {{ $invoice->customer_address ?? 'N/A' }}<br>
            Email: {{ $invoice->customer_email ?? 'N/A' }}<br>
            Phone: {{ $invoice->customer_phone ?? 'N/A' }}
        </div>
        
        <h3 class="section-title">TOUR DETAILS</h3>
        <table>
            <tr>
                <td width="30%"><strong>Package Name:</strong></td>
                <td>{{ $invoice->package_name ?? 'Tour Package' }}</td>
            </tr>
            <tr>
                <td><strong>Package Type:</strong></td>
                <td>{{ $invoice->package_type ?? 'Standard Package' }}</td>
            </tr>
            <tr>
                <td><strong>Departure Date:</strong></td>
                <td>{{ $invoice->departure_date ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Return Date:</strong></td>
                <td>{{ $invoice->return_date ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Accommodation:</strong></td>
                <td>{{ $invoice->accommodation ?? 'Standard Package' }}</td>
            </tr>
            <tr>
                <td><strong>No. of Adults:</strong></td>
                <td>{{ $invoice->adults_count ?? 0 }}</td>
            </tr>
            <tr>
                <td><strong>No. of Children:</strong></td>
                <td>{{ $invoice->children_count ?? 0 }}</td>
            </tr>
        </table>
        
        <h3 class="section-title">TRAVELERS DETAILS</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($invoice->adults_details) && !empty($invoice->adults_details))
                    @foreach($invoice->adults_details as $key => $adult)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ ($adult['first'] ?? '') . ' ' . ($adult['last'] ?? '') }}</td>
                        <td>{{ $adult['age'] ?? 'N/A' }}</td>
                        <td>{{ $adult['gender'] ?? 'N/A' }}</td>
                        <td>Adult</td>
                    </tr>
                    @endforeach
                @endif
                
                @if(isset($invoice->children_details) && !empty($invoice->children_details))
                    @foreach($invoice->children_details as $key => $child)
                    <tr>
                        <td>{{ ($invoice->adults_count ?? 0) + $key + 1 }}</td>
                        <td>{{ ($child['first'] ?? '') . ' ' . ($child['last'] ?? '') }}</td>
                        <td>{{ $child['age'] ?? 'N/A' }}</td>
                        <td>{{ $child['gender'] ?? 'N/A' }}</td>
                        <td>Child</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        
        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td class="text-right">₹{{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                </tr>
                 @if(($invoice->tax ?? 0) > 0)
                <tr>
                    <td>GST (5%):</td>
                    <td class="text-right">₹{{ number_format($invoice->tax ?? 0, 2) }}</td>
                </tr>
                @endif
                @if(($invoice->discount ?? 0) > 0)
                <tr>
                    <td>Discount ({{ $invoice->coupon_code ?? 'Coupon' }}):</td>
                    <td class="text-right text-success">-₹{{ number_format($invoice->discount ?? 0, 2) }}</td>
                </tr>
                @endif
               
                <tr>
                    <td><strong>Total Amount:</strong></td>
                    <td class="text-right"><strong>₹{{ number_format($invoice->total_paid ?? $invoice->total_amount ?? 0, 2) }}</strong></td>
                </tr>
            </table>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="footer">
            <p>Thank you for choosing Phoenix Tours & Travels!</p>
            <p>This is a system generated invoice and does not require signature.</p>
            <p>For any queries, please contact us at support@phoenixtoursandtravels.in</p>
        </div>
    </div>
</body>
</html>