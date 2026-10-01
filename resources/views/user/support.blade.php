@extends('layouts.web')
@section('title','Support Tickets | Phoenix Tours & Travels')

@section('content')
 <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">Support Center</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item active" aria-current="page">Submit and track your support requests</li>
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

<!-- Support Tickets -->

<div class="col-lg-9">

<div class="card shadow border-0">

<div class="card-header bg-white d-flex justify-content-between align-items-center">

<h5 class="mb-0">
<i class="fas fa-headset text-primary me-2"></i>
Support Tickets
</h5>

<a href="{{route('user.ticket.create')}}" class="btn btn-primary btn-sm">
<i class="fas fa-plus"></i> New Ticket
</a>

</div>

<div class="card-body p-0">

<table class="table table-hover align-middle mb-0">

<thead class="table-light">

<tr>
<th>Ticket ID</th>
<th>Subject</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>

</thead>

<tbody>

@forelse($tickets as $ticket)

<tr>

<td>#{{ $ticket->id }}</td>

<td>{{ $ticket->subject }}</td>

<td>{{ $ticket->created_at->format('d M Y') }}</td>

<td>

@if($ticket->status == 'open')

<span class="badge bg-warning">Open</span>

@elseif($ticket->status == 'progress')

<span class="badge bg-primary">In Progress</span>

@else

<span class="badge bg-success">Closed</span>

@endif

</td>

<td>

<a href="{{route('user.ticket.view',$ticket->id)}}"
class="btn btn-sm btn-outline-primary">

<i class="fas fa-eye"></i>

</a>

</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center p-4 text-muted">
No support tickets found
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

</section>

@endsection