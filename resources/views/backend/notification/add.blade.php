@extends('layouts.app')
@section('title', 'Add Notifications Dashboard')
@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Notifications</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add Notifications</li>
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
                            <h6 class="mb-0 text-uppercase">Add Notifications</h6>
                            <hr>
                            <form method="post" id="add_notification" action="" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" value="" id="title"
                                            class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Body</label>
                                        <textarea type="text" name="body" value="" id="body" rows="4" cols="50"
                                            class="form-control"></textarea>
                                    </div>
                                    
                                    <!-- User Selection Section -->
                                    <div class="col-12">
                                        <label class="form-label">Send To</label>
                                        <div class="mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="user_type" id="user_type_all" value="all" checked>
                                                <label class="form-check-label" for="user_type_all">All Users</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="user_type" id="user_type_single" value="single">
                                                <label class="form-check-label" for="user_type_single">Single User</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="user_type" id="user_type_multiple" value="multiple">
                                                <label class="form-check-label" for="user_type_multiple">Multiple Users</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Single User Selection -->
                                    <div class="col-12" id="single_user_section" style="display: none;">
                                        <label class="form-label">Select User</label>
                                        <select name="user_ids" id="single_user" class="form-control">
                                            <option value="">Select User</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Multiple Users Selection -->
                                    <div class="col-12" id="multiple_user_section" style="display: none;">
                                        <label class="form-label">Select Multiple Users</label>
                                        <select name="user_ids[]"  class="form-select" id="multiple-select-field" data-placeholder="Choose one Tours" multiple>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Hold Ctrl/Cmd to select multiple users</small>
                                    </div>

                                    <!-- Pagination Controls -->
                                    
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

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }
</style>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: 'Select a user',
            allowClear: true
        });
        
        $('.select2-multiple').select2({
            placeholder: 'Select multiple users',
            allowClear: true
        });

        // Handle user type change
        $('input[name="user_type"]').change(function() {
            var userType = $(this).val();
            
            $('#single_user_section, #multiple_user_section').hide();
            $('.select2, .select2-multiple').prop('disabled', true);
            
            if (userType === 'single') {
                $('#single_user_section').show();
                $('#single_user').prop('disabled', false);
            } else if (userType === 'multiple') {
                $('#multiple_user_section').show();
                $('#multiple_users').prop('disabled', false);
            }
        });

        $("#add_notification").submit(function(event) {
            event.preventDefault();
            
            // Validate user selection
            var userType = $('input[name="user_type"]:checked').val();
            if (userType === 'single' && !$('#single_user').val()) {
                notify(null, 'Please select a user', 'top right');
                return false;
            }
            if (userType === 'multiple' && !$('#multiple_users').val()) {
                notify(null, 'Please select at least one user', 'top right');
                return false;
            }

            $("#submit").prop('disabled', true);
            $("#submit").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading....');
            
            var formData = new FormData($("#add_notification")[0]);
            
            // For multiple select, ensure all selected values are included
            if (userType === 'multiple') {
                var selectedUsers = $('#multiple_users').val();
                formData.delete('user_ids[]');
                $.each(selectedUsers, function(index, value) {
                    formData.append('user_ids[]', value);
                });
            }

            axios.post("{{ route('add.notification') }}", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                var data = response.data;
                if (data.success) {
                    notify(null, 'Notification added successfully', 'top right', 'success', true,
                        "{{ route('list.notification') }}?page=" + data.data.pagination.current_page, 1000);
                } else {
                    $("#submit").prop('disabled', false);
                    $("#submit").html('Submit');
                    if (data.error && data.error.message) {
                        if (Array.isArray(data.error.message)) {
                            data.error.message.forEach(function(msg) {
                                notify(null, msg, 'top right', 'error');
                            });
                        } else {
                            notify(null, data.error.message, 'top right', 'error');
                        }
                    }
                }
            }).catch(error => {
                $("#submit").prop('disabled', false);
                $("#submit").html('Submit');
                if (error.response && error.response.data && error.response.data.error) {
                    var errors = error.response.data.error;
                    if (errors.message) {
                        if (Array.isArray(errors.message)) {
                            errors.message.forEach(function(msg) {
                                notify(null, msg, 'top right', 'error');
                            });
                        } else {
                            notify(null, errors.message, 'top right', 'error');
                        }
                    }
                } else {
                    notify(null, 'Something went wrong', 'top right', 'error');
                }
                console.log(error);
            });
        });
    });
</script>
@stop