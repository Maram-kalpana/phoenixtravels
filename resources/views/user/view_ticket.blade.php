@extends('layouts.web')
@section('title','View Ticket')
@section('css')
<style>
    .ticket-conversation{
max-height:400px;
overflow-y:auto;
}

.ticket-message{
padding:12px;
border-radius:8px;
margin-bottom:10px;
}

.user-message{
background:#e9f2ff;
text-align:right;
}

.admin-message{
background:#f8f9fa;
}
</style>
@endsection
@section('content')
 <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">Support Ticket</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item active" aria-current="page">Track and reply to your support request</li>
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

<!-- Ticket Content -->
<div class="col-lg-9">

<div class="card shadow border-0">

<div class="card-header bg-white d-flex justify-content-between">

<h5 class="mb-0">
<i class="fas fa-ticket-alt text-primary me-2"></i>
Ticket #{{ $ticket->id }}
</h5>

<span class="badge bg-success">
{{ ucfirst($ticket->status) }}
</span>

</div>

<div class="card-body">

<!-- Ticket Subject -->

<div class="mb-4">

<h6 class="text-muted">Subject</h6>

<h5>{{ $ticket->subject }}</h5>

</div>

<!-- Conversation -->

<div class="ticket-conversation mb-4">

@foreach($messages as $msg)

@if($msg->type == 'user')

<div class="ticket-message user-message">

<strong>You</strong>

<p>{{ $msg->message }}</p>

<small>{{ $msg->created_at->format('d M Y H:i') }}</small>

</div>

@else

<div class="ticket-message admin-message">

<strong>Support Team</strong>

<p>{{ $msg->message }}</p>

<small>{{ $msg->created_at->format('d M Y H:i') }}</small>

</div>

@endif

@endforeach

</div>

<!-- Reply Form -->

@if($ticket->status != 'closed')

<form method="post" action="{{route('user.ticket.reply',$ticket->id)}}">

@csrf

<div class="mb-3">

<label class="form-label">Reply</label>

<textarea
name="message"
class="form-control"
rows="4"
placeholder="Type your message..."
required></textarea>

</div>

<button class="btn btn-primary">
<i class="fas fa-paper-plane me-1"></i>
Send Reply
</button>

</form>

@endif

</div>

</div>

</div>

</div>

</div>
</section>

@endsection