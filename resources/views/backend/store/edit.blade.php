@extends('layouts.app')
@section('title', 'Edit Store Dashboard')
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Store</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Store</li>
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
                        <h6 class="mb-0 text-uppercase">Edit Store</h6>
                        <hr>
                        <form method="post" id="edit_store" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Store Name</label>
                                        <input type="text" name="store_name" value="{{$store->store_name}}" id="store_name" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Owner Name</label>
                                       <input type="text" name="owner_name" value="{{$store->owner_name}}" id="owner_name" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Email</label>
                                        <input type="text" name="email" value="{{$store->email}}" id="email"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" value="{{$store->phone}}" id="phone"
                                        class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="image" id="image" value=""
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" value="{{$store->location}}" id="location"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Full Address</label>
                                    <input type="text" name="full_address" value="{{$store->full_address}}" id="full_address"
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
    $("#edit_store").submit(function() {
        event.preventDefault();
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
        axios.post("{{ route('edit.store',$store->id) }}", new FormData($("#edit_store")[0])).then(response => {
            var data = response.data;
            if (data.success) notify(null, 'Updated added successfully', 'top right', 'success', true,
                "{{ route('list.store') }}", 1000);
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

