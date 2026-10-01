<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportTicketsModel;
use App\Models\TicketMessageModel;
use App\Models\User;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    public function index()
    {
        return view('backend.support_tickets.list');
    }

    public function listData(Request $request)
    {
        try {
            $query = SupportTicketsModel::with('user');
            
            // Search functionality
            if ($request->has('search') && isset($request->search['value']) && $request->search['value'] != '') {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('subject', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }
            
            $totalRecords = SupportTicketsModel::count();
            $filteredRecords = $query->count();
            
            // Ordering
            $orderColumn = 'id';
            $orderDir = 'desc';
            
            if ($request->has('order') && !empty($request->order)) {
                $columnIndex = $request->order[0]['column'];
                $orderDir = $request->order[0]['dir'];
                
                $columns = ['id', 'subject', 'user_id', 'image', 'status'];
                if (isset($columns[$columnIndex])) {
                    $orderColumn = $columns[$columnIndex];
                }
            }
            
            // Handle user ordering separately
            if ($orderColumn == 'user_id') {
                $query->join('users', 'support_tickets.user_id', '=', 'users.id')
                      ->orderBy('users.name', $orderDir)
                      ->select('support_tickets.*');
            } else {
                $query->orderBy($orderColumn, $orderDir);
            }
            
            // Pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $tickets = $query->skip($start)->take($length)->get();
            
            $data = [];
            $index = $start + 1;
            
            foreach ($tickets as $ticket) {
                // Image column
                if ($ticket->image && file_exists(public_path('uploads/tickets/' . $ticket->image))) {
                    $image = '<img src="' . asset('uploads/tickets/' . $ticket->image) . '" 
                                   width="50" height="50" style="object-fit: cover;" class="rounded">';
                } else {
                    $image = '<span class="badge bg-secondary">No Image</span>';
                }
                
                // Status column
                $checked = ($ticket->status == 'closed') ? 'checked' : '';
                $statusBadge = ($ticket->status == 'open') ? 'success' : 'secondary';
                $status = '<div class="form-check form-switch">
                            <input class="form-check-input status-update" type="checkbox" 
                                data-id="' . $ticket->id . '" ' . $checked . '>
                            <span class="badge bg-' . $statusBadge . '">' . ucfirst($ticket->status) . '</span>
                           </div>';
                
                // Action column
                $action = '<div class="d-flex gap-2">
                            <a href="' . route('support.tickets.show', $ticket->id) . '" 
                               class="btn btn-info btn-sm" title="View/Reply">
                                <i class="bx bx-show"></i>
                            </a>
                            <button class="btn btn-danger btn-sm deleteMCQ" 
                                    data-id="' . $ticket->id . '" title="Delete">
                                <i class="bx bx-trash"></i>
                            </button>
                           </div>';
                
                $data[] = [
                    'DT_RowIndex' => $index++,
                    'title' => $ticket->subject ?? 'N/A',
                    'user' => $ticket->user ? $ticket->user->name : 'Guest',
                    'image' => $image,
                    'status' => $status,
                    'action' => $action
                ];
            }
            
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->draw ?? 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $ticket = SupportTicketsModel::with(['messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'user'])->findOrFail($id);
        
        return view('backend.support_tickets.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $ticket = SupportTicketsModel::findOrFail($id);

            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/tickets'), $imageName);
            }

            TicketMessageModel::create([
                'ticket_id' => $ticket->id,
                'type' => 'admin',
                'message' => $request->message,
                'image' => $imageName
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error sending reply: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:support_tickets,id',
            'status' => 'required'
        ]);

        try {
            $ticket = SupportTicketsModel::find($request->id);
            $ticket->status = $request->status ? 'closed' : 'open';
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status'
            ], 500);
        }
    }
    
    public function markRead($id)
    {
        try {
            // You can implement your logic here if you have a read status
            // For example, updating a read_at timestamp
            
            $ticket = SupportTicketsModel::findOrFail($id);
            // $ticket->read_at = now();
            // $ticket->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Ticket marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking as read'
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $ticket = SupportTicketsModel::findOrFail($id);
            
            // Delete associated messages
            TicketMessageModel::where('ticket_id', $id)->delete();
            
            // Delete ticket
            $ticket->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ticket deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting ticket'
            ], 500);
        }
    }
}