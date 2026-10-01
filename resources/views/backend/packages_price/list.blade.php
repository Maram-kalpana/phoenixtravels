@extends('layouts.app')
@section('title', 'Package Price List')

@section('css')
<link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .dataTable td {
        vertical-align: middle !important;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        margin: 0 2px;
    }
    .form-switch .form-check-input {
        width: 2em;
        height: 1em;
        cursor: pointer;
    }
    .dataTable .table {
        margin-bottom: 0;
    }
    .dataTable .table th,
    .dataTable .table td {
        padding: 6px 8px;
        font-size: 12px;
    }
    .dataTable .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Package Price</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0 align-items-center">
                    <li class="breadcrumb-item">
                        <a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Package Price Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Package Price List</h5>
                </div>
                <div class="card-body">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="ms-auto">
                            <a href="{{ route('add.package.price', [$tour_id, $package_id]) }}" class="btn btn-primary">
                                <i class="bx bxs-plus-square"></i> Add New Package Price
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="5%">S.NO</th>
                                    <th width="10%">Package ID</th>
                                    <th width="15%">Package Title</th>
                                    <th width="25%">Car Prices</th>
                                    <th width="30%">Room Prices</th>
                                    <th width="10%">Date</th>
                                    <th width="5%">Status</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('backend/assets/dataTables/datatables.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#example').DataTable({
        pageLength: 10,
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('list.package.price.data', [$tour_id, $package_id]) }}",
            type: 'GET',
            error: function(xhr) {
                console.log('Error:', xhr);
                toastr.error('Error loading data: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'package_id', name: 'package_id' },
            { data: 'package_title', name: 'package_title' },
            { data: 'car_prices', name: 'car_prices', orderable: false, searchable: false },
            { data: 'room_prices', name: 'room_prices', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[5, 'desc']],
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            emptyTable: 'No package price data available',
            zeroRecords: 'No matching records found'
        }
    });

    $('#example').on('change', '.status-update', function() {
        let id = $(this).data('id');
        let status = this.checked ? 1 : 0;
        let $checkbox = $(this);

        $.ajax({
            url: "{{ route('update.package.price.status') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                id: id,
                status: status,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                if (data.success) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                    $checkbox.prop('checked', !status);
                }
            },
            error: function(xhr) {
                toastr.error('Something went wrong: ' + (xhr.responseJSON?.message || 'Unknown error'));
                $checkbox.prop('checked', !status);
            }
        });
    });
});

function validate(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this! This will delete all car and room prices associated with this package.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Delete it!",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'DELETE',
                url: "{{ route('package.price.destroy') }}" + '/' + id,
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(data) {
                    if (data.success) {
                        Swal.fire({
                            title: "Deleted!",
                            text: data.message,
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        });
                        $('#example').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: data.message,
                            icon: "error"
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: "Error!",
                        text: "Something went wrong! " + (xhr.responseJSON?.message || ''),
                        icon: "error"
                    });
                }
            });
        }
    });
}
</script>
@endsection