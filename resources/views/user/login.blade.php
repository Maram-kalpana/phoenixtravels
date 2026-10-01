@extends('layouts.web')
@section('title', 'Login | Phoenix Tours & Travels')

@section('content')

<section id="common_banner" class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="fw-bold" style="color: #fff;">Login</h2>
        <p class="text-muted mb-0" style="color: #fff !important;">Access your account to manage bookings</p>
    </div>
</section>

<section class="section_padding py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold">Welcome Back</h4>
                            <p class="text-muted small">Login using your mobile number</p>
                        </div>

                        <form action="" method="post" id="login_submit" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" class="form-control" placeholder="Enter mobile number" name="phone" id="phone">
                                </div>
                                <input type="hidden" class="form-control" name="referral_by" id="referral_by" value="{{ request()->get('ref') }}">
                            </div>
                            <div class="d-grid">
                                <button type="submit" id="submit" class="btn btn-primary btn-lg">
                                    Send OTP
                                </button>
                            </div>
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
        $("#login_submit").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....'
            );
            axios.post("{{ route('user.login') }}", new FormData($("#login_submit")[0])).then(
                response => {
                    var data = response.data;
                    if (data.success) notify(null, 'OTP has been sent on Your Mobile Number.', 'top right', 'success',
                        true,"{{ route('user.otp') }}", 1000);
                    else {
                        $("#submit").prop('disabled', false);
                        $("#submit").html('Login <i class="bi bi-arrow-right"></i>');
                        for (var a in data['error']['message']) {
                            notify(null, data['error']['message'][a][0], 'botton left');
                            if (a == 'success' | a == 'error') notify(null, data['error']['message'][a][0],
                                'botton left');
                        }
                    }
                }).catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('Login <i class="bi bi-arrow-right"></i>');
                notify(null, 'Something went wrong', 'top right');
                console.log(error);
            });
        });
    </script>
@stop
