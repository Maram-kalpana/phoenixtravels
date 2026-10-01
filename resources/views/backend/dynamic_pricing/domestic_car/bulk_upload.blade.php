@extends('layouts.app')
@section('title', 'Bulk Upload Car Prices')
@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dynamic Price</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Bulk Upload</li>
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
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 text-uppercase">Bulk Upload Car Prices</h6>
                                <a href="{{ route('car.bulk.upload.template') }}" class="btn btn-sm btn-success">
                                    <ion-icon name="download-outline"></ion-icon> Download Template
                                </a>
                            </div>
                            <hr>
                            
                            <form method="post" id="bulk_upload_form" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Select State <span class="text-danger">*</span></label>
                                        <select name="state_id" id="state_id" class="form-control" required>
                                            <option value="">Select State Name</option>
                                            @foreach($states as $state)
                                            <option value="{{$state->id}}">{{$state->tour_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row g-3 mt-3">
                                    <div class="col-12">
                                        <label class="form-label">Upload CSV File <span class="text-danger">*</span></label>
                                        <div class="upload-area" id="upload_area">
                                            <div class="upload-content">
                                                <ion-icon name="cloud-upload-outline" class="upload-icon"></ion-icon>
                                                <h5>Drag & Drop CSV File Here</h5>
                                                <p class="text-muted">or click to browse</p>
                                                <input type="file" name="csv_file" id="csv_file" accept=".csv" class="upload-input" required>
                                                <div id="file_info" class="file-info" style="display: none;">
                                                    <ion-icon name="document-text-outline" class="file-icon"></ion-icon>
                                                    <span class="file-name"></span>
                                                    <span class="file-size"></span>
                                                    <button type="button" class="btn btn-sm btn-danger remove-file">
                                                        <ion-icon name="close-outline"></ion-icon>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <ion-icon name="information-circle-outline"></ion-icon> 
                                                Supported format: .csv (Max size: 5MB)
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <ion-icon name="file-tray-full-outline"></ion-icon> 
                                                Required columns: Pax, Car Name, Car P/D, Driver Batta
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row g-3 mt-3">
                                    <div class="col-12">
                                        <div class="progress" style="display: none; height: 30px;" id="progress_container">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                                 id="progress_bar" 
                                                 role="progressbar" 
                                                 style="width: 0%;" 
                                                 aria-valuenow="0" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                <span id="progress_text">0%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row g-3 mt-3">
                                    <div class="col-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" id="submit_btn" class="btn btn-primary">
                                                <ion-icon name="cloud-upload-outline"></ion-icon> Upload
                                            </button>
                                            <button type="reset" class="btn btn-secondary" id="reset_btn">
                                                <ion-icon name="refresh-outline"></ion-icon> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                            <!-- Result Section -->
                            <div id="result_container" style="display: none; margin-top: 20px;">
                                <hr>
                                <div id="result_message"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // File upload handling
        const uploadArea = $('#upload_area');
        const fileInput = $('#csv_file');
        const fileInfo = $('#file_info');
        const fileName = $('.file-name');
        const fileSize = $('.file-size');
        const removeFileBtn = $('.remove-file');
        
        // Click to browse
        uploadArea.on('click', function(e) {
            if (!$(e.target).closest('#file_info').length) {
                fileInput.click();
            }
        });
        
        // File selection
        fileInput.on('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const validTypes = ['text/csv', 'application/vnd.ms-excel'];
                const maxSize = 5 * 1024 * 1024; // 5MB
                
                // Validate file type
                if (!validTypes.includes(file.type) && !file.name.endsWith('.csv')) {
                    notify('Please upload a valid CSV file.', 'error');
                    this.value = '';
                    return;
                }
                
                // Validate file size
                if (file.size > maxSize) {
                    notify('File size exceeds 5MB limit.', 'error');
                    this.value = '';
                    return;
                }
                
                // Display file info
                fileName.text(file.name);
                fileSize.text((file.size / 1024).toFixed(2) + ' KB');
                fileInfo.show();
                uploadArea.addClass('file-selected');
            }
        });
        
        // Remove file
        removeFileBtn.on('click', function(e) {
            e.stopPropagation();
            fileInput.val('');
            fileInfo.hide();
            uploadArea.removeClass('file-selected');
        });
        
        // Reset button
        $('#reset_btn').on('click', function() {
            fileInput.val('');
            fileInfo.hide();
            uploadArea.removeClass('file-selected');
            $('#state_id').val('');
            $('#result_container').hide();
            $('#result_message').html('');
        });
        
        // Drag and drop
        uploadArea.on('dragover', function(e) {
            e.preventDefault();
            uploadArea.addClass('drag-over');
        });
        
        uploadArea.on('dragleave', function(e) {
            e.preventDefault();
            uploadArea.removeClass('drag-over');
        });
        
        uploadArea.on('drop', function(e) {
            e.preventDefault();
            uploadArea.removeClass('drag-over');
            
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                fileInput.prop('files', files);
                fileInput.trigger('change');
            }
        });
        
        // Form submission
        $('#bulk_upload_form').submit(function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            var submitBtn = $('#submit_btn');
            var progressContainer = $('#progress_container');
            var progressBar = $('#progress_bar');
            var progressText = $('#progress_text');
            var resultContainer = $('#result_container');
            var resultMessage = $('#result_message');
            var csvFile = $('#csv_file')[0].files[0];
            
            // Check if file is selected
            if (!csvFile) {
                notify('Please select a CSV file to upload.', 'error');
                return;
            }
            
            // Check if state is selected
            if (!$('#state_id').val()) {
                notify('Please select a state.', 'error');
                return;
            }
            
            // Reset results
            resultContainer.hide();
            resultMessage.html('');
            
            // Show progress
            progressContainer.show();
            progressBar.css('width', '0%');
            progressText.text('0%');
            submitBtn.prop('disabled', true);
            submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...');
            
            // Simulate progress
            var progress = 0;
            var interval = setInterval(function() {
                progress += Math.floor(Math.random() * 10) + 1;
                if (progress >= 95) {
                    progress = 95;
                    clearInterval(interval);
                }
                progressBar.css('width', progress + '%');
                progressText.text(progress + '%');
            }, 300);
            
            // AJAX request
            $.ajax({
                url: "{{ route('car.bulk.upload.process') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    clearInterval(interval);
                    
                    progressBar.css('width', '100%');
                    progressText.text('100%');
                    
                    setTimeout(function() {
                        progressContainer.hide();
                        submitBtn.prop('disabled', false);
                        submitBtn.html('<ion-icon name="cloud-upload-outline"></ion-icon> Upload');
                        
                        resultContainer.show();
                        
                        // Check if response is successful
                        if (response.success) {
                            // Success message - FIXED
                            var successMessage = '';
                            if (response.error && response.error.success) {
                                if (Array.isArray(response.error.success)) {
                                    successMessage = response.error.success.join('<br>');
                                } else {
                                    successMessage = response.error.success;
                                }
                            } else {
                                successMessage = 'Data uploaded successfully!';
                            }
                            
                            resultMessage.html('<div class="alert alert-success">' + 
                                '<h5><ion-icon name="checkmark-circle-outline"></ion-icon> Success!</h5>' +
                                '<p>' + successMessage + '</p>' +
                                '</div>');
                            
                            // Reset form on success
                            fileInput.val('');
                            fileInfo.hide();
                            uploadArea.removeClass('file-selected');
                            $('#state_id').val('');
                        } else {
                            // Error message - FIXED
                            var errorHtml = '<div class="alert alert-danger">' +
                                '<h5><ion-icon name="close-circle-outline"></ion-icon> Error!</h5>';
                            
                            if (response.error) {
                                if (response.error.error) {
                                    // Check if it's an array of errors
                                    if (Array.isArray(response.error.error)) {
                                        errorHtml += '<ul>';
                                        response.error.error.forEach(function(error) {
                                            errorHtml += '<li>' + error + '</li>';
                                        });
                                        errorHtml += '</ul>';
                                    } 
                                    // Check if it's an object (validator errors)
                                    else if (typeof response.error.error === 'object') {
                                        errorHtml += '<ul>';
                                        for (var key in response.error.error) {
                                            if (response.error.error.hasOwnProperty(key)) {
                                                if (Array.isArray(response.error.error[key])) {
                                                    errorHtml += '<li>' + response.error.error[key][0] + '</li>';
                                                } else {
                                                    errorHtml += '<li>' + response.error.error[key] + '</li>';
                                                }
                                            }
                                        }
                                        errorHtml += '</ul>';
                                    } 
                                    // String error
                                    else {
                                        errorHtml += '<p>' + response.error.error + '</p>';
                                    }
                                } else if (typeof response.error === 'string') {
                                    errorHtml += '<p>' + response.error + '</p>';
                                } else {
                                    errorHtml += '<p>Something went wrong. Please try again.</p>';
                                }
                            } else {
                                errorHtml += '<p>Something went wrong. Please try again.</p>';
                            }
                            
                            errorHtml += '</div>';
                            resultMessage.html(errorHtml);
                        }
                    }, 500);
                },
                error: function(xhr, status, error) {
                    clearInterval(interval);
                    
                    progressContainer.hide();
                    submitBtn.prop('disabled', false);
                    submitBtn.html('<ion-icon name="cloud-upload-outline"></ion-icon> Upload');
                    
                    resultContainer.show();
                    
                    // Handle different error responses
                    var errorMessage = 'Something went wrong. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        if (xhr.responseJSON.error.error) {
                            if (Array.isArray(xhr.responseJSON.error.error)) {
                                errorMessage = xhr.responseJSON.error.error.join('<br>');
                            } else if (typeof xhr.responseJSON.error.error === 'object') {
                                var errors = [];
                                for (var key in xhr.responseJSON.error.error) {
                                    if (xhr.responseJSON.error.error.hasOwnProperty(key)) {
                                        errors.push(xhr.responseJSON.error.error[key][0]);
                                    }
                                }
                                errorMessage = errors.join('<br>');
                            } else {
                                errorMessage = xhr.responseJSON.error.error;
                            }
                        } else {
                            errorMessage = xhr.responseJSON.error;
                        }
                    }
                    
                    resultMessage.html('<div class="alert alert-danger">' +
                        '<h5><ion-icon name="close-circle-outline"></ion-icon> Error!</h5>' +
                        '<p>' + errorMessage + '</p>' +
                        '<p><small>Status: ' + xhr.status + ' - ' + xhr.statusText + '</small></p>' +
                        '</div>');
                    
                    console.log('Error:', xhr.responseText);
                }
            });
        });
    });
