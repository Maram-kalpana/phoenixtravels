@extends('layouts.app')
@section('title', 'Tour Packages Add')
@section('css')
@endsection
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Tour Packages</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Tour Packages</li>
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
                        <h6 class="mb-0 text-uppercase">Add Tour Packages</h6>
                        <hr>
                        <form method="post" id="add_tour" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Tour Category Name</label>
                                        <select  name="tour_category_id" id="tour_category_id" class="form-control">
                                            <!--<option value="">Select Category Name</option>-->
                                            <option value="1" selected>India</option>
                                            <!--<option value="2">International</option>-->
                                            <!--<option value="3">Holiday</option>-->
                                            <!--<option value="4">Cruise</option>-->
                                        </select>
                                </div>
                                
                                <div class="col-6">
                                    <label class="form-label">Tour Name</label>
                                        <input type="text" name="tour_name" value="" id="tour_name" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="description"></textarea>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Banner Image</label>
                                        <input type="file" name="image" value="" id="image" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Meta Title</label>
                                        <input type="text" name="meta_title" value="" id="meta_title" class="form-control">
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Meta keywords</label>
                                        <input type="text" name="meta_keywords" value="" id="meta_keywords" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Meta Description</label>
                                        <input type="text" name="meta_description" value="" id="meta_description" class="form-control">
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
    CKEDITOR.replace('descriptions');
</script>
<script>
    $("#add_tour").submit(function() {
        event.preventDefault();
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
        axios.post("{{ route('add.tours') }}", new FormData($("#add_tour")[0])).then(response => {
            var data = response.data;
            if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true,
                "{{ route('list.tours') }}", 1000);
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


<script type="text/javascript">
$(document).ready(function(){      
    var i = 1;  

    $('#add').click(function(){  
        i++;  
        $('#dynamic_field').append(
            '<div class="row mb-2" id="row'+i+'">' +
                '<div class="col-10">' +
                    '<label class="form-label">Image</label>' +
                    '<input type="file" name="image[]" class="form-control" required />' +
                '</div>' +
                '<div class="col-2 d-flex align-items-end">' +
                    '<button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button>' +
                '</div>' +
            '</div>'
        );  
    });

    $(document).on('click', '.btn_remove', function(){  
        var button_id = $(this).attr("id");   
        $('#row'+button_id).remove();  
    });  
});
</script>
@stop

