<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator,Auth;
use Carbon\Carbon;
use App\Models\SmtpModel;
use App\Models\SettingModel;
use App\Models\PackagePercentage;
use App\Models\SMSModel;
use App\Models\SocialModel;
use App\Models\PaymentModel;
use App\Facades\ResponseHelper;
class SettingsController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }
    function index(){

    }
    public function PaymentSettings(Request $request){
        $payment = PaymentModel::where('id','1')->first();
        return view('backend.setting.payment_settings',compact('payment'));
    }

    public function UpdatePaymentSettings(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'razorpay_key_live'=>'required',
                'razorpay_key_secret_live'=>'required',
                'razorpay_key_test'=>'required',
                'razorpay_key_secret_test'=>'required',
                'mode_type'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array(
                    "razorpay_key_live" => $data['razorpay_key_live'],
                    "razorpay_key_secret_live" => $data['razorpay_key_secret_live'],
                    "razorpay_key_test" => $data['razorpay_key_test'],
                    "razorpay_key_secret_test" => $data['razorpay_key_secret_test'],
                    "mode_type" => $data['mode_type'],
                );
                $affectedRows = PaymentModel::where("id", 1)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
    }

    public function generalSettings(Request $request){
        $settings = SettingModel::where('id','1')->first();
        return view('backend.setting.general',compact('settings'));
    }

    public function UpdateGeneralSettings(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'about'=>'required',
                'address'=>'required',
                'phone'=>'required|numeric',
                'email'=>'required',
                'company_name'=>'required',
                'color'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('logo') != ''){
                    $logo_image = 'uploads/settings/'.$request->file('logo')->getClientOriginalName();
                    $file_name = time().rand(100,999).$logo_image;
                    $request->file('logo')->move('uploads/settings/', $file_name);
                    }else{
                        $get_setting = SettingModel::where('id',1)->first();
                        $logo_image = $get_setting->logo;
                }
                if($request->file('fav') != ''){
                    $fav_image = 'uploads/settings/'.$request->file('fav')->getClientOriginalName();
                    $file_name_fav = time().rand(100,999).$fav_image;
                    $request->file('fav')->move('uploads/settings/', $file_name_fav);
                    }else{
                        $get_setting = SettingModel::where('id',1)->first();
                        $fav_image = $get_setting->fav;
                }
                $update_arr = array(
                    "logo" => $logo_image,
                    "fav" => $fav_image,
                    "about" => $data['about'],
                    "address" => $data['address'],
                    "email" => $data['email'],
                    "phone" => $data['phone'],
                    "company_name" => $data['company_name'],
                    "color" => $data['color'],
                );
                $affectedRows = SettingModel::where("id", 1)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
    }
    
    
    public function UpdatePackagePercentage(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'second_person_percentage'=>'required',
                'third_person_percentage'=>'required',
                'four_person_percentage'=>'required',
                'five_person_percentage'=>'required',
                'six_person_percentage'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array(
                    "second_person_percentage" => $data['second_person_percentage'],
                    "third_person_percentage" => $data['third_person_percentage'],
                    "four_person_percentage" => $data['four_person_percentage'],
                    "five_person_percentage" => $data['five_person_percentage'],
                    "six_person_percentage" => $data['six_person_percentage'],
                );
                $affectedRows = PackagePercentage::where("id", 1)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $package_percentage = PackagePercentage::where('id','1')->first();
        return view('backend.setting.package_percentage',compact('package_percentage'));
    }

    public function socialSettings(Request $request){
        $_header_name = array('S No','Name','Code','Link','Status','Actions');
        return view('backend.setting.social',compact('_header_name'));
    }


    public function getSocialLists(Request $request){
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
        $total = SocialModel::Where(function ($query) use ($search) {
                $query->Where('social.name', 'like', '%' . $search . '%');

            })->count();

        $social = SocialModel::Where(function ($query) use ($search) {
                $query->Where('social.name', 'like', '%' . $search . '%');
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($social as $key => $val) {
            $status = $val->status ? "checked" : "";
            $data[] = array(
                $key + 1,
                $val->name,
                $val->code,
                $val->link,
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.social.status', [$val->id]) . '" class="editModule" data-id="' . $val->id . '" data-name="' . $val->name . '" data-code="' . $val->code . '" data-link="' . $val->link . '" data-status="' . $val->status . '" ><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | <a  href="javascript::void(0)" class="deleteMCQ" data-id="' . $val->id . '"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        echo json_encode($records);
    }

    public function updateSocalStatus(Request $request){
        $update = SocialModel::where('id', $request->id)->update(['status' => $request->status]);
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => []
        ]);

    }

    public function AddsocialSettings(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'name'=>'required',
                'code'=>'required',
                'link'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $social = new SocialModel();
                $social->name = $data['name'];
                $social->code = $data['code'];
                $social->link = $data['link'];
                $social->status = 1;
                $social->created_at = date('Y-m-d H:i:s');
                $social->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('backend.setting.add_social');
    }

    public function editSocalStatus(Request $request,$id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'name'=>'required',
                'code'=>'required',
                'link'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array(
                    "name" => $data['name'],
                    "code" => $data['code'],
                    "link" => $data['link'],
                );
                $affectedRows = SocialModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $social = SocialModel::where('id',$id)->first();
        return view('backend.setting.edit_social',compact('social'));
    }

    public function deleteSocial(Request $request){
        try {
            $id = SocialModel::findOrFail($request->id);
            $delete = $id->delete();
            if ($delete) {
                $this->sendWebResponse(true, "Successfully Deleted");
            } else {
                $this->sendWebResponse(false, "Something went wrong, please try again");
            }
        } catch (Exception $e) {
            $this->sendWebResponse(false, "Something went wrong, please try again");
        }
    }


    public function smtpSettings(Request $request){
        $smtp = SmtpModel::where('id','1')->first();
        return view('backend.setting.smtp',compact('smtp'));
    }

    public function smsSettings(Request $request){
        $sms = SMSModel::where('id','1')->first();
        return view('backend.setting.sms',compact('sms'));
    }

    public function UpdateSmtpSettings(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'protocol'=>'required',
                'smtp_host'=>'required',
                'smtp_port'=>'required',
                'smtp_user'=>'required',
                'smtp_pass'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array(
                    "protocol" => $data['protocol'],
                    "smtp_host" => $data['smtp_host'],
                    "smtp_port" => $data['smtp_port'],
                    "smtp_user" => $data['smtp_user'],
                    "smtp_pass" => $data['smtp_pass'],
                );
                $affectedRows = SmtpModel::where("id", 1)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
    }



    public function UpdateSmsSettings(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'sms_url'=>'required',
                'api_key'=>'required',
                'sender_id'=>'required',
                'username'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array(
                    "sms_url" => $data['sms_url'],
                    "api_key" => $data['api_key'],
                    "sender_id" => $data['sender_id'],
                    "username" => $data['username'],
                );
                $affectedRows = SMSModel::where("id", 1)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
    }
}
