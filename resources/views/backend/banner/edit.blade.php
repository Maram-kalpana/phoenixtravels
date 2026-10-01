@extends('layouts.app')
@section('title', 'Edit Banner Dashboard')
@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Banner</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Banner</li>
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
                            <h6 class="mb-0 text-uppercase">Edit Banner</h6>
                            <hr>
                            <form method="post" id="edit_banner" action="" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" id="title" value="{{$banner->title}}" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Sub Title</label>
                                        <input type="text" name="sub_title" id="sub_title" value="{{$banner->sub_title}}"
                                            class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Banner Image</label>
                                        <input type="file" name="image" id="image" value=""
                                            class="form-control">
                                    </div>
                                    <div class="col-6"></div>
                                    <div class="col-6">
                                        <img src="{{asset($banner->image)}}" style="height: auto;width: 155px;">
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
        $("#edit_banner").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('edit.banner',$banner->id) }}", new FormData($("#edit_banner")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Updated added successfully', 'top right', 'success', true, "{{ route('list.banner') }}", 1000);
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
