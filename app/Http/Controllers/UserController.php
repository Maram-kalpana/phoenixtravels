<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Auth;
use Carbon\Carbon;
use App\Models\CustomerModel;
use App\Models\WalletModel;
use App\Models\CustomerOtpModel;
use App\Facades\ResponseHelper;


class UserController extends Controller{
    function index(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'phone'=>'required|digits:10',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $get_phone = CustomerModel::select('*')->where('phone', $data['phone'])->orderBy('id', 'desc')->first();
                if(!empty($get_phone)){
                $update_arr = array( "phone" => $data['phone']);
                $now = Carbon::now();
                $customer_id = $get_phone->id;
                \Session::put('customer_id', $customer_id);
                $verify_code = rand(123456, 999999);
                send_mobile_otp($data['phone'],$verify_code . ' is the OTP to login into your Phoenix Tours & Travels account. Don\'t share your OTP/bank info with anyone - PHINTR','1707177648198956954');
                CustomerOtpModel::updateOrCreate(['customer_id' => $get_phone->id],['otp' => $verify_code,'expire_at' => $now->addMinutes(10)]);
                $affectedRows = CustomerModel::where("phone", $data['phone'])->update($update_arr);
                }else{
                    $customer = new CustomerModel();
                    $customer->referral_id = generateSalt_e(8);
                    $customer->phone = $data['phone'];
                    $customer->status = 1;
                    $customer->referral_by = $data['referral_by'] ?? '';
                    $customer->profile_completion_status = 0;
                    $customer->created_at = date('Y-m-d H:i:s');
                    $customer->save();
                    $insertedId = $customer->id;
                    $now = Carbon::now();
                    \Session::put('customer_id', $insertedId);
                    $verify_code = rand(123456, 999999);
                    send_mobile_otp($data['phone'],$verify_code . ' is the OTP to login into your Phoenix Tours & Travels account. Don\'t share your OTP/bank info with anyone - PHINTR','1707177648198956954');
                    $walletData = [
                        'transaction_id' => 'TXN' . strtoupper(\Illuminate\Support\Str::random(10)),
                        'user_id' => 1,
                        'referral_id' => null,
                        'booking_id' => null,
                        'booking_data' => null,
                        'amount' => 1000,
                        'type' => 'Credit',
                        'message' => 'Congratulations! Enjoy your Rs.1000 welcome bonus with Phoenix Tours and Travels for your next trip!',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    \App\Models\WalletModel::create($walletData);
                    CustomerOtpModel::create(['customer_id' => $insertedId, 'otp' => $verify_code,'expire_at' => $now->addMinutes(10)]);
                }
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('user.login');
    }


    function OtpVerification(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=['otp'=>'required|digits:6'];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $customer_id = \Session::get('customer_id');
                $CustomerOtpModel   = CustomerOtpModel::where('customer_id', $customer_id)->first();
                if($CustomerOtpModel->otp != $request->otp) {
                    $response_code = 200;
                    $message = array('error_new'=>array('Your OTP is not correct'));
                    $success = false;
                }else{
                    CustomerOtpModel::where('customer_id',$customer_id)->delete();
                    $success = true;
                    $message = array('success'=>array('added successfully'));
                }
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('user.otp');
    }


    function logout(Request $request){
        $customer_id = \Session::get('customer_id');
        \Session::flush($customer_id);
        return redirect()->route('home');
    }
}
