<?php

namespace App\Http\Controllers;

use App\Models\InternationalPackageModel;
use App\Models\InternationalPackageRoomPriceModel;
use App\Models\InternationalPackageCarPriceModel;
use App\Models\ToursModel;
use App\Models\PackagesImagesModel;
use App\Models\VehicleImages;
use App\Models\HotelsImages;
use App\Models\PackageFlight;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class InternationalPackageController extends Controller{
    
    public function index(){
     return view('backend.packages.international.index');
    }

   
    public function create(Request $request,$id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'package_title'=>'required',
                'destination_type'=>'required',
                'days_count'=>'required',
                'meta_title'=>'required',
                'meta_keywords'=>'required',
                'meta_desc'=>'required',
                'image.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('package_homepage_image') != ''){
                    $package_homepage_image = 'uploads/package/'.$request->file('package_homepage_image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$package_homepage_image;
                    $request->file('package_homepage_image')->move('uploads/package/', $file_name);
                }
                if($request->file('package_banner_image') != ''){
                    $package_banner_image = 'uploads/package/'.$request->file('package_banner_image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$package_banner_image;
                    $request->file('package_banner_image')->move('uploads/package/', $file_name);
                }
                
               
                
                $tour_id = $id;
                $code = strtoupper(substr($data['package_title'], 0, 3)) . autoGenerate();
                $package = new InternationalPackageModel();
                $package->package_id = $code;
                $package->package_homepage_image = $package_homepage_image;
                $package->package_banner_image = $package_banner_image;
                $package->tour_id = $tour_id;
                $package->package_title = $data['package_title'];
                $package->destination_type = $data['destination_type'];
                $package->days_count = $data['days_count'];
                $package->nights_count = $data['nights_count'];
                $package->slug = slug($data['slug']);
                $package->s_individual = $data['s_individual'];
                $package->offer_text = $data['offer_text'];
                $package->d_individual = $data['d_individual'];
                $package->p_individual = $data['p_individual'];
                $package->pack_location_cover = $data['pack_location_cover'];
                $package->overview = $data['overview'];
                $package->inclusions = $data['inclusions'];
                $package->exclusions = $data['exclusions'];
                $package->old_price = $data['old_price'];
                $package->new_price = $data['new_price'];
                $package->amenities = $data['amenities'];
                $package->visit_places = $data['visit_places'];
                $package->meta_title = $data['meta_title'];
                $package->meta_keywords = $data['meta_keywords'];
                $package->meta_desc = $data['meta_desc'];
                $package->status = 1;
                $package->created_at = date('Y-m-d H:i:s');
                $package->save();
                
                $lastPackageId = InternationalPackageModel::max('id');
                $nextId = $lastPackageId;
                if(!empty($nextId)){
                    $package_flight = new PackageFlight();
                    $package_flight->package_id = $lastPackageId;
                    $package_flight->type = 'international';
                    $package_flight->form_date = $data['form_date'] ?? '';
                    $package_flight->to_date = $data['to_date'] ?? '';
                    $package_flight->from_time = $data['from_time'] ?? '';
                    $package_flight->to_time = $data['to_time'] ?? '';
                    $package_flight->start_place = $data['start_place'] ?? '';
                    $package_flight->end_place = $data['end_place'] ?? '';
                    $package_flight->cabin_place = $data['cabin_place'] ?? '';
                    $package_flight->check_in = $data['check_in'] ?? '';
                    $package_flight->save();
                }
                 // Save 2 Star Room Configurations
                if (!empty($data['room_config_2']) && is_array($data['room_config_2'])) {
                    foreach ($data['room_config_2'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new InternationalPackageRoomPriceModel();
                            $roomPrice->package_id = $lastPackageId;
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
                            $roomPrice = new InternationalPackageRoomPriceModel();
                            $roomPrice->package_id = $lastPackageId;
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
                            $roomPrice = new InternationalPackageRoomPriceModel();
                            $roomPrice->package_id = $lastPackageId;
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
                            $roomPrice = new InternationalPackageRoomPriceModel();
                            $roomPrice->package_id = $lastPackageId;
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
                
                if (!empty($data['car_config']) && is_array($data['car_config'])) {
                    foreach ($data['car_config'] as $carConfig) {
                        if(!empty($carConfig['car_name'])){
                            $carPrice = new InternationalPackageCarPriceModel();
                            $carPrice->package_id = $lastPackageId;
                            $carPrice->pax = $carConfig['pax'] ?? null;
                            $carPrice->car_name = $carConfig['car_name'] ?? '';
                            $carPrice->car_per_day = $carConfig['car_per_day'] ?? 0;
                            $carPrice->driver_batta = $carConfig['driver_batta'] ?? 0;
                            $carPrice->save();
                        }
                    }
                }
                if($request->hasFile('image')) {
                    foreach ($request->file('image') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $file_name = time().'_'.rand(100, 999). '_'.$originalName;
                        $file->move('uploads/package/', $file_name);
                        $package_image = new PackagesImagesModel();
                        $package_image->package_id = $nextId;
                        $package_image->images = $file_name;
                        $package_image->save(); // Don't forget to save!
                    }
                }
                
                if($request->hasFile('vehicle_image')) {
                    foreach ($request->file('vehicle_image') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $file_name = 'vehicle_image_'.time().'_'.rand(100, 999). '_'.$originalName;
                        $file->move('uploads/package/', $file_name);
                        $package_image = new VehicleImages();
                        $package_image->package_id = $nextId;
                        $package_image->images = $file_name;
                        $package_image->save(); // Don't forget to save!
                    }
                }
                
                if($request->hasFile('hotels_image')) {
                    foreach ($request->file('hotels_image') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $file_name = 'hotels_image_'.time().'_'.rand(100, 999). '_'.$originalName;
                        $file->move('uploads/package/', $file_name);
                        $package_image = new HotelsImages();
                        $package_image->package_id = $nextId;
                        $package_image->images = $file_name;
                        $package_image->save(); // Don't forget to save!
                    }
                }
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('backend.packages.international.add');
    }

    function getPackageData(Request $request, $tour_id){
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
        $total = InternationalPackageModel::Where(function ($query) use ($search) {
                $query->Where('international_packages.package_id', 'like', '%' . $search . '%')->where('tour_id',request()->segment(3));
            })->count();

        $course = InternationalPackageModel::Where(function ($query) use ($search) {
                $query->Where('international_packages.package_id', 'like', '%' . $search . '%')->where('tour_id',request()->segment(3));
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        $i = 1 + $offset;
        $data = [];
        foreach ($course as $key => $val) {
            $status = $val->status ? "checked" : "";
            $new_image = asset(''). $val->image;
            $image = ($val->image) ? '<img src="' .$new_image . '" class="img-fluid" height="100px" width="100px">' : '';
            $data[] = array(
                $key + 1,
                $val->package_id,
                $val->package_title,
                \Carbon\Carbon::parse($val->created_at)->format('d-m-Y'),
                '<a href="' . route('list.international.itinerary', [request()->segment(3), $val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="lni lni-circle-plus text-success" style="font-size: 22px;"></i></a>',
                '<td class=" text-center"><button class="btn btn-sm btn-info view-price-details" data-id="' . $val->id . '" title="View Details"><i class="lni lni-eye text-white"></i></button></td>',
                '<a href="' . route('international.package.highlights.list', [request()->segment(3), $val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="lni lni-circle-plus text-success" style="font-size: 22px;"></i></a>',
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.international.packages', [request()->segment(3), $val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
                <a  href="javascript::void(0)" class="deleteMCQ" onclick="validate('.$val->id .')"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        echo json_encode($records);
    }
    
    function edit(Request $request, $tour_id, $id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'package_title'=>'required',
                'destination_type'=>'required',
                'days_count'=>'required',
                'meta_title'=>'required',
                'meta_keywords'=>'required',
                'meta_desc'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                
                if($request->file('package_homepage_image') != ''){
                    $package_homepage_image = 'uploads/package/'.$request->file('package_homepage_image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$package_homepage_image;
                    $request->file('package_homepage_image')->move('uploads/package/', $file_name);
                }else{
                    $old_image =  InternationalPackageModel::where('id',$id)->first();
                    $package_homepage_image =$old_image->package_homepage_image;
                }
                
                if($request->file('package_banner_image') != ''){
                    $package_banner_image = 'uploads/package/'.$request->file('package_banner_image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$package_banner_image;
                    $request->file('package_banner_image')->move('uploads/package/', $file_name);
                }else{
                    $old_image =  InternationalPackageModel::where('id',$id)->first();
                    $package_banner_image =$old_image->package_banner_image;
                }
                $lastPackageId = $id;
                if(!empty($data['package_flight_id'])){
                    $update_arr = array("form_date" => $data['form_date'],"to_date" => $data['to_date'],"from_time" => $data['from_time'],"to_time" => $data['to_time'],"start_place" => $data['start_place'],"offer_text" => $data['offer_text'],"end_place" => $data['end_place'],"cabin_place" => $data['cabin_place'],"check_in" => $data['check_in']);
                    $affectedRowss = PackageFlight::where("id", $data['package_flight_id'])->update($update_arr);
                }else{
                    $package_flight = new PackageFlight();
                    $package_flight->package_id = $lastPackageId;
                    $package_flight->type = 'international';
                    $package_flight->form_date = $data['form_date'] ?? '';
                    $package_flight->to_date = $data['to_date'] ?? '';
                    $package_flight->from_time = $data['from_time'] ?? '';
                    $package_flight->to_time = $data['to_time'] ?? '';
                    $package_flight->start_place = $data['start_place'] ?? '';
                    $package_flight->end_place = $data['end_place'] ?? '';
                    $package_flight->cabin_place = $data['cabin_place'] ?? '';
                    $package_flight->check_in = $data['check_in'] ?? '';
                    $package_flight->save(); 
                }
                 if($request->hasFile('image')) {
                    foreach ($request->file('image') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $file_name = time().'_'.rand(100, 999). '_'.$originalName;
                        $file->move('uploads/package/', $file_name);
                        $package_image = new PackagesImagesModel();
                        $package_image->package_id = $lastPackageId;
                        $package_image->images = $file_name;
                        $package_image->save(); // Don't forget to save!
                    }
                }
                
                if($request->hasFile('vehicle_image')) {
                    foreach ($request->file('vehicle_image') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $file_name = 'vehicle_image_'.time().'_'.rand(100, 999). '_'.$originalName;
                        $file->move('uploads/package/', $file_name);
                        $package_image = new VehicleImages();
                        $package_image->package_id = $lastPackageId;
                        $package_image->images = $file_name;
                        $package_image->save(); // Don't forget to save!
                    }
                }
                
                if($request->hasFile('hotels_image')) {
                    foreach ($request->file('hotels_image') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $file_name = 'hotels_image_'.time().'_'.rand(100, 999). '_'.$originalName;
                        $file->move('uploads/package/', $file_name);
                        $package_image = new HotelsImages();
                        $package_image->package_id = $lastPackageId;
                        $package_image->images = $file_name;
                        $package_image->save(); // Don't forget to save!
                    }
                }
                
                
                 // Save 2 Star Room Configurations
                if (!empty($data['room_config_2']) && is_array($data['room_config_2'])) {
                    foreach ($data['room_config_2'] as $roomConfig) {
                        if(!empty($roomConfig['hotel']) && $roomConfig['hotel'] != 'Hotel / Resort'){
                            $roomPrice = new InternationalPackageRoomPriceModel();
                            $roomPrice->package_id = $lastPackageId;
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
                            $roomPrice = new InternationalPackageRoomPriceModel();
                            $roomPrice->package_id = $lastPackageId;
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
                            $roomPrice = new InternationalPackageRoomPriceModel();
                            $roomPrice->package_id = $lastPackageId;
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
                            $roomPrice = new InternationalPackageRoomPriceModel();
                            $roomPrice->package_id = $lastPackageId;
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
                
                
                if (!empty($data['car_config']) && is_array($data['car_config'])) {
                    foreach ($data['car_config'] as $carConfig) {
                        if(!empty($carConfig['car_name'])){
                            $carPrice = new InternationalPackageCarPriceModel();
                            $carPrice->package_id = $lastPackageId;
                            $carPrice->pax = $carConfig['pax'] ?? null;
                            $carPrice->car_name = $carConfig['car_name'] ?? '';
                            $carPrice->car_per_day = $carConfig['car_per_day'] ?? 0;
                            $carPrice->driver_batta = $carConfig['driver_batta'] ?? 0;
                            $carPrice->save();
                        }
                    }
                }
                
                $update_arr = array("slug" => slug($data['slug']),"package_homepage_image" => $package_homepage_image,"package_banner_image" => $package_banner_image,"package_title" => $data['package_title'],"destination_type" => $data['destination_type'],"days_count" => $data['days_count'],"nights_count" => $data['nights_count'],"s_individual" => $data['s_individual'],"d_individual" => $data['d_individual'],"p_individual" => $data['p_individual'],"pack_location_cover" => $data['pack_location_cover'],"overview" => $data['overview'],"inclusions" => $data['inclusions'],"exclusions" => $data['exclusions'],"old_price" => $data['old_price'],"new_price" => $data['new_price'],"amenities" => $data['amenities'],"visit_places" => $data['visit_places'],"meta_title" => $data['meta_title'],"meta_keywords" => $data['meta_keywords'],"meta_desc" => $data['meta_desc']);
                $affectedRows = InternationalPackageModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $package = InternationalPackageModel::where('id',$id)->first();
        $package_image = PackagesImagesModel::where('package_id',$id)->get();
        $vehicle_image = VehicleImages::where('package_id',$id)->get();
        $hotels_image = HotelsImages::where('package_id',$id)->get();
        $package_room_prices = InternationalPackageRoomPriceModel::where('package_id',$id)->get();
        $package_car_prices = InternationalPackageCarPriceModel::where('package_id',$id)->get();
        $package_flight = PackageFlight::where('type','international')->where('package_id',$id)->orderBy('id', 'asc')->first();
        return view('backend.packages.international.edit',compact('package_flight','package','package_image','package_room_prices','package_car_prices','hotels_image','vehicle_image'));
    }
    
    public function updateRoomPrice(Request $request, $packageId)
    {
        //dd($request->all());
        try {
            $validated = $request->validate([
                'price_id' => 'required|exists:package_room_prices,id',
                'hotel_name' => 'required|string|max:255',
                'star_category' => 'required|in:2,3,4,5',
                'rooms_cost' => 'required|numeric|min:0',
                'extra_bed_cost' => 'required|numeric|min:0',
                'attract_tickets' => 'required|numeric|min:0',
                'honeymoon_cost' => 'required|numeric|min:0',
            ]);
            
            $roomPrice = InternationalPackageRoomPriceModel::findOrFail($request->price_id);
            $roomPrice->update([
                'hotel_name' => $request->hotel_name,
                'star_category' => $request->star_category,
                'rooms_cost' => $request->rooms_cost,
                'extra_bed_cost' => $request->extra_bed_cost,
                'attract_tickets' => $request->attract_tickets,
                'honeymoon_cost' => $request->honeymoon_cost,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Room price updated successfully',
                'data' => $roomPrice
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Room price record not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function updateCarPrice(Request $request, $packageId){
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'car_id' => 'required|exists:package_car_prices,id',
                'pax' => 'required|integer|min:1',
                'car_name' => 'required|string|max:255',
                'car_per_day' => 'required|numeric|min:0',
                'driver_batta' => 'required|numeric|min:0',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find and update the car price record
            $carPrice = InternationalPackageCarPriceModel::findOrFail($request->car_id);
            
            // Verify that the car price belongs to the package
            if ($carPrice->package_id != $packageId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $carPrice->pax = $request->pax;
            $carPrice->car_name = $request->car_name;
            $carPrice->car_per_day = $request->car_per_day;
            $carPrice->driver_batta = $request->driver_batta;
            $carPrice->save();

            return response()->json([
                'success' => true,
                'message' => 'Car price updated successfully',
                'data' => [
                    'id' => $carPrice->id,
                    'pax' => $carPrice->pax,
                    'car_name' => $carPrice->car_name,
                    'car_per_day' => $carPrice->car_per_day,
                    'driver_batta' => $carPrice->driver_batta
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update car price: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePackagesStatus(Request $request){
        $update = InternationalPackageModel::where('id', $request->id)->update(['status' => $request->status]);
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
            $state = InternationalPackageModel::findOrFail($id);
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
        
        
    public function VehicleImageDestroy($imageId){
        try {
            $image = VehicleImages::findOrFail($imageId);
            $image->delete();
            return response()->json(['success' => true,'message' => 'Image deleted successfully!'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the image.'
            ], 500);
        }
    }
    
    
    public function HotelsImageDestroy($imageId){
        try {
            $image = HotelsImages::findOrFail($imageId);
            $image->delete();
            return response()->json(['success' => true,'message' => 'Image deleted successfully!'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the image.'
            ], 500);
        }
    }
    
    public function ImageDestroy($imageId){
        try {
            $image = PackagesImagesModel::findOrFail($imageId);
            $image->delete();
            return response()->json(['success' => true,'message' => 'Image deleted successfully!'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the image.'
            ], 500);
        }
    }
    
      
      
    public function getPackagePriceDetails(Request $request, $id = null){
        try {
            $packageId = $id ?: $request->input('id');
            if (!$packageId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package ID is required'
                ], 400);
            }
            // Get package room prices
            $roomPrices = InternationalPackageRoomPriceModel::where('package_id', $packageId)
                ->orderBy('star_category', 'asc')
                ->get();
            // Get package car prices
            $carPrices = InternationalPackageCarPriceModel::where('package_id', $packageId)
                ->orderBy('pax', 'asc')
                ->get();
            return response()->json([
                'success' => true,
                'data' => [
                    'room_prices' => $roomPrices,
                    'car_prices' => $carPrices
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching package details: ' . $e->getMessage()
            ], 500);
        }
    }

    
    
}
