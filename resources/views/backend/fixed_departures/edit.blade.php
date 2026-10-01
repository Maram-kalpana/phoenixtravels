@extends('layouts.app')
@section('title', 'Fixed Departures Packages Edit')
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
                    <li class="breadcrumb-item active" aria-current="page">Edit Fixed Departures</li>
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
                        <h6 class="mb-0 text-uppercase">Edit Fixed Departures</h6>
                        <hr>
                        <form method="post" id="edit_tour" action="" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Tour Category Name</label>
                                    <select name="tour_category_id" id="tour_category_id" class="form-control" required>
                                        <option value="">Select Tour Category Name</option>
                                        <option value="1" {{ $fixed_departures->tour_category_id == 1 ? 'selected' : '' }}>Domestic</option>
                                        <option value="2" {{ $fixed_departures->tour_category_id == 2 ? 'selected' : '' }}>International</option>
                                    </select>
                                </div>
                                
                                <!-- Domestic Tour Dropdown -->
                                <div class="col-6" id="domestic_tour_div" style="{{ $fixed_departures->tour_category_id == 1 ? '' : 'display:none;' }}">
                                    <label class="form-label">Tour Name (Domestic)</label>
                                    <select name="domestic_tour_id" id="domestic_tour_id" class="form-control">
                                        <option value="">Select Tour Name</option>
                                        @foreach($tour as $key => $val)
                                        <option value="{{$val->id}}" {{ $fixed_departures->tour_id == $val->id ? 'selected' : '' }}>{{$val->tour_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- International Tour Dropdown -->
                                <div class="col-6" id="international_tour_div" style="{{ $fixed_departures->tour_category_id == 2 ? '' : 'display:none;' }}">
                                    <label class="form-label">Tour Name (International)</label>
                                    <select name="international_tour_id" id="international_tour_id" class="form-control">
                                        <option value="">Select Tour Name</option>
                                        @foreach($international_tour as $key => $val)
                                        <option value="{{$val->id}}" {{ $fixed_departures->tour_id == $val->id ? 'selected' : '' }}>{{$val->tour_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Package Name</label>
                                    <input type="text" name="package_name" value="{{$fixed_departures->package_name}}" id="package_name" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Offer Price</label>
                                    <input type="number" name="price" value="{{$fixed_departures->price}}" id="price" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="image" value="" id="image" class="form-control">
                                    <small class="text-muted">Leave empty to keep current image</small>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Current Image</label>
                                    @if($fixed_departures->package_image)
                                        <img src="{{asset($fixed_departures->package_image)}}" style="height: auto;width: 125px;border-radius: 10%;display:block;">
                                        <input type="hidden" name="old_image" value="{{$fixed_departures->package_image}}">
                                    @else
                                        <p>No image available</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="form-label">Days Count</label>
                                    <input class="form-control" name="days_count" value="{{$fixed_departures->days_count}}" id="days_count" required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Nights Count</label>
                                    <input class="form-control" name="nights_count" value="{{$fixed_departures->nights_count}}" id="nights_count" required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Meeting Point</label>
                                    <input type="text" name="meeting_point" value="{{$fixed_departures->meeting_point}}" id="meeting_point" class="form-control">
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Itinerary ID</label>
                                    <input type="text" name="itinerary_id" value="{{$fixed_departures->itinerary_id}}" id="itinerary_id" class="form-control">
                                </div>
                                <div class="col-6">
                                    <!-- Empty for spacing -->
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Pack Location Cover</label>
                                    <textarea name="pack_location_cover" id="pack_location_cover" class="form-control" rows="4">{{$fixed_departures->pack_location_cover}}</textarea>
                                </div>
                            </div>
                            
                            <br>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="">
                                        <button type="submit" id="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('list.fixed.departures') }}" class="btn btn-secondary">Cancel</a>
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
    $(document).ready(function() {
        // Show/hide tour dropdowns based on category selection
        $('#tour_category_id').change(function() {
            var category_id = $(this).val();
            
            // Hide both dropdowns first
            $('#domestic_tour_div').hide();
            $('#international_tour_div').hide();
            
            // Show appropriate dropdown and reset the other
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
    $("#edit_tour").submit(function(event) {
        event.preventDefault();
        
        // Validate that a tour is selected
        var tour_id = $('#domestic_tour_id').val() || $('#international_tour_id').val();
        if (!tour_id) {
            notify(null, 'Please select a tour name', 'top right', 'error');
            return false;
        }
        
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating....');
        
        var formData = new FormData($("#edit_tour")[0]);
        
        axios.post("{{ route('edit.FixedDepartures',$fixed_departures->id) }}", formData, {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'multipart/form-data'
            }
        }).then(response => {
            var data = response.data;
            if (data.success) {
                notify(null, 'Fixed Departures updated successfully', 'top right', 'success', true,
                    "{{ route('list.fixed.departures') }}", 1000);
            } else {
                $("#submit").prop('disabled', false);
                $("#submit").html('Update');
                if (data.error && data.error.message) {
                    if (typeof data.error.message === 'object') {
                        for (var a in data.error.message) {
                            notify(null, data.error.message[a][0], 'bottom left');
                        }
                    } else {
                        notify(null, data.error.message, 'bottom left');
                    }
                }
            }
        }).catch(error => {
            $("#submit").prop('disabled', false);
            $("#submit").html('Update');
            if (error.response && error.response.data && error.response.data.error) {
                notify(null, error.response.data.error, 'top right', 'error');
            } else {
                notify(null, 'Something went wrong. Please try again.', 'top right', 'error');
            }
            console.log(error);
        });
    });
</script>
@stop