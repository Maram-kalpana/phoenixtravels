@extends('layouts.app')
@section('title', 'Coupons List')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Coupons</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List Coupons</li>
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
                            <div class="ms-auto"><a href="{{ route('add.coupon') }}"
                                    class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New
                                    Coupon</a></div>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered coupons-table"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Coupon Name</th>
                                        <th>Coupon Code</th>
                                        <th>Start Date</th>
                                        <th>Expiry Date</th>
                                        <th>Max. No of Users</th>
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
                $('.coupons-table').DataTable({
                    pageLength: 10,
                    responsive: true,
                    dom: '<"html5buttons"B>lTfgitp',
                    buttons: [],
                    "serverSide": true,
                    "ajax": {
                        url: "{{ route('list.coupon.data') }}",
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
                            "coupons_name": "coupons_name",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 2,
                            "coupons_code": "coupons_code",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 3,
                            "start_date": "start_date",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 4,
                            "date_end": "date_end",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 5,
                            "max_no_of_uses": "max_no_of_uses",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 6,
                            "status": "status",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 7,
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
                        url: "{{ route('update.coupon.status') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                            status: status,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            notify(null, 'Status Updated successfully', 'top right', 'success',
                                false, "{{ route('list.category') }}", 1000);
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
                url: "{{route('coupon.destroy')}}" + '/' + id,
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
