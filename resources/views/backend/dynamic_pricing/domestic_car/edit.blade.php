@extends('layouts.app')
@section('title', 'Add Car Dynamic Price Dashboard')
@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Car Dynamic Price</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Car Dynamic Price</li>
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
                            <h6 class="mb-0 text-uppercase">Add Car Dynamic Price</h6>
                            <hr>
                            <form method="post" id="add_car_dynamic_price" action="" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">State Name</label>
                                            <select name="state_id" value="" id="state_id" class="form-control">
                                                <option value="">Select State Name</option>
                                                @foreach($state as $key => $val)
                                                <option value="{{$val->id}}" {{ $val->id == $dynamic_price->state_id ? 'selected' : '' }}>{{$val->tour_name}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                                <!-- Car Rental Prices Section -->
                                <div class="row g-3 mt-3">
                                    <div class="col-12">
                                        <h6 class="mb-0 text-uppercase">Car Rental Prices</h6>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="car_price_table">
                                                <thead>
                                                    <tr>
                                                        <th>Pax</th>
                                                        <th>Car Name</th>
                                                        <th>Car P/D (per day)</th>
                                                        <th>Driver Batta (per day)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="number" name="pax" value="{{$dynamic_price->pax}}" class="form-control" placeholder="Pax" min="1" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="car_name" value="{{$dynamic_price->car_name}}" class="form-control" placeholder="Car Name">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="car_per_day" value="{{$dynamic_price->car_per_day}}" class="form-control" placeholder="Car P/D" step="0.01" value="0.00">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="driver_batta" value="{{$dynamic_price->driver_batta}}" class="form-control" placeholder="Driver Batta" step="0.01" value="0.00">
                                                        </td>
                                                        
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
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
    <script src="{{ asset('backend/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('editor');
    </script>
    
    

    <script>
        $("#add_car_dynamic_price").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('edit.car.dynamic.price',$dynamic_price->id) }}", new FormData($("#add_car_dynamic_price")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true,
                    "{{ route('list.car.dynamic.price') }}", 1000);
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
    
    
<script>
    $(document).ready(function(){
        $(document).on('change','#country_id', function() {
            let country_id = $(this).val();
                $.ajax({
                    method: 'post',
                    url: "{{ route('get.state.name') }}",
                    data: {country_id: country_id, _token: '{{csrf_token()}}'},
                    success: function(res) {
                        if (res!='') {
                            $('#state_id').html(res);
                        }
                    }
                })
        });
    });
</script>

@stop