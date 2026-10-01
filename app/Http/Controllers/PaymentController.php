<?php

namespace App\Http\Controllers;

use App\Models\PaymentModel;
use App\Models\CustomerModel;
use App\Models\CityModel;
use App\Models\StateModel;
use App\Models\CountryModel;
use Validator,Auth;
use App\Facades\ResponseHelper;
use App\Models\Payment;
use Illuminate\Http\Request;
use DB;
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     
    public function add(Request $request){
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
                $user = new CustomerModel();
                $user->referral_id = generateSalt_e(8);
                $user->full_name = $data['full_name'];
                $user->email = $data['email'];
                $user->phone = $data['phone'];
                $user->profile_image = $file_name;
                $user->country_id = $data['country_id'];
                $user->address = $data['address'];
                $user->state_id = $data['state_id'];
                $user->city_id = $data['city_id'];
                $user->pin_code = $data['pin_code'];
                $user->referral_by = $data['referral_by'] ?? '';
                $user->status = 1;
                $user->created_at = date('Y-m-d H:i:s');
                $user->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $country = CountryModel::where('status',1)->get();
        $state = StateModel::where('status',1)->get();
        return view('backend.user.add_users',compact('country','state'));
    }
    
    public function edit(Request $request,$id){
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
                  $old_image =  CustomerModel::where('id',$id)->first();
                  $data['profile_image'] =$old_image->profile_image;
                }
                $update_arr = array("full_name" => $data['full_name'],"profile_image" => $data['profile_image'],"email" => $data['email'],"phone" => $data['phone'],"country_id" => $data['country_id'],"address" => $data['address'],"state_id" => $data['state_id'],"city_id" => $data['city_id'],"pin_code" => $data['pin_code']);
                $affectedRows = CustomerModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $user = CustomerModel::where('id',$id)->first();
        $country = CountryModel::where('status',1)->get();
        $state = StateModel::where('status',1)->get();
        $city = CityModel::where('status',1)->get();
        return view('backend.user.edit_users',compact('user','country','state','city'));
    }
    public function index(){
       return view('backend.user.list_users');
    }

    function getUsersData(Request $request){
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
        $total = CustomerModel::Where(function ($query) use ($search) {
                $query->Where('customer.full_name', 'like', '%' . $search . '%');
            })->count();

        $students = CustomerModel::Where(function ($query) use ($search) {
                $query->Where('customer.full_name', 'like', '%' . $search . '%');
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
                '<a href="' . route('edit.users', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | <a  href="javascript::void(0)" class="deleteMCQ" onclick="validate('.$val->id .')" data-id="' . $val->id . '"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        echo json_encode($records);
    }
    public function destroy(Request $request, $id){
         if ($request->ajax()){
            $banner = CustomerModel::findOrFail($id);
            if ($banner){
                $banner->delete();
                return response()->json(array('success' => true));
            }
        }
    }
    public function updateUsersStatus(Request $request){
        $update = CustomerModel::where('id', $request->id)->update(['status' => $request->status]);
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => []
        ]);

    }
    /**
     * Show the form for creating a new resource.
     */
    public function ListPayments(Request $request){
        return view('backend.user.list_paymnets');
    }
    
    function getPaymentsData(Request $request){
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
        $total = Payment::Where(function ($query) use ($search) {
                $query->Where('payments.r_payment_id', 'like', '%' . $search . '%');
            })->count();

        $students = Payment::Where(function ($query) use ($search) {
                $query->Where('payments.r_payment_id', 'like', '%' . $search . '%');
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($students as $key => $val) {
            $group = DB::table('group')->where('id',$val->coures_id)->first();
            $stud_info = DB::table('students')->where('id',$val->student_id)->first();
            $stud_name = $stud_info->first_name.' '. $stud_info->last_name;
            $data[] = array(
                $key + 1,
                $stud_name,
                $val->email,
                $val->phone,
                $group->group_name,
                $val->amount,
                $val->r_payment_id
                
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        echo json_encode($records);
    }

}
