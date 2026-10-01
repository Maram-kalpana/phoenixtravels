@extends('layouts.web')
@section('title','Create Ticket | Phoenix Tours & Travels')
@section('css')
<style>
    .card{
border-radius:10px;
}

.form-control:focus{
box-shadow:none;
border-color:#0d6efd;
}
</style>
@endsection
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

<!-- Content -->
<div class="col-lg-9">

<div class="card shadow border-0">

<div class="card-header bg-white">
<h5 class="mb-0">
<i class="fas fa-ticket-alt text-primary me-2"></i>
Create Support Ticket
</h5>
</div>

<div class="card-body">
<form method="post" id="ticket_submit">
@csrf
<div class="mb-3">
<label class="form-label">Subject</label>
<input type="text" name="subject" class="form-control" placeholder="Enter ticket subject">
</div>

<div class="mb-3">
<label class="form-label">Message</label>
<textarea name="message" class="form-control" rows="5" placeholder="Describe your issue or request"></textarea>
</div>

<div class="d-flex gap-2">

<button class="btn btn-primary" id="submit">
<i class="fas fa-paper-plane me-1"></i>
Submit Ticket
</button>

<a href="{{route('user.support')}}" class="btn btn-outline-secondary">
Back
</a>

</div>

</form>

</div>

</div>

</div>

</div>

</div>
</section>

@endsection

@section('script')
    <script>
        $("#ticket_submit").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....'
            );
            axios.post("{{ route('user.ticket.create') }}", new FormData($("#ticket_submit")[0])).then(
                response => {
                    var data = response.data;
                    if (data.success) notify(null, 'Your support ticket has been submitted successfully. We will get back to you soon.', 'top right', 'success',
                        true,"{{ route('user.support') }}", 1000);
                    else {
                        $("#submit").prop('disabled', false);
                        $("#submit").html('<i class="fas fa-paper-plane me-1"></i> Submit Ticket <i class="bi bi-arrow-right"></i>');
                        for (var a in data['error']['message']) {
                            notify(null, data['error']['message'][a][0], 'botton left');
                            if (a == 'success' | a == 'error') notify(null, data['error']['message'][a][0],
                                'botton left');
                        }
                    }
                }).catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('<i class="fas fa-paper-plane me-1"></i> Submit Ticket <i class="bi bi-arrow-right"></i>');
                notify(null, 'Something went wrong', 'top right');
                console.log(error);
            });
        });
    </script>
@stop
