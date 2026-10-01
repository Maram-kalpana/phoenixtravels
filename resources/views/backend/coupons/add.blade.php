@extends('layouts.app')
@section('title', 'Add Coupon Dashboard')
@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Coupon</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Coupon</li>
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
                            <h6 class="mb-0 text-uppercase">Add Coupon</h6>
                            <hr>
                            <form method="post" id="add_coupon" action="" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Coupon Name</label>
                                        <input type="text" name="coupons_name" value="" id="coupons_name"
                                            class="form-control">
                                    </div>
                                     <div class="col-6">
                                        <label class="form-label">Coupon Code</label>
                                        <input type="text" name="coupons_code" value="" id="coupons_code"
                                            class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Coupon Description</label>
                                        <textarea type="text" name="coupons_description" value="" id="coupons_description"
                                            class="form-control"></textarea>
                                    </div>
                                     <div class="col-6">
                                        <label class="form-label">Type</label>
                                        <select  name="type" id="type" class="form-control">
                                            <option value="P" {{ old('type') == 'P' ? 'selected' : '' }}>Percentage</option>
                                            <option value="F" {{ old('type') == 'F' ? 'selected' : '' }}>Fixed Amount</option>
                                        </select>
                                        <p style="color: red;font-size: 14px;">Note: Percentage or Fixed Amount.</p>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Select Tour</label>
                                        <select  name="tour_id" id="tour_id" class="form-control">
                                            <option value="">Select Tour</option>
                                            @foreach($tours as $key => $val)
                                            <option value="{{$val->id}}">{{$val->tour_name}}</option>
                                            @endforeach
                                        </select>
                                        <p style="color: red;font-size: 14px;">Note: Choose all Package under selected State.</p>
                                    </div>
                                     <div class="col-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" name="start_date" value="" id="start_date"
                                            class="form-control">
                                    </div>
                                     <div class="col-6">
                                        <label class="form-label">Date End</label>
                                        <input type="date" name="date_end" value="" id="date_end"
                                            class="form-control">
                                    </div>
                                     <div class="col-6">
                                        <label class="form-label">Discount</label>
                                        <input type="text" name="discount" value="" id="discount"
                                            class="form-control">
                                    </div>
                                     <div class="col-6">
                                        <label class="form-label">Total Amount</label>
                                        <input type="text" name="total_amount" value="" id="total_amount"
                                            class="form-control">
                                            <p style="color: red;font-size: 14px;">Note: The total amount that must be reached before the coupon is valid.</p>
                                    </div>
                                     <div class="col-6">
                                        <label class="form-label">Max No.of Uses</label>
                                        <input type="text" name="max_no_of_uses" value="" id="max_no_of_uses"
                                            class="form-control">
                                        <p style="color: red;font-size: 14px;">Note: The maximum number of times the coupon can be used by any customer. Leave blank for unlimited</p>
                                    </div>
                                     <div class="col-6">
                                        <label class="form-label">Uses Per Customer</label>
                                        <input type="text" name="max_no_of_customer" value="" id="max_no_of_customer"
                                            class="form-control">
                                        <p style="color: red;font-size: 14px;">Note: The maximum number of times the coupon can be used by a single customer. Leave blank for unlimited</p>
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
        $("#add_coupon").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('add.coupon') }}", new FormData($("#add_coupon")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true,
                    "{{ route('list.coupon') }}", 1000);
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
