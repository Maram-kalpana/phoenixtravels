<?php

namespace App\Http\Controllers;

use App\Models\ToursModel;
use App\Models\DynamicPriceModel;
use App\Models\DynamicPriceCarPriceModel;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class DynamicPricingController extends Controller{
    /**
     * Display a listing of the resource.
     */
    public function index(){
     return view('backend.dynamic_pricing.domestic.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=['state_id'=>'required'];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                
                // Save 2 Star Room Configurations
                if (!empty($data['room_config_2']) && is_array($data['room_config_2'])) {
                    foreach ($data['room_config_2'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new DynamicPriceModel();
                            $roomPrice->state_id = $data['state_id'];
                            $roomPrice->star_category = '2';
                            $roomPrice->hotel_name = $roomConfig['hotel'] ?? '';
                            $roomPrice->rooms_cost = $roomConfig['rooms'] ?? 0;
                            $roomPrice->extra_bed_cost = $roomConfig['extra_bed_cost'] ?? 0;
                            $roomPrice->attract_tickets = $roomConfig['total_cost'] ?? 0;
                            $roomPrice->honeymoon_cost = $roomConfig['honeymoon_cost'] ?? 0;
                            $roomPrice->save();
                        }
                    }
                }
                
                // Save 3 Star Room Configurations
                if (!empty($data['room_config_3']) && is_array($data['room_config_3'])) {
                    foreach ($data['room_config_3'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new DynamicPriceModel();
                            $roomPrice->state_id = $data['state_id'];
                            $roomPrice->star_category = '3';
                            $roomPrice->hotel_name = $roomConfig['hotel'] ?? '';
                            $roomPrice->rooms_cost = $roomConfig['rooms'] ?? 0;
                            $roomPrice->extra_bed_cost = $roomConfig['extra_bed_cost'] ?? 0;
                            $roomPrice->attract_tickets = $roomConfig['total_cost'] ?? 0;
                            $roomPrice->honeymoon_cost = $roomConfig['honeymoon_cost'] ?? 0;
                            $roomPrice->save();
                        }
                    }
                }
                
                // Save 4 Star Room Configurations
                if (!empty($data['room_config_4']) && is_array($data['room_config_4'])) {
                    foreach ($data['room_config_4'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new DynamicPriceModel();
                            $roomPrice->state_id = $data['state_id'];
                            $roomPrice->star_category = '4';
                            $roomPrice->hotel_name = $roomConfig['hotel'] ?? '';
                            $roomPrice->rooms_cost = $roomConfig['rooms'] ?? 0;
                            $roomPrice->extra_bed_cost = $roomConfig['extra_bed_cost'] ?? 0;
                            $roomPrice->attract_tickets = $roomConfig['total_cost'] ?? 0;
                            $roomPrice->honeymoon_cost = $roomConfig['honeymoon_cost'] ?? 0;
                            $roomPrice->save();
                        }
                    }
                }
                
                // Save 5 Star Room Configurations
                if (!empty($data['room_config_5']) && is_array($data['room_config_5'])) {
                    foreach ($data['room_config_5'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new DynamicPriceModel();
                            $roomPrice->state_id = $data['state_id'];
                            $roomPrice->star_category = '5';
                            $roomPrice->hotel_name = $roomConfig['hotel'] ?? '';
                            $roomPrice->rooms_cost = $roomConfig['rooms'] ?? 0;
                            $roomPrice->extra_bed_cost = $roomConfig['extra_bed_cost'] ?? 0;
                            $roomPrice->attract_tickets = $roomConfig['total_cost'] ?? 0;
                            $roomPrice->honeymoon_cost = $roomConfig['honeymoon_cost'] ?? 0;
                            $roomPrice->save();
                        }
                    }
                }
                
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $state = ToursModel::where('status',1)->get();
        return view('backend.dynamic_pricing.domestic.add',compact('state'));
    }

    function getDynamicPriceData(Request $request)
{
    if (isset($_GET['search']['value'])) {
        $search = $_GET['search']['value'];
    } else {
        $search = '';
    }

    $limit  = $_GET['length'] ?? 10;
    $offset = $_GET['start'] ?? 0;

    // Optional: column-click sorting (applied within the state grouping)
    $orderType  = $_GET['order'][0]['dir'] ?? 'asc';
    $orderCol    = $_GET['order'][0]['column'] ?? 0;
    $columnMap   = [
        0 => 'id',
        1 => 'id',                 // S.NO
        2 => 'state_id',           // State Name
        3 => 'star_category',      // Star Category
        4 => 'hotel_name',         // Hotel Name
        5 => 'rooms_cost',         // Room Cost
        6 => 'extra_bed_cost',     // Extra Bed Cost
        7 => 'attract_tickets',    // Attract/Entry Tickets
        8 => 'honeymoon_cost',     // Honeymoon Cost
    ];
    $orderColumn = $columnMap[$orderCol] ?? 'id';

    // Search on state name (from states table) instead of state_id
    $searchQuery = function ($query) use ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('state.name', 'like', '%' . $search . '%')
              ->orWhere('dynamic_price.state_id', 'like', '%' . $search . '%');
        });
    };

    $total = DynamicPriceModel::join('state', 'state.id', '=', 'dynamic_price.state_id')
        ->where($searchQuery)
        ->count();

    $course = DynamicPriceModel::join('state', 'state.id', '=', 'dynamic_price.state_id')
        ->where($searchQuery)
        ->offset($offset)
        ->limit($limit)
        ->orderBy('state.name', 'asc')                 // States in alphabetical order
        ->orderBy('dynamic_price.star_category', 'asc') // Within state: 2, 3, 4, 5
        ->orderBy('dynamic_price.' . $orderColumn, $orderType) // Column-click sorting inside group
        ->select('dynamic_price.*')
        ->get();

    $data = [];
    foreach ($course as $key => $val) {
        $status = $val->status ? "checked" : "";
        $data[] = array(
            $key + 1 + $offset,
            getTourName($val->state_id),
            $val->star_category,
            $val->hotel_name,
            $val->rooms_cost,
            $val->extra_bed_cost,
            $val->attract_tickets,
            $val->honeymoon_cost,
            "<div class='switch'>
                <div class='onoffswitch'>
                    <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                    <label class='onoffswitch-label' for='example" . $val->id . "'>
                        <span class='onoffswitch-inner'></span>
                        <span class='onoffswitch-switch'></span>
                    </label>
                </div>
            </div>",
            '<a href="' . route('edit.dynamic.price', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
            <a href="javascript::void(0)" class="deleteMCQ" onclick="validate(' . $val->id . ')"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
        );
    }

    $records['recordsTotal'] = $total;
    $records['recordsFiltered'] = $total;
    $records['data'] = $data;
    echo json_encode($records);
}
    
    function edit(Request $request, $id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'state_id'=>'required',
                'hotel_name'=>'required',
                'rooms_cost'=>'required',
                'extra_bed_cost'=>'required',
                'attract_tickets'=>'required',
                'honeymoon_cost'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array("hotel_name" => $data['hotel_name'],"state_id" => $data['state_id'],"rooms_cost" => $data['rooms_cost'],"extra_bed_cost" => $data['extra_bed_cost'],"attract_tickets" => $data['attract_tickets'],"updated_at" => date('Y-m-d H:i:s'));
                $affectedRows = DynamicPriceModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $dynamic_price = DynamicPriceModel::where('id',$id)->first();
        //dd($dynamic_price);
        $state = ToursModel::where('status',1)->get();
        return view('backend.dynamic_pricing.domestic.edit',compact('dynamic_price','state'));
    }
    
    public function updateDynamicPriceStatus(Request $request){
        $update = DynamicPriceModel::where('id', $request->id)->update(['status' => $request->status]);
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
            $course = DynamicPriceModel::findOrFail($id);
            if ($course){
                $course->delete();
                return response()->json(array('success' => true));
            }
        }
    }

    public function Roomindex(){
        $states = ToursModel::where('status', 1)->get();
        return view('backend.dynamic_pricing.domestic.bulk_upload', compact('states'));
    }
  
    public function Roomupload(Request $request)
{
    $response_code = 200;
    $message = ['error' => ['Something went wrong']];
    $success = false;

    $data = $request->all();

    // ---------- VALIDATION ----------
    $rules = [
        'state_id' => 'required|exists:state,id',
        'csv_file' => 'required|file|mimes:csv,txt|max:5120',
    ];

    $messages = [
        'state_id.required' => 'Please select a state.',
        'state_id.exists'   => 'Selected state is invalid.',
        'csv_file.required' => 'Please select a CSV file to upload.',
        'csv_file.mimes'    => 'Only CSV files are allowed.',
        'csv_file.max'      => 'File size should not exceed 5MB.',
    ];

    $validator = Validator::make($data, $rules, $messages);

    if ($validator->fails()) {
        return ResponseHelper::returnResponse(200, false, $validator->errors());
    }

    try {
        $file    = $request->file('csv_file');
        $csvData = file_get_contents($file->getRealPath());

        $rows    = array_filter(explode("\n", trim($csvData)));
        $rows    = array_values($rows);
        $headers = array_map('trim', str_getcsv(array_shift($rows)));

        // ---------- HEADER VALIDATION ----------
        $expectedHeaders = [
            'star_category',
            'hotel_name',
            'rooms_cost',
            'extra_bed_cost',
            'attract_tickets',
            'honeymoon_cost',
        ];

        // Allow headers in any order
        $headerMap = [];
        foreach ($expectedHeaders as $expected) {
            $index = array_search($expected, $headers);
            if ($index === false) {
                return ResponseHelper::returnResponse(200, false, [
                    'error' => ['Missing required column: ' . $expected],
                ]);
            }
            $headerMap[$expected] = $index;
        }

        $validStarCategories = [2, 3, 4, 5];
        $roomData  = [];
        $errors    = [];
        $rowNumber = 1;
        $seenStars = [];

        // ---------- PARSE ROWS ----------
        foreach ($rows as $row) {
            $rowNumber++;
            $columns = str_getcsv(trim($row));

            if (empty(array_filter($columns))) {
                continue;
            }

            $starCategory  = trim($columns[$headerMap['star_category']]  ?? '');
            $hotelName     = trim($columns[$headerMap['hotel_name']]     ?? '');
            $roomsCost     = trim($columns[$headerMap['rooms_cost']]     ?? '');
            $extraBedCost  = trim($columns[$headerMap['extra_bed_cost']] ?? '');
            $attractTickets= trim($columns[$headerMap['attract_tickets']]?? '');
            $honeymoonCost = trim($columns[$headerMap['honeymoon_cost']] ?? '');

            // Validate star_category
            if (!is_numeric($starCategory) || !in_array((int) $starCategory, $validStarCategories)) {
                $errors[] = "Row {$rowNumber}: star_category must be 2, 3, 4, or 5.";
                continue;
            }

            $starCategory = (int) $starCategory;

            // Prevent duplicate star_category in same CSV
            if (in_array($starCategory, $seenStars)) {
                $errors[] = "Row {$rowNumber}: Duplicate star_category ({$starCategory}) found.";
                continue;
            }
            $seenStars[] = $starCategory;

            // Validate numerics
            $numericFields = [
                'rooms_cost'      => $roomsCost,
                'extra_bed_cost'  => $extraBedCost,
                'attract_tickets' => $attractTickets,
                'honeymoon_cost'  => $honeymoonCost,
            ];

            $hasError = false;
            foreach ($numericFields as $fieldName => $value) {
                if ($value === '' || !is_numeric($value) || $value < 0) {
                    $errors[] = "Row {$rowNumber}: {$fieldName} must be a valid number ≥ 0.";
                    $hasError = true;
                    break;
                }
            }
            if ($hasError) {
                continue;
            }

            $roomData[] = [
                'state_id'       => (int) $data['state_id'],
                'star_category'  => $starCategory,
                'hotel_name'     => $hotelName ?: 'Hotel / Resort / Cottage',
                'rooms_cost'     => (float) $roomsCost,
                'extra_bed_cost' => (float) $extraBedCost,
                'attract_tickets'=> (float) $attractTickets,
                'honeymoon_cost' => (float) $honeymoonCost,
                'status'         => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        // ---------- ERROR HANDLING ----------
        if (!empty($errors)) {
            return ResponseHelper::returnResponse(200, false, [
                'error' => $errors,
            ]);
        }

        if (empty($roomData)) {
            return ResponseHelper::returnResponse(200, false, [
                'error' => ['No valid data found to upload.'],
            ]);
        }

        // ---------- SAVE (DELETE OLD + INSERT NEW) ----------
        \DB::beginTransaction();

        // Remove old rows for this state
        \DB::table('dynamic_price')
            ->where('state_id', $data['state_id'])
            ->delete();

        // Insert new rows
        \DB::table('dynamic_price')->insert($roomData);

        \DB::commit();

        $success = true;
        $message = [
            'success' => ['Room prices uploaded successfully. ' . count($roomData) . ' records added.'],
        ];

    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('Roomupload error: ' . $e->getMessage());
        $success = false;
        $message = ['error' => ['Upload failed: ' . $e->getMessage()]];
    }

    return ResponseHelper::returnResponse($response_code, $success, $message);
}

    /**
     * Download sample CSV template for room pricing
     */
   public function downloadTemplate()
{
    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename="room_prices_template.csv"',
    ];

    $columns = [
        'state_id',
        'star_category',
        'hotel_name',
        'rooms_cost',
        'extra_bed_cost',
        'attract_tickets',
        'honeymoon_cost',
    ];

    // One row per star category (matches your new DB structure)
    $sampleData = [
        ['1', '2', 'Hotel / Resort / Cottage', '2500.00', '500.00', '100.00', '5000.00'],
        ['1', '3', 'Hotel / Resort / Cottage', '3500.00', '700.00', '120.00', '5500.00'],
        ['1', '4', 'Hotel / Resort / Cottage', '4500.00', '900.00', '150.00', '6000.00'],
        ['1', '5', 'Hotel / Resort / Cottage', '5500.00', '1100.00', '180.00', '6500.00'],
    ];

    $callback = function () use ($columns, $sampleData) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        foreach ($sampleData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

    
    public function Carindex(){
     return view('backend.dynamic_pricing.domestic_car.list');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function Carcreate(Request $request)
    {
        if ($request->isMethod('post')) {
            $success = false;
            $message = ['error' => ['Something went wrong']];
    
            $data = $request->all();
    
            $rules = [
                'state_id' => 'required|integer',
            ];
    
            $validator = Validator::make($data, $rules);
    
            if ($validator->fails()) {
                return ResponseHelper::returnResponse(200, false, $validator->errors());
            }
    
            try {
                \DB::beginTransaction();
    
                if (!empty($data['car_config']) && is_array($data['car_config'])) {
                    foreach ($data['car_config'] as $carConfig) {
    
                        // Skip empty rows
                        if (empty($carConfig['car_name'])) {
                            continue;
                        }
    
                        $carPrice = new DynamicPriceCarPriceModel();
                        $carPrice->state_id     = $data['state_id'];
                        $carPrice->pax          = $carConfig['pax']          ?? null;
                        $carPrice->car_name     = $carConfig['car_name']     ?? '';
                        $carPrice->car_per_day  = $carConfig['car_per_day']  ?? 0;
                        $carPrice->driver_batta = $carConfig['driver_batta'] ?? 0;
                        $carPrice->save();
                    }
                }
    
                \DB::commit();
    
                $success = true;
                $message = ['success' => ['Added successfully']];
    
            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Carcreate error: ' . $e->getMessage());
                $message = ['error' => ['Something went wrong: ' . $e->getMessage()]];
            }
            return ResponseHelper::returnResponse(200, $success, $message);
        }
        $state = ToursModel::where('status', 1)->get();
        return view('backend.dynamic_pricing.domestic_car.add', compact('state'));
    }

    function getCarDynamicPriceData(Request $request){
        if (isset($_GET['search']['value'])) {
            $search = $_GET['search']['value'];
        } else {
            $search = '';
        }
        if (isset($_GET['length'])) {
            $limit = $_GET['length'];
        } else {
            $limit = 10;
        }
        if (isset($_GET['start'])) {
            $offset = $_GET['start'];
        } else {
            $offset = 0;
        }
        $orderType = $_GET['order'][0]['dir'];
        $nameOrder = $_GET['columns'][$_GET['order'][0]['column']]['name'];
        $total = DynamicPriceCarPriceModel::Where(function ($query) use ($search) {
                $query->Where('dynamic_car_prices.pax', 'like', '%' . $search . '%');
            })->count();

        $course = DynamicPriceCarPriceModel::Where(function ($query) use ($search) {
                $query->Where('dynamic_car_prices.pax', 'like', '%' . $search . '%');
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($course as $key => $val) {
            $status = $val->status ? "checked" : "";
            $data[] = array(
                $key + 1,
                getTourName($val->state_id),
                $val->pax,
                $val->car_name,
                $val->car_per_day,
                $val->driver_batta,
                '<a href="' . route('edit.car.dynamic.price', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
                <a  href="javascript::void(0)" class="deleteMCQ" onclick="validate('.$val->id .')"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        echo json_encode($records);
    }
    
    function Caredit(Request $request, $id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'state_id'=>'required',
                'pax'=>'required',
                'car_name'=>'required',
                'car_per_day'=>'required',
                'driver_batta'=>'required'
               
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array("state_id" => $data['state_id'],"pax" => $data['pax'],"car_name" => $data['car_name'],"car_per_day" => $data['car_per_day'],"driver_batta" => $data['driver_batta'],"updated_at" => date('Y-m-d H:i:s'));
                $affectedRows = DynamicPriceCarPriceModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $dynamic_price = DynamicPriceCarPriceModel::where('id',$id)->first();
        $state = ToursModel::where('status',1)->get();
        return view('backend.dynamic_pricing.domestic_car.edit',compact('dynamic_price','state'));
    }
    
    public function updateCarDynamicPriceStatus(Request $request){
        $update = DynamicPriceCarPriceModel::where('id', $request->id)->update(['status' => $request->status]);
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => []
        ]);

    }
    /**
     * Remove the specified resource from storage.
     */
    public function Cardestroy(Request $request, $id){
         if ($request->ajax()){
            $course = DynamicPriceCarPriceModel::findOrFail($id);
            if ($course){
                $course->delete();
                return response()->json(array('success' => true));
            }
        }
    }
    
    
    
    public function CarBulkUpload(){
        $states = ToursModel::where('status', 1)->get();
        return view('backend.dynamic_pricing.domestic_car.bulk_upload', compact('states'));
    }

    /**
     * Process bulk upload
     */
    public function CarUpload(Request $request){
    $response_code = 200;
    $message = ['error' => ['Something went wrong']];
    $success = false;
    
    $data = $request->all();
    
    // Validation rules
    $rules = [
        'state_id' => 'required',
        'csv_file' => 'required|file|mimes:csv,txt|max:5120' // 5MB
    ];
    
    $messages = [
        'state_id.required' => 'Please select a state.',
        'state_id.exists' => 'Selected state is invalid.',
        'csv_file.required' => 'Please select a CSV file to upload.',
        'csv_file.mimes' => 'Only CSV files are allowed.',
        'csv_file.max' => 'File size should not exceed 5MB.'
    ];
    
    $validator = Validator::make($data, $rules, $messages);
    
    if ($validator->fails()) {
        $success = false;
        $message = $validator->errors();
        return ResponseHelper::returnResponse(200, $success, $message);
    }
    
    try {
        $file = $request->file('csv_file');
        $csvData = file_get_contents($file->getRealPath());
        
        // Parse the CSV data
        $rows = explode("\n", trim($csvData));
        $headers = str_getcsv(array_shift($rows));
        
        // Validate headers
        $expectedHeaders = ['Pax', 'Car Name', 'Car P/D', 'Driver Batta'];
        $headerDiff = array_diff($expectedHeaders, $headers);
        
        if (!empty($headerDiff)) {
            $success = false;
            $message = ['error' => ['Invalid CSV headers. Expected: ' . implode(', ', $expectedHeaders) . '. Found: ' . implode(', ', $headers)]];
            return ResponseHelper::returnResponse(200, $success, $message);
        }
        
        $carData = [];
        $errors = [];
        $rowNumber = 1;
        $successCount = 0;
        
        foreach ($rows as $row) {
            $rowNumber++;
            $columns = str_getcsv(trim($row));
            
            // Skip empty rows
            if (empty(array_filter($columns))) {
                continue;
            }
            
            // Validate row has exactly 4 columns
            if (count($columns) !== 4) {
                $errors[] = "Row {$rowNumber}: Invalid number of columns. Expected 4 columns, got " . count($columns) . ".";
                continue;
            }
            
            // Extract data
            $pax = trim($columns[0]);
            $carName = trim($columns[1]);
            $carPd = trim($columns[2]);
            $driverBatta = trim($columns[3]);
            
            // Validate Pax
            if (!is_numeric($pax) || $pax < 1) {
                $errors[] = "Row {$rowNumber}: Pax must be a number greater than 0.";
                continue;
            }
            
            // Validate Car Name
            if (empty($carName)) {
                $errors[] = "Row {$rowNumber}: Car Name is required.";
                continue;
            }
            
            // Validate Car P/D
            if (!is_numeric($carPd) || $carPd < 0) {
                $errors[] = "Row {$rowNumber}: Car P/D must be a valid number greater than or equal to 0.";
                continue;
            }
            
            // Validate Driver Batta
            if (!is_numeric($driverBatta) || $driverBatta < 0) {
                $errors[] = "Row {$rowNumber}: Driver Batta must be a valid number greater than or equal to 0.";
                continue;
            }
            
            // Add valid data
            $carData[] = [
                'pax' => (int)$pax,
                'car_name' => $carName,
                'car_per_day' => (float)$carPd,
                'driver_batta' => (float)$driverBatta
            ];
            $successCount++;
        }
        
        // Check if any errors occurred
        if (!empty($errors)) {
            $errorMessage = $errors;
            if ($successCount > 0) {
                $errorMessage[] = "{$successCount} records were processed successfully.";
            }
            $success = false;
            $message = ['error' => $errorMessage];
            return ResponseHelper::returnResponse(200, $success, $message);
        }
        
        // Check if data is empty
        if (empty($carData)) {
            $success = false;
            $message = ['error' => ['No valid data found to upload.']];
            return ResponseHelper::returnResponse(200, $success, $message);
        }
        
        // Delete existing records for this state
        DynamicPriceCarPriceModel::where('state_id', $data['state_id'])->delete();
        
        // Insert new records
        $insertData = [];
        foreach ($carData as $car) {
            $insertData[] = [
                'state_id' => $data['state_id'],
                'pax' => $car['pax'],
                'car_name' => $car['car_name'],
                'car_per_day' => $car['car_per_day'],
                'driver_batta' => $car['driver_batta'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        DynamicPriceCarPriceModel::insert($insertData);
        
        $success = true;
        $message = ['success' => ['Car data uploaded successfully. ' . count($carData) . ' records added.']];
        
    } catch (\Exception $e) {
        $success = false;
        $message = ['error' => ['Upload failed: ' . $e->getMessage()]];
    }
    
    return ResponseHelper::returnResponse($response_code, $success, $message);
}

    /**
     * Download sample CSV template
     */
    public function CarDownloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="car_prices_template.csv"',
        ];
        
        $columns = ['Pax', 'Car Name', 'Car P/D', 'Driver Batta'];
        $sampleData = [
            [1, 'Sedan / Swift Dizer Ac', '3000.00', '500.00'],
            [2, 'Sedan / Swift Dizer Ac', '3000.00', '550.00'],
            [3, 'Sedan / Swift Dizer Ac', '3000.00', '500.00'],
            [4, 'Ertiga Ac', '4000.00', '500.00'],
            [5, 'Innova Cresta Ac', '5500.00', '600.00'],
            [6, 'Innova Cresta Ac', '5500.00', '600.50']
        ];
        
        $callback = function() use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
