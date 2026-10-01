@extends('layouts.app')
@section('title', 'Honeymoon Tour Packages Edit')
@section('css')
@endsection
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Honeymoon Tour Packages</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Honeymoon Tour Packages</li>
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
                        <h6 class="mb-0 text-uppercase">Edit Honeymoon Tour Packages</h6>
                        <hr>
                        <form method="post" id="edit_honeymoon_tours" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Tour Category Name</label>
                                        <select  name="tour_category_id" id="tour_category_id" class="form-control">
                                            <option value="">Select Category Name</option>
                                            <option value="1" {{ $tours->tour_category_id == 1 ? 'selected' : '' }}>India</option>
                                            <option value="2" {{ $tours->tour_category_id == 2 ? 'selected' : '' }}>International</option>
                                            <!--<option value="3" {{ $tours->tour_category_id == 3 ? 'selected' : '' }}>Holiday</option>-->
                                            <!--<option value="4" {{ $tours->tour_category_id == 4 ? 'selected' : '' }}>Cruise</option>-->
                                        </select>
                                </div>
                                
                                <div class="col-6">
                                    <label class="form-label">Tour Name</label>
                                        <input type="text" name="tour_name" value="{{$tours->tour_name}}" id="tour_name" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="editor">{{$tours->description}}</textarea>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Banner Image</label>
                                        <input type="file" name="image" value="" id="image" class="form-control">
                                </div>
                                    <div class="col-6">
                                        <img src="{{asset($tours->image)}}" style="height: auto;width: 155px;">
                                    </div>
                                
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Meta Title</label>
                                        <input type="text" name="meta_title" value="{{$tours->meta_title}}" id="meta_title" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Meta keywords</label>
                                        <input type="text" name="meta_keywords" value="{{$tours->meta_keywords}}" id="meta_keywords" class="form-control">
                                </div>
                                
                            </div>
                            <div class="row g-3">
                            <div class="col-6">
                                    <label class="form-label">Meta Description</label>
                                        <input type="text" name="meta_description" value="{{$tours->meta_description}}" id="meta_description" class="form-control">
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
        $("#edit_honeymoon_tours").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('edit.honeymoon.tours',$tours->id) }}", new FormData($("#edit_honeymoon_tours")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Updated added successfully', 'top right', 'success', true, "{{ route('list.honeymoon.tours') }}", 1000);
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

