<?php

namespace App\Http\Controllers;

use App\Models\SubscribeModel;
use App\Models\SubscribeHistoryModel;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
     return view('backend.subscribe.index');
    }
    
    public function History(){
     return view('backend.subscribe.history');
    }
    
    function getHistorySubscribeData(Request $request){
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
        $total = SubscribeHistoryModel::Where(function ($query) use ($search) {
                $query->Where('subscribe.user_id', 'like', '%' . $search . '%');
            })->count();

        $course = SubscribeHistoryModel::Where(function ($query) use ($search) {
                $query->Where('subscribe.user_id', 'like', '%' . $search . '%');
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
                $val->subscribe_plan_name,
                $val->subscribe_plan_type,
                $val->discount_percentage."".'%',
                "₹".' '.$val->amount,
                $val->days.' '."Days",
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.subscribe', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
                <a  href="javascript::void(0)" class="deleteMCQ" onclick="validate('.$val->id .')"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        echo json_encode($records);
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
                'subscribe_plan_name'=>'required',
                'subscribe_plan_type'=>'required',
                'discount_percentage'=>'required',
                'amount'=>'required',
                'days'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $subscribe = new SubscribeModel();
                $subscribe->subscribe_plan_name = $data['subscribe_plan_name'];
                $subscribe->subscribe_plan_type = $data['subscribe_plan_type'];
                $subscribe->discount_percentage = $data['discount_percentage'];
                $subscribe->amount = $data['amount'];
                $subscribe->days = $data['days'];
                $subscribe->status = 1;
                $subscribe->created_at = date('Y-m-d H:i:s');
                $subscribe->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('backend.subscribe.add');
    }

    function getSubscribeData(Request $request){
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
        $total = SubscribeModel::Where(function ($query) use ($search) {
                $query->Where('subscribe.subscribe_plan_name', 'like', '%' . $search . '%');
            })->count();

        $course = SubscribeModel::Where(function ($query) use ($search) {
                $query->Where('subscribe.subscribe_plan_name', 'like', '%' . $search . '%');
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
                $val->subscribe_plan_name,
                $val->subscribe_plan_type,
                $val->discount_percentage."".'%',
                "₹".' '.$val->amount,
                $val->days.' '."Days",
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.subscribe', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
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
            $rules=[
                'subscribe_plan_name'=>'required',
                'subscribe_plan_type'=>'required',
                'amount'=>'required',
                'days'=>'required',
                'discount_percentage'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array(
                    "subscribe_plan_name" => $data['subscribe_plan_name'],
                    "subscribe_plan_type" => $data['subscribe_plan_type'],
                    "discount_percentage" => $data['discount_percentage'],
                    "amount" => $data['amount'],
                    "days" => $data['days'],
                );
                $affectedRows = SubscribeModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $subscribe = SubscribeModel::where('id',$id)->first();
        return view('backend.subscribe.edit',compact('subscribe'));
    }
    public function updateSubscribeStatus(Request $request){
        $update = SubscribeModel::where('id', $request->id)->update(['status' => $request->status]);
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
            $course = SubscribeModel::findOrFail($id);
            if ($course){
                $course->delete();
                return response()->json(array('success' => true));
            }
        }
    }


    
    
}
