<?php

namespace App\Http\Controllers;
use Validator,Auth;
use App\Facades\ResponseHelper;
use App\Models\CouponModel;
use App\Models\ToursModel;
use App\Models\CouponsHistoryModel;
use Illuminate\Http\Request;
class CouponController extends Controller{
    
    public function index(Request $request){
        return view('backend.coupons.index');
    }
    public function History(){
     return view('backend.coupons.history');
    }
    function getCouponData(Request $request){
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
        $total = CouponModel::Where(function ($query) use ($search) {
                $query->Where('coupons.coupons_name', 'like', '%' . $search . '%');
            })->count();

        $course = CouponModel::Where(function ($query) use ($search) {
                $query->Where('coupons.coupons_name', 'like', '%' . $search . '%');
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
                $val->coupons_name,
                $val->coupons_code,
                $val->start_date,
                $val->date_end,
                $val->max_no_of_uses,
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.coupon', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
                <a  href="javascript::void(0)" class="deleteMCQ" onclick="validate('.$val->id .')"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        echo json_encode($records);
    }
    
     public function create(Request $request){
         if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'coupons_name'=>'required',
                'coupons_code'=>'required',
                'coupons_description'=>'required',
                'type'=>'required',
                'tour_id'=>'required',
                'start_date'=>'required',
                'date_end'=>'required',
                'discount'=>'required',
                'max_no_of_uses'=>'required',
                'max_no_of_customer'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $coupon = new CouponModel();
                $coupon->coupons_name = $data['coupons_name'];
                $coupon->coupons_code = $data['coupons_code'];
                $coupon->coupons_description = $data['coupons_description'];
                $coupon->type = $data['type'];
                $coupon->tour_id = $data['tour_id'];
                $coupon->start_date = $data['start_date'];
                $coupon->date_end = $data['date_end'];
                $coupon->discount = $data['discount'];
                $coupon->total_amount = $data['total_amount'];
                $coupon->max_no_of_uses = $data['max_no_of_uses'];
                $coupon->max_no_of_customer = $data['max_no_of_customer'];
                $coupon->status = 1;
                $coupon->created_at = date('Y-m-d H:i:s');
                $coupon->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $tours = ToursModel::where('status',1)->get();
        return view('backend.coupons.add',compact('tours'));
    }
    
    
    public function updateCouponStatus(Request $request){
        $update = CouponModel::where('id', $request->id)->update(['status' => $request->status]);
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => []
        ]);

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,$id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'coupons_name'=>'required',
                'coupons_description'=>'required',
                'coupons_code'=>'required',
                'type'=>'required',
                'tour_id'=>'required',
                'start_date'=>'required',
                'date_end'=>'required',
                'discount'=>'required',
                'max_no_of_uses'=>'required',
                'max_no_of_customer'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = 
                array(
                "coupons_name" => $data['coupons_name'],
                "coupons_description" => $data['coupons_description'],
                "coupons_code" => $data['coupons_code'],
                "type" => $data['type'],
                "tour_id" => $data['tour_id'],
                "start_date" => $data['start_date'],
                "date_end" => $data['date_end'],
                "discount" => $data['discount'],
                "total_amount" => $data['total_amount'],
                "max_no_of_uses" => $data['max_no_of_uses'],
                "max_no_of_customer" => $data['max_no_of_customer'],
                "updated_at" => date('Y-m-d H:i:s')
                );
                $affectedRows = CouponModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $coupon = CouponModel::where('id',$id)->first();
        $tours = ToursModel::where('status',1)->get();
        return view('backend.coupons.edit',compact('coupon','tours'));
    }

    
   public function destroy(Request $request, $id){
         if ($request->ajax()){
            $product = CouponModel::findOrFail($id);
            if ($product){
                $product->delete();
                return response()->json(array('success' => true));
            }
        }
    }
    
    
    
 
    
    
}
