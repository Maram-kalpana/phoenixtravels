<?php

namespace App\Http\Controllers;

use App\Models\BlogModel;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
     return view('backend.blog.list');
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
                'title'=>'required',
                'slug'=>'required',
                'short_description'=>'required',
                'image'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                if($request->file('image') != ''){
                    $image = 'uploads/blog/'.$request->file('image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$image;
                    $request->file('image')->move('uploads/blog/', $file_name);
                }
                $blog = new blogModel();
                $blog->title = $data['title'];
                $blog->image = $image;
                $blog->slug = $data['slug'];
                $blog->short_description = $data['short_description'];
                $blog->description = $data['description'];
                $blog->meta_title = $data['meta_title'];
                $blog->meta_keywords = $data['meta_keywords'];
                $blog->meta_description = $data['meta_description'];
                $blog->status = 1;
                $blog->created_at = date('Y-m-d H:i:s');
                $blog->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('backend.blog.add');
    }

    function getBlogData(Request $request){
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
        $total = BlogModel::Where(function ($query) use ($search) {
                $query->Where('blog.title', 'like', '%' . $search . '%');
            })->count();

        $course = BlogModel::Where(function ($query) use ($search) {
                $query->Where('blog.title', 'like', '%' . $search . '%');
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($course as $key => $val) {
            $new_image = asset(''). $val->image;
            $image = ($val->image) ? '<img src="' .$new_image . '" class="img-fluid" height="100px" width="100px">' : '';
            $status = $val->status ? "checked" : "";
            $data[] = array(
                $key + 1,
                $val->title,
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
                '<a href="' . route('edit.blog', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | 
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
            $rules=['title'=>'required'];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                 if($request->file('image') != ''){
                    $blog_image = 'uploads/blog/'.$request->file('image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$banner_image;
                    $request->file('image')->move('uploads/blog/', $file_name);
                }else{
                  $old_image =  BlogModel::where('id',$id)->first();
                  $blog_image =$old_image->image;
                }
                $update_arr = array(
                "image" => $blog_image,
                "title" => $data['title'],
                "slug" => $data['slug'],
                "short_description" => $data['short_description'],
                "description" => $data['description'],
                "meta_title" => $data['meta_title'],
                "meta_keywords" => $data['meta_keywords'],
                "meta_description" => $data['meta_description']);
                $affectedRows = BlogModel::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $blog = BlogModel::where('id',$id)->first();
        return view('backend.blog.edit',compact('blog'));
    }
    public function updateBlogStatus(Request $request){
        $update = BlogModel::where('id', $request->id)->update(['status' => $request->status]);
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
            $course = BlogModel::findOrFail($id);
            if ($course){
                $course->delete();
                return response()->json(array('success' => true));
            }
        }
    }

  
    
    
}