</script>


<style>
    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
        position: relative;
    }
    
    .upload-area:hover {
        border-color: #0d6efd;
        background: #e7f1ff;
    }
    
    .upload-area.drag-over {
        border-color: #0d6efd;
        background: #e7f1ff;
        transform: scale(1.02);
        box-shadow: 0 0 20px rgba(13, 110, 253, 0.2);
    }
    
    .upload-area.file-selected {
        border-color: #28a745;
        background: #f0fff4;
    }
    
    .upload-icon {
        font-size: 48px;
        color: #6c757d;
        margin-bottom: 15px;
        display: block;
    }
    
    .upload-area.file-selected .upload-icon {
        color: #28a745;
    }
    
    .upload-content h5 {
        color: #495057;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .upload-content p {
        margin-bottom: 0;
        font-size: 14px;
        color: #6c757d;
    }
    
    .file-info {
        margin-top: 15px;
        padding: 12px 20px;
        background: #e9ecef;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid #dee2e6;
    }
    
    .file-info .file-icon {
        font-size: 24px;
        color: #0d6efd;
    }
    
    .file-info .file-name {
        font-weight: 500;
        color: #0d6efd;
        flex: 1;
        word-break: break-all;
    }
    
    .file-info .file-size {
        color: #6c757d;
        font-size: 12px;
        white-space: nowrap;
    }
    
    .file-info .remove-file {
        margin-left: auto;
        padding: 2px 8px;
        font-size: 16px;
        line-height: 1;
    }
    
    .upload-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }
    
    .progress {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
        background: #e9ecef;
    }
    
    .progress-bar {
        transition: width 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        background: linear-gradient(45deg, #0d6efd, #0dcaf0);
        color: #fff;
    }
    
    #result_container {
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .alert ul {
        margin-bottom: 0;
        padding-left: 20px;
    }
    
    .alert h5 {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .alert h5 ion-icon {
        font-size: 20px;
    }
    
    .d-flex.gap-2 {
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    .mt-2 {
        margin-top: 0.5rem !important;
    }
    
    .mt-3 {
        margin-top: 1rem !important;
    }
    
    @media (max-width: 576px) {
        .upload-area {
            padding: 20px 15px;
        }
        
        .upload-icon {
            font-size: 32px;
        }
        
        .d-flex.gap-2 {
            flex-direction: column;
        }
        
        .d-flex.gap-2 .btn {
            width: 100%;
        }
        
        .file-info {
            flex-wrap: wrap;
        }
        
        .file-info .file-name {
            width: 100%;
            font-size: 14px;
        }
        
        .file-info .file-size {
            font-size: 11px;
        }
    }
</style>
@stop