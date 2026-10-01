<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Auth;
use App\Models\User;
use App\Models\BannerModel;
use App\Models\CategoryModel;
use App\Models\CustomerModel;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use App\Facades\ResponseHelper;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $BannerCount = BannerModel::where('status',1)->count();
        $CategoryCount = CategoryModel::where('status',1)->count();
        $UsersCount = CustomerModel::where('status',1)->count();
        $payment_amount = Payment::where('status','success')->sum('amount');
        $payment_status = Payment::where('status','success')->count();
        return view('dashboard',compact('BannerCount','CategoryCount','UsersCount','payment_amount','payment_status'));
    }

    public function profile(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'name'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array(
                    "name" => $data['name']
                );
                $affectedRows = User::where("id", 1)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $user = User::where('id','1')->first();
        return view('backend.profile',compact('user'));
    }

    public function ChangePassward(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'password_confirmation'=>'required|same:password|min:6',
                'password'=>'required|min:6|max:12|confirmed',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array(
                    "password" => Hash::make($data['password']),
                );
                $affectedRows = User::where("id", 1)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('backend.changepassward');
    }
    
   
}
