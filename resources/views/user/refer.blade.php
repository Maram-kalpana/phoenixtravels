@extends('layouts.web')
@section('title','Refer & Earn | Phoenix Tours & Travels')

@section('content')
 <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">Refer & Earn</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item active" aria-current="page">Manage your wallet balance and transactions</li>
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

<!-- Refer Section -->

<div class="col-lg-9">

<div class="row g-4">

<!-- Earnings Card -->

<div class="col-md-4">

<div class="card shadow border-0 text-center">

<div class="card-body">

<i class="fas fa-gift fa-2x text-danger mb-2"></i>

<h4>₹{{ $total_earnings ?? 0 }}</h4>

<p class="text-muted mb-0">Total Earnings</p>

</div>

</div>

</div>

<!-- Total Referrals -->

<div class="col-md-4">

<div class="card shadow border-0 text-center">

<div class="card-body">

<i class="fas fa-users fa-2x text-primary mb-2"></i>

<h4>{{ $total_referrals ?? 0 }}</h4>

<p class="text-muted mb-0">Total Referrals</p>

</div>

</div>

</div>

<!-- Wallet Bonus -->

<div class="col-md-4">

<div class="card shadow border-0 text-center">

<div class="card-body">

<i class="fas fa-wallet fa-2x text-success mb-2"></i>

<h4>₹{{ $wallet_bonus ?? 0 }}</h4>

<p class="text-muted mb-0">Wallet Bonus</p>

</div>

</div>

</div>

</div>

<!-- Referral Link -->

<div class="card shadow border-0 mt-4">

<div class="card-header bg-white">

<h5 class="mb-0">
<i class="fas fa-share-alt me-2 text-primary"></i>
Share Your Referral Link
</h5>

</div>

<div class="card-body">
    <div class="input-group">
        <input type="text" class="form-control" id="referral_link" value="{{ url('/user/login?ref=' . $customer_details->referral_id) }}" readonly>
        <button class="btn btn-primary" onclick="copyReferral()">
        Copy
        </button>
    </div>

<div class="mt-3">

<a href="https://wa.me/?text={{ url('/user/login?ref=' . $customer_details->referral_id) }}"
target="_blank"
class="btn btn-success">

<i class="fab fa-whatsapp"></i>
Share on WhatsApp

</a>

</div>

</div>

</div>

<!-- Referral History -->

<div class="card shadow border-0 mt-4">

<div class="card-header bg-white">

<h5 class="mb-0">Referral History</h5>

</div>

<div class="card-body p-0">

<table class="table table-hover mb-0">

<thead class="table-light">

<tr>
<th>User</th>
<th>Date</th>
<th>Commission</th>
<th>Status</th>
</tr>

</thead>

<tbody>

@foreach($referrals as $ref)

<tr>

<td>{{ $ref->user->name }}</td>

<td>{{ $ref->created_at->format('d M Y') }}</td>

<td>₹{{ $ref->commission }}</td>

<td>
<span class="badge bg-success">Earned</span>
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
@section('script')
<script>
function copyReferral() {
var copyText = document.getElementById("referral_link");
copyText.select();
copyText.setSelectionRange(0, 99999);
navigator.clipboard.writeText(copyText.value);
alert("Referral link copied");
}
</script>
@stop