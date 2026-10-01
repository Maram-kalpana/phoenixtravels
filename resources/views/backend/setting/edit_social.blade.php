@extends('layouts.app')
@section('title', 'Edit Social Settings Dashboard')
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Social</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit social</li>
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
                        <h6 class="mb-0 text-uppercase">Edit social</h6>
                        <hr>
                        <form method="post" id="edit_social" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Social Name</label>
                                    <input type="text" name="name" value="{{$social->name}}" id="name"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Social Code</label>
                                    <input type="text" name="code" value="{{$social->code}}" id="code" value=""
                                        class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Social Link</label>
                                    <input type="text" name="link" value="{{$social->link}}" id="link" value=""
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
$("#edit_social").submit(function() {
    event.preventDefault();
     $("#submit").prop('disabled', true);
    $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
    axios.post("{{ route('edit.social.status',$social->id) }}", new FormData($("#edit_social")[0])).then(response => {
        var data = response.data;
        $('#edit_social')[0].reset();
        if (data.success) notify(null, 'Updated added successfully', 'top right', 'success', true, "{{ route('social.settings') }}", 1000);
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
