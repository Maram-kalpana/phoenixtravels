@extends('layouts.app')
@section('title', 'Add Dynamic Price Dashboard')
@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dynamic Price</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Dynamic Price</li>
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
                            <h6 class="mb-0 text-uppercase">Add Dynamic Price</h6>
                            <hr>
                            <form method="post" id="add_car_dynamic_price" action="" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">State Name</label>
                                            <select name="state_id" value="" id="state_id" class="form-control">
                                                <option value="">Select State Name</option>
                                                @foreach($state as $key => $val)
                                                <option value="{{$val->id}}">{{ $val->tour_name }}</option>
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
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="number" name="car_config[0][pax]" class="form-control" placeholder="Pax" min="1" value="1">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="car_config[0][car_name]" class="form-control" placeholder="Car Name">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="car_config[0][car_per_day]" class="form-control" placeholder="Car P/D" step="0.01" value="0.00">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="car_config[0][driver_batta]" class="form-control" placeholder="Driver Batta" step="0.01" value="0.00">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-success add-car-row">
                                                                <ion-icon name="add-outline"></ion-icon>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger remove-car-row">
                                                                <ion-icon name="trash-outline"></ion-icon>
                                                            </button>
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
        // Add new car row with proper indexing
        $(document).ready(function() {
            let carRowCount = $('#car_price_table tbody tr').length;
            
            // Function to generate new row HTML with index
            function getNewRowHtml(index) {
                return `
                    <tr>
                        <td>
                            <input type="number" name="car_config[${index}][pax]" class="form-control" placeholder="Pax" min="1" value="">
                        </td>
                        <td>
                            <input type="text" name="car_config[${index}][car_name]" class="form-control" placeholder="Car Name">
                        </td>
                        <td>
                            <input type="number" name="car_config[${index}][car_per_day]" class="form-control" placeholder="Car P/D" step="0.01" value="0.00">
                        </td>
                        <td>
                            <input type="number" name="car_config[${index}][driver_batta]" class="form-control" placeholder="Driver Batta" step="0.01" value="0.00">
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success add-car-row">
                                <ion-icon name="add-outline"></ion-icon>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger remove-car-row">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </td>
                    </tr>
                `;
            }

            // Re-index all rows
            function reindexRows() {
                $('#car_price_table tbody tr').each(function(index) {
                    $(this).find('input[name^="car_config"]').each(function() {
                        var name = $(this).attr('name');
                        // Update the index in the name attribute
                        var newName = name.replace(/car_config\[\d+\]/, 'car_config[' + index + ']');
                        $(this).attr('name', newName);
                    });
                });
                carRowCount = $('#car_price_table tbody tr').length;
            }

            // Add new row
            $('.add-car-row').click(function() {
                var newIndex = $('#car_price_table tbody tr').length;
                var newRow = getNewRowHtml(newIndex);
                $('#car_price_table tbody').append(newRow);
                bindRowEvents();
            });

            // Remove car row
            $(document).on('click', '.remove-car-row', function() {
                if ($('#car_price_table tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                    // Re-index after removal
                    reindexRows();
                } else {
                    notify(null, 'At least one row is required', 'top right');
                }
            });

            function bindRowEvents() {
                $('.add-car-row').off('click').click(function() {
                    var newIndex = $('#car_price_table tbody tr').length;
                    var newRow = getNewRowHtml(newIndex);
                    $('#car_price_table tbody').append(newRow);
                    bindRowEvents();
                });
            }

            // Auto-calculate total car price per day
            $(document).on('input', 'input[name^="car_config"][name$="[car_per_day]"], input[name^="car_config"][name$="[driver_batta]"]', function() {
                var row = $(this).closest('tr');
                var car_pd = parseFloat(row.find('input[name$="[car_per_day]"]').val()) || 0;
                var driver_batta = parseFloat(row.find('input[name$="[driver_batta]"]').val()) || 0;
                // You can add a total column here if needed
            });
        });
    </script>

    <script>
        $("#add_car_dynamic_price").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('add.car.dynamic.price') }}", new FormData($("#add_car_dynamic_price")[0])).then(response => {
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