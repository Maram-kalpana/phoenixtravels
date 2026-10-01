<?php

namespace App\Http\Controllers;

use App\Models\ContactModel;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class EnquiryController extends Controller{
    
    public function index(){
     return view('backend.enquiry.list-enquiry');
    }
     
    public function QuickEnquiry(){
     return view('backend.enquiry.list-quick-enquiry');
    }
    
    public function BookingEnquiry(){
     return view('backend.enquiry.list-booking-enquiry');
    }
   
    

      public function getEnquiryData(Request $request){
          
            try {
                $draw = $request->get('draw');
                $start = $request->get('start', 0);
                $length = $request->get('length', 10);
                $search = $request->get('search')['value'] ?? '';
                $orderColumn = $request->get('order')[0]['column'] ?? 0;
                $orderDir = $request->get('order')[0]['dir'] ?? 'desc';
                
                // Get filter parameters
                $status = $request->get('status');
                $phone = $request->get('phone');
                $email = $request->get('email');
                $startDate = $request->get('start_date');
                $endDate = $request->get('end_date');
                
                // Column mapping for ordering
                $columns = [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'message',
                    'created_at',
                    'status'
                ];
                
                $orderBy = $columns[$orderColumn] ?? 'created_at';
                
                // Build query
                $query = ContactModel::query();
                
                // Apply search
                if (!empty($search)) {
                    $query->where(function($q) use ($search) {
                        $q->where('first_name', 'like', '%' . $search . '%')
                          ->orWhere('last_name', 'like', '%' . $search . '%')
                          ->orWhere('email', 'like', '%' . $search . '%')
                          ->orWhere('phone', 'like', '%' . $search . '%')
                          ->orWhere('message', 'like', '%' . $search . '%');
                    });
                }
                
                // Apply status filter
                if ($status !== null && $status !== '') {
                    $query->where('status', $status);
                }
                
                // Apply phone filter
                if (!empty($phone)) {
                    $query->where('phone', 'like', '%' . $phone . '%');
                }
                
                // Apply email filter
                if (!empty($email)) {
                    $query->where('email', 'like', '%' . $email . '%');
                }
                
                // Apply date range filter
                if (!empty($startDate) && !empty($endDate)) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ]);
                } elseif (!empty($startDate)) {
                    $query->whereDate('created_at', '>=', Carbon::parse($startDate)->startOfDay());
                } elseif (!empty($endDate)) {
                    $query->whereDate('created_at', '<=', Carbon::parse($endDate)->endOfDay());
                }
                
                // Get total count
                $totalRecords = $query->count();
                
                // Apply ordering and pagination
                $enquiries = $query->orderBy($orderBy, $orderDir)
                                  ->offset($start)
                                  ->limit($length)
                                  ->get();
                
                // Prepare data for DataTable
                $data = [];
                foreach ($enquiries as $index => $enquiry) {
                    $data[] = [
                        'id' => $enquiry->id,
                        'first_name' => $enquiry->first_name,
                        'last_name' => $enquiry->last_name,
                        'email' => $enquiry->email,
                        'phone' => $enquiry->phone,
                        'message' => $enquiry->message,
                        'created_at' => $enquiry->created_at,
                        'status' => $enquiry->status,
                        'DT_RowId' => 'row_' . $enquiry->id,
                    ];
                }
                
                return response()->json([
                    'draw' => $draw,
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $totalRecords,
                    'data' => $data
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Error fetching enquiry data: ' . $e->getMessage());
                
                return response()->json([
                    'draw' => $request->get('draw', 0),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Failed to load data'
                ], 500);
            }
        }
        
        // Add these new methods to your controller
        
        public function updateEnquiryStatus(Request $request)
        {
            try {
                $enquiry = ContactModel::findOrFail($request->id);
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
        
        public function deleteEnquiry(Request $request)
        {
            try {
                $enquiry = ContactModel::findOrFail($request->id);
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
        
        public function bulkDeleteEnquiries(Request $request)
        {
            try {
                $ids = $request->ids;
                
                if (empty($ids)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No enquiries selected'
                    ]);
                }
                
                ContactModel::whereIn('id', $ids)->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => count($ids) . ' enquiries deleted successfully'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete enquiries'
                ], 500);
            }
        }
        
        public function exportEnquiries(Request $request)
        {
            try {
                // Get filter parameters
                $search = $request->get('search');
                $dateRange = $request->get('date_range');
                $status = $request->get('status');
                $phone = $request->get('phone');
                $email = $request->get('email');
                
                // Build query
                $query = ContactModel::query();
                
                // Apply filters
                if (!empty($search)) {
                    $query->where(function($q) use ($search) {
                        $q->where('first_name', 'like', '%' . $search . '%')
                          ->orWhere('last_name', 'like', '%' . $search . '%')
                          ->orWhere('email', 'like', '%' . $search . '%')
                          ->orWhere('phone', 'like', '%' . $search . '%');
                    });
                }
                
                if (!empty($dateRange)) {
                    $dates = explode(' to ', $dateRange);
                    if (count($dates) == 2) {
                        $query->whereBetween('created_at', [
                            Carbon::parse($dates[0])->startOfDay(),
                            Carbon::parse($dates[1])->endOfDay()
                        ]);
                    }
                }
                
                if ($status !== null && $status !== '') {
                    $query->where('status', $status);
                }
                
                if (!empty($phone)) {
                    $query->where('phone', 'like', '%' . $phone . '%');
                }
                
                if (!empty($email)) {
                    $query->where('email', 'like', '%' . $email . '%');
                }
                
                $enquiries = $query->orderBy('created_at', 'desc')->get();
                
                // Generate CSV
                $fileName = 'contact-enquiries-' . date('Y-m-d-H-i-s') . '.csv';
                
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                ];
                
                $callback = function() use ($enquiries) {
                    $file = fopen('php://output', 'w');
                    
                    // Add CSV headers
                    fputcsv($file, [
                        'ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Message', 
                        'Status', 'Created At', 'Updated At'
                    ]);
                    
                    // Add data rows
                    foreach ($enquiries as $enquiry) {
                        fputcsv($file, [
                            $enquiry->id,
                            $enquiry->first_name,
                            $enquiry->last_name,
                            $enquiry->email,
                            $enquiry->phone,
                            $enquiry->message,
                            $enquiry->status ? 'Active' : 'Inactive',
                            $enquiry->created_at,
                            $enquiry->updated_at
                        ]);
                    }
                    
                    fclose($file);
                };
                
                return response()->stream($callback, 200, $headers);
                
            } catch (\Exception $e) {
                \Log::error('Export error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to export data');
            }
        }
    
    
    
    
    function edit(Request $request, $id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=['tour_category_id'=>'required','tour_name'=>'required','meta_title'=>'required','meta_keywords'=>'required','meta_description'=>'required'];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('image') != ''){
                    $banner_image = 'uploads/tours/'.$request->file('image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$banner_image;
                    $request->file('image')->move('uploads/tours/', $file_name);
                }else{
                  $old_image =  ToursModel::where('id',$id)->first();
                  $banner_image =$old_image->image;
                }
                $update_arr = array("image" => $banner_image,"tour_category_id" => $data['tour_category_id'],"tour_name" => $data['tour_name'],"description" => $data['description'],"meta_title" => $data['meta_title'],"meta_keywords" => $data['meta_keywords'],"meta_description" => $data['meta_description']);
                $affectedRows = ToursModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $tours = ToursModel::where('id',$id)->first();
        return view('backend.tours.edit',compact('tours'));
    }
    public function updateToursStatus(Request $request){
        $update = ToursModel::where('id', $request->id)->update(['status' => $request->status]);
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => []
        ]);

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id){
         if ($request->ajax()){
            $state = ToursModel::findOrFail($id);
            if ($state){
                $state->delete();
                return response()->json(array('success' => true));
            }
        }
    }

      public function GetStateName(Request $request){
             $country_id = $request->country_id;
             $state = StateModel::where('country_id',$country_id)->where('status',1)->get();
             if(!empty($state)){
    			$html=' <select class="form-control state_id" name="state_id" id="state_id"><option value="">----Select State----</option>';
    			foreach($state as $sub){
    				$html.=	 '<option value="'.$sub->id.'"  >'.$sub->name.'</option>';
    			}
    			$html.=' </select><span id="crr_statelist" style="color:red"></span>';
    		}else{
    			$html= ' <select name="state_id" id="state_id"><option value="">--No data--</option></select>'; 
    		}
    		echo $html;exit;
            
        }
        
    
}
