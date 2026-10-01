@extends('layouts.app')
@section('title', 'SMS Settings Dashboard')
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
                        <li class="breadcrumb-item active" aria-current="page">Sms Settings</li>
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
                            <h6 class="mb-0 text-uppercase">SMS Settings</h6>
                            <hr>
                            <form method="post" id="update_sms" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Sms Url</label>
                                    <input type="text" name="sms_url" value="{{$sms->sms_url}}" id="sms_url" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Api Key</label>
                                    <input type="text" name="api_key" id="api_key" value="{{$sms->api_key}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Sender Id</label>
                                    <input type="text" name="sender_id" id="sender_id" value="{{$sms->sender_id}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">User Name</label>
                                    <input type="text" name="username" id="username" value="{{$sms->username}}" class="form-control">
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
$("#update_sms").submit(function() {
    event.preventDefault();
     $("#submit").prop('disabled', true);
    $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
    axios.post("{{ route('update.sms.settings') }}", new FormData($("#update_sms")[0])).then(response => {
        var data = response.data;
        $('#update_sms')[0].reset();
        if (data.success) notify(null, 'Sms Settings Updated successfully', 'top right', 'success', true);
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
