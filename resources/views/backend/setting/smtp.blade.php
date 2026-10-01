@extends('layouts.app')
@section('title', 'SMTP Settings Dashboard')
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
                        <li class="breadcrumb-item active" aria-current="page">Smtp Settings</li>
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
                            <h6 class="mb-0 text-uppercase">Smtp Settings</h6>
                            <hr>
                            <form method="post" id="update_smtp" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Smtp Protocol</label>
                                    <input type="text" name="protocol" value="{{$smtp->protocol}}" id="protocol" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Smtp Host</label>
                                    <input type="text" name="smtp_host" id="smtp_host" value="{{$smtp->smtp_host}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Smtp Port</label>
                                    <input type="text" name="smtp_port" id="smtp_port" value="{{$smtp->smtp_port}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Smtp User</label>
                                    <input type="text" name="smtp_user" id="smtp_user" value="{{$smtp->smtp_user}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Smtp Password</label>
                                    <input type="text" name="smtp_pass" id="smtp_pass" value="{{$smtp->smtp_pass}}" class="form-control">
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
$("#update_smtp").submit(function() {
    event.preventDefault();
     $("#submit").prop('disabled', true);
    $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
    axios.post("{{ route('update.smtp.settings') }}", new FormData($("#update_smtp")[0])).then(response => {
        var data = response.data;
        $('#update_smtp')[0].reset();
        if (data.success) notify(null, 'Smtp Settings Updated successfully', 'top right', 'success', true);
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
