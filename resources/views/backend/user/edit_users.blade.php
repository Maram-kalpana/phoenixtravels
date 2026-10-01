@extends('layouts.app')
@section('title', 'Edit Users')
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Users</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Users</li>
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
                        <h6 class="mb-0 text-uppercase">Edit Users</h6>
                        <hr>
                        <form method="post" id="edit_users" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="full_name" value="{{$user->full_name}}" id="full_name"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Email</label>
                                    <input type="text" name="email" id="email" value="{{$user->email}}"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" id="phone" value="{{$user->phone}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Profile Image</label>
                                    <input type="file" name="profile_image" id="profile_image" class="form-control">
                                </div>
                                <div class="col-6">
                                        <img src="{{asset($user->profile_image)}}" style="height: auto;width: 155px;">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Street Address</label>
                                    <textarea type="text" name="address" id="address" value=""  class="form-control">{{$user->address}}</textarea>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Country</label>
                                    <select name="country_id" value="" id="country_id" class="form-control">
                                                <option value="">Select Country</option>
                                                @foreach($country as $key => $val)
                                                <option value="{{$val->id}}" {{ (old('country_id', $user->country_id) == $val->id) ? 'selected' : '' }}>{{$val->name}}</option>
                                                @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">State</label>
                                    <select name="state_id" id="state_id" class="form-control state_id">
                                        <option value="">Select State</option>
                                        @foreach($state as $key => $val)
                                        <option value="{{$val->id}}" {{ (old('state_id', $user->state_id) == $val->id) ? 'selected' : '' }}>{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-6">
                                    <label class="form-label">City</label>
                                    <select name="city_id" id="city_id" class="form-control city_id">
                                                <option value="">Select City</option>
                                                @foreach($city as $key => $val)
                                                <option value="{{$val->id}}" {{ (old('city_id', $user->city_id) == $val->id) ? 'selected' : '' }}>{{$val->name}}</option>
                                                @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Postcode</label>
                                    <input type="text" name="pin_code" value="{{$user->pin_code}}" id="pin_code" class="form-control">
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
<script src="{{asset('backend/ckeditor/ckeditor.js')}}"></script>
<script>
    CKEDITOR.replace('editor');
</script>
<script>
    $("#edit_users").submit(function() {
        event.preventDefault();
         $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
        axios.post("{{ route('edit.users',$user->id) }}", new FormData($("#edit_users")[0])).then(response => {
            var data = response.data;
            if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true, "{{ route('list.users') }}", 1000);
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
