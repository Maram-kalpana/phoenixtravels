@extends('layouts.app')
@section('title', 'Package Highlights Edit')
@section('css')
<style>
    .onoffswitch {
        position: relative;
        width: 60px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }
    .onoffswitch-checkbox {
        display: none;
    }
    .onoffswitch-label {
        display: block;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid #999999;
        border-radius: 20px;
    }
    .onoffswitch-inner {
        display: block;
        width: 200%;
        margin-left: -100%;
        transition: margin 0.3s ease-in 0s;
    }
    .onoffswitch-inner:before, .onoffswitch-inner:after {
        display: block;
        float: left;
        width: 50%;
        height: 30px;
        padding: 0;
        line-height: 30px;
        font-size: 14px;
        color: white;
        font-family: Trebuchet, Arial, sans-serif;
        font-weight: bold;
        box-sizing: border-box;
    }
    .onoffswitch-inner:before {
        content: "ON";
        padding-left: 10px;
        background-color: #34A7C1;
        color: #FFFFFF;
    }
    .onoffswitch-inner:after {
        content: "OFF";
        padding-right: 10px;
        background-color: #EEEEEE;
        color: #999999;
        text-align: right;
    }
    .onoffswitch-switch {
        display: block;
        width: 18px;
        margin: 6px;
        background: #FFFFFF;
        position: absolute;
        top: 0;
        bottom: 0;
        right: 26px;
        border: 2px solid #999999;
        border-radius: 20px;
        transition: all 0.3s ease-in 0s;
    }
    .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
        margin-left: 0;
    }
    .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
        right: 0px;
    }
    .existing-image {
        position: relative;
        display: inline-block;
        margin: 5px;
    }
    .existing-image img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #ddd;
    }
    .existing-image .remove-image {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .existing-image .remove-image:hover {
        background: #c82333;
    }
    .image-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
</style>
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
                    <li class="breadcrumb-item active" aria-current="page">Edit Package Highlights</li>
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
                        <h6 class="mb-0 text-uppercase">Edit Package Highlights</h6>
                        <hr>
                        <form method="post" id="edit_itinerary" action="" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="highlight_id" value="{{ $highlight->id ?? '' }}">
                            
                            <h6 class="mb-0 text-uppercase">Package Highlights</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Short Description</label>
                                    <textarea type="text" name="highlights_short_description" id="short_description" class="form-control">{{ old('highlights_short_description', $highlight->short_description ?? '') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Image</label>
                                    @if(isset($highlight) && $highlight->highlights_image && file_exists(public_path('uploads/highlights/' . $highlight->highlights_image)))
                                        <div class="mb-2">
                                            <img src="{{ asset('uploads/highlights/' . $highlight->highlights_image) }}" alt="Current Image" width="150" height="150" style="object-fit:cover; border-radius: 8px; border: 2px solid #ddd;">
                                            <br>
                                            <small class="text-muted">Current Image</small>
                                        </div>
                                    @endif
                                    <input type="file" name="highlights_image" id="highlights_image" class="form-control">
                                    <small class="text-muted">Leave empty to keep current image</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Youtube Link</label>
                                    <textarea type="text" name="youtube_link" id="youtube_link" class="form-control">{{ old('youtube_link', $highlight->youtube_link ?? '') }}</textarea>
                                </div>
                            </div>
                        
                            <!-- Gallery Section -->
                            <div class="row g-3 mt-3">
                                <h6 class="mb-0 text-uppercase">Package Highlights Gallery</h6>
                                
                                @if(isset($highlight) && $highlight->gallery->count() > 0)
                                    <div class="col-12">
                                        <label class="form-label">Existing Images</label>
                                        <div class="image-container">
                                            @foreach($highlight->gallery as $gallery)
                                                <div class="existing-image" id="gallery_div_{{ $gallery->id }}">
                                                    <img src="{{ asset('uploads/highlights/gallery/' . $gallery->image) }}" alt="Gallery Image">
                                                    <button type="button" class="remove-image" onclick="removeGalleryImage({{ $gallery->id }})">×</button>
                                                    <input type="hidden" name="existing_gallery[]" value="{{ $gallery->id }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <div id="gallery_dynamic_field">
                                    <div class="row gallery_row mb-2" id="gallery_row_1">
                                        <div class="col-10">
                                            <label class="form-label">Add New Image</label>
                                            <input type="file" name="gallery_image[]" class="form-control">
                                        </div>
                                        <div class="col-2 d-flex align-items-end">
                                            <button type="button" name="gallery_add" id="gallery_add" class="btn btn-success">Add More</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sightseeing Section -->
                            <div class="row g-3 mt-3">
                                <h6 class="mb-0 text-uppercase">Activities and Sightseeing</h6>
                                
                                @if(isset($highlight) && $highlight->sightseeing->count() > 0)
                                    <div class="col-12">
                                        <label class="form-label">Existing Images</label>
                                        <div class="image-container">
                                            @foreach($highlight->sightseeing as $sightseeing)
                                                <div class="existing-image" id="sightseeing_div_{{ $sightseeing->id }}">
                                                    <img src="{{ asset('uploads/highlights/sightseeing/' . $sightseeing->image) }}" alt="Sightseeing Image">
                                                    <button type="button" class="remove-image" onclick="removeSightseeingImage({{ $sightseeing->id }})">×</button>
                                                    <input type="hidden" name="existing_sightseeing[]" value="{{ $sightseeing->id }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <div id="sightseeing_dynamic_field">
                                    <div class="row sightseeing_row mb-2" id="sightseeing_row_1">
                                        <div class="col-10">
                                            <label class="form-label">Add New Image</label>
                                            <input type="file" name="sightseeing_image[]" class="form-control">
                                        </div>
                                        <div class="col-2 d-flex align-items-end">
                                            <button type="button" name="sightseeing_add" id="sightseeing_add" class="btn btn-success">Add More</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Property Photos Section -->
                            <div class="row g-3 mt-3">
                                <h6 class="mb-0 text-uppercase">Property Photos</h6>
                                
                                @if(isset($highlight) && $highlight->propertyPhotos->count() > 0)
                                    <div class="col-12">
                                        <label class="form-label">Existing Images</label>
                                        <div class="image-container">
                                            @foreach($highlight->propertyPhotos as $property)
                                                <div class="existing-image" id="property_div_{{ $property->id }}">
                                                    <img src="{{ asset('uploads/highlights/property/' . $property->image) }}" alt="Property Image">
                                                    <button type="button" class="remove-image" onclick="removePropertyImage({{ $property->id }})">×</button>
                                                    <input type="hidden" name="existing_property[]" value="{{ $property->id }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <div id="property_dynamic_field">
                                    <div class="row property_row mb-2" id="property_row_1">
                                        <div class="col-10">
                                            <label class="form-label">Add New Image</label>
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
                                        <button type="submit" id="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('international.package.highlights.list', [request()->get('package_id'), request()->get('tours_id')]) }}" class="btn btn-secondary">Cancel</a>
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
    // Remove gallery image
    function removeGalleryImage(id) {
        if (confirm('Are you sure you want to remove this image?')) {
            $('#gallery_div_' + id).remove();
            // Add hidden input to mark for deletion
            $('<input>').attr({
                type: 'hidden',
                name: 'delete_gallery[]',
                value: id
            }).appendTo('#edit_itinerary');
        }
    }
    
    // Remove sightseeing image
    function removeSightseeingImage(id) {
        if (confirm('Are you sure you want to remove this image?')) {
            $('#sightseeing_div_' + id).remove();
            $('<input>').attr({
                type: 'hidden',
                name: 'delete_sightseeing[]',
                value: id
            }).appendTo('#edit_itinerary');
        }
    }
    
    // Remove property image
    function removePropertyImage(id) {
        if (confirm('Are you sure you want to remove this image?')) {
            $('#property_div_' + id).remove();
            $('<input>').attr({
                type: 'hidden',
                name: 'delete_property[]',
                value: id
            }).appendTo('#edit_itinerary');
        }
    }
</script>

<script>
    $("#edit_itinerary").submit(function(event) {
        event.preventDefault();
        $("#submit").prop('disabled', true);
        $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating....');
        
        var formData = new FormData($("#edit_itinerary")[0]);
        
        axios.post("{{ route('international.package.highlights.edit', ['package_id' => request()->get('package_id'), 'tours_id' => request()->get('tours_id'), 'id' => $highlight->id ?? '']) }}", formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).then(response => {
            var data = response.data;
            if (data.success) {
                notify(null, 'Data updated successfully', 'top right', 'success', true, "{{ route('international.package.highlights.list', [request()->get('package_id'), request()->get('tours_id')]) }}", 1000);
            } else {
                $("#submit").prop('disabled', false);
                $("#submit").html('Update');
                if (data.message && data.message.error) {
                    for (var a in data.message.error) {
                        notify(null, data.message.error[a], 'bottom left');
                    }
                }
            }
        }).catch(error => {
            $("#submit").prop('disabled', false);
            $("#submit").html('Update');
            notify(null, 'Something went wrong', 'top right');
            console.log(error);
        });
    });
</script>

<!-- Gallery Add More -->
<script type="text/javascript">
$(document).ready(function(){      
    var gallery_i = 1;  
    $('#gallery_add').click(function(){  
        gallery_i++;  
        $('#gallery_dynamic_field').append(
            '<div class="row gallery_row mb-2" id="gallery_row_'+gallery_i+'">' +
                '<div class="col-10">' +
                    '<label class="form-label">Image</label>' +
                    '<input type="file" name="gallery_image[]" class="form-control" />' +
                '</div>' +
                '<div class="col-2 d-flex align-items-end">' +
                    '<button type="button" name="gallery_remove" id="'+gallery_i+'" class="btn btn-danger gallery_btn_remove">×</button>' +
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

<!-- Sightseeing Add More -->
<script type="text/javascript">
$(document).ready(function(){      
    var sightseeing_i = 1;  
    $('#sightseeing_add').click(function(){  
        sightseeing_i++;  
        $('#sightseeing_dynamic_field').append(
            '<div class="row sightseeing_row mb-2" id="sightseeing_row_'+sightseeing_i+'">' +
                '<div class="col-10">' +
                    '<label class="form-label">Image</label>' +
                    '<input type="file" name="sightseeing_image[]" class="form-control" />' +
                '</div>' +
                '<div class="col-2 d-flex align-items-end">' +
                    '<button type="button" name="sightseeing_remove" id="'+sightseeing_i+'" class="btn btn-danger sightseeing_btn_remove">×</button>' +
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

<!-- Property Add More -->
<script type="text/javascript">
$(document).ready(function(){      
    var property_i = 1;  
    $('#property_add').click(function(){  
        property_i++;  
        $('#property_dynamic_field').append(
            '<div class="row property_row mb-2" id="property_row_'+property_i+'">' +
                '<div class="col-10">' +
                    '<label class="form-label">Image</label>' +
                    '<input type="file" name="d_image[]" class="form-control" />' +
                '</div>' +
                '<div class="col-2 d-flex align-items-end">' +
                    '<button type="button" name="property_remove" id="'+property_i+'" class="btn btn-danger property_btn_remove">×</button>' +
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

<!-- Notification function -->
<script>
    window.notify = function(title, message, position, type, redirect = false, url = '', timeout = 1000) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message, title, {
                positionClass: 'toast-' + (position || 'top-right'),
                timeOut: timeout || 3000,
                extendedTimeOut: timeout || 3000
            });
        } else {
            alert(message);
        }
        
        if (redirect && url) {
            setTimeout(function() {
                window.location.href = url;
            }, timeout || 1000);
        }
    };
</script>
@stop