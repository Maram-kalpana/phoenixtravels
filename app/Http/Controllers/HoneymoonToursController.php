<?php

namespace App\Http\Controllers;

use App\Models\HoneymoonToursModel;
use App\Models\CustomSection;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class HoneymoonToursController extends Controller{
    
    public function index(){
     return view('backend.tours.honeymoon.index');
    }

   
    public function create(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'tour_category_id'=>'required',
                'tour_name'=>'required',
                'image'=>'required',
                'meta_title'=>'required',
                'meta_keywords'=>'required',
                'meta_description'=>'required'
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
                $tours = new HoneymoonToursModel();
                $tours->image = $image;
                $tours->tour_category_id = $data['tour_category_id'];
                $tours->tour_name = $data['tour_name'];
                $tours->slug = slug($data['tour_name']);
                $tours->description = $data['description'];
                $tours->meta_title = $data['meta_title'];
                $tours->meta_keywords = $data['meta_keywords'];
                $tours->meta_description = $data['meta_description'];
                $tours->status = 1;
                $tours->created_at = date('Y-m-d H:i:s');
                $tours->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $custom_section = CustomSection::where('status',1)->get();
        return view('backend.tours.honeymoon.add',compact('custom_section'));
    }

    function getToursData(Request $request){
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
        $total = HoneymoonToursModel::Where(function ($query) use ($search) {
                $query->Where('honeymoon_tour.tour_name', 'like', '%' . $search . '%')->whereIn('tour_category_id',[1,2]);
            })->count();

        $course = HoneymoonToursModel::Where(function ($query) use ($search) {
                $query->Where('honeymoon_tour.tour_name', 'like', '%' . $search . '%')->whereIn('tour_category_id',[1,2]);
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($course as $key => $val) {
            $status = $val->status ? "checked" : "";
             $new_image = asset(''). $val->image;
            $package_image = asset(''). 'uploads/tracking-number_16767935.png';
            $image = ($val->image) ? '<img src="' .$new_image . '" class="img-fluid" height="100px" width="100px">' : '';
            $data[] = array(
                $key + 1,
                getTourCategoryName($val->tour_category_id),
                $val->tour_name,
                $image,
                '<a href="' . route('list.honeymoon.packages', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><img src="' .$package_image . '" class="img-fluid" height="45px" width="45px"></a>',
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.honeymoon.tours', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
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
                  $old_image =  HoneymoonToursModel::where('id',$id)->first();
                  $banner_image =$old_image->image;
                }
                $update_arr = array("image" => $banner_image,"tour_category_id" => $data['tour_category_id'],"slug" => slug($data['tour_name']),"tour_name" => $data['tour_name'],"description" => $data['description'],"meta_title" => $data['meta_title'],"meta_keywords" => $data['meta_keywords'],"meta_description" => $data['meta_description']);
                $affectedRows = HoneymoonToursModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $custom_section = CustomSection::where('status',1)->get();
        $tours = HoneymoonToursModel::where('id',$id)->first();
        return view('backend.tours.honeymoon.edit',compact('tours','custom_section'));
    }
    public function updateToursStatus(Request $request){
        $update = HoneymoonToursModel::where('id', $request->id)->update(['status' => $request->status]);
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
            $state = HoneymoonToursModel::findOrFail($id);
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
