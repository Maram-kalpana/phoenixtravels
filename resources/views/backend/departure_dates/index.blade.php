@extends('layouts.app')
@section('title', 'Fixed Departures Dates List')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="page-content">
    <!--start breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Fixed Departures Dates</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a></li>
                    <li class="breadcrumb-item active" aria-current="page">List Fixed Departures Dates</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="position-relative"></div>
                        <div class="ms-auto">
                            <a href="{{ route('add.departures.dates', Request::segment(3)) }}" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                                <i class="bx bxs-plus-square"></i> Add New Fixed Departures Dates
                            </a>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered student-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Tour Name</th>
                                    <th>Date</th>
                                    <th>Price</th>
                                    <th>Group Size</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table_data"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->

@endsection

@section('script')
    <script src="{{ asset('backend/dataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/dataTables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#example').DataTable({
                pageLength: 10,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [],
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('list.departures.dates.data', Request::segment(3)) }}",
                    type: 'GET',
                    error: function(xhr, error, thrown) {
                        console.log('DataTable Ajax Error:', error, thrown);
                        console.log('Response:', xhr.responseText);
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        searchable: false,
                        orderable: false,
                        width: '5%'
                    },
                    {
                        targets: 5,
                        searchable: false,
                        orderable: false,
                        width: '10%'
                    },
                    {
                        targets: 6,
                        searchable: false,
                        orderable: false,
                        width: '15%'
                    }
                ],
                order: [[0, 'desc']]
            });

            // Status update handler
            $(document).on('click', '.status-update', function() {
                let id = $(this).attr('data-id');
                let status = this.checked ? 1 : 0;
                
                $.ajax({
                    url: "{{ route('update.departures.dates.status') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id,
                        status: status,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.success) {
                            showNotification('Status Updated successfully', 'success');
                            table.ajax.reload(null, false);
                        } else {
                            showNotification('Status Updated successfully', 'success');
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function(xhr, status, error) {
                        showNotification('Error updating status', 'error');
                        console.error('Status update error:', error);
                    }
                });
            });

            // Delete button handler
            $(document).on('click', '.delete-departure', function() {
                $('#mcq-id').val($(this).attr('data-id'));
                $('#deleteModal').modal('show');
            });

            // Delete form submission
            $('#deleteModuleSubmit').on('submit', function(e) {
                e.preventDefault();
                let id = $('#mcq-id').val();
                
                $.ajax({
                    url: "{{ route('departures.dates.destroy')}}",
                    type: 'Post',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id":id
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#errosDeleteMsg').html(
                                '<div class="alert alert-success">' + data.message + '</div>'
                            );
                            table.ajax.reload(null, false);
                            setTimeout(function() {
                                $('#errosDeleteMsg').html('');
                                $('#deleteModal').modal('hide');
                            }, 1000);
                        } else {
                            $('#errosDeleteMsg').html(
                                '<div class="alert alert-danger">' + data.message + '</div>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#errosDeleteMsg').html(
                            '<div class="alert alert-danger">Error deleting record</div>'
                        );
                        console.error('Delete error:', error);
                        console.log('Status:', status);
                        console.log('Response:', xhr.responseText);
                    }
                });
            });

            // Close modal event - clear messages
            $('#deleteModal').on('hidden.bs.modal', function() {
                $('#errosDeleteMsg').html('');
                $('#mcq-id').val('');
            });
        });

        // Sweet Alert delete confirmation
        function validate(id) {
            swal({
                title: "Are you sure?",
                text: "Are you sure you want to delete this record?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'post',
                        url: "{{ route('departures.dates.destroy') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(data) {
                            if (data.success) {
                                swal("Deleted!", "Your record has been deleted.", "success")
                                    .then(() => {
                                        $('#example').DataTable().ajax.reload(null, false);
                                    });
                            } else {
                                swal("Error!", "Failed to delete record.", "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            swal("Error!", "Something went wrong.", "error");
                            console.error('Delete error:', error);
                        }
                    });
                }
            });
        }

        // Notification helper function
        function showNotification(message, type) {
            if (typeof toastr !== 'undefined') {
                if (type === 'success') {
                    toastr.success(message);
                } else {
                    toastr.error(message);
                }
            } else {
                toastr.success(message);
            }
        }
    </script>
@endsection