@extends('layouts.app')
@section('title', 'Tour Packages Add')
@section('css')
@endsection
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Fixed Departures</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Fixed Departures</li>
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
                        <h6 class="mb-0 text-uppercase">Add Fixed Departures</h6>
                        <hr>
                        <form method="post" id="add_tour" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Tour Category Name</label>
                                    <select name="tour_category_id" id="tour_category_id" class="form-control">
                                        <option value="">Select Tour Category Name</option>
                                        <option value="1" selected>Domestic</option>
                                        <option value="2">International</option>
                                    </select>
                                </div>
                                
                                <!-- Domestic Tour Dropdown (visible by default) -->
                                <div class="col-6" id="domestic_tour_div">
                                    <label class="form-label">Tour Name</label>
                                    <select name="domestic_tour_id" id="domestic_tour_id" class="form-control">
                                        <option value="">Select Tour Name</option>
                                        @foreach($tour as $key => $val)
                                        <option value="{{$val->id}}">{{$val->tour_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- International Tour Dropdown (hidden by default) -->
                                <div class="col-6" id="international_tour_div" style="display:none;">
                                    <label class="form-label">Tour Name</label>
                                    <select name="international_tour_id" id="international_tour_id" class="form-control">
                                        <option value="">Select Tour Name</option>
                                        @foreach($international_tour as $key => $val)
                                        <option value="{{$val->id}}">{{$val->tour_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Package Name</label>
                                    <input type="text" name="package_name" value="" id="package_name" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="image" value="" id="image" class="form-control">
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Offer Price</label>
                                    <input type="number" name="price" value="" id="price" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Meeting Point</label>
                                    <input type="text" name="meeting_point" value="" id="meeting_point" class="form-control">
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="form-label">Days Count</label>
                                    <input class="form-control" name="days_count" id="days_count">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Nights Count</label>
                                    <input class="form-control" name="nights_count" id="nights_count">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Itinerary ID</label>
                                    <input type="text" name="itinerary_id" value="" id="itinerary_id" class="form-control">
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Pack Location Cover</label>
                                    <textarea type="text" name="pack_location_cover" value="" id="pack_location_cover" class="form-control"></textarea>
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
    CKEDITOR.replace('descriptions');
</script>

<script>
    $(document).ready(function() {
        // Show/hide tour dropdowns based on category selection
        $('#tour_category_id').change(function() {
            var category_id = $(this).val();
            
            // Hide both dropdowns first
            $('#domestic_tour_div').hide();
            $('#international_tour_div').hide();
            
            // Show appropriate dropdown
            if (category_id == '1') {
                $('#domestic_tour_div').show();
                $('#international_tour_id').val(''); // Reset international selection
            } else if (category_id == '2') {
                $('#international_tour_div').show();
                $('#domestic_tour_id').val(''); // Reset domestic selection
            }
        });
    });
</script>

<script>
    $("#add_tour").submit(function() {
        event.preventDefault();
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
        
        // Create FormData
        var formData = new FormData($("#add_tour")[0]);
        
        axios.post("{{ route('add.FixedDepartures') }}", formData).then(response => {
            var data = response.data;
            if (data.success) {
                notify(null, 'Fixed Departures Data added successfully', 'top right', 'success', true,
                    "{{ route('list.fixed.departures') }}", 1000);
            } else {
                $("#submit").prop('disabled', false);
                $("#submit").html('Submit');
                if (data.error && data.error.message) {
                    for (var a in data.error.message) {
                        notify(null, data.error.message[a][0], 'bottom left');
                    }
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

<script type="text/javascript">
    $(document).ready(function() {      
        var i = 1;  

        $('#add').click(function() {  
            i++;  
            $('#dynamic_field').append(
                '<div class="row mb-2" id="row'+i+'">' +
                    '<div class="col-10">' +
                        '<label class="form-label">Image</label>' +
                        '<input type="file" name="image[]" class="form-control" required />' +
                    '</div>' +
                    '<div class="col-2 d-flex align-items-end">' +
                        '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>' +
                    '</div>' +
                '</div>'
            );  
        });

        $(document).on('click', '.btn_remove', function() {  
            var button_id = $(this).attr("id");   
            $('#row'+button_id).remove();  
        });  
    });
</script>
@stop