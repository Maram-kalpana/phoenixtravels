@extends('layouts.app')
@section('title', 'Departures Dates Add')
@section('css')
@endsection
@section('content')
<div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Departures Dates</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Departures Dates</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="border p-3 rounded">
                            <h6 class="mb-0 text-uppercase">Edit Departures Dates</h6>
                            <hr>
                            <form method="post" id="add_itinerary" action="" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-4">
                                        <label class="form-label">Departure Date</label>
                                        <input type="date" name="departure_date" value="{{ $fixed_departure_dates->departure_date }}" id="departure_date" class="form-control">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Price</label>
                                        <input type="text" name="price" value="{{ $fixed_departure_dates->price }}" id="price" class="form-control">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Available Seats</label>
                                        <input type="text" name="available_seats" value="{{ $fixed_departure_dates->available_seats }}" id="available_seats" class="form-control">
                                    </div>
                                </div>
                                <br>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="">
                                            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#add_itinerary").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('edit.departures.dates',[Request::segment(3),Request::segment(4)]) }}", new FormData($("#add_itinerary")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Data updated successfully', 'top right', 'success', true, "{{ route('list.departures.dates',[Request::segment(3)]) }}", 1000);
                else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Submit');
                    for (var a in data['error']['message']) {
                        notify(null, data['error']['message'][a][0], 'botton left');
                        if (a == 'success' | a == 'error') notify(null, data['error']['message'][a][0],
                            'botton left');
                    }
                }
            }).catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('Submit');
                notify(null, 'Something went wrong', 'top right');
                console.log(error);
            });
        });
    </script>
@stop

