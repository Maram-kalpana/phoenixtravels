<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        $menuItems = [
            '9999' => 'Breakfast Menu',
            '9988' => 'Lunch Menu',
            '9977' => 'Dinner Menu',
            '9966' => 'Conference - Morning Snacks',
            '9955' => 'Conference - Evening Snacks',
            '9933' => 'Starters',
            '9922' => 'Ammunities',
            '9911' => 'Seeting Type',
        ];
        
        $item = $menuItems[$request->query('module_id')] ?? 'Drinks Type';
     return view('backend.category.index', compact('item'));
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
                'category_name'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $category = new CategoryModel();
                $category->category_name = $data['category_name'];
                $category->module_id = $request->query('module_id');
                $category->status = 1;
                $category->created_at = date('Y-m-d H:i:s');
                $category->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $menuItems = [
            '9999' => 'Breakfast Menu',
            '9988' => 'Lunch Menu',
            '9977' => 'Dinner Menu',
            '9966' => 'Conference - Morning Snacks',
            '9955' => 'Conference - Evening Snacks',
            '9933' => 'Starters',
            '9922' => 'Ammunities',
            '9911' => 'Seeting Type',
        ];
        
        $item = $menuItems[$request->query('module_id')] ?? 'Drinks Type';
        return view('backend.category.add', compact('item'));
    }

    function getCategoryData(Request $request){
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
        $total = CategoryModel::Where(function ($query) use ($search) {
                $query->Where('category.category_name', 'like', '%' . $search . '%');
            })->where('module_id', $request->query('module_id'))->count();

        $course = CategoryModel::Where(function ($query) use ($search) {
                $query->Where('category.category_name', 'like', '%' . $search . '%');
            })
            ->offset($offset)->limit($limit)
            ->where('module_id', $request->query('module_id'))
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($course as $key => $val) {
            $status = $val->status ? "checked" : "";
            $data[] = array(
                $key + 1,
                $val->category_name,
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.category', array_merge([$val->id], ['module_id' => request()->query('module_id')])) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
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
            $rules=['category_name'=>'required'];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array("category_name" => $data['category_name']);
                $affectedRows = CategoryModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $category = CategoryModel::where('id',$id)->first();
        $menuItems = [
            '9999' => 'Breakfast Menu',
            '9988' => 'Lunch Menu',
            '9977' => 'Dinner Menu',
            '9966' => 'Conference - Morning Snacks',
            '9955' => 'Conference - Evening Snacks',
            '9933' => 'Starters',
            '9922' => 'Ammunities',
            '9911' => 'Seeting Type',
        ];
        $item = $menuItems[$request->query('module_id')] ?? 'Drinks Type';
        return view('backend.category.edit',compact('category','item'));
    }
    public function updateCategoryStatus(Request $request){
        $update = CategoryModel::where('id', $request->id)->update(['status' => $request->status]);
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
            $course = CategoryModel::findOrFail($id);
            if ($course){
                $course->delete();
                return response()->json(array('success' => true));
            }
        }
    }

  
    
    
}
