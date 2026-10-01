@extends('layouts.app')
@section('title', 'Edit Product Dashboard')
@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Products</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Products</li>
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
                        <h6 class="mb-0 text-uppercase">Edit Products</h6>
                        <hr>
                        <form method="post" id="edit_product" action="" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Store Name</label>
                                        <select  name="store_id" id="store_id" class="form-control">
                                            <option value="">Select Store Name</option>
                                            @foreach ($store as $key => $val)
                                            <option value="{{$val->id}}" {{ $product->store_id == $val->id ? 'selected' : '' }}>{{$val->store_name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Category Name</label>
                                        <select  name="category_id" id="category_id" class="form-control">
                                            <option value="">Select Category Name</option>
                                            @foreach ($category_name as $key => $val)
                                            <option value="{{$val->id}}" {{ $product->category_id == $val->id ? 'selected' : '' }}>{{$val->category_name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" value="{{$product->title}}" id="title"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Actual Price</label>
                                    <input type="text" name="actual_price" id="actual_price" value="{{$product->actual_price}}"
                                        class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Discount Price</label>
                                    <input type="text" name="discount_price" id="discount_price" value="{{$product->discount_price}}"
                                        class="form-control">
                                </div>
                                
                                <div class="col-6">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                </div>
                                <div class="col-6">
                                        <img src="{{asset($product->image)}}" style="height: auto;width: 155px;">
                                </div>
                                 <div id="dynamic_field">
                                    <div class="row mb-2">
                                        <div class="col-10">
                                            <label class="form-label">Image</label>
                                            <input type="file" name="image[]" id="image" class="form-control">
                                        </div>
                                        <div class="col-2 d-flex align-items-end">
                                            <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="editor">{{$product->description}}</textarea>
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
    $("#edit_product").submit(function() {
        event.preventDefault();
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
        axios.post("{{ route('edit.product',$product->id) }}", new FormData($("#edit_product")[0])).then(response => {
            var data = response.data;
            if (data.success) notify(null, 'Updated added successfully', 'top right', 'success', true,
                "{{ route('list.product') }}", 1000);
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
