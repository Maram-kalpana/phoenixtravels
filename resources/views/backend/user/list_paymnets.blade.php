@extends('layouts.app')
@section('title', 'Payments List')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Payments</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List Payments</li>
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
                            {{-- <div class="ms-auto"><a href="{{ route('add.page') }}"
                                    class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New
                                    Page</a></div> --}}
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered student-table"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Course</th>
                                        <th>Amount</th>
                                        <th>Product Id</th>
                                        
                                       
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
                        url: "{{ route('list.payments.data') }}",
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
                            "first_name": "first_name",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 2,
                            "email": "email",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 3,
                            "phone": "phone",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 4,
                            "group_name": "group_name",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 5,
                            "amount": "amount",
                            'searchable': false,
                            'orderable': false
                        },
                        {
                            "targets": 6,
                            "r_payment_id": "r_payment_id",
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
                        url: "{{ route('update.users.status') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                            status: status,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            notify(null, 'Status Updated successfully', 'top right', 'success',
                                false, "{{ route('list.pages') }}", 1000);
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
    @stop

