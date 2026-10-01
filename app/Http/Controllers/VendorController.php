<?php

namespace App\Http\Controllers;
use App\Models\CityModel;
use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\VendorModel;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
     return view('backend.vendor.index');
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
            $rules=[
                'full_name'=>'required',
                'email'=>'required',
                'phone'=>'required',
                'country_id'=>'required',
                'address'=>'required',
                'state_id'=>'required',
                'city_id'=>'required',
                'pin_code'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('profile_image') != ''){
                    $image = 'uploads/profile/'.$request->file('profile_image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$image;
                    $request->file('profile_image')->move('uploads/profile/', $file_name);
                }
                $vendor = new VendorModel();
                $vendor->full_name = $data['full_name'];
                $vendor->email = $data['email'];
                $vendor->phone = $data['phone'];
                $vendor->profile_image = $image;
                $vendor->country_id = $data['country_id'];
                $vendor->address = $data['address'];
                $vendor->state_id = $data['state_id'];
                $vendor->city_id = $data['city_id'];
                $vendor->pin_code = $data['pin_code'];
                $vendor->status = 1;
                $vendor->created_at = date('Y-m-d H:i:s');
                $vendor->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $country = CountryModel::where('status',1)->get();
        return view('backend.vendor.add',compact('country'));
    }

    function getVendorData(Request $request){
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
        $total = VendorModel::Where(function ($query) use ($search) {
                $query->Where('vendor.full_name', 'like', '%' . $search . '%');
            })->count();

        $students = VendorModel::Where(function ($query) use ($search) {
                $query->Where('vendor.full_name', 'like', '%' . $search . '%');
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($students as $key => $val) {
            $status = $val->status ? "checked" : "";
            $new_image = asset(''). $val->profile_image;
            $image = ($val->profile_image) ? '<img src="' .$new_image . '" class="img-fluid" height="100px" width="100px">' : '';
            $data[] = array(
                $key + 1,
                $val->full_name,
                $val->email,
                $val->phone,
                $image,
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.vendor', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | <a  href="javascript::void(0)" class="deleteMCQ" onclick="validate('.$val->id .')" data-id="' . $val->id . '"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
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
               'full_name'=>'required',
                'email'=>'required',
                'phone'=>'required',
                'country_id'=>'required',
                'address'=>'required',
                'state_id'=>'required',
                'city_id'=>'required',
                'pin_code'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('profile_image') != ''){
                    $data['profile_image'] = 'uploads/profile/'.$request->file('profile_image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$data['profile_image'];
                    $request->file('profile_image')->move('uploads/profile/', $file_name);
                }else{
                  $old_image =  VendorModel::where('id',$id)->first();
                  $data['profile_image'] =$old_image->profile_image;
                }
                $update_arr = array("full_name" => $data['full_name'],"profile_image" => $data['profile_image'],"email" => $data['email'],"phone" => $data['phone'],"country_id" => $data['country_id'],"address" => $data['address'],"state_id" => $data['state_id'],"city_id" => $data['city_id'],"pin_code" => $data['pin_code']);
                $affectedRows = VendorModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $user = VendorModel::where('id',$id)->first();
        $city = CityModel::where('status',1)->get();
        $state = StateModel::where('status',1)->get();
        $country = CountryModel::where('status',1)->get();
        return view('backend.vendor.edit',compact('user','state','country','city'));
    }
    public function updateVendorStatus(Request $request){
        $update = VendorModel::where('id', $request->id)->update(['status' => $request->status]);
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
            $state = VendorModel::findOrFail($id);
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
