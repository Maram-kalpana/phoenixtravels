<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;
use App\Models\BannerModel;
use App\Models\NotificationModel;
use App\Models\CustomerOtpModel;
use App\Models\PagesModel;
use App\Models\StoreModel;
use App\Models\SubscribeModel;
use App\Models\SubscribeHistoryModel;
use App\Models\SettingModel;
use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\CityModel;
use App\Mail\OtpMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Mail;
use Exception;

class UserController extends Controller
{
    private $folder = "user";

    
    
    public function signup(Request $request){
        try{
          $validation = Validator::make(
                $request->all(),
                [
                    'full_name' => 'required',
                    'phone' => 'required|unique:customer,phone',
                    'email' => 'required|unique:customer,email',
                ],
                [
                    'full_name.required' => __('api_msg.please_enter_required_fields'),
                    'phone.required' => __('api_msg.please_enter_required_fields'),
                    'email.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {
                $errorMessage = $validation->errors()->first();
                return APIResponse(400, $errorMessage ?: __('api_msg.validation_error'));
            }
           if($request->file('profile_image') != ''){
                    $data['profile_image'] = 'uploads/profile/'.$request->file('profile_image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$data['profile_image'];
                    $request->file('profile_image')->move('uploads/profile/', $file_name);
            }
         $customer = new CustomerModel();
         $customer->full_name = $request->full_name;
         $customer->phone = $request->phone;
         $customer->email = $request->email;
         $customer->profile_image = $data['profile_image'];
         $customer->address = $request->address;
         $customer->city_id = $request->city_id;
         $customer->state_id = $request->state_id;
         $customer->country_id = $request->country_id;
         $customer->status = 1;
         $customer->created_at = date('Y-m-d H:i:s');
         $customer->save();
         return APIResponse(200, __('api_msg.add_successfully'));
        }catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
    public function login(Request $request){
    try {
        $validation = Validator::make(
            $request->all(),
            [
                'phone' => 'required|string',
            ],
            [
                'phone.required' => __('api_msg.please_enter_required_fields'),
            ]
        );
        
        if ($validation->fails()) {
            $errorMessage = $validation->errors()->first();
            return APIResponse(400, $errorMessage ?: __('api_msg.validation_error'));
        }
        
        // Clean the phone number (remove spaces, dashes, etc.)
        $phone = preg_replace('/[^0-9+]/', '', $request->phone);
        
        // Try to find customer by phone or email
        $customer = CustomerModel::where('phone', $phone)
            ->orWhere('email', $phone)
            ->orderBy('id', 'desc')
            ->first();
        
        $now = Carbon::now();
        $verify_code = '123456';
        //$verify_code = rand(100000, 999999); // 6-digit OTP
        //send_mobile_otp($phone,$verify_code . ' is the OTP to login into your Phoenix Tours & Travels account. Don\'t share your OTP/bank info with anyone - PHINTR','1707177648198956954');
        if (!$customer) {
            // If user doesn't exist, create a new one with just phone number
            $customer = CustomerModel::create([
                'phone' => $phone,
                'is_verified' => false
            ]);
            
            // Create OTP for new user
            CustomerOtpModel::create([
                'customer_id' => $customer->id,
                'otp' => $verify_code,
                'expire_at' => $now->addMinutes(10),
                'is_used' => false
            ]);
            
            $message = __('api_msg.new_user_created');
        } else {
            // Handle phone number update if needed
            if ($customer->phone !== $phone && !filter_var($phone, FILTER_VALIDATE_EMAIL)) {
                // Check if new phone number is already taken by another user
                $existingUser = CustomerModel::where('phone', $phone)
                    ->where('id', '!=', $customer->id)
                    ->first();
                
                if ($existingUser) {
                    return APIResponse(409, __('api_msg.phone_already_exists'));
                }
                
                // Update phone number
                $customer->phone = $phone;
                $customer->save();
            }
            
            // Update or create OTP
            CustomerOtpModel::updateOrCreate(
                ['customer_id' => $customer->id],
                [
                    'otp' => $verify_code,
                    'expire_at' => $now->addMinutes(10),
                    'is_used' => false,
                    'attempts' => 0 // Reset attempts if you have this field
                ]
            );
            
            $message = __('api_msg.otp_send_successfully');
        }
        
        // In production, don't return OTP in response
        $response = [
            'message' => $message,
            'user_id' => $customer->id,
            'is_new_user' => !$customer->wasRecentlyCreated ? false : true,
            'phone' => $customer->phone
        ];
        
        // Only include OTP in development environment
        if (config('app.env') !== 'production') {
            $response['debug_otp'] = $verify_code;
        }
        
        // Here you would typically send the OTP via SMS
        // $this->sendOtpViaSms($customer->phone, $verify_code);
        
        return APIResponse(200, $message, $response);
        
    } catch (Exception $e) {
        \Log::error('Login error: ' . $e->getMessage(), [
            'phone' => $request->phone ?? null,
            'trace' => $e->getTraceAsString()
        ]);
        
        return APIResponse(500, __('api_msg.server_error'), [
            'error' => config('app.debug') ? $e->getMessage() : null
        ]);
    }
}

/**
 * Optional: Add rate limiting to prevent OTP spam
 */
    protected function checkRateLimit($phone){
        $key = 'otp_attempt_' . md5($phone);
        $attempts = Cache::get($key, 0);
        
        if ($attempts >= 3) {
            $lastAttempt = Cache::get($key . '_last');
            if ($lastAttempt && Carbon::now()->diffInMinutes($lastAttempt) < 15) {
                return false;
            }
            // Reset after 15 minutes
            Cache::forget($key);
        }
        
        Cache::put($key, $attempts + 1, now()->addMinutes(15));
        Cache::put($key . '_last', Carbon::now(), now()->addMinutes(15));
        
        return true;
    }

        public function OtpVerification(Request $request){
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'otp' => 'required|numeric|min:6',
                ],
                [
                    'phone.required' => __('api_msg.please_enter_required_fields'),
                    'otp.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {
                $errors = $validation->errors()->first('user_id');
                $errors1 = $validation->errors()->first('otp');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                } elseif ($errors1) {
                    $data['message'] = $errors1;
                }
                return $data;
            }

            $user_id = $request->user_id;
            $otp = $request->otp;
            $User_Data = CustomerModel::where('id', $user_id)->first();
            if (!empty($User_Data)) {
                $CustomerOtp   = CustomerOtpModel::where('customer_id', $User_Data->id)->first();
                if(!empty($CustomerOtp)){
                if($request->otp != $CustomerOtp->otp) {
                    return APIResponse(400, __('api_msg.invalid_otp'));
                }else{
                    CustomerOtpModel::where('customer_id',$User_Data->id)->delete();
                   $Data = [
                        'id' => $User_Data->id,
                        'phone' => $User_Data->phone,
                        'full_name' => $User_Data->full_name,
                        'email' => $User_Data->email,
                        'image' => $User_Data->profile_image ? asset($User_Data->profile_image) : '',
                         'address'=> $User_Data->address,
                         'city_id'=> $User_Data->city_id,
                         'state_id'=> $User_Data->state_id,
                         'country_id'=> $User_Data->country_id,
                        'status' => $User_Data->status,
                        'created_at' => $User_Data->created_at->toDateTimeString(),
                        'updated_at' => $User_Data->updated_at->toDateTimeString(),
                    ];
                    return APIResponse(200, __('api_msg.get_record_successfully'), array($Data));
                }
            }else{
                return APIResponse(400, __('api_msg.data_not_found'));
            }
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function get_profile(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }

            $id = $request->user_id;
            $User_Data = CustomerModel::where('id', $id)->first();
            $Data = [
                'id' => $User_Data->id,
                'phone' => $User_Data->phone,
                'full_name' => $User_Data->full_name,
                'email' => $User_Data->email,
                'image' => $User_Data->profile_image ? asset($User_Data->profile_image) : null,
                'address'=> $User_Data->address,
                'city_id'=> $User_Data->city_id,
                'state_id'=> $User_Data->state_id,
                'country_id'=> $User_Data->country_id,
                'status' => $User_Data->status,
                'created_at' => $User_Data->created_at->toDateTimeString(),
                'updated_at' => $User_Data->updated_at->toDateTimeString(),
            ];
            if (!empty($Data)) {
                return APIResponse(200, __('api_msg.get_record_successfully'), array($Data));
            } else {
                return APIResponse(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }


    public function update_profile(Request $request){
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.please_enter_required_fields'),
                ]
            );
            if ($validation->fails()) {

                $errors = $validation->errors()->first('id');
                $data['status'] = 400;
                if ($errors) {
                    $data['message'] = $errors;
                }
                return $data;
            }
            $id = $request->user_id;
            $data = array();
            $User_Data = CustomerModel::where('id', $id)->first();
            if (!empty($User_Data)) {
                if (isset($request->full_name) && $request->full_name != '') {
                    $data['full_name'] = $request->full_name;
                }
                if (isset($request->email) && $request->email != '') {
                    $data['email'] = $request->email;
                }
                if (isset($request->phone) && $request->phone != '') {
                    $data['phone'] = $request->phone;
                }
                if (isset($request->address) && $request->address != '') {
                    $data['address'] = $request->address;
                }
                if (isset($request->city_id) && $request->city_id != '') {
                    $data['city_id'] = $request->city_id;
                }
                if (isset($request->state_id) && $request->state_id != '') {
                    $data['state_id'] = $request->state_id;
                }
                if (isset($request->country_id) && $request->country_id != '') {
                    $data['country_id'] = $request->country_id;
                }
                if($request->file('profile_image') != ''){
                    $data['profile_image'] = 'uploads/profile/'.$request->file('profile_image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$data['profile_image'];
                    $request->file('profile_image')->move('uploads/profile/', $file_name);
                }else{
                  $old_image =  CustomerModel::where('id',$id)->first();
                  $data['profile_image'] =$old_image->image;
                }
                $affectedRows = CustomerModel::where("id", $id)->update($data);
                $User_Data->update($data);
                if (isset($affectedRows)) {
                     $User_d = CustomerModel::where('id', $id)->first();
                     $userData = [
                        'id' => $User_d->id,
                        'phone' => $User_d->phone,
                        'full_name' => $User_d->full_name,
                        'email' => $User_d->email,
                        'image' => $User_d->profile_image ? asset($User_d->profile_image) : null,
                        'status' => $User_d->status,
                        'address' => $User_d->address,
                        'city_id' => $User_d->city_id,
                        'state_id' => $User_d->state_id,
                        'country_id' => $User_d->country_id,
                        'created_at' => $User_d->created_at->toDateTimeString(),
                        'updated_at' => $User_d->updated_at->toDateTimeString(),
                    ];
                    return APIResponse(200, __('api_msg.update_profile_sucessfuly'), $userData);
                }
            } else {
                return APIResponse(400, __('api_msg.User_id_worng'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function dashboard(Request $request){
        try {
          $id = $request->user_id;
          $user_datas = CustomerModel::where('id', $id)->first();
          $User_Data['id'] = $user_datas->id;
          $User_Data['phone'] = $user_datas->phone;
          $User_Data['full_name'] = $user_datas->full_name;
          $User_Data['email'] = $user_datas->email;
          $User_Data['profile_image'] = $user_datas->profile_image ? asset($user_datas->profile_image) : '';
          $User_Data['status'] = $user_datas->status;
          $User_Data['created_at'] = $user_datas->created_at->toDateTimeString();
          $User_Data['updated_at'] = $user_datas->updated_at->toDateTimeString();
          $banners = BannerModel::all();
          $bannerData = $banners->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'image' => $banner->image ? asset($banner->image) : '',
                    'status' => $banner->status,
                    'created_at' => $banner->created_at->toDateTimeString(),
                    'updated_at' => $banner->updated_at->toDateTimeString(),
                ];
          });
          $get_category_list = CategoryModel::select('*')->where('status',1)->get();
          $get_data = [];
          foreach($get_category_list as $key => $val){
              $temp = [
                        'category_id' => $val->id,
                        'category_name' => $val->category_name,
                        'products_list' => []
                    ];
              $get_product_list = ProductModel::select('*')->where('category_id',$val->id)->where('status',1)->get();
              foreach ($get_product_list as $product_list) {
                   $temp['products_list'][] = [
                        'id' => $product_list->id,
                        'title' => $product_list->title,
                        'discount_price' => $product_list->discount_price, 
                        'actual_price' => $product_list->actual_price, 
                        'image' => $product_list->image, 
                        'description' => $product_list->description,
                       ];
              }
              $get_data[] = $temp;
          }
          $data = array('user_data'=>$User_Data,'banner_data' => $bannerData, 'products_data'=>$get_data);
          return APIResponse(200, __('api_msg.get_record_successfully'), array($data));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
    

    
    public function contact_us(Request $request){
        try {
          $get_contact_us = SettingModel::select('phone','email','address','about')->where('id',1)->first();
          return APIResponse(200, __('api_msg.get_record_successfully'), array($get_contact_us));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
     public function account_delete(Request $request){
        try {
          $id = $request->user_id;
          $User_Data = CustomerModel::where('id', $id)->first();
          if(!empty($User_Data)){
              $User_Data->delete();
              return APIResponse(200, __('api_msg.delete_success'));
          }else{
              return APIResponse(200, __('api_msg.data_not_found'));
          }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
  
    
    public function country(Request $request){
        try {
          $get_country = CountryModel::select('id','name')->where('status',1)->get();
          return APIResponse(200, __('api_msg.get_record_successfully'), array($get_country));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
    
    public function state(Request $request,$country_id){
        try {
          $get_state = StateModel::select('id','name')->where('status',1)->where('country_id',$country_id)->get();
          return APIResponse(200, __('api_msg.get_record_successfully'), array($get_state));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
    public function city(Request $request,$country_id,$state_id){
        try {
          $get_city = CityModel::select('id','name')->where('status',1)->where('country_id',$country_id)->where('state_id',$state_id)->get();
          return APIResponse(200, __('api_msg.get_record_successfully'), array($get_city));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
    
    public function notification(Request $request){
        try {
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            $get_notification = NotificationModel::select('id', 'title', 'body', 'created_at')
                ->where('status', 1)
                ->paginate($perPage, ['*'], 'page', $page);
            $response = [
                'data' => $get_notification->items(),
                'pagination' => [
                    'total' => $get_notification->total(),
                    'per_page' => $get_notification->perPage(),
                    'current_page' => $get_notification->currentPage(),
                    'last_page' => $get_notification->lastPage(),
                ]
            ];
            
            return APIResponse(200, __('api_msg.get_record_successfully'), $response);
            
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
