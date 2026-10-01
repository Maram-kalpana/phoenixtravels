<?php

namespace App\Http\Controllers\Api; // Add namespace
use App\Http\Controllers\Controller; // Import Controller
use App\Models\CouponModel;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Validator;
use Exception;

class CouponController extends Controller{


    public function index(Request $request){
         try {
          $get_coupon_list = CouponModel::select('id','coupons_name','coupons_code')->where('status',1)->get();
          return APIResponse(200, __('api_msg.get_record_successfully'), array($get_coupon_list));
          } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    
   
   
   
   public function check_coupon(Request $request){
    try {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string|max:100',
            'user_id' => 'nullable|numeric',
                'amount' => 'required|numeric|min:0'
        ], [
            'coupon_code.required' => __('api_msg.please_enter_required_fields'),
            'amount.required' => __('api_msg.please_enter_required_fields')
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $couponCode = $request->coupon_code;
        $userId = $request->user_id;
        $amount = $request->amount;
        $currentDate = date('Y-m-d');

        // Find the coupon
        $coupon = DB::table('coupons')
            ->where('coupons_code', $couponCode)
            ->where('status', 1)
            ->where('start_date', '<=', $currentDate)
            ->where('date_end', '>=', $currentDate)
            ->first();

        // Check if coupon exists
        if (!$coupon) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid or expired coupon code'
            ], 400);
        }

        // Check max number of uses (global)
        if ($coupon->max_no_of_uses !== null) {
            $totalUses = DB::table('coupons_history')
                ->where('coupon_id', $coupon->id)
                ->count();
                
            if ($totalUses >= $coupon->max_no_of_uses) {
                return response()->json([
                    'status' => 400,
                    'message' => 'This coupon has reached its maximum usage limit'
                ], 400);
            }
        }

        // Check per customer limit (if user is logged in)
        if ($userId && $coupon->max_no_of_customer !== null) {
            $userUses = DB::table('coupons_history')
                ->where('coupon_id', $coupon->id)
                ->where('user_id', $userId)
                ->count();
                
            if ($userUses >= $coupon->max_no_of_customer) {
                return response()->json([
                    'status' => 400,
                    'message' => 'You have already used this coupon maximum times'
                ], 400);
            }
        }

        // Check minimum amount requirement (if total_amount is set)
        if ($coupon->total_amount !== null && $amount < $coupon->total_amount) {
            return response()->json([
                'status' => 400,
                'message' => 'Minimum order amount should be ' . $coupon->total_amount
            ], 400);
        }

        // Calculate discount
        $discountAmount = 0;
        $finalAmount = $amount;

        if ($coupon->type == 'P') { // Percentage discount
            $discountPercent = floatval($coupon->discount);
            $discountAmount = ($amount * $discountPercent) / 100;
            $finalAmount = $amount - $discountAmount;
        } else { // Fixed discount
            $discountAmount = floatval($coupon->discount);
            $finalAmount = max(0, $amount - $discountAmount); // Ensure not negative
        }

        // Return coupon details
        $response = [
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->coupons_code,
            'coupon_name' => $coupon->coupons_name,
            'type' => $coupon->type,
            'discount' => $coupon->discount,
            'original_amount' => $amount,
            'discount_amount' => round($discountAmount, 2),
            'final_amount' => round($finalAmount, 2),
            'message' => 'Coupon applied successfully'
        ];

        return response()->json([
            'status' => 200,
            'message' => 'Coupon is valid',
            'data' => $response
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'status' => 500,
            'errors' => $e->getMessage()
        ], 500);
    }
}

/**
 * Apply coupon and record usage
 */
public function apply_coupon(Request $request){
    try {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string|max:100',
            'user_id' => 'nullable|numeric',
            'amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ], 400);
        }

        // First check if coupon is valid
        $checkResponse = $this->check_coupon($request);
        $checkData = json_decode($checkResponse->getContent(), true);

        if ($checkData['status'] != 200) {
            return $checkResponse;
        }

        // Record coupon usage
        $couponData = $checkData['data'];
        
        DB::table('coupons_history')->insert([
            'user_id' => $request->user_id,
            'coupon_id' => $couponData['coupon_id'],
            'coupon_code' => $request->coupon_code,
            'created_at' => now(),
            'updated_at' => now(),
            'status' => 1
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Coupon applied and recorded successfully',
            'data' => $couponData
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'status' => 500,
            'errors' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get user's coupon usage history
 */
public function user_coupon_history(Request $request){
    try {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $history = DB::table('coupons_history')
            ->join('coupons', 'coupons_history.coupon_id', '=', 'coupons.id')
            ->where('coupons_history.user_id', $request->user_id)
            ->where('coupons_history.status', 1)
            ->select(
                'coupons_history.*',
                'coupons.coupons_name',
                'coupons.discount',
                'coupons.type'
            )
            ->orderBy('coupons_history.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'Coupon history fetched successfully',
            'data' => $history
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'status' => 500,
            'errors' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get all active coupons
 */
public function active_coupons(Request $request){
    try {
        $currentDate = date('Y-m-d');
        
        $coupons = DB::table('coupons')
            ->where('status', 1)
            ->where('start_date', '<=', $currentDate)
            ->where('date_end', '>=', $currentDate)
            ->select(
                'id',
                'coupons_name',
                'coupons_code',
                'type',
                'discount',
                'total_amount as min_amount',
                'start_date',
                'date_end as end_date'
            )
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'Active coupons fetched successfully',
            'data' => $coupons
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'status' => 500,
            'errors' => $e->getMessage()
        ], 500);
    }
}
}