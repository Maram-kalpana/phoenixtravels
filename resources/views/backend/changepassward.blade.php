@extends('layouts.app')
@section('title', 'Profile')
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Profile</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Update Change Password</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card radius-10">
                <div class="card-body">
                    <form method="post" id="update_password" action="" enctype="multipart/form-data">
                        <h5 class="mb-0 mt-4">Update Change Password</h5>
                        <hr>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="password" value="">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password_confirmation" value="">
                            </div>
                        </div>
                        <div class="text-start mt-3">
                            <button type="submit" id="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div><!--end row-->

</div>
@endsection

@section('script')
        <script>
$("#update_password").submit(function() {
    event.preventDefault();
     $("#submit").prop('disabled', true);
    $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
    axios.post("{{ route('update.change.passward') }}", new FormData($("#update_password")[0])).then(response => {
        var data = response.data;
        $('#update_password')[0].reset();
        if (data.success) notify(null, 'Password Updated successfully', 'top right', 'success', true);
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
