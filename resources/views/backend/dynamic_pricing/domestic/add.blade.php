@extends('layouts.app')
@section('title', 'Add Dynamic Price Dashboard')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
    <style>
    .price-table-container {
        overflow-x: auto;
        margin-top: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
    }
    .price-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
        min-width: 800px;
    }
    .price-table th,
    .price-table td {
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
        text-align: center;
        vertical-align: middle;
    }
    .price-table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #1e293b;
    }
    .table-input {
        width: 100%;
        min-width: 80px;
        padding: 0.25rem 0.5rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        text-align: center;
    }
    .table-input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        padding-bottom: 0.25rem;
        border-bottom: 2px solid #e2e8f0;
    }
    .btn-add-row {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .star-3 {
        background-color: #14aa62;
        border-left: 4px solid #1ea2d2;
        color:#fff;
    }
    .star-2 {
        background-color: #fef3c7;
        border-left: 4px solid #f59e0b;
    }
    .star-4 {
        background-color: #e0f2fe;
        border-left: 4px solid #3b82f6;
    }
    .star-5 {
        background-color: #f3e8ff;
        border-left: 4px solid #a855f7;
    }
</style>
@endsection
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
                            <form method="post" id="add_dynamic_price" action="" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">State Name</label>
                                            <select name="state_id" value="" id="state_id" class="form-control">
                                                <option value="">Select State Name</option>
                                                @foreach($state as $key => $val)
                                                <option value="{{$val->id}}">{{$val->tour_name}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                   
                                </div>
                                <div class="border p-3 rounded">
                        <hr>
                           <!-- 2 Star Category Table -->
                            <div class="section-title star-2">
                                <span>&nbsp;&nbsp;2 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_3">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                            <th>Honeymoon Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_3">
                                        <tr>
                                            <td><input type="text" name="room_config_2[0][hotel]" value="Hotel / Resort / Cottage" class="table-input"></td>
                                            <td><input type="text" name="room_config_2[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_2[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_2[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                            <td><input type="number" name="room_config_2[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Honeymoon Cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- 3 Star Category Table -->
                            <div class="section-title star-3">
                                <span>&nbsp;&nbsp;3 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_3">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                            <th>Honeymoon Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_3">
                                        <tr>
                                            <td><input type="text" name="room_config_3[0][hotel]" value="Hotel / Resort / Cottage" class="table-input"></td>
                                            <td><input type="text" name="room_config_3[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_3[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_3[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                            <td><input type="number" name="room_config_3[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Honeymoon Cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- 4 Star Category Table -->
                            <div class="section-title star-4 mt-4">
                                <span>&nbsp;&nbsp;4 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_4">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                            <th>Honeymoon Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_4">
                                        <tr>
                                            <td><input type="text" name="room_config_4[0][hotel]" value="Hotel / Resort / Cottage" class="table-input"></td>
                                            <td><input type="text" name="room_config_4[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_4[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_4[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                            <td><input type="number" name="room_config_4[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Honeymoon Cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- 5 Star Category Table -->
                            <div class="section-title star-5 mt-4">
                                <span>&nbsp;&nbsp;5 Star Category - Room Pricing</span>
                            </div>
                            <div class="price-table-container">
                                <table class="price-table" id="room_price_table_5">
                                    <thead>
                                        <tr>
                                            <th>Hotel / Resort / Cottage</th>
                                            <th>Rooms Cost</th>
                                            <th>Extra Bed / Mattress Cost</th>
                                            <th>Attract/Entry Tickets</th>
                                            <th>Honeymoon Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room_price_body_5">
                                        <tr>
                                            <td><input type="text" name="room_config_5[0][hotel]" value="Hotel / Resort / Cottage" class="table-input"></td>
                                            <td><input type="text" name="room_config_5[0][rooms]" value="" class="table-input" placeholder="Room cost"></td>
                                            <td><input type="number" name="room_config_5[0][extra_bed_cost]" value="" class="table-input" style="width:100px" placeholder="Extra bed cost"></td>
                                            <td><input type="number" name="room_config_5[0][total_cost]" value="" class="table-input" style="width:100px" placeholder="Ticket cost"></td>
                                            <td><input type="number" name="room_config_5[0][honeymoon_cost]" value="" class="table-input" style="width:100px" placeholder="Honeymoon Cost"></td>
                                        </tr>
                                    </tbody>
                                </table>
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
        $("#add_dynamic_price").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('add.dynamic.price') }}", new FormData($("#add_dynamic_price")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true,
                    "{{ route('list.dynamic.price') }}", 1000);
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
