<?php

namespace App\Http\Controllers;
use Validator,Auth;
use App\Facades\ResponseHelper;
use App\Models\StoreModel;
use Illuminate\Http\Request;
class StoreController extends Controller{
    
    public function index(Request $request){
        return view('backend.store.index');
    }
    
    function getStoreData(Request $request){
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
        $total = StoreModel::Where(function ($query) use ($search) {
                $query->Where('store.store_name', 'like', '%' . $search . '%');
            })->count();

        $course = StoreModel::Where(function ($query) use ($search) {
                $query->Where('store.store_name', 'like', '%' . $search . '%');
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($course as $key => $val) {
            $status = $val->status ? "checked" : "";
            $new_image = asset(''). $val->image;
            $image = ($val->image) ? '<img src="' .$new_image . '" class="img-fluid" height="100px" width="100px">' : '';
            $data[] = array(
                $key + 1,
                $val->store_name,
                $val->email,
                $val->phone,
                $val->location,
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
                '<a href="' . route('edit.store', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
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
                'store_name'=>'required',
                'owner_name'=>'required',
                'email'=>'required',
                'phone'=>'required',
                'location'=>'required',
                'full_address'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('image') != ''){
                    $image = 'uploads/store/'.$request->file('image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$image;
                    $request->file('image')->move('uploads/store/', $file_name);
                }
                $store = new StoreModel();
                $store->store_name = $data['store_name'];
                $store->owner_name = $data['owner_name'];
                $store->email = $data['email'];
                $store->phone = $data['phone'];
                $store->image = $image ?? '';
                $store->location = $data['location'];
                $store->full_address = $data['full_address'];
                $store->status = 1;
                $store->created_at = date('Y-m-d H:i:s');
                $store->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('backend.store.add');
    }
    
    
    public function updateStoreStatus(Request $request){
        $update = StoreModel::where('id', $request->id)->update(['status' => $request->status]);
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
                'store_name'=>'required',
                'owner_name'=>'required',
                'email'=>'required',
                'phone'=>'required',
                'location'=>'required',
                'full_address'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('image') != ''){
                    $image = 'uploads/store/'.$request->file('image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$image;
                    $request->file('image')->move('uploads/store/', $file_name);
                }else{
                  $old_image =  StoreModel::where('id',$id)->first();
                  $image =$old_image->image;
                }
                $update_arr = 
                array(
                "store_name" => $data['store_name'],
                "owner_name" => $data['owner_name'],
                "email" => $data['email'],
                "phone" => $data['phone'],
                "location" => $data['location'],
                "full_address" => $data['full_address'],
                "image" => $image,
                "updated_at" => date('Y-m-d H:i:s')
                );
                $affectedRows = StoreModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $store = StoreModel::where('id',$id)->first();
        return view('backend.store.edit',compact('store'));
    }

    
   public function destroy(Request $request, $id){
         if ($request->ajax()){
            $product = StoreModel::findOrFail($id);
            if ($product){
                $product->delete();
                return response()->json(array('success' => true));
            }
        }
    }
}
