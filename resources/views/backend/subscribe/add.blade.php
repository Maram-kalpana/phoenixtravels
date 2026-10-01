@extends('layouts.app')
@section('title', 'Add Subscribe Plans Dashboard')
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Subscribe Plans</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Subscribe Plans</li>
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
                        <h6 class="mb-0 text-uppercase">Add Subscribe Plans</h6>
                        <hr>
                        <form method="post" id="add_subscribe" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                
                                <div class="col-6">
                                    <label class="form-label">Subscribe Plan Name</label>
                                    <input type="text" name="subscribe_plan_name" value="" id="subscribe_plan_name"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Subscribe Plan Type</label>
                                    <input type="text" name="subscribe_plan_type" id="subscribe_plan_type" value=""
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Discount Percentage</label>
                                    <input type="text" name="discount_percentage" value="" id="discount_percentage"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Plan Amount</label>
                                    <input type="text" name="amount" value="" id="amount"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Days</label>
                                    <input type="text" name="days" value="" id="days"
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


<script>
    $("#add_subscribe").submit(function() {
        event.preventDefault();
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
        axios.post("{{ route('add.subscribe') }}", new FormData($("#add_subscribe")[0])).then(response => {
            var data = response.data;
            if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true,
                "{{ route('list.subscribe') }}", 1000);
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
