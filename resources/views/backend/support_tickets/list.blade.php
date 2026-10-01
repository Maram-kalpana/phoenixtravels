@extends('layouts.app')
@section('title', 'Support Tickets List')
@section('css')
    <link href="{{ asset('backend/assets/dataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Support Tickets</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a></li>
                        <li class="breadcrumb-item active" aria-current="page">List Support Tickets</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Title</th>
                                        <th>User</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- DataTables will populate this -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteModuleSubmit">
                        <div class="modal-body">
                            <input type="hidden" id="mcq-id" name="id">
                            <div id="errosDeleteMsg"></div>
                            <p>Are you sure you want to delete this ticket? This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
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
                processing: true,
                serverSide: true,
                pageLength: 10,
                responsive: true,
                ajax: {
                    url: "{{ route('list.support.tickets.data') }}",
                    type: "GET",
                    dataType: "JSON"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'user', name: 'user', orderable: false },
                    { data: 'image', name: 'image', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']],
                drawCallback: function() {
                    // Status Update
                    $('.status-update').off('change').on('change', function() {
                        let id = $(this).data('id');
                        let status = this.checked ? 1 : 0;
                        
                        $.ajax({
                            url: "{{ route('support.tickets.status') }}",
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                id: id,
                                status: status,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(data) {
                                if(data.success) {
                                    alert('Status updated successfully');
                                    table.ajax.reload(null, false);
                                }
                            }
                        });
                    });

                    // Delete Button
                    $('.deleteMCQ').off('click').on('click', function() {
                        $('#mcq-id').val($(this).data('id'));
                        $('#deleteModal').modal('show');
                    });
                }
            });

            // Delete Submit
            $('#deleteModuleSubmit').on('submit', function(e) {
                e.preventDefault();
                let id = $('#mcq-id').val();
                
                $.ajax({
                    url: "{{ url('support-tickets/support-tickets') }}/" + id,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.success) {
                            table.ajax.reload(null, false);
                            $('#deleteModal').modal('hide');
                            alert(data.message);
                        }
                    }
                });
            });
        });
    </script>
@endsection