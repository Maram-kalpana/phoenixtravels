<?php

namespace App\Http\Controllers;

use App\Models\HoneymoonItineraryModel;
use App\Models\ItineraryImages;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class HoneymoonItineraryController extends Controller{
    
    public function index(){
     return view('backend.itinerary.honeymoon.index');
    }

   
    public function create(Request $request,$tour_id, $package_id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'question'=>'required',
                'answaer'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('image') != ''){
                    $image = 'uploads/itinerary/'.$request->file('image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$image;
                    $request->file('image')->move('uploads/itinerary/', $file_name);
                }
                if($request->file('image_two') != ''){
                    $image_two = 'uploads/itinerary/'.$request->file('image_two')->getClientOriginalName();
                    $file_name = time().rand(100,999).$image_two;
                    $request->file('image_two')->move('uploads/itinerary/', $file_name);
                }
                $itinerary = new HoneymoonItineraryModel();
                $itinerary->package_id = $package_id;
                $itinerary->image = $image ?? '';
                $itinerary->image_two = $image_two ?? '';
                $itinerary->question = $data['question'];
                $itinerary->answer = $data['answaer'];
                $itinerary->short_description = $data['short_description'];
                $itinerary->status = 1;
                $itinerary->created_at = date('Y-m-d H:i:s');
                $itinerary->save();
                $lastItineraryId = HoneymoonItineraryModel::max('id');
                $nextId = $lastItineraryId;
                if($request->hasFile('h_image')) {
                    foreach ($request->file('h_image') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $file_name = 'honeymoon_itinerary_image_'.time().'_'.rand(100, 999). '_'.$originalName;
                        $file->move('uploads/itinerary/', $file_name);
                        $itinerary_image = new ItineraryImages();
                        $itinerary_image->itinerary_id = $nextId;
                        $itinerary_image->type = 'honeymoon';
                        $itinerary_image->image = $file_name;
                        $itinerary_image->save(); // Don't forget to save!
                    }
                }
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('backend.itinerary.honeymoon.add');
    }

    function getItineraryData(Request $request, $tour_id, $package_id){
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
        $total = HoneymoonItineraryModel::Where(function ($query) use ($search) {
                $query->Where('honeymoon_itinerary.question', 'like', '%' . $search . '%')->where('package_id',request()->segment(4));
            })->count();

        $course = HoneymoonItineraryModel::Where(function ($query) use ($search) {
                $query->Where('honeymoon_itinerary.question', 'like', '%' . $search . '%')->where('package_id',request()->segment(4));
            })
            ->offset($offset)->limit($limit)->orderBy('order_column', 'asc')->orderBy('id', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        $i = 1 + $offset;
        $data = [];
        foreach ($course as $key => $val) {
            $status = $val->status ? "checked" : "";
            $new_image = asset(''). $val->image;
            $image = ($val->image) ? '<img src="' .$new_image . '" class="img-fluid" height="100px" width="100px">' : '';
            $data[] = array(
                '<span class="drag-handle"><i class="bx bx-menu"></i></span>',
                $key + 1,
                $val->question,
                \Carbon\Carbon::parse($val->created_at)->format('d-m-Y'),
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.honeymoon.itinerary', [request()->segment(3),request()->segment(4), $val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
                <a  href="javascript::void(0)" class="deleteMCQ" onclick="validate('.$val->id .')"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        echo json_encode($records);
    }
    
    public function reorder(Request $request){
        try {
            $ids = $request->ids;
            
            foreach ($ids as $index => $id) {
                $itinerary = HoneymoonItineraryModel::find($id);
                if ($itinerary) {
                    $itinerary->order_column = $index;
                    $itinerary->save();
                }
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Order updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    function edit(Request $request, $tour_id,$package_id, $id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                 'question'=>'required',
                'answaer'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                
                if($request->file('image') != ''){
                    $image = 'uploads/itinerary/'.$request->file('image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$image;
                    $request->file('image')->move('uploads/itinerary/', $file_name);
                }else{
                    $old_image =  HoneymoonItineraryModel::where('id',$id)->first();
                    $image =$old_image->image;
                }
                
                if($request->file('image_two') != ''){
                    $image_two = 'uploads/itinerary/'.$request->file('image_two')->getClientOriginalName();
                    $file_name = time().rand(100,999).$image_two;
                    $request->file('image_two')->move('uploads/itinerary/', $file_name);
                }else{
                    $old_two_image =  HoneymoonItineraryModel::where('id',$id)->first();
                    $image_two =$old_two_image->image_two;
                }
                if($request->hasFile('h_image')) {
                    foreach ($request->file('h_image') as $file) {
                        $originalName = $file->getClientOriginalName();
                        $file_name = 'honeymoon_itinerary_image_'.time().'_'.rand(100, 999). '_'.$originalName;
                        $file->move('uploads/itinerary/', $file_name);
                        $itinerary_image = new ItineraryImages();
                        $itinerary_image->itinerary_id = $id;
                        $itinerary_image->type = 'honeymoon';
                        $itinerary_image->image = $file_name;
                        $itinerary_image->save(); // Don't forget to save!
                    }
                }
                $update_arr = array("image" => $image,"image_two" => $image_two,"question" => $data['question'],"answer" => $data['answaer']);
                $affectedRows = HoneymoonItineraryModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $itinerary = HoneymoonItineraryModel::where('id',$id)->first();
        $itinerary_image = ItineraryImages::where('type','honeymoon')->where('itinerary_id',$id)->get();
        return view('backend.itinerary.honeymoon.edit',compact('itinerary','itinerary_image'));
    }
    
    public function ImageDestroy($imageId){
        try {
            $image = ItineraryImages::findOrFail($imageId);
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
    public function updateItineraryStatus(Request $request){
        $update = HoneymoonItineraryModel::where('id', $request->id)->update(['status' => $request->status]);
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => []
        ]);

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request){
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.'
            ], 400);
        }
        $id = $request->id;
        $state = HoneymoonItineraryModel::find($id);
        
        if (!$state) {
            return response()->json([
                'success' => false,
                'message' => 'Honeymoon itinerary not found.'
            ], 404);
        }
        $state->delete();
        return response()->json([
            'success' => true,
            'message' => 'Honeymoon itinerary deleted successfully.'
        ]);
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
