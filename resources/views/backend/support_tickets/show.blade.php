@extends('layouts.app')
@section('title', 'Ticket Details')
@section('css')
    <style>
        .chat-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .message-bubble {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 10px;
            max-width: 70%;
        }
        .message-user {
            background-color: #f0f0f0;
            float: left;
            clear: both;
        }
        .message-admin {
            background-color: #007bff;
            color: white;
            float: right;
            clear: both;
        }
        .message-time {
            font-size: 11px;
            color: #888;
            margin-top: 5px;
        }
        .ticket-header {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Support Tickets</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="{{ route('list.support.tickets') }}"><ion-icon name="home-outline"></ion-icon></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ticket #{{ $ticket->id }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <!-- Ticket Info Sidebar -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Ticket Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Ticket ID</th>
                                <td>#{{ $ticket->id }}</td>
                            </tr>
                            <tr>
                                <th>Subject</th>
                                <td>{{ $ticket->subject }}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>{{ $ticket->user ? $ticket->user->name : 'Guest' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $ticket->status == 'open' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created</th>
                                <td>{{ $ticket->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('list.support.tickets') }}" class="btn btn-secondary w-100 mt-3">
                            <i class="bx bx-arrow-back"></i> Back to Tickets
                        </a>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Conversation</h5>
                    </div>
                    <div class="card-body">
                        <!-- Messages Container -->
                        <div class="chat-container mb-4" id="messagesContainer" style="max-height: 500px; overflow-y: auto; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                            <!-- Original Ticket Message -->
                            <div class="d-flex flex-column mb-3">
                                <div class="p-3 rounded-3" style="background: #e9ecef; max-width: 75%;">
                                    <strong>{{ $ticket->user ? $ticket->user->name : 'User' }}</strong>
                                    <p class="mb-0 mt-1">{{ $ticket->message }}</p>
                                    @if($ticket->image)
                                        <img src="{{ asset('uploads/tickets/'.$ticket->image) }}" 
                                             class="img-fluid mt-2 rounded" style="max-width: 200px;">
                                    @endif
                                </div>
                                <small class="text-muted mt-1">{{ $ticket->created_at->format('d M Y, h:i A') }}</small>
                            </div>

                            <!-- Reply Messages -->
                            @foreach($ticket->messages as $message)
                                <div class="d-flex flex-column mb-3 {{ $message->type == 'admin' ? 'align-items-end' : '' }}">
                                    <div class="p-3 rounded-3" style="max-width: 75%; {{ $message->type == 'admin' ? 'background: #0d6efd; color: white;' : 'background: #e9ecef;' }}">
                                        <strong>{{ $message->type == 'admin' ? 'Admin' : ($ticket->user ? $ticket->user->name : 'User') }}</strong>
                                        <p class="mb-0 mt-1">{{ $message->message }}</p>
                                        @if($message->image)
                                            <img src="{{ asset('uploads/tickets/'.$message->image) }}" 
                                                 class="img-fluid mt-2 rounded" style="max-width: 200px;">
                                        @endif
                                    </div>
                                    <small class="text-muted mt-1">{{ $message->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                            @endforeach
                        </div>

                        <!-- Reply Form -->
                        @if($ticket->status == 'open')
                        <div class="border-top pt-3">
                            <h6>Send Reply</h6>
                            <form id="replyForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="message" id="replyMessage" class="form-control" rows="3" 
                                              placeholder="Type your reply here..." required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Attach Image (optional)</label>
                                    <input type="file" name="image" id="replyImage" class="form-control" accept="image/*">
                                    <small class="text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                                </div>
                                <button type="submit" class="btn btn-primary" id="sendReplyBtn">
                                    <i class="bx bx-send"></i> Send Reply
                                </button>
                            </form>
                        </div>
                        @else
                        <div class="alert alert-info mb-0">
                            <i class="bx bx-lock-alt"></i> This ticket is closed. Reopen it to send replies.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Auto-scroll to bottom of messages
            var messagesContainer = $('#messagesContainer');
            messagesContainer.scrollTop(messagesContainer[0].scrollHeight);

            // Submit Reply
            $('#replyForm').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                var submitBtn = $('#sendReplyBtn');
                
                submitBtn.prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Sending...');
                
                $.ajax({
                    url: "{{ route('support.tickets.reply', $ticket->id) }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.success) {
                            // Show success and reload
                            notify(null, 'Reply sent successfully!', 'top right');
                            location.reload();
                        } else {
                            alert('Error sending reply');
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = 'Error sending reply';
                        if(xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            errorMsg = Object.values(errors).flat().join('\n');
                        }
                        alert(errorMsg);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html('<i class="bx bx-send"></i> Send Reply');
                    }
                });
            });
        });
    </script>
@endsection