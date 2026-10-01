@extends('layouts.web')
@section('title','My Bookings | Phoenix Tours & Travels')

@section('content')
 <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">My Bookings</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item active" aria-current="page">View your travel booking history</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>


<section class="py-5">
<div class="container">

<div class="row">

<!-- Sidebar -->
<div class="col-lg-3">

@include('user.siderbar')

</div>

<!-- Booking History -->

<div class="col-lg-9">

<div class="card shadow border-0">

<div class="card-header bg-white">

<h5 class="mb-0">
<i class="fas fa-suitcase me-2 text-primary"></i>
Booking History
</h5>

</div>

<div class="card-body p-0">

<table class="table table-hover align-middle mb-0">

<thead class="table-light">

<tr>
<th>Tour</th>
<th>Travel Date</th>
<th>Amount</th>
<th>Payment</th>
<th>Status</th>
<th>Action</th>
</tr>

</thead>

<tbody>

@foreach($bookings as $booking)

<tr>

<td>

<div class="d-flex align-items-center">

<img src="{{ asset($booking->tour->image ?? 'frontend/img/default-tour.jpg') }}"
width="60"
class="rounded me-3">

<div>

<strong>{{ $booking->tour->tour_name }}</strong><br>

<small class="text-muted">
Booking ID : #{{ $booking->id }}
</small>

</div>

</div>

</td>

<td>
{{ date('d M Y',strtotime($booking->travel_date)) }}
</td>

<td>
₹{{ number_format($booking->amount) }}
</td>

<td>

@if($booking->payment_status == 'paid')
<span class="badge bg-success">Paid</span>
@else
<span class="badge bg-warning">Pending</span>
@endif

</td>

<td>

@if($booking->status == 'confirmed')
<span class="badge bg-success">Confirmed</span>
@elseif($booking->status == 'cancelled')
<span class="badge bg-danger">Cancelled</span>
@else
<span class="badge bg-secondary">Processing</span>
@endif

</td>

<td>

<a href="{{route('user.booking.details',$booking->id)}}"
class="btn btn-sm btn-outline-primary">

<i class="fas fa-eye"></i>
</a>

<a href="{{route('user.booking.invoice',$booking->id)}}"
class="btn btn-sm btn-outline-success">

<i class="fas fa-download"></i>
</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>
</section>

@endsection