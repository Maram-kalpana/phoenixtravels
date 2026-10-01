@extends('layouts.app')
@section('title', 'Edit Home Page Section Dashboard')
@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Home Page Section</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Home Page Section</li>
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
                            <h6 class="mb-0 text-uppercase">Edit Home Page Section</h6>
                            <hr>
                            <form method="post" id="edit_faq" action="" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label">Select Tours</label>
                                            @php
                                                $selectedTours = explode(',', $home_page_section->tour_id ?? '');
                                            @endphp
                                            <select name="tour_id[]" class="form-select" id="multiple-select-field" data-placeholder="Choose one Tours" multiple>
                                                <option value="">Select Tours</option>
                                            
                                                @foreach($tours as $key => $val)
                                                    <option value="{{ $val->id }}"
                                                        {{ in_array($val->id, $selectedTours) ? 'selected' : '' }}>
                                                        {{ $val->tour_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Section Name</label>
                                        <input type="text" name="section_name" value="{{$home_page_section->section_name}}" id="section_name" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Section Title</label>
                                        <input type="text" name="section_title" value="{{$home_page_section->section_title}}" id="section_title" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Section Style</label>
                                            <select name="section_style" value="" id="section_style"  class="form-control">
                                                <option value="">Select Section Style</option>
                                                <option value="cruise-type-ol text-center" {{ old('section_style', $home_page_section->section_style) == 'cruise-type-ol text-center' ? 'selected' : '' }}>Oval Shape</option>
                                                <option vlaue="cruise-type-sq text-cente"  {{ old('section_style', $home_page_section->section_style) == 'cruise-type-sq text-cente' ? 'selected' : '' }}>Square Shape</option>
                                                <option value=""></option>
                                            </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Section Style Image</label>
                                            <select name="section_style_image" id="section_style_image"  class="form-control">
                                                <option value="">Select Section Style Image</option>
                                                <option value="cruise-type-lo-image" {{ old('section_style_image', $home_page_section->section_style_image) == 'cruise-type-lo-image' ? 'selected' : '' }}>Yes</option>
                                                <option value="cruise-type-lo-images" {{ old('section_style_image', $home_page_section->section_style_image) == 'cruise-type-lo-images' ? 'selected' : '' }}>NO</option>
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
        $("#edit_faq").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('edit.home.page.section',$home_page_section->id) }}", new FormData($("#edit_faq")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true,
                    "{{ route('list.home.page.section') }}", 1000);
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
