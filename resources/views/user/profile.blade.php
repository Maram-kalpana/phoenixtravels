@extends('layouts.web')
@section('title','Profile | Phoenix Tours & Travels')
@section('content')
 <div class="breadcrumb-bar breadcrumb-bg-02 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title mb-2">My Profile</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center mb-0">
                            <li class="breadcrumb-item active" aria-current="page">Manage your personal information</li>
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
<!-- Profile Content -->
<div class="col-lg-9">
<div class="card shadow border-0">
<div class="card-header bg-white">
<h5 class="mb-0">
<i class="fas fa-user text-primary me-2"></i>
Profile Information
</h5>
</div>
<div class="card-body">
<form method="post" id="update_profile" action="" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="col-md-6 mb-3">
    <label class="form-label">Full Name</label>
    <input type="text" name="full_name" class="form-control" value="{{ $customer_details->full_name }}">
    </div>

    <div class="col-md-6 mb-3">
    <label class="form-label">Mobile Number</label>
    <input type="text" name="phone" class="form-control" value="{{ $customer_details->phone }}" readonly>
    </div>

    <div class="col-md-6 mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ $customer_details->email }}">
    </div>
   <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">Country</label>
                                                <select name="country_id" value="" id="country_id" class="form-control form-select bg_input">
                                                    <option value="">Select Country</option>
                                                    @foreach($country as $key => $val)
                                                    <option value="{{$val->id}}" {{ $customer_details->country_id == $val->id ? 'selected' : '' }}>{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <select class="form-control form-select bg_input state_id" name="state_id" id="state_id" required>
                                                    <option value="">State</option>
                                                   @foreach($state as $key => $val)
                                                    <option value="{{$val->id}}" {{ $customer_details->state_id == $val->id ? 'selected' : '' }}>{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <select class="form-control form-select bg_input city_id" name="city_id" id="city_id">
                                                    <option value="">City</option>
                                                    @foreach($city as $key => $val)
                                                    <option value="{{$val->id}}" {{ $customer_details->city_id == $val->id ? 'selected' : '' }}>{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
    
    <div class="col-md-12 mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" class="form-control" rows="3">{{ $customer_details->address }}</textarea>
</div>
</div>

<button id="submit" class="btn btn-primary">
Update Profile
</button>

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
$("#update_profile").submit(function() {
    event.preventDefault();
     $("#submit").prop('disabled', true);
    $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
    axios.post("{{ route('user.update.profile') }}", new FormData($("#update_profile")[0])).then(response => {
        var data = response.data;
        $('#update_profile')[0].reset();
        if (data.success) notify(null, 'Profile Updated successfully', 'top right', 'success', true);
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
        
        $(document).on('change','#state_id', function() {
            let state_id = $(this).val();
            let country_id = $('#country_id').val();
                $.ajax({
                    method: 'post',
                    url: "{{ route('get.city.name') }}",
                    data: {country_id: country_id,state_id: state_id, _token: '{{csrf_token()}}'},
                    success: function(res) {
                        if (res!='') {
                            $('#city_id').html(res);
                        }
                    }
                })
        });
    });
</script>
@stop
