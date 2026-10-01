@extends('layouts.app')
@section('title', 'Itinerary Add')
@section('css')
@endsection
@section('content')
<div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Itinerary</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Itinerary</li>
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
                            <h6 class="mb-0 text-uppercase">Add Itinerary</h6>
                            <hr>
                            <form method="post" id="add_itinerary" action="" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Question</label>
                                        <input type="text" name="question" value="" id="question" class="form-control">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Answer</label>
                                        <textarea type="text" name="answaer" value="" id="answaer"  class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Short Description</label>
                                        <textarea type="text" name="short_description" value="" id="short_description"  class="form-control"></textarea>
                                    </div>
                                </div>
                            <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Image</label>
                                        <input type="file" name="image" value="" id="image" class="form-control">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Image two</label>
                                        <input type="file" name="image_two" value="" id="image_two" class="form-control">
                                    </div>
                                </div>
                            <div class="row g-3">
                                <div id="dynamic_field">
                                        <div class="row mb-2">
                                            <div class="col-10">
                                                <label class="form-label">Image</label>
                                                <input type="file" name="d_image[]" id="d_image" class="form-control">
                                            </div>
                                            <div class="col-2 d-flex align-items-end">
                                                <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                                <!--<div class="row g-3">-->
                                <!--    <div class="col-12">-->
                                <!--        <label class="form-label">Image</label>-->
                                <!--        <input type="file" name="image" value="" id="image" class="form-control">-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--<div class="row g-3">-->
                                <!--    <div class="col-12">-->
                                <!--        <label class="form-label">Image two</label>-->
                                <!--        <input type="file" name="image_two" value="" id="image_two" class="form-control">-->
                                <!--    </div>-->
                                <!--</div>-->
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
        $("#add_itinerary").submit(function() {
            event.preventDefault();
            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            axios.post("{{ route('add.domestic.itinerary',[Request::segment(3),Request::segment(4)]) }}", new FormData($("#add_itinerary")[0])).then(response => {
                var data = response.data;
                if (data.success) notify(null, 'Data added successfully', 'top right', 'success', true, "{{ route('list.domestic.itinerary',[Request::segment(3),Request::segment(4)]) }}", 1000);
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
                    '<input type="file" name="d_image[]" class="form-control" required />' +
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

