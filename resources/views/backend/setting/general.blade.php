@extends('layouts.app')
@section('title', 'General Settings Dashboard')
@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Settings</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">General Settings</li>
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
                            <h6 class="mb-0 text-uppercase">General Settings</h6>
                            <hr>
                            <form method="post" id="update_general" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Logo</label>
                                    <input type="file" name="logo"  id="logo" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Logo Image</label>
                                    <img src="{{asset($settings->logo)}}" class="img-thumbnail" alt="" style="max-width: 100px;max-height: 100px">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Fav</label>
                                    <input type="file" name="fav" id="fav" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Fav Image</label>
                                    <img src="{{asset($settings->fav)}}" class="img-thumbnail" alt="" style="max-width: 100px;max-height: 100px">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">About</label>
                                    <textarea type="text" name="about" id="about"  class="form-control">{{$settings->about}}</textarea>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Address</label>
                                    <textarea type="text" name="address" id="address" class="form-control">{{$settings->address}}</textarea>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" id="phone" value="{{$settings->phone}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Email</label>
                                    <input type="text" name="email" id="email" value="{{$settings->email}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" value="{{$settings->company_name}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Color Code</label>
                                    <input type="color" name="color" id="color" value="{{$settings->color}}" class="form-control">
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
$("#update_general").submit(function() {
    event.preventDefault();
     $("#submit").prop('disabled', true);
    $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
    axios.post("{{ route('update.general.settings') }}", new FormData($("#update_general")[0])).then(response => {
        var data = response.data;
        $('#update_general')[0].reset();
        if (data.success) notify(null, 'General Settings Updated successfully', 'top right', 'success', true);
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
