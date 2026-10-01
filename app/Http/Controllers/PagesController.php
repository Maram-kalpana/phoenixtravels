<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Auth;
use App\Models\PagesModel;
use App\Facades\ResponseHelper;
class PagesController extends Controller{

    public function __construct(){
        $this->middleware('auth');
    }

    function index(Request $request){
        return view('backend.pages.index');
    }

    public function getPagesData(Request $request){
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
        $total = PagesModel::Where(function ($query) use ($search) {
                $query->Where('title', 'like', '%' . $search . '%');

            })->count();

        $pages = PagesModel::Where(function ($query) use ($search) {
                $query->Where('title', 'like', '%' . $search . '%');
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($pages as $key => $val) {
            $status = $val->status ? "checked" : "";
            $data[] = array(
                $key + 1,
                $val->title,
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.page', [$val->id]) . '" class="editModule" data-id="' . $val->id . '" data-title="' . $val->title . '" data-slug="' . $val->slug .  '" data-status="' . $val->status . '" ><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | <a  href="javascript::void(0)" class="deleteMCQ" data-id="' . $val->id . '"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        echo json_encode($records);
    }

        public function updatePageStatus(Request $request){
            $update = PagesModel::where('id', $request->id)->update(['status' => $request->status]);
            return response()->json([
                'status' => true,
                'message' => "Success",
                'data' => []
            ]);

        }
    function AddPage(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'title'=>'required',
                'slug'=>'required',
                'description'=>'required',
                'meta_title'=>'required',
                'meta_keywords'=>'required',
                'meta_description'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $page = new PagesModel();
                $page->title = $data['title'];
                $page->slug = slug($data['slug']);
                $page->description = $data['description'];
                $page->meta_title = $data['meta_title'];
                $page->meta_keywords = $data['meta_keywords'];
                $page->meta_description = $data['meta_description'];
                $page->status = 1;
                $page->created_at = date('Y-m-d H:i:s');
                $page->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('backend.pages.add');
    }

    function EditPage(Request $request,$id){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'title'=>'required',
                'slug'=>'required',
                'description'=>'required',
                'meta_title'=>'required',
                'meta_keywords'=>'required',
                'meta_description'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array(
                    "title" => $data['title'],
                    "slug" => $data['slug'],
                    "description" => $data['description'],
                    "meta_title" => $data['meta_title'],
                    "meta_keywords" => $data['meta_keywords'],
                    "meta_description" => $data['meta_description'],
                );
                $affectedRows = PagesModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $page = PagesModel::where('id',$id)->first();
        return view('backend.pages.edit',compact('page'));
    }
}
