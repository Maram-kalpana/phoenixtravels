@extends('layouts.app')
@section('title', 'Package Highlights Add')
@section('css')
@endsection
@section('content')
<div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Package Highlights</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Package Highlights</li>
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
                            <h6 class="mb-0 text-uppercase">Add Package Highlights</h6>
                            <hr>
                            <form method="post" id="add_itinerary" action="" enctype="multipart/form-data">
                               <h6 class="mb-0 text-uppercase">Package Highlights</h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Short Description</label>
                                        <textarea type="text" name="highlights_short_description" value="" id="short_description"  class="form-control"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Image</label>
                                        <input type="file" name="highlights_image" value="" id="highlights_image"  class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">youtube link</label>
                                        <textarea type="text" name="youtube_link" value="" id="youtube_link"  class="form-control"></textarea>
                                    </div>
                                </div>
                           
                            <div class="row g-3">
                                <h6 class="mb-0 text-uppercase">Package Highlights Gallery</h6>
                                <div id="gallery_dynamic_field">
                                        <div class="row gallery_row mb-2" id="gallery_row_1">
                                            <div class="col-10">
                                                <label class="form-label">Image </label>
                                                <input type="file" name="gallery_image[]" class="form-control">
                                            </div>
                                            <div class="col-2 d-flex align-items-end">
                                                <button type="button" name="gallery_add" id="gallery_add" class="btn btn-success">Add More</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            
                            <div class="row g-3">
                                <h6 class="mb-0 text-uppercase">Activities and Sightseeing</h6>
                                <div id="sightseeing_dynamic_field">
                                        <div class="row sightseeing_row mb-2" id="sightseeing_row_1">
                                            <div class="col-10">
                                                <label class="form-label">Image </label>
                                                <input type="file" name="sightseeing_image[]" class="form-control">
                                            </div>
                                            <div class="col-2 d-flex align-items-end">
                                                <button type="button" name="sightseeing_add" id="sightseeing_add" class="btn btn-success">Add More</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                             <div class="row g-3">
                                <h6 class="mb-0 text-uppercase">Property photos</h6>
                                <div id="property_dynamic_field">
                                        <div class="row property_row mb-2" id="property_row_1">
                                            <div class="col-10">
                                                <label class="form-label">Image </label>
                                                <input type="file" name="d_image[]" class="form-control">
                                            </div>
                                            <div class="col-2 d-flex align-items-end">
                                                <button type="button" name="property_add" id="property_add" class="btn btn-success">Add More</button>
                                            </div>
                                        </div>
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
    $("#add_itinerary").submit(function(event) {
        event.preventDefault(); // Make sure this is included
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
        
        var formData = new FormData($("#add_itinerary")[0]);
        
        axios.post("{{ route('international.package.highlights.add',[Request::segment(3),Request::segment(4)]) }}", formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).then(response => {
            var data = response.data;
            if (data.success) {
                notify(null, 'Data added successfully', 'top right', 'success', true, "{{ route('international.package.highlights.list',[Request::segment(3),Request::segment(4)]) }}", 1000);
            } else {
                $("#submit").prop('disabled', false);
                $("#submit").html('Submit');
                if (data.message && data.message.error) {
                    for (var a in data.message.error) {
                        notify(null, data.message.error[a], 'bottom left');
                    }
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
    var gallery_i = 1;  
    $('#gallery_add').click(function(){  
        gallery_i++;  
        $('#gallery_dynamic_field').append(
            '<div class="row gallery_row mb-2" id="gallery_row_'+gallery_i+'">' +
                '<div class="col-10">' +
                    '<label class="form-label">Image</label>' +
                    '<input type="file" name="gallery_image[]" class="form-control" required />' +
                '</div>' +
                '<div class="col-2 d-flex align-items-end">' +
                    '<button type="button" name="gallery_remove" id="'+gallery_i+'" class="btn btn-danger gallery_btn_remove">X</button>' +
                '</div>' +
            '</div>'
        );  
    });
    
    $(document).on('click', '.gallery_btn_remove', function(){  
        var button_id = $(this).attr("id");   
        $('#gallery_row_'+button_id).remove();  
    });  
});
</script>

<script type="text/javascript">
$(document).ready(function(){      
    var sightseeing_i = 1;  
    $('#sightseeing_add').click(function(){  
        sightseeing_i++;  
        $('#sightseeing_dynamic_field').append(
            '<div class="row sightseeing_row mb-2" id="sightseeing_row_'+sightseeing_i+'">' +
                '<div class="col-10">' +
                    '<label class="form-label">Image</label>' +
                    '<input type="file" name="sightseeing_image[]" class="form-control" required />' +
                '</div>' +
                '<div class="col-2 d-flex align-items-end">' +
                    '<button type="button" name="sightseeing_remove" id="'+sightseeing_i+'" class="btn btn-danger sightseeing_btn_remove">X</button>' +
                '</div>' +
            '</div>'
        );  
    });
    
    $(document).on('click', '.sightseeing_btn_remove', function(){  
        var button_id = $(this).attr("id");   
        $('#sightseeing_row_'+button_id).remove();  
    });  
});
</script>

<script type="text/javascript">
$(document).ready(function(){      
    var property_i = 1;  
    $('#property_add').click(function(){  
        property_i++;  
        $('#property_dynamic_field').append(
            '<div class="row property_row mb-2" id="property_row_'+property_i+'">' +
                '<div class="col-10">' +
                    '<label class="form-label">Image</label>' +
                    '<input type="file" name="d_image[]" class="form-control" required />' +
                '</div>' +
                '<div class="col-2 d-flex align-items-end">' +
                    '<button type="button" name="property_remove" id="'+property_i+'" class="btn btn-danger property_btn_remove">X</button>' +
                '</div>' +
            '</div>'
        );  
    });
    
    $(document).on('click', '.property_btn_remove', function(){  
        var button_id = $(this).attr("id");   
        $('#property_row_'+button_id).remove();  
    });  
});
</script>
@stop