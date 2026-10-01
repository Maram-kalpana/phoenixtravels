<?php

namespace App\Http\Controllers;
use Validator,Auth;
use App\Facades\ResponseHelper;
use App\Models\CustomSection;
use App\Models\ToursModel;
use Illuminate\Http\Request;

class HomePageSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('backend.home_page_section.list');
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
                'tour_id'=>'required',
                'section_name'=>'required',
                'section_title'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $home_page_section = new CustomSection();
                $home_page_section->tour_id =  implode(',', $data['tour_id']);
                $home_page_section->section_name = $data['section_name'];
                $home_page_section->section_title = $data['section_title'];
                $home_page_section->section_style = $data['section_style'];
                $home_page_section->section_style_image = $data['section_style_image'];
                $home_page_section->status = 1;
                $home_page_section->created_at = date('Y-m-d H:i:s');
                $home_page_section->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $tours = ToursModel::where('status',1)->get();
        return view('backend.home_page_section.add',compact('tours'));
    }

    /**
     * Store a newly created resource in storage.
     */
    function getHomePageSectionData(Request $request)
    {
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
        $total = CustomSection::Where(function ($query) use ($search) {
                $query->Where('custom_section.section_name', 'like', '%' . $search . '%');
            })->count();

        $home_page_section = CustomSection::Where(function ($query) use ($search) {
                $query->Where('custom_section.section_name', 'like', '%' . $search . '%');
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($home_page_section as $key => $val) {
            $status = $val->status ? "checked" : "";
            $data[] = array(
                $key + 1,
                $val->section_name,
                "<div class='switch'>
                    <div class='onoffswitch'>
                        <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='example" . $val->id . "'>
                        <label class='onoffswitch-label' for='example" . $val->id . "'>
                            <span class='onoffswitch-inner'></span>
                            <span class='onoffswitch-switch'></span>
                        </label>
                    </div>
                </div>",
                '<a href="' . route('edit.home.page.section', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | <a  href="javascript::void(0)" class="deleteMCQ" onclick="validate('.$val->id .')"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        echo json_encode($records);
    }
    

    /**
     * Display the specified resource.
     */
    public function updateHomePageSectionStatus(Request $request){
        $update = CustomSection::where('id', $request->id)->update(['status' => $request->status]);
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
                'tour_id'=>'required',
                'section_name'=>'required',
                'section_title'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $update_arr = array("tour_id" => implode(',', $data['tour_id']),"section_name" => $data['section_name'],"section_title" => $data['section_title'],"section_style" => $data['section_style'],"section_style_image" => $data['section_style_image']);
                $affectedRows = CustomSection::where("id", $id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $home_page_section = CustomSection::where('id',$id)->first();
        $tours = ToursModel::where('status',1)->get();
        return view('backend.home_page_section.edit',compact('home_page_section','tours'));
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
            $banner = CustomSection::findOrFail($id);
            if ($banner){
                $banner->delete();
                return response()->json(array('success' => true));
            }
        }
    }
}
