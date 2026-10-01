<?php

namespace App\Http\Controllers;
use Validator,Auth;
use App\Facades\ResponseHelper;
use App\Models\DepartureDates;
use Illuminate\Http\Request;

class DeparturesDatesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('backend.departure_dates.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,$fixed_departure_id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'departure_date'=>'required',
                'price'=>'required',
                'available_seats'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $departure_dates = new DepartureDates();
                $departure_dates->fixed_departure_id = $fixed_departure_id;
                $departure_dates->departure_date = $data['departure_date'];
                $departure_dates->price = $data['price'];
                $departure_dates->available_seats = $data['available_seats'];
                $departure_dates->status = 1;
                $departure_dates->created_at = date('Y-m-d H:i:s');
                $departure_dates->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('backend.departure_dates.add');
    }

    /**
     * Store a newly created resource in storage.
     */
     
     function getData(Request $request, $fixed_departure_id){
        try {
            // Get DataTable parameters with proper validation
            $draw = $request->input('draw', 1);
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $search = $request->input('search.value', '');
            $orderColumnIndex = $request->input('order.0.column', 0);
            $orderDir = $request->input('order.0.dir', 'desc');
            
            // Map column index to database column name
            $columnMap = [
                0 => 'id',
                1 => 'fixed_departure_id',
                2 => 'departure_date',
                3 => 'price',
                4 => 'available_seats',
                5 => 'status',
                6 => 'created_at'
            ];
            
            // Get the correct column name for ordering
            $orderBy = isset($columnMap[$orderColumnIndex]) ? $columnMap[$orderColumnIndex] : 'created_at';
            
            // Validate order direction
            $orderDir = strtolower($orderDir);
            if (!in_array($orderDir, ['asc', 'desc'])) {
                $orderDir = 'desc';
            }
            
            // Base query
            $query = DepartureDates::where('fixed_departure_id', $fixed_departure_id);
            
            // Get total records count (before filtering)
            $totalRecords = DepartureDates::where('fixed_departure_id', $fixed_departure_id)->count();
            
            // Apply search filter if search term exists
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('price', 'like', '%' . $search . '%')
                      ->orWhere('departure_date', 'like', '%' . $search . '%')
                      ->orWhere('available_seats', 'like', '%' . $search . '%');
                });
            }
            
            // Get filtered records count
            $filteredRecords = $query->count();
            
            // Get paginated and ordered data
            $departure_dates = $query->orderBy($orderBy, $orderDir)
                ->offset($start)
                ->limit($length)
                ->get();
            
            // Prepare data array
            $data = [];
            $counter = $start + 1;
            
            foreach ($departure_dates as $key => $val) {
                $status = $val->status ? "checked" : "";
                
                $data[] = [
                    $counter++,
                    getFixedName($val->fixed_departure_id),
                    $val->departure_date,
                    $val->price,
                    $val->available_seats,
                    "<div class='switch'>
                        <div class='onoffswitch'>
                            <input type='checkbox' {$status} data-id='{$val->id}' class='onoffswitch-checkbox status-update' id='example{$val->id}'>
                            <label class='onoffswitch-label' for='example{$val->id}'>
                                <span class='onoffswitch-inner'></span>
                                <span class='onoffswitch-switch'></span>
                            </label>
                        </div>
                    </div>",
                    '<a href="' . route('edit.departures.dates', [$val->fixed_departure_id, $val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
                    <a href="javascript:void(0)" class="delete-departure" data-id="' . $val->id . '" onclick="validate(' . $val->id . ')"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
                ];
            }
            
            // Prepare response
            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            \Log::error('GetData Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'draw' => intval($request->input('draw', 1)),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }
        

    /**
     * Display the specified resource.
     */
    public function updateStatus(Request $request){
        $update = DepartureDates::where('id', $request->id)->update(['status' => $request->status]);
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => []
        ]);

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,$fixed_departures,$id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'departure_date'=>'required',
                'price'=>'required',
                'available_seats'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array("departure_date" => $data['departure_date'],"price" => $data['price'],"available_seats" => $data['available_seats']);
                $affectedRows = DepartureDates::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $fixed_departure_dates = DepartureDates::where('id',$id)->first();
        return view('backend.departure_dates.edit',compact('fixed_departure_dates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Request $request, $id = null){
        if ($request->ajax()){
            // If ID comes from request data
            $id = $request->id ?? $id;
            $banner = DepartureDates::findOrFail($id);
            if ($banner){
                $banner->delete();
                return response()->json(array('success' => true, 'message' => 'Deleted successfully'));
            }
        }
        return response()->json(['success' => false, 'message' => 'Not found'], 404);
    }
}
