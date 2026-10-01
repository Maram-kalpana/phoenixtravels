@extends('layouts.web')
@section('title', 'Payment Success | Phoenix Tours & Travels')

@section('content')
<div class="breadcrumb-bar breadcrumb-bg-02 text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title mb-2">Payment Successful!</h2>
            </div>
        </div>
    </div>
</div>

<section class="section_padding">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card shadow-lg border-0 rounded-4 text-center p-4">

                    <!-- Success Icon -->
                    <div class="mb-4">
                        <div class="success-icon mx-auto">
                            ✓
                        </div>
                    </div>

                    <!-- Heading -->
                    <h2 class="text-success fw-bold mb-2">
                        Payment Successful!
                    </h2>

                    <p class="text-muted mb-4">
                        Thank you for booking with <strong>Phoenix Tours & Travels</strong>.
                        Your journey is confirmed and we are excited to serve you.
                    </p>

                    <!-- Booking Details -->
                    <div class="booking-details text-start p-3 rounded bg-light mb-4">

                        <h5 class="fw-bold mb-3">Booking Details</h5>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Booking ID:</strong><br>
                                {{ $booking->booking_id }}
                            </div>

                            <div class="col-md-6 mb-2">
                                <strong>Payment ID:</strong><br>
                                {{ $booking->razorpay_payment_id }}
                            </div>

                            <div class="col-md-6 mb-2">
                                <strong>Package:</strong><br>
                                {{ getPackageName($booking->package_id) }}
                            </div>

                            <div class="col-md-6 mb-2">
                                <strong>Total Amount:</strong><br>
                                ₹{{ $booking->total_amount }}
                            </div>

                            <div class="col-md-6 mb-2">
                                <strong>Travel Date:</strong><br>
                                {{ \Carbon\Carbon::parse($booking->from_date)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($booking->to_date)->format('d M Y') }}
                            </div>

                            <div class="col-md-6 mb-2">
                                <strong>Travellers:</strong><br>
                                {{ $booking->adults_count }} Adults
                                {{ $booking->children_count ? ', '.$booking->children_count.' Children' : '' }}
                            </div>

                        </div>
                    </div>

                    <!-- Message -->
                    <div class="alert alert-success border-0">
                        🎫 Your booking confirmation and travel details have been sent to your email.
                    </div>

                    <!-- CTA Buttons -->
                    <!--<div class="mt-4">-->

                    <!--    <a href="{{ url('/') }}" class="btn btn_theme btn_md me-2">-->
                    <!--        Back to Home-->
                    <!--    </a>-->

                    <!--    <a href="{{ url('/user/dashboard') }}" class="btn btn-outline-success btn_md">-->
                    <!--        View My Bookings-->
                    <!--    </a>-->

                    <!--</div>-->

                </div>

            </div>

        </div>
    </div>
</section>


<style>
.success-icon {
    width: 80px;
    height: 80px;
    background: #28a745;
    color: #fff;
    font-size: 40px;
    line-height: 80px;
    border-radius: 50%;
    font-weight: bold;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.booking-details {
    border: 1px dashed #ddd;
}
</style>

@endsection
