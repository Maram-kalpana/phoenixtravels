<?php

namespace App\Http\Controllers;
use App\Models\CityModel;
use App\Models\StateModel;
use Illuminate\Support\Facades\Hash;
use App\Models\CountryModel;
use App\Models\AgentModel;
use Validator,Auth;
use App\Facades\ResponseHelper;
use Illuminate\Http\Request;

class AgentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
     return view('backend.agents.index');
    }

    /**
     * Show the form for creating a new resource.
     */
        public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $response_code = 200;
                $message = ['error' => ['something went wrong']];
                $success = false;
                $data = $request->all();

                // Validation rules
                $rules = [
                    'full_legal_name' => 'required|string|max:255',
                    'personal_mobile' => 'required|string|max:20',
                    'personal_email' => 'required|email|unique:agents,personal_email',
                    'date_of_joining' => 'required|date',
                    'designation' => 'required|string|max:100',
                    'department' => 'required|string|max:100',
                    'pan_number' => 'required|string|max:20|unique:agents,pan_number',
                    'aadhaar_number' => 'required|string|max:20|unique:agents,aadhaar_number',
                    'account_holder' => 'required|string|max:255',
                    'account_number' => 'required|string|max:50',
                    'ifsc_code' => 'required|string|max:20',
                    'password' => 'required|string|min:8|confirmed',
                    'password_confirmation' => 'required',
                    'acknowledge_handbook' => 'required|accepted',
                    'acknowledge_data_privacy' => 'required|accepted',
                    'acknowledge_fraud_policy' => 'required|accepted',
                    'acknowledge_non_compete' => 'required|accepted',
                ];

                $validator = Validator::make($data, $rules);

                if ($validator->fails()) {
                    $success = false;
                    $message = ['error' => $validator->errors()];
                    return ResponseHelper::returnResponse(422, $success, $message);
                }

                // Handle profile image upload
                $profile_image = null;
                if ($request->hasFile('profile_image')) {
                    $image = $request->file('profile_image');
                    $file_name = time() . rand(100, 999) . '.' . $image->getClientOriginalExtension();
                    $image->move('uploads/profile/', $file_name);
                    $profile_image = 'uploads/profile/' . $file_name;
                }

                // Handle PAN card upload
                $pan_card_upload = null;
                if ($request->hasFile('pan_card_upload')) {
                    $file = $request->file('pan_card_upload');
                    $file_name = 'pan_' . time() . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                    $file->move('uploads/documents/', $file_name);
                    $pan_card_upload = 'uploads/documents/' . $file_name;
                }

                // Handle Aadhaar card upload
                $aadhaar_upload = null;
                if ($request->hasFile('aadhaar_upload')) {
                    $file = $request->file('aadhaar_upload');
                    $file_name = 'aadhaar_' . time() . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                    $file->move('uploads/documents/', $file_name);
                    $aadhaar_upload = 'uploads/documents/' . $file_name;
                }

                // Generate Employee ID
                $employee_id = $request->employee_id ?? 'PTT' . date('Y') . str_pad(AgentModel::count() + 1, 4, '0', STR_PAD_LEFT);

                // Create agent
                $agent = new AgentModel();
                $agent->full_legal_name = $data['full_legal_name'];
                $agent->preferred_name = $data['preferred_name'] ?? null;
                $agent->personal_mobile = $data['personal_mobile'];
                $agent->personal_email = $data['personal_email'];
                $agent->dob = $data['dob'] ?? null;
                $agent->gender = $data['gender'] ?? null;
                $agent->profile_image = $profile_image;
                $agent->employee_id = $employee_id;
                $agent->phoenix_email = $data['phoenix_email'] ?? null;
                $agent->date_of_joining = $data['date_of_joining'];
                $agent->designation = $data['designation'];
                $agent->department = $data['department'];
                $agent->reporting_manager = $data['reporting_manager'] ?? null;
                $agent->work_location = $data['work_location'] ?? null;
                $agent->work_type = $data['work_type'] ?? null;
                $agent->core_specialization = $data['core_specialization'] ?? null;
                $agent->secondary_specialization = $data['secondary_specialization'] ?? null;
                $agent->gds_systems = isset($data['gds_systems']) ? implode(',', $data['gds_systems']) : null;
                $agent->languages_spoken = $data['languages_spoken'] ?? null;
                $agent->experience_years = $data['experience_years'] ?? 0;
                $agent->pan_number = $data['pan_number'];
                $agent->pan_card_upload = $pan_card_upload;
                $agent->aadhaar_number = $data['aadhaar_number'];
                $agent->aadhaar_upload = $aadhaar_upload;
                $agent->emergency_contact_name = $data['emergency_contact_name'] ?? null;
                $agent->emergency_contact_relation = $data['emergency_contact_relation'] ?? null;
                $agent->emergency_contact_phone = $data['emergency_contact_phone'] ?? null;
                $agent->address = $data['address'] ?? null;
                $agent->country_id = $data['country_id'] ?? null;
                $agent->state_id = $data['state_id'] ?? null;
                $agent->city_id = $data['city_id'] ?? null;
                $agent->pin_code = $data['pin_code'] ?? null;
                $agent->account_holder = $data['account_holder'];
                $agent->account_number = $data['account_number'];
                $agent->bank_name = $data['bank_name'] ?? null;
                $agent->bank_branch = $data['bank_branch'] ?? null;
                $agent->ifsc_code = $data['ifsc_code'];
                $agent->commission_type = $data['commission_type'] ?? 'fixed';
                $agent->commission_value = $data['commission_value'] ?? null;
                $agent->monthly_target = $data['monthly_target'] ?? 0;
                $agent->system_access = isset($data['system_access']) ? implode(',', $data['system_access']) : null;
                $agent->laptop_serial = $data['laptop_serial'] ?? null;
                $agent->sim_number = $data['sim_number'] ?? null;
                $agent->id_card_number = $data['id_card_number'] ?? null;
                $agent->uniform_size = $data['uniform_size'] ?? null;
                $agent->referral_code = $data['referral_code'] ?? null;
                $agent->referred_by = $data['referred_by'] ?? null;
                $agent->password = Hash::make($data['password']);
                $agent->acknowledge_handbook = isset($data['acknowledge_handbook']) ? 1 : 0;
                $agent->acknowledge_data_privacy = isset($data['acknowledge_data_privacy']) ? 1 : 0;
                $agent->acknowledge_fraud_policy = isset($data['acknowledge_fraud_policy']) ? 1 : 0;
                $agent->acknowledge_non_compete = isset($data['acknowledge_non_compete']) ? 1 : 0;
                $agent->status = 1;
                $agent->created_at = date('Y-m-d H:i:s');
                $agent->save();

                $success = true;
                $message = ['success' => ['Agent onboarded successfully! Employee ID: ' . $employee_id]];
                return ResponseHelper::returnResponse(200, $success, $message);

            } catch (\Exception $e) {
                return ResponseHelper::returnResponse(500, false, ['error' => [$e->getMessage()]]);
            }
        }

        // GET request - show form
        $country = CountryModel::where('status', 1)->get();
        return view('backend.agents.add', compact('country'));
    }


    function getAgentsData(Request $request){
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
        $total = AgentModel::Where(function ($query) use ($search) {
                $query->Where('agents.full_legal_name', 'like', '%' . $search . '%');
            })->count();

        $students = AgentModel::Where(function ($query) use ($search) {
                $query->Where('agents.full_legal_name', 'like', '%' . $search . '%');
            })
            ->offset($offset)->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($modules);
        $i = 1 + $offset;
        $data = [];
        foreach ($students as $key => $val) {
            $status = $val->status ? "checked" : "";
            $new_image = asset(''). $val->profile_image;
            $image = ($val->profile_image) ? '<img src="' .$new_image . '" class="img-fluid" height="100px" width="100px">' : '';
            $data[] = array(
                $key + 1,
                $val->full_legal_name,
                $val->personal_email,
                $val->personal_mobile,
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
                '<a href="' . route('edit.agents', [$val->id]) . '" class="editModule" data-id="' . $val->id . '"><i class="bx bxs-edit text-success" style="font-size: 22px;"></i></a> | <a  href="javascript::void(0)" class="deleteMCQ" onclick="validate('.$val->id .')" data-id="' . $val->id . '"><i class="bx bxs-trash text-danger" style="font-size: 22px;"></i></a>'
            );
        }
        $records['recordsTotal'] = $total;
        $records['recordsFiltered'] = $total;
        $records['data'] = $data;
        echo json_encode($records);
    }
    

    public function edit($id){
        $agent = AgentModel::findOrFail($id);
        $countries = CountryModel::where('status', 1)->get();
        $states = StateModel::where('status', 1)->get();
        $cities = CityModel::where('status', 1)->get();
        
        return view('backend.agents.edit', compact('agent', 'countries', 'states', 'cities'));
    }
    
    /**
     * Update agent
     */
    public function update(Request $request, $id)
{
    try {
        $agent = AgentModel::findOrFail($id);
        $success = false;
        $message = ['error' => ['Something went wrong']];

        // Validation rules
        $rules = [
            'full_legal_name' => 'required|string|max:255',
            'personal_mobile' => 'required|string|max:20',
            'personal_email' => 'required|email|unique:agents,personal_email,' . $id,
            'date_of_joining' => 'required|date',
            'designation' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'pan_number' => 'required|string|max:20|unique:agents,pan_number,' . $id,
            'aadhaar_number' => 'required|string|max:20|unique:agents,aadhaar_number,' . $id,
            'account_holder' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code' => 'required|string|max:20',
        ];

        // Add password validation only if password is provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['password_confirmation'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => ['error' => $validator->errors()]
            ], 422);
        }

        $data = $request->all();

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($agent->profile_image && file_exists(public_path($agent->profile_image))) {
                unlink(public_path($agent->profile_image));
            }
            
            $image = $request->file('profile_image');
            $file_name = time() . rand(100, 999) . '.' . $image->getClientOriginalExtension();
            $image->move('uploads/profile/', $file_name);
            $agent->profile_image = 'uploads/profile/' . $file_name;
        }

        // Handle PAN card upload
        if ($request->hasFile('pan_card_upload')) {
            if ($agent->pan_card_upload && file_exists(public_path($agent->pan_card_upload))) {
                unlink(public_path($agent->pan_card_upload));
            }
            
            $file = $request->file('pan_card_upload');
            $file_name = 'pan_' . time() . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/documents/', $file_name);
            $agent->pan_card_upload = 'uploads/documents/' . $file_name;
        }

        // Handle Aadhaar card upload
        if ($request->hasFile('aadhaar_upload')) {
            if ($agent->aadhaar_upload && file_exists(public_path($agent->aadhaar_upload))) {
                unlink(public_path($agent->aadhaar_upload));
            }
            
            $file = $request->file('aadhaar_upload');
            $file_name = 'aadhaar_' . time() . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/documents/', $file_name);
            $agent->aadhaar_upload = 'uploads/documents/' . $file_name;
        }

        // Update all fields
        $agent->full_legal_name = $data['full_legal_name'];
        $agent->preferred_name = $data['preferred_name'] ?? null;
        $agent->personal_mobile = $data['personal_mobile'];
        $agent->personal_email = $data['personal_email'];
        $agent->dob = $data['dob'] ?? null;
        $agent->gender = $data['gender'] ?? null;
        $agent->phoenix_email = $data['phoenix_email'] ?? null;
        $agent->date_of_joining = $data['date_of_joining'];
        $agent->designation = $data['designation'];
        $agent->department = $data['department'];
        $agent->reporting_manager = $data['reporting_manager'] ?? null;
        $agent->work_location = $data['work_location'] ?? null;
        $agent->work_type = $data['work_type'] ?? null;
        $agent->core_specialization = $data['core_specialization'] ?? null;
        $agent->secondary_specialization = $data['secondary_specialization'] ?? null;
        $agent->gds_systems = isset($data['gds_systems']) ? implode(',', $data['gds_systems']) : null;
        $agent->languages_spoken = $data['languages_spoken'] ?? null;
        $agent->experience_years = $data['experience_years'] ?? 0;
        $agent->pan_number = $data['pan_number'];
        $agent->aadhaar_number = $data['aadhaar_number'];
        $agent->emergency_contact_name = $data['emergency_contact_name'] ?? null;
        $agent->emergency_contact_relation = $data['emergency_contact_relation'] ?? null;
        $agent->emergency_contact_phone = $data['emergency_contact_phone'] ?? null;
        $agent->address = $data['address'] ?? null;
        $agent->country_id = $data['country_id'] ?? null;
        $agent->state_id = $data['state_id'] ?? null;
        $agent->city_id = $data['city_id'] ?? null;
        $agent->pin_code = $data['pin_code'] ?? null;
        $agent->account_holder = $data['account_holder'];
        $agent->account_number = $data['account_number'];
        $agent->bank_name = $data['bank_name'] ?? null;
        $agent->bank_branch = $data['bank_branch'] ?? null;
        $agent->ifsc_code = $data['ifsc_code'];
        $agent->commission_type = $data['commission_type'] ?? 'fixed';
        $agent->commission_value = $data['commission_value'] ?? null;
        $agent->monthly_target = $data['monthly_target'] ?? 0;
        $agent->system_access = isset($data['system_access']) ? implode(',', $data['system_access']) : null;
        $agent->laptop_serial = $data['laptop_serial'] ?? null;
        $agent->sim_number = $data['sim_number'] ?? null;
        $agent->id_card_number = $data['id_card_number'] ?? null;
        $agent->uniform_size = $data['uniform_size'] ?? null;
        $agent->referral_code = $data['referral_code'] ?? null;
        $agent->referred_by = $data['referred_by'] ?? null;
        
        // Update password only if provided
        if ($request->filled('password')) {
            $agent->password = Hash::make($data['password']);
        }
        
        $agent->acknowledge_handbook = isset($data['acknowledge_handbook']) ? 1 : 0;
        $agent->acknowledge_data_privacy = isset($data['acknowledge_data_privacy']) ? 1 : 0;
        $agent->acknowledge_fraud_policy = isset($data['acknowledge_fraud_policy']) ? 1 : 0;
        $agent->acknowledge_non_compete = isset($data['acknowledge_non_compete']) ? 1 : 0;
        $agent->status = isset($data['status']) ? 1 : 0;
        $agent->updated_at = date('Y-m-d H:i:s');
        $agent->save();

        // Return simple success response without the error wrapper
        return response()->json([
            'success' => true,
            'message' => 'Agent updated successfully!'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function updateAgentsStatus(Request $request){
        $update = AgentModel::where('id', $request->id)->update(['status' => $request->status]);
        return response()->json([
            'status' => true,
            'message' => "Success",
            'data' => []
        ]);

    }
    public function destroy(Request $request, $id){
         if ($request->ajax()){
            $banner = AgentModel::findOrFail($id);
            if ($banner){
                $banner->delete();
                return response()->json(array('success' => true));
            }
        }
    }
    

      public function GetStateName(Request $request){
             $country_id = $request->country_id;
             $state = StateModel::where('country_id',$country_id)->where('status',1)->get();
             if(!empty($state)){
    			$html=' <select class="form-control state_id" name="state_id" id="state_id"><option value="">----Select State----</option>';
    			foreach($state as $sub){
    				$html.=	 '<option value="'.$sub->id.'"  >'.$sub->name.'</option>';
    			}
    			$html.=' </select><span id="crr_statelist" style="color:red"></span>';
    		}else{
    			$html= ' <select name="state_id" id="state_id"><option value="">--No data--</option></select>'; 
    		}
    		echo $html;exit;
            
        }
        
    
}
