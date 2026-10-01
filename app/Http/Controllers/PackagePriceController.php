<?php

namespace App\Http\Controllers;

use App\Models\PackageModel;
use App\Models\PackageRoomPriceModel;
use App\Models\PackageCarPriceModel;
use Validator,Auth,DB;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PackagePriceController extends Controller{
    
    public function index(Request $request, $tour_id, $package_id){
        return view('backend.packages_price.list', compact('tour_id', 'package_id'));
    }

    public function create(Request $request, $id, $sub_id = null){
        if ($request->isMethod('post')) {
            $message = array('error' => array('something went wrong'));
            $success = false;
            $data = $request->all();
            
            // Get package
            $package = PackageModel::find($id);
            if (!$package) {
                return ResponseHelper::returnResponse(200, false, ['error' => ['Package not found']]);
            }
            $lastPackageId = $package->id;
            
            // Filter out empty rows before validation
            if (isset($data['room_config_3']) && is_array($data['room_config_3'])) {
                $data['room_config_3'] = array_filter($data['room_config_3'], function($item) {
                    return (!empty($item['hotel']) && $item['hotel'] != 'Hotel / Resort') || 
                           !empty($item['rooms']) || 
                           !empty($item['extra_bed_cost']) || 
                           !empty($item['total_cost']);
                });
                $data['room_config_3'] = array_values($data['room_config_3']);
            }
            
            if (isset($data['room_config_4']) && is_array($data['room_config_4'])) {
                $data['room_config_4'] = array_filter($data['room_config_4'], function($item) {
                    return (!empty($item['hotel']) && $item['hotel'] != 'Hotel / Resort') || 
                           !empty($item['rooms']) || 
                           !empty($item['extra_bed_cost']) || 
                           !empty($item['total_cost']);
                });
                $data['room_config_4'] = array_values($data['room_config_4']);
            }
            
            if (isset($data['room_config_5']) && is_array($data['room_config_5'])) {
                $data['room_config_5'] = array_filter($data['room_config_5'], function($item) {
                    return (!empty($item['hotel']) && $item['hotel'] != 'Hotel / Resort') || 
                           !empty($item['rooms']) || 
                           !empty($item['extra_bed_cost']) || 
                           !empty($item['total_cost']);
                });
                $data['room_config_5'] = array_values($data['room_config_5']);
            }
            
            if (isset($data['car_config']) && is_array($data['car_config'])) {
                $data['car_config'] = array_filter($data['car_config'], function($item) {
                    return !empty($item['pax']) || 
                           !empty($item['car_name']) || 
                           !empty($item['car_per_day']) || 
                           !empty($item['driver_batta']);
                });
                $data['car_config'] = array_values($data['car_config']);
            }
            
            // Validation rules
            $validator = Validator::make($data, [
                'room_config_3' => 'nullable|array',
                'room_config_3.*.hotel' => 'nullable|string|max:255',
                'room_config_3.*.rooms' => 'nullable|numeric|min:0',
                'room_config_3.*.extra_bed_cost' => 'nullable|numeric|min:0',
                'room_config_3.*.total_cost' => 'nullable|numeric|min:0',
                'room_config_4' => 'nullable|array',
                'room_config_4.*.hotel' => 'nullable|string|max:255',
                'room_config_4.*.rooms' => 'nullable|numeric|min:0',
                'room_config_4.*.extra_bed_cost' => 'nullable|numeric|min:0',
                'room_config_4.*.total_cost' => 'nullable|numeric|min:0',
                'room_config_5' => 'nullable|array',
                'room_config_5.*.hotel' => 'nullable|string|max:255',
                'room_config_5.*.rooms' => 'nullable|numeric|min:0',
                'room_config_5.*.extra_bed_cost' => 'nullable|numeric|min:0',
                'room_config_5.*.total_cost' => 'nullable|numeric|min:0',
                'car_config' => 'nullable|array',
                'car_config.*.pax' => 'nullable|integer|min:1|max:20',
                'car_config.*.car_name' => 'nullable|string|max:255',
                'car_config.*.car_per_day' => 'nullable|numeric|min:0',
                'car_config.*.driver_batta' => 'nullable|numeric|min:0',
            ]);
            
            if ($validator->fails()) {
                $success = false;
                $message = $validator->errors();
                return ResponseHelper::returnResponse(422, $success, $message);
            }
            
            try {
                DB::beginTransaction();
                
                // Delete existing records for this package to avoid duplicates
                PackageRoomPriceModel::where('package_id', $lastPackageId)->delete();
                PackageCarPriceModel::where('package_id', $lastPackageId)->delete();
                
                // Save 3 Star Room Configurations
                if (!empty($data['room_config_3']) && is_array($data['room_config_3'])) {
                    foreach ($data['room_config_3'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new PackageRoomPriceModel();
                            $roomPrice->package_id = $lastPackageId;
                            $roomPrice->star_category = '3';
                            $roomPrice->hotel_name = $roomConfig['hotel'] ?? '';
                            $roomPrice->rooms_cost = $roomConfig['rooms'] ?? 0;
                            $roomPrice->extra_bed_cost = $roomConfig['extra_bed_cost'] ?? 0;
                            $roomPrice->attract_tickets = $roomConfig['total_cost'] ?? 0;
                            $roomPrice->save();
                        }
                    }
                }
                
                // Save 4 Star Room Configurations
                if (!empty($data['room_config_4']) && is_array($data['room_config_4'])) {
                    foreach ($data['room_config_4'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new PackageRoomPriceModel();
                            $roomPrice->package_id = $lastPackageId;
                            $roomPrice->star_category = '4';
                            $roomPrice->hotel_name = $roomConfig['hotel'] ?? '';
                            $roomPrice->rooms_cost = $roomConfig['rooms'] ?? 0;
                            $roomPrice->extra_bed_cost = $roomConfig['extra_bed_cost'] ?? 0;
                            $roomPrice->attract_tickets = $roomConfig['total_cost'] ?? 0;
                            $roomPrice->save();
                        }
                    }
                }
                
                // Save 5 Star Room Configurations
                if (!empty($data['room_config_5']) && is_array($data['room_config_5'])) {
                    foreach ($data['room_config_5'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new PackageRoomPriceModel();
                            $roomPrice->package_id = $lastPackageId;
                            $roomPrice->star_category = '5';
                            $roomPrice->hotel_name = $roomConfig['hotel'] ?? '';
                            $roomPrice->rooms_cost = $roomConfig['rooms'] ?? 0;
                            $roomPrice->extra_bed_cost = $roomConfig['extra_bed_cost'] ?? 0;
                            $roomPrice->attract_tickets = $roomConfig['total_cost'] ?? 0;
                            $roomPrice->save();
                        }
                    }
                }
                
                // Save Car Configurations
                if (!empty($data['car_config']) && is_array($data['car_config'])) {
                    foreach ($data['car_config'] as $carConfig) {
                        if(!empty($carConfig['car_name'])){
                            $carPrice = new PackageCarPriceModel();
                            $carPrice->package_id = $lastPackageId;
                            $carPrice->pax = $carConfig['pax'] ?? null;
                            $carPrice->car_name = $carConfig['car_name'] ?? '';
                            $carPrice->car_per_day = $carConfig['car_per_day'] ?? 0;
                            $carPrice->driver_batta = $carConfig['driver_batta'] ?? 0;
                            $carPrice->save();
                        }
                    }
                }
                
                DB::commit();
                $success = true;
                $message = array('success' => array('Package price added successfully'));
                
            } catch (\Exception $e) {
                DB::rollback();
                $success = false;
                $message = array('error' => array('Database error: ' . $e->getMessage()));
                Log::error('Package price save error: ' . $e->getMessage());
            }
            
            return ResponseHelper::returnResponse(200, $success, $message);
        }
        
        return view('backend.packages_price.add', compact('id'));
    }

   public function getPackageData(Request $request, $tour_id, $package_id)
    {
        try {
            $draw   = (int) $request->input('draw', 1);
    
            $package = PackageModel::where('tour_id', $tour_id)
                ->where('id', $package_id)
                ->first();
    
            if (!$package) {
                return response()->json([
                    'draw' => $draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => []
                ]);
            }
    
            $carPrices = PackageCarPriceModel::where('package_id', $package->id)
                ->orderBy('id', 'asc')
                ->get();
    
            $roomPrices = PackageRoomPriceModel::where('package_id', $package->id)
                ->orderBy('star_category', 'asc')
                ->orderBy('id', 'asc')
                ->get();
    
            $carPricesHtml = '<span class="text-muted">No car prices added</span>';
            if ($carPrices->count() > 0) {
                $carPricesHtml = '<div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0" style="min-width:300px;">
                        <thead class="table-light">
                            <tr>
                                <th>Car Name</th>
                                <th>Per Day (₹)</th>
                                <th>Driver Batta (₹)</th>
                                <th>Pax</th>
                            </tr>
                        </thead>
                        <tbody>';
    
                foreach ($carPrices as $carPrice) {
                    $carPricesHtml .= '<tr>
                        <td>' . e($carPrice->car_name) . '</td>
                        <td>₹' . number_format((float) ($carPrice->car_per_day ?? 0), 2) . '</td>
                        <td>₹' . number_format((float) ($carPrice->driver_batta ?? 0), 2) . '</td>
                        <td>' . e($carPrice->pax) . '</td>
                    </tr>';
                }
    
                $carPricesHtml .= '</tbody></table></div>';
            }
    
            $roomPricesHtml = '<span class="text-muted">No room prices added</span>';
            if ($roomPrices->count() > 0) {
                $roomPricesHtml = '<div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0" style="min-width:500px;">
                        <thead class="table-light">
                            <tr>
                                <th>Star</th>
                                <th>Hotel Name</th>
                                <th>Room Cost (₹)</th>
                                <th>Extra Bed (₹)</th>
                                <th>Tickets (₹)</th>
                            </tr>
                        </thead>
                        <tbody>';
    
                foreach ($roomPrices as $roomPrice) {
                    $starCount = (int) ($roomPrice->star_category ?? 0);
                    $starDisplay = $starCount > 0 ? str_repeat('★', $starCount) : '-';
    
                    $roomPricesHtml .= '<tr>
                        <td>' . $starDisplay . '</td>
                        <td>' . e($roomPrice->hotel_name) . '</td>
                        <td>₹' . number_format((float) ($roomPrice->rooms_cost ?? 0), 2) . '</td>
                        <td>₹' . number_format((float) ($roomPrice->extra_bed_cost ?? 0), 2) . '</td>
                        <td>₹' . number_format((float) ($roomPrice->attract_tickets ?? 0), 2) . '</td>
                    </tr>';
                }
    
                $roomPricesHtml .= '</tbody></table></div>';
            }
    
            $status = $package->status == 1 ? 'checked' : '';
    
            $packageIdValue = $package->package_id ?? $package->id ?? '';
            $packageTitleValue = $package->package_title ?? $package->title ?? $package->name ?? '';
    
            $data = [[
                'DT_RowIndex' => 1,
                'package_id' => $packageIdValue,
                'package_title' => $packageTitleValue,
                'car_prices' => $carPricesHtml,
                'room_prices' => $roomPricesHtml,
                'created_at' => \Carbon\Carbon::parse($package->created_at)->format('d-m-Y H:i'),
                'status' => '<div class="form-check form-switch">
                    <input class="form-check-input status-update" type="checkbox" role="switch"
                        id="status_' . $package->id . '"
                        data-id="' . $package->id . '"
                        ' . $status . '>
                    <label class="form-check-label" for="status_' . $package->id . '"></label>
                </div>',
                'actions' => '<div class="btn-group" role="group">
                    <a href="' . route('edit.package.price', [$tour_id, $package_id, $package->id]) . '" class="btn btn-sm btn-primary">
                        <i class="bx bxs-edit"></i> Edit
                    </a>
                    <button type="button" onclick="validate(' . $package->id . ')" class="btn btn-sm btn-danger">
                        <i class="bx bxs-trash"></i> Delete
                    </button>
                </div>'
            ]];
    
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => 1,
                'recordsFiltered' => 1,
                'data' => $data
            ]);
        } catch (\Throwable $e) {
            Log::error('getPackageData error: ' . $e->getMessage());
    
            return response()->json([
                'draw' => (int) $request->input('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ], 500);
        }
    }
    
    public function edit(Request $request, $tour_id, $package_id, $id){
        if($request->isMethod('post')){
            $message = array('error'=>array('something went wrong'));
            $success = false;
            $data = $request->all();
            
            // Filter out empty rows
            if (isset($data['room_config_3']) && is_array($data['room_config_3'])) {
                $data['room_config_3'] = array_filter($data['room_config_3'], function($item) {
                    return (!empty($item['hotel']) && $item['hotel'] != 'Hotel / Resort') || 
                           !empty($item['rooms']) || 
                           !empty($item['extra_bed_cost']) || 
                           !empty($item['total_cost']);
                });
                $data['room_config_3'] = array_values($data['room_config_3']);
            }
            
            if (isset($data['room_config_4']) && is_array($data['room_config_4'])) {
                $data['room_config_4'] = array_filter($data['room_config_4'], function($item) {
                    return (!empty($item['hotel']) && $item['hotel'] != 'Hotel / Resort') || 
                           !empty($item['rooms']) || 
                           !empty($item['extra_bed_cost']) || 
                           !empty($item['total_cost']);
                });
                $data['room_config_4'] = array_values($data['room_config_4']);
            }
            
            if (isset($data['room_config_5']) && is_array($data['room_config_5'])) {
                $data['room_config_5'] = array_filter($data['room_config_5'], function($item) {
                    return (!empty($item['hotel']) && $item['hotel'] != 'Hotel / Resort') || 
                           !empty($item['rooms']) || 
                           !empty($item['extra_bed_cost']) || 
                           !empty($item['total_cost']);
                });
                $data['room_config_5'] = array_values($data['room_config_5']);
            }
            
            if (isset($data['car_config']) && is_array($data['car_config'])) {
                $data['car_config'] = array_filter($data['car_config'], function($item) {
                    return !empty($item['pax']) || 
                           !empty($item['car_name']) || 
                           !empty($item['car_per_day']) || 
                           !empty($item['driver_batta']);
                });
                $data['car_config'] = array_values($data['car_config']);
            }
            
            // Validation rules
            $validator = Validator::make($data, [
                'room_config_3' => 'nullable|array',
                'room_config_3.*.hotel' => 'nullable|string|max:255',
                'room_config_3.*.rooms' => 'nullable|numeric|min:0',
                'room_config_3.*.extra_bed_cost' => 'nullable|numeric|min:0',
                'room_config_3.*.total_cost' => 'nullable|numeric|min:0',
                'room_config_4' => 'nullable|array',
                'room_config_4.*.hotel' => 'nullable|string|max:255',
                'room_config_4.*.rooms' => 'nullable|numeric|min:0',
                'room_config_4.*.extra_bed_cost' => 'nullable|numeric|min:0',
                'room_config_4.*.total_cost' => 'nullable|numeric|min:0',
                'room_config_5' => 'nullable|array',
                'room_config_5.*.hotel' => 'nullable|string|max:255',
                'room_config_5.*.rooms' => 'nullable|numeric|min:0',
                'room_config_5.*.extra_bed_cost' => 'nullable|numeric|min:0',
                'room_config_5.*.total_cost' => 'nullable|numeric|min:0',
                'car_config' => 'nullable|array',
                'car_config.*.pax' => 'nullable|integer|min:1|max:20',
                'car_config.*.car_name' => 'nullable|string|max:255',
                'car_config.*.car_per_day' => 'nullable|numeric|min:0',
                'car_config.*.driver_batta' => 'nullable|numeric|min:0',
            ]);
            
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
                return ResponseHelper::returnResponse(422, $success, $message);
            }
            
            try {
                DB::beginTransaction();
                
                // Delete existing room prices and car prices for this package
                PackageRoomPriceModel::where('package_id', $id)->delete();
                PackageCarPriceModel::where('package_id', $id)->delete();
                
                // Save 3 Star Room Configurations
                if (!empty($data['room_config_3']) && is_array($data['room_config_3'])) {
                    foreach ($data['room_config_3'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new PackageRoomPriceModel();
                            $roomPrice->package_id = $id;
                            $roomPrice->star_category = '3';
                            $roomPrice->hotel_name = $roomConfig['hotel'] ?? '';
                            $roomPrice->rooms_cost = $roomConfig['rooms'] ?? 0;
                            $roomPrice->extra_bed_cost = $roomConfig['extra_bed_cost'] ?? 0;
                            $roomPrice->attract_tickets = $roomConfig['total_cost'] ?? 0;
                            $roomPrice->save();
                        }
                    }
                }
                
                // Save 4 Star Room Configurations
                if (!empty($data['room_config_4']) && is_array($data['room_config_4'])) {
                    foreach ($data['room_config_4'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new PackageRoomPriceModel();
                            $roomPrice->package_id = $id;
                            $roomPrice->star_category = '4';
                            $roomPrice->hotel_name = $roomConfig['hotel'] ?? '';
                            $roomPrice->rooms_cost = $roomConfig['rooms'] ?? 0;
                            $roomPrice->extra_bed_cost = $roomConfig['extra_bed_cost'] ?? 0;
                            $roomPrice->attract_tickets = $roomConfig['total_cost'] ?? 0;
                            $roomPrice->save();
                        }
                    }
                }
                
                // Save 5 Star Room Configurations
                if (!empty($data['room_config_5']) && is_array($data['room_config_5'])) {
                    foreach ($data['room_config_5'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new PackageRoomPriceModel();
                            $roomPrice->package_id = $id;
                            $roomPrice->star_category = '5';
                            $roomPrice->hotel_name = $roomConfig['hotel'] ?? '';
                            $roomPrice->rooms_cost = $roomConfig['rooms'] ?? 0;
                            $roomPrice->extra_bed_cost = $roomConfig['extra_bed_cost'] ?? 0;
                            $roomPrice->attract_tickets = $roomConfig['total_cost'] ?? 0;
                            $roomPrice->save();
                        }
                    }
                }
                
                // Save Car Configurations
                if (!empty($data['car_config']) && is_array($data['car_config'])) {
                    foreach ($data['car_config'] as $carConfig) {
                        if(!empty($carConfig['car_name'])){
                            $carPrice = new PackageCarPriceModel();
                            $carPrice->package_id = $id;
                            $carPrice->pax = $carConfig['pax'] ?? null;
                            $carPrice->car_name = $carConfig['car_name'] ?? '';
                            $carPrice->car_per_day = $carConfig['car_per_day'] ?? 0;
                            $carPrice->driver_batta = $carConfig['driver_batta'] ?? 0;
                            $carPrice->save();
                        }
                    }
                }
                
                DB::commit();
                $success = true;
                $message = array('success'=>array('Package price updated successfully'));
                
            } catch (\Exception $e) {
                DB::rollback();
                $success = false;
                $message = array('error'=>array('Database error: ' . $e->getMessage()));
                Log::error('Package price update error: ' . $e->getMessage());
            }
            
            return ResponseHelper::returnResponse(200, $success, $message);
        }
        
        // Get package details
        $package = PackageModel::find($id);
        
        // Load existing room prices
        $roomPrices = PackageRoomPriceModel::where('package_id', $id)->get();
        $carPrices = PackageCarPriceModel::where('package_id', $id)->get();
        
        // Group room prices by star category
        $roomPricesByStar = [
            '3' => $roomPrices->where('star_category', '3')->values(),
            '4' => $roomPrices->where('star_category', '4')->values(),
            '5' => $roomPrices->where('star_category', '5')->values(),
        ];
        
        return view('backend.packages_price.edit', compact('package', 'roomPricesByStar', 'carPrices', 'tour_id', 'package_id', 'id'));
    }
    
    public function updateStatus(Request $request){
        try {
            $update = PackageModel::where('id', $request->id)->update(['status' => $request->status]);
            return response()->json([
                'success' => true,
                'message' => "Status updated successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to update status"
            ]);
        }
    }

    public function destroy(Request $request, $id){
        if ($request->ajax()){
            try {
                // Delete related records first
                PackageRoomPriceModel::where('package_id', $id)->delete();
                PackageCarPriceModel::where('package_id', $id)->delete();
                
                // Delete the package
                $package = PackageModel::find($id);
                if ($package) {
                    $package->delete();
                    return response()->json(['success' => true, 'message' => 'Package deleted successfully!']);
                }
                
                return response()->json(['success' => false, 'message' => 'Package not found!']);
            } catch (\Exception $e) {
                Log::error('Package deletion error: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Failed to delete package!']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Invalid request']);
    }
}