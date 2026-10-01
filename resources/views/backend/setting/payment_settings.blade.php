@extends('layouts.app')
@section('title', 'Payment Settings Dashboard')
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
                        <li class="breadcrumb-item active" aria-current="page">Payment Settings</li>
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
                            <h6 class="mb-0 text-uppercase">Payment Settings</h6>
                            <hr>
                            <form method="post" id="update_payment" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Razorpay Key Live</label>
                                    <input type="text" name="razorpay_key_live" value="{{$payment->razorpay_key_live}}" id="razorpay_key_live" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Razorpay Key Secret</label>
                                    <input type="text" name="razorpay_key_secret_live" id="razorpay_key_secret_live" value="{{$payment->razorpay_key_secret_live}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Razorpay Key Test Mode</label>
                                    <input type="text" name="razorpay_key_test" id="razorpay_key_test" value="{{$payment->razorpay_key_test}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Razorpay Key Secret Test Mode</label>
                                    <input type="text" name="razorpay_key_secret_test" id="razorpay_key_secret_test" value="{{$payment->razorpay_key_secret_test}}" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Mode Type</label>
                                    <select name="mode_type" class="form-control">
                                        <option value="">Select Mode</option>
                                        <option value="live_mode" {{ $payment->mode_type == 'live_mode' ? 'selected' : '' }}>Live Mode</option>
                                        <option value="test_mode" {{ $payment->mode_type == 'test_mode' ? 'selected' : '' }}>Test Mode</option>
                                    </select>
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
$("#update_payment").submit(function() {
    event.preventDefault();
     $("#submit").prop('disabled', true);
    $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
    axios.post("{{ route('update.payment.settings') }}", new FormData($("#update_payment")[0])).then(response => {
        var data = response.data;
        $('#update_payment')[0].reset();
        if (data.success) notify(null, 'Payment Settings Updated successfully', 'top right', 'success', true);
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
