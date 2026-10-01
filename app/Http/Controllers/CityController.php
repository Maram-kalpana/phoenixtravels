<?php

namespace App\Http\Controllers;
use App\Models\CityModel;
use App\Models\StateModel;
use App\Models\CountryModel;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
     return view('backend.city.list');
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
                'name'=>'required',
                'country_id'=>'required',
                'state_id'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $city = new CityModel();
                $city->name = $data['name'];
                $city->country_id = $data['country_id'];
                $city->state_id = $data['state_id'];
                $city->status = 1;
                $city->created_at = date('Y-m-d H:i:s');
                $city->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $country = CountryModel::where('status',1)->get();
        $state = StateModel::where('status',1)->get();
        return view('backend.city.add',compact('country','state'));
    }

    function getCityData(Request $request){
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
        $total = CityModel::Where(function ($query) use ($search) {
                $query->Where('city.name', 'like', '%' . $search . '%');
            })->count();

        $course = CityModel::Where(function ($query) use ($search) {
                $query->Where('city.name', 'like', '%' . $search . '%');
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
                getCountryName($val->country_id),
                getStateName($val->state_id),
                $val->name,
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.city', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
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
            $rules=['name'=>'required','country_id'=>'required','state_id'=>'required'];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array("name" => $data['name'],"country_id" => $data['country_id'],"state_id" => $data['state_id']);
                $affectedRows = CityModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $city = CityModel::where('id',$id)->first();
        $state = StateModel::where('status',1)->get();
        $country = CountryModel::where('status',1)->get();
        return view('backend.city.edit',compact('state','country','city'));
    }
    public function updateCityStatus(Request $request){
        $update = CityModel::where('id', $request->id)->update(['status' => $request->status]);
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
            $city = CityModel::findOrFail($id);
            if ($city){
                $city->delete();
                return response()->json(array('success' => true));
            }
        }
    }

  
    
    public function GetCityName(Request $request){
             $country_id = $request->country_id;
             $state_id = $request->state_id;
             $state = CityModel::where('country_id',$country_id)->where('state_id',$state_id)->where('status',1)->get();
             if(!empty($state)){
    			$html=' <select class="form-control city_id" name="city_id" id="city_id"><option value="">----Select City----</option>';
    			foreach($state as $sub){
    				$html.=	 '<option value="'.$sub->id.'"  >'.$sub->name.'</option>';
    			}
    			$html.=' </select><span id="crr_statelist" style="color:red"></span>';
    		}else{
    			$html= ' <select name="city_id" id="city_id"><option value="">--No data--</option></select>'; 
    		}
    		echo $html;exit;
            
        }
}
