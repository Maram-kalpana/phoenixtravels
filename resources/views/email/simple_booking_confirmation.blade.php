<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; text-align: center; border-radius: 5px; }
        .details { margin: 20px 0; }
        .details table { width: 100%; border-collapse: collapse; }
        .details td { padding: 8px; border-bottom: 1px solid #eee; }
        .details td:first-child { font-weight: bold; width: 40%; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmed!</h1>
            <p>Thank you for booking with us.</p>
        </div>
        
        <div class="details">
            <h2>Booking Details</h2>
            <table>
                <tr>
                    <td>Booking Number:</td>
                    <td>{{ $booking->booking_number }}</td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td>{{ $booking->name }}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{ $booking->email }}</td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td>{{ $booking->phone }}</td>
                </tr>
                <tr>
                    <td>Travel Date:</td>
                    <td>{{ date('d M Y', strtotime($booking->travel_date)) }}</td>
                </tr>
                <tr>
                    <td>Seats:</td>
                    <td>{{ $booking->total_seats }} ({{ $booking->seat_numbers }})</td>
                </tr>
                <tr>
                    <td>Total Amount:</td>
                    <td>₹{{ number_format($booking->total_amount, 2) }}</td>
                </tr>
                @if($tour)
                <tr>
                    <td>Package:</td>
                    <td>{{ $tour->tour_name ?? 'N/A' }}</td>
                </tr>
                @endif
                <tr>
                    <td>Status:</td>
                    <td><strong>{{ ucfirst($booking->status) }}</strong></td>
                </tr>
                <tr>
                    <td>Payment Status:</td>
                    <td>{{ ucfirst($booking->payment_status) }}</td>
                </tr>
            </table>
        </div>
        
        <div class="footer">
            <p>If you have any questions, please contact us.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>