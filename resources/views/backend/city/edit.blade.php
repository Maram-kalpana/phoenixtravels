@extends('layouts.app')
@section('title', 'Edit City Dashboard')
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">City</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit City</li>
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
                        <h6 class="mb-0 text-uppercase">Edit City</h6>
                        <hr>
                        <form method="post" id="edit_city" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                        <label class="form-label">Country Name</label>
                                            <select name="country_id" value="" id="country_id" class="form-control">
                                                <option value="">Select Country Name</option>
                                                @foreach($country as $key => $val)
                                                <option value="{{$val->id}}" {{ $val->id == $city->country_id ? 'selected' : '' }}>{{$val->name}}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                 <div class="col-6">
                                        <label class="form-label">State Name</label>
                                            <select name="state_id" value="" id="state_id" class="form-control">
                                                <option value="">Select State Name</option>
                                                @foreach($state as $key => $val)
                                                <option value="{{$val->id}}" {{ $val->id == $city->state_id ? 'selected' : '' }}>{{$val->name}}</option>
                                                @endforeach
                                            </select>
                                </div>
                               
                                <div class="col-6">
                                        <label class="form-label">City Name</label>
                                        <input type="text" name="name" value="{{$city->name}}" id="name"
                                            class="form-control">
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
        $("#edit_city").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('edit.city',$city->id) }}", new FormData($("#edit_city")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Updated added successfully', 'top right', 'success', true, "{{ route('list.city') }}", 1000);
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
