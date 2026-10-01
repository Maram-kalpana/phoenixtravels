@extends('layouts.app')
@section('title', 'Package Highlights List')
@section('css')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
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
    .dataTables_wrapper .dataTables_filter {
        float: right;
    }
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5em;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5rem 1rem;
        margin: 0 2px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #34A7C1;
        color: white !important;
        border-color: #34A7C1;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Package Highlights</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Package Highlights List</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('domestic.package.highlights.add', [$package_id ?? '', $tours_id ?? '']) }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Add New
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="border p-3 rounded">
                        <h6 class="mb-0 text-uppercase">Package Highlights List</h6>
                        <hr>
                        
                        <div class="table-responsive">
                            <table id="highlightsTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Short Description</th>
                                        <th>Gallery</th>
                                        <th>Sightseeing</th>
                                        <th>Property</th>
                                        <th>YouTube</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    // Get package_id and tours_id from PHP variables
    var packageId = "{{ $package_id ?? '' }}";
    var toursId = "{{ $tours_id ?? '' }}";
    
    console.log('Package ID:', packageId);
    console.log('Tours ID:', toursId);
    
    // Build the URL correctly
    var dataUrl = "{{ route('domestic.package.highlights.get-data', ['package_id' => $package_id, 'tours_id' => $package_id]) }}";
    
    console.log('Data URL:', dataUrl);
    
    // Initialize DataTable
    var table = $('#highlightsTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: dataUrl,
            type: 'GET',
            dataSrc: 'data',
            error: function(xhr, error, thrown) {
                console.log('DataTable Error:', error);
                console.log('Response:', xhr.responseText);
            }
        },
        columns: [
            { data: 0, name: 'id' },
            { data: 1, name: 'image', orderable: false },
            { data: 2, name: 'short_description' },
            { data: 3, name: 'gallery_count', orderable: false },
            { data: 4, name: 'sightseeing_count', orderable: false },
            { data: 5, name: 'property_count', orderable: false },
            { data: 6, name: 'youtube_link', orderable: false },
            { data: 7, name: 'status', orderable: false },
            { data: 8, name: 'created_at' },
            { data: 9, name: 'action', orderable: false }
        ],
        order: [[0, 'asc']],
        pageLength: 10,
        responsive: true,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            emptyTable: "No data available in table",
            zeroRecords: "No matching records found"
        }
    });

    // Status update handler
    $(document).on('change', '.status-update', function() {
        var id = $(this).data('id');
        var status = $(this).is(':checked') ? 1 : 0;
        var $checkbox = $(this);
        
        $.ajax({
            url: "{{ route('domestic.package.highlights.update-status', ['package_id' => $package_id ?? '', 'tours_id' => $tours_id ?? '']) }}",
            type: 'POST',
            data: {
                id: id,
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    notify(null, response.message, 'top right', 'success');
                } else {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                    notify(null, response.message, 'top right', 'error');
                }
            },
            error: function() {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
                notify(null, 'Something went wrong', 'top right', 'error');
            }
        });
    });

    // Delete handler
    window.deleteHighlight = function(id) {
        if (confirm('Are you sure you want to delete this package highlight?')) {
            $.ajax({
                url: "{{ route('domestic.package.highlights.delete', ['package_id' => $package_id ?? '', 'tours_id' => $tours_id ?? '']) }}",
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        notify(null, response.message, 'top right', 'success');
                        table.ajax.reload();
                    } else {
                        notify(null, response.message, 'top right', 'error');
                    }
                },
                error: function() {
                    notify(null, 'Something went wrong', 'top right', 'error');
                }
            });
        }
    };

    // Notification function
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
});
</script>
@stop