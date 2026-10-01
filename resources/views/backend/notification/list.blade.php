@extends('layouts.app')
@section('title', 'Notifications List')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection
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
                        <li class="breadcrumb-item active" aria-current="page">List Notifications</li>
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
                            <div class="position-relative">
                            </div>
                            <div class="ms-auto"><a href="{{ route('add.notification') }}"
                                    class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New
                                    Notifications</a></div>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered student-table"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="table_data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script src="{{ asset('backend/dataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('backend/dataTables/dataTables.bootstrap4.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.student-table').DataTable({
                    pageLength: 10,
                    responsive: true,
                    dom: '<"html5buttons"B>lTfgitp',
                    buttons: [],
                    "serverSide": true,
                    "ajax": {
                        url: "{{ route('list.notification.data') }}",
                        dataFilter: function(data) {
                            var json = jQuery.parseJSON(data);
                            json.recordsTotal = json.recordsTotal;
                            json.recordsFiltered = json.recordsFiltered;
                            json.data = json.data;
                            return JSON.stringify(json); // return JSON string
                        }
                    },
                    'columnDefs': [{
                            'targets': 0,
                            'searchable': false,
                            'orderable': false,
                        },
                        {
                            "targets": 1,
                            "question": "question",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 2,
                            "status": "status",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 3,
                            "action": "action",
                            'searchable': false,
                            'orderable': false
                        },

                    ],
                    'order': [
                        [1, 'desc']
                    ],
                });
                $('body').on('click', '.status-update', function() {
                    let id = $(this).attr('data-id')
                    let status = this.checked ? 1 : 0;
                    $.ajax({
                        url: "{{ route('update.notification.status') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                            status: status,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            notify(null, 'Status Updated successfully', 'top right', 'success',
                                false, "{{ route('list.notification') }}", 1000);
                        }
                    });
                    $('body').on('click', '.deleteMCQ', function() {
                        $('#mcq-id').val($(this).attr('data-id'))
                        $('#deleteModal').modal('show')
                    })
                    $('#deleteModuleSubmit').on('submit', function(e) {
                        e.preventDefault()
                        let fd = new FormData(this)
                        $.ajax({
                            url: "",
                            type: 'POST',
                            dataType: 'json',
                            data: fd,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                if (data.status) {
                                    $('#errosDeleteMsg').html(
                                        `<div class="alert alert-success">${data.message}</div>`
                                        )
                                    $('.mcq-table').DataTable().ajax.reload(null, false);
                                    setTimeout(() => {
                                        $('#errosDeleteMsg').html(``)
                                        $('#deleteModal').modal('hide')
                                    }, 1000);
                                } else {
                                    $('#errosDeleteMsg').html(
                                        `<div class="alert alert-danger">${data.message}</div>`
                                        )
                                }
                            }
                        });
                    })
                })
            });
        </script>
        
<script>
function validate(id) {
    swal({
        title: "Are you sure?",
        text: "Are you sure you want to delete this record",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Delete it!",
        closeOnConfirm: false
    }, function() {
        $.ajax({
                type:'DELETE',
                url: "{{route('notification.destroy')}}" + '/' + id,
                data:{"_token": "{{ csrf_token() }}",},
                success: function (data) {
                    var jso = JSON.stringify(data); 
                    if (data.success){
                        swal({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            type: "success",
                        });
                        location.reload();
                            }
                    }         
            });
    });
}

</script>
    @stop

