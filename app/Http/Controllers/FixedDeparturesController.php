<?php

namespace App\Http\Controllers;
use App\Models\ToursModel;
use App\Models\InternationalToursModel;
use App\Models\FixedDepartures;
use App\Models\DepartureDates;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class FixedDeparturesController extends Controller{
    
    public function index(){
     return view('backend.fixed_departures.index');
    }

   
    public function create(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'package_name'=>'required',
                'days_count'=>'required',
                'image'=>'required',
                'nights_count'=>'required',
                'price'=>'required',
                'meeting_point'=>'required',
                'itinerary_id'=>'required'
               
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('image') != ''){
                    $image = 'uploads/tours/'.$request->file('image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$image;
                    $request->file('image')->move('uploads/tours/', $file_name);
                }
                if($request->tour_category_id == 2){
                    $tour_id = $request->international_tour_id;
                }else{
                    $tour_id =  $request->domestic_tour_id;
                }
                $code = strtoupper(substr($data['package_name'], 0, 3)) ."FD". autoFGenerate();
                $tours = new FixedDepartures();
                $tours->package_id = $code;
                $tours->tour_category_id = $data['tour_category_id'];
                $tours->slug = slug($data['package_name']);
                $tours->tour_id = $tour_id;
                $tours->package_image = $image;
                $tours->package_name = $data['package_name'];
                $tours->days_count = $data['days_count'];
                $tours->nights_count = $data['nights_count'];
                $tours->itinerary_id = $data['itinerary_id'];
                $tours->price = $data['price'];
                $tours->meeting_point = $data['meeting_point'];
                $tours->pack_location_cover = $data['pack_location_cover'];
                $tours->status = 1;
                $tours->created_at = date('Y-m-d H:i:s');
                $tours->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $tour = ToursModel::where('tour_category_id',1)->where('status',1)->get();
        $international_tour = InternationalToursModel::where('tour_category_id',2)->where('status',1)->get();
        return view('backend.fixed_departures.add',compact('tour','international_tour'));
    }

    function getFixedDeparturesData(Request $request){
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
        $total = FixedDepartures::Where(function ($query) use ($search) {
                $query->Where('fixed_departures.package_name', 'like', '%' . $search . '%');
            })->count();

        $course = FixedDepartures::Where(function ($query) use ($search) {
                $query->Where('fixed_departures.package_name', 'like', '%' . $search . '%');
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($course as $key => $val) {
            $status = $val->status ? "checked" : "";
            $new_image = asset(''). $val->package_image;
            $image = ($val->package_image) ? '<img src="' .$new_image . '" class="img-fluid" height="100px" width="100px">' : '';
            $duration = $val->nights_count." Night - ".$val->days_count." Day";
            $data[] = array(
                $key + 1,
                getTourCategoryName($val->tour_category_id),
                getTourName($val->tour_id),
                $val->package_name,
                $image,
                $duration,
                $val->price,
                $val->meeting_point,
                '<a href="' . route('list.departures.dates', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="lni lni-circle-plus text-success" style="font-size: 22px;"></i></a>',
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.FixedDepartures', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
                <a  href="javascript::void(0)" class="deleteMCQ" onclick="validate('.$val->id .')"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
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
            $rules=['package_name'=>'required','days_count'=>'required','nights_count'=>'required','price'=>'required','meeting_point'=>'required','itinerary_id'=>'required','pack_location_cover'=>'required'];
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
                  $old_image =  FixedDepartures::where('id',$id)->first();
                  $banner_image = $old_image->package_image;
                }
                if($request->tour_category_id == 2){
                    $tour_id = $request->international_tour_id;
                }else{
                    $tour_id =  $request->domestic_tour_id;
                }
                $update_arr = array("tour_id" => $tour_id,"slug" => slug($data['package_name']),"package_image" => $banner_image,"tour_category_id" => $data['tour_category_id'],"package_name" => $data['package_name'],"package_name" => $data['package_name'],"days_count" => $data['days_count'],"nights_count" => $data['nights_count'],"price" => $data['price'],"meeting_point" => $data['meeting_point'],"pack_location_cover" => $data['pack_location_cover'],"itinerary_id" => $data['itinerary_id']);
                $affectedRows = FixedDepartures::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $tour = ToursModel::where('tour_category_id',1)->where('status',1)->get();
        $international_tour = InternationalToursModel::where('tour_category_id',2)->where('status',1)->get();
        $fixed_departures = FixedDepartures::where('id',$id)->first();
        return view('backend.fixed_departures.edit',compact('tour','fixed_departures','international_tour'));
    }
    public function updateFixedDeparturesStatus(Request $request){
        $update = FixedDepartures::where('id', $request->id)->update(['status' => $request->status]);
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
            $state = FixedDepartures::findOrFail($id);
            if ($state){
                $state->delete();
                return response()->json(array('success' => true));
            }
        }
    }

     
}
