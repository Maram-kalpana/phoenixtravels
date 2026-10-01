<?php

namespace App\Http\Controllers;
use Validator,Auth;
use App\Facades\ResponseHelper;
use App\Models\CorporateTourEnquiry;
use Illuminate\Http\Request;

class UserBookingGroupToursController extends Controller{
    
    public function corporate_tours(Request $request){
        return view('backend.user_booking.corporate_tours');
    }
    
    public function getCorporateBooking(Request $request){
        try {
            $columns = [
                0 => 'id',
                1 => 'company_name',
                2 => 'full_name',
                3 => 'email',
                4 => 'phone',
                5 => 'destination',
                6 => 'preferred_date',
                7 => 'number_of_people',
                8 => 'status',
                9 => 'created_at',
            ];

            $totalData = CorporateTourEnquiry::count();
            $totalFiltered = $totalData;
            
            $limit = $request->input('length', 10);
            $start = $request->input('start', 0);
            $orderColumn = $columns[$request->input('order.0.column', 0)];
            $orderDir = $request->input('order.0.dir', 'desc');
            
            $query = CorporateTourEnquiry::query();
            
            // Search handling
            if (!empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function($q) use ($search) {
                    $q->where('company_name', 'LIKE', "%{$search}%")
                      ->orWhere('full_name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%")
                      ->orWhere('destination', 'LIKE', "%{$search}%");
                });
                $totalFiltered = $query->count();
            }
            
            $enquiries = $query->orderBy($orderColumn, $orderDir)
                ->offset($start)
                ->limit($limit)
                ->get();
            
            $data = [];
            foreach ($enquiries as $enquiry) {
                $data[] = [
                    'id' => $enquiry->id,
                    'company_name' => '<strong>' . htmlspecialchars($enquiry->company_name) . '</strong>',
                    'full_name' => htmlspecialchars($enquiry->full_name),
                    'email' => $enquiry->email,
                    'phone' => $enquiry->phone,
                    'destination' => htmlspecialchars($enquiry->destination),
                    'preferred_date' => $enquiry->preferred_date ? date('d M Y', strtotime($enquiry->preferred_date)) : '-',
                    'number_of_people' => $enquiry->number_of_people,
                    'status' => $enquiry->status_badge,
                    'created_at' => $enquiry->created_at ? date('d M Y h:i A', strtotime($enquiry->created_at)) : '-',
                    'action' => $this->getActionButtons($enquiry->id)
                ];
            }
            
            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('DataTables Error: ' . $e->getMessage());
            return response()->json([
                "draw" => intval($request->input('draw', 0)),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Get enquiry details for modal view
     */
    public function getDetails($id)
    {
        try {
            $enquiry = CorporateTourEnquiry::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $enquiry
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Enquiry not found'
            ], 404);
        }
    }

    /**
     * Update enquiry status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:1,2,3,4'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $enquiry = CorporateTourEnquiry::findOrFail($id);
            $enquiry->status = $request->status;
            $enquiry->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }
    
    /**
     * Delete enquiry
     */
    public function destroy($id){
        
        try {
            $enquiry = CorporateTourEnquiry::findOrFail($id);
            $enquiry->delete();

            return response()->json([
                'success' => true,
                'message' => 'Enquiry deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete enquiry'
            ], 500);
        }
    }

    /**
     * Export all enquiries to CSV
     */
    public function exportCSV()
    {
        try {
            $enquiries = CorporateTourEnquiry::all();
            
            $filename = "corporate_tour_enquiries_" . date('Y-m-d_His') . ".csv";
            $handle = fopen('php://memory', 'w');
            
            // Add CSV headers
            fputcsv($handle, [
                'ID', 'Company Name', 'Contact Person', 'Email', 'Phone',
                'Destination', 'Preferred Date', 'Number of People', 'Status', 'Created At'
            ]);
            
            // Add data rows
            foreach ($enquiries as $enquiry) {
                fputcsv($handle, [
                    $enquiry->id,
                    $enquiry->company_name,
                    $enquiry->full_name,
                    $enquiry->email,
                    $enquiry->phone,
                    $enquiry->destination,
                    $enquiry->preferred_date,
                    $enquiry->number_of_people,
                    $enquiry->status_text,
                    $enquiry->created_at
                ]);
            }
            
            fseek($handle, 0);
            
            return response()->streamDownload(function() use ($handle) {
                fpassthru($handle);
                fclose($handle);
            }, $filename, [
                'Content-Type' => 'text/csv',
            ]);
            
        } catch (\Exception $e) {
            Log::error('Export Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to export data'
            ], 500);
        }
    }

    private function getActionButtons($id)
    {
        return '
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-info view-enquiry" data-id="' . $id . '" title="View Details">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-sm btn-warning edit-status" data-id="' . $id . '" title="Update Status">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger delete-enquiry" data-id="' . $id . '" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        ';
    }

    public function college_excursion(Request $request){
        return view('backend.user_booking.college_excursion');
    }
    
    public function school_excursion(Request $request){
        return view('backend.user_booking.school_excursion');
    }
}