<?php
namespace App\Http\Controllers;
use Validator,Auth;
use App\Facades\ResponseHelper;
use App\Models\ProductModel;
use App\Models\StoreModel;
use App\Models\CategoryModel;
use Illuminate\Http\Request;

class ProductController extends Controller{
    
    public function index(Request $request){
        return view('backend.products.index');
    }
    
       function getProductData(Request $request){
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
        $total = ProductModel::Where(function ($query) use ($search) {
                $query->Where('product.title', 'like', '%' . $search . '%');
            })->count();

        $course = ProductModel::Where(function ($query) use ($search) {
                $query->Where('product.title', 'like', '%' . $search . '%');
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
                getStoreName($val->store_id),
                getCategoryName($val->category_id),
                $val->title,
                $val->actual_price,
                $val->discount_price,
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
                '<a href="' . route('edit.product', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
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
                'store_id'=> 'required',
                'category_id'=>'required',
                'title'=>'required',
                'discount_price'=>'required',
                'actual_price'=>'required',
                'image'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('image') != ''){
                    $image = 'uploads/product/'.$request->file('image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$image;
                    $request->file('image')->move('uploads/product/', $file_name);
                }
                $product = new ProductModel();
                $product->store_id = $data['store_id'];
                $product->category_id = $data['category_id'];
                $product->title = $data['title'];
                $product->discount_price = $data['discount_price'];
                $product->actual_price = $data['actual_price'];
                $product->image = $image;
                $product->description = $data['description'];
                $product->status = 1;
                $product->created_at = date('Y-m-d H:i:s');
                $product->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $category_name = CategoryModel::where('status',1)->get();
        $store = StoreModel::where('status',1)->get();
        return view('backend.products.add',compact('category_name','store'));
    }

  
    /**
     * Display the specified resource.
     */
    public function updateProductStatus(Request $request){
        $update = ProductModel::where('id', $request->id)->update(['status' => $request->status]);
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
                'store_id'=> 'required',
               'category_id'=>'required',
                'title'=>'required',
                'discount_price'=>'required',
                'actual_price'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('image') != ''){
                    $image = 'uploads/product/'.$request->file('image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$image;
                    $request->file('image')->move('uploads/product/', $file_name);
                }else{
                  $old_image =  ProductModel::where('id',$id)->first();
                  $image =$old_image->image;
                }
                $update_arr = array("store_id"=>$data['store_id'],"category_id" => $data['category_id'],"title" => $data['title'],"discount_price" => $data['discount_price'],"actual_price" => $data['actual_price'],"description" => $data['description'],"image" => $image);
                $affectedRows = ProductModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $category_name = CategoryModel::where('status',1)->get();
        $product = ProductModel::where('id',$id)->first();
        $store = StoreModel::where('status',1)->get();
        return view('backend.products.edit',compact('store','category_name','product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Request $request, $id){
         if ($request->ajax()){
            $product = ProductModel::findOrFail($id);
            if ($product){
                $product->delete();
                return response()->json(array('success' => true));
            }
        }
    }
}
