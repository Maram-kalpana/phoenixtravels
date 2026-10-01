@extends('layouts.app')
@section('title', 'Itinerary List')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"> Itinerary </div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List Itinerary</li>
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
                            <div class="ms-auto"><a href="{{ route('add.honeymoon.itinerary', [Request::segment(3), Request::segment(4)]) }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i> Add New Itinerary</a></div>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;"></th>
                                        <th>S.NO</th>
                                        <th>Question</th>
                                        <th>Date</th>
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
                   var table = $('#example').DataTable({
                pageLength: 10,
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('list.honeymoon.itinerary.data', [Request::segment(3), Request::segment(4)]) }}",
                    dataFilter: function(data) {
                        var json = jQuery.parseJSON(data);
                        json.recordsTotal = json.recordsTotal;
                        json.recordsFiltered = json.recordsFiltered;
                        json.data = json.data;
                        return JSON.stringify(json);
                    }
                },
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: 1,
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: 4,
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: 5,
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[2, 'asc']],
                drawCallback: function() {
                    initializeDragAndDrop();
                }
            });

            function initializeDragAndDrop() {
                var tableBody = $('#example tbody');
                tableBody.off('mousedown', '.drag-handle');
                
                var draggedRow = null;
                
                tableBody.on('mousedown', '.drag-handle', function(e) {
                    if (e.button !== 0) return;
                    e.preventDefault();
                    
                    draggedRow = $(this).closest('tr');
                    var initialIndex = draggedRow.index();
                    
                    draggedRow.addClass('dragging-row');
                    tableBody.css('cursor', 'grabbing');
                    
                    function onMouseMove(e) {
                        if (!draggedRow) return;
                        
                        var rows = tableBody.find('tr:not(.dragging-row)');
                        var targetRow = null;
                        
                        rows.each(function() {
                            var row = $(this);
                            var rowTop = row.offset().top;
                            var rowBottom = rowTop + row.outerHeight();
                            
                            if (e.pageY >= rowTop && e.pageY <= rowBottom) {
                                targetRow = row;
                                return false;
                            }
                        });
                        
                        if (targetRow && targetRow.length) {
                            var currentIndex = draggedRow.index();
                            var targetIndex = targetRow.index();
                            
                            if (currentIndex < targetIndex) {
                                targetRow.after(draggedRow);
                            } else if (currentIndex > targetIndex) {
                                targetRow.before(draggedRow);
                            }
                        }
                    }
                    
                    function onMouseUp(e) {
                        $(document).off('mousemove', onMouseMove);
                        $(document).off('mouseup', onMouseUp);
                        
                        if (draggedRow) {
                            draggedRow.removeClass('dragging-row');
                            tableBody.css('cursor', '');
                            
                            var rowIds = [];
                            tableBody.find('tr').each(function() {
                                var id = $(this).find('.deleteMCQ').attr('onclick');
                                if (id) {
                                    var match = id.match(/validate\((\d+)\)/);
                                    if (match) {
                                        rowIds.push(match[1]);
                                    }
                                }
                            });
                            
                            if (rowIds.length > 0 && initialIndex !== draggedRow.index()) {
                                updateOrder(rowIds);
                            }
                            
                            draggedRow = null;
                        }
                    }
                    
                    $(document).on('mousemove', onMouseMove);
                    $(document).on('mouseup', onMouseUp);
                });
            }

            function updateOrder(rowIds) {
                showLoader();
                
                $.ajax({
                    url: "{{ route('honeymoon.itinerary.reorder') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: rowIds
                    },
                    success: function(response) {
                        hideLoader();
                        if (response.status) {
                            notify(null, 'Order updated successfully', 'top right', 'success', false, null, 1000);
                            table.ajax.reload(null, false);
                        } else {
                            notify(null, response.message || 'Failed to update order', 'top right', 'error');
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function() {
                        hideLoader();
                        notify(null, 'Failed to update order', 'top right', 'error');
                        table.ajax.reload(null, false);
                    }
                });
            }

            function showLoader() {
                $('#loader-overlay').addClass('active');
                $('body').css('overflow', 'hidden');
            }

            function hideLoader() {
                $('#loader-overlay').removeClass('active');
                $('body').css('overflow', '');
            }
            
                $('body').on('click', '.status-update', function() {
                    let id = $(this).attr('data-id')
                    let status = this.checked ? 1 : 0;
                    $.ajax({
                        url: "{{ route('update.honeymoon.itinerary.status') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: id,
                            status: status,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            notify(null, 'Status Updated successfully', 'top right', 'success',
                                false, "{{ route('list.packages',Request::segment(3)) }}", 1000);
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
                type:'POST',
                url: "{{route('honeymoon.itinerary.destroy')}}",
                data:{
                    "_token": "{{ csrf_token() }}",
                    "id":id},
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

