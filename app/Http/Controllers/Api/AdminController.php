<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\StoreModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Mail;
use Exception;
class AdminController extends Controller{
    
     public function login(Request $request){
            try {
                $validation = Validator::make(
                    $request->all(),
                    [
                        'email' => 'required|email',
                        'password' => 'required',
                    ],
                    [
                        'email.required' => __('api_msg.please_enter_required_fields'),
                        'password.required' => __('api_msg.please_enter_required_fields'),
                    ]
                );
                if ($validation->fails()) {
                    $errors = $validation->errors()->first();
                    return APIResponse(400, $errors ?: __('api_msg.please_enter_required_fields'), []);
                }
                $user = User::where('email', $request->email)->first();
                if (!$user || !Hash::check($request->password, $user->password)) {
                    return APIResponse(401, __('api_msg.invalid_credentials'), []);
                }
                return APIResponse(200, __('api_msg.login_successfully'), [$user]);
            } catch (Exception $e) {
                return APIResponse(500, __('api_msg.server_error'), ['error' => $e->getMessage()]);
            }
        }
        
        
    public function store_list(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'per_page' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
            ]);
            if ($validator->fails()) {
                return APIResponse(400, __('api_msg.validation_error'), ['errors' => $validator->errors()]);
            }
            $perPage = $request->per_page ?? 15; // Default to 15 items per page
            $stores = StoreModel::where('status', 1)
                ->orderBy('created_at', 'desc') // Default ordering
                ->paginate($perPage);
            $response = [
                'data' => $stores->items(),
                'pagination' => [
                    'total' => $stores->total(),
                    'per_page' => $stores->perPage(),
                    'current_page' => $stores->currentPage(),
                    'last_page' => $stores->lastPage(),
                    'from' => $stores->firstItem(),
                    'to' => $stores->lastItem(),
                ]
            ];
            return APIResponse(200, __('api_msg.get_record_successfully'), $response);
        } catch (Exception $e) {
            \Log::error('Store list error: ' . $e->getMessage());
            return APIResponse(500, __('api_msg.server_error'), [
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ]);
        }
    }
    
    public function search_store_list(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'search_term' => 'nullable|string|max:255',
                'per_page' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
            ]);
            if ($validator->fails()) {
                return APIResponse(400, __('api_msg.validation_error'), ['errors' => $validator->errors()]);
            }
            $query = StoreModel::where('status', 1);
            if ($request->has('search_term') && !empty($request->search_term)) {
                $searchTerm = $request->search_term;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('store_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('owner_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('location', 'LIKE', "%{$searchTerm}%");
                });
            }
            $query->orderBy('store_name', 'asc');
            $perPage = $request->per_page ?? 10;
            $stores = $query->paginate($perPage);
            $response = [
                'data' => $stores->items(),
                'pagination' => [
                    'total' => $stores->total(),
                    'per_page' => $stores->perPage(),
                    'current_page' => $stores->currentPage(),
                    'last_page' => $stores->lastPage(),
                    'from' => $stores->firstItem(),
                    'to' => $stores->lastItem(),
                ]
            ];
            return APIResponse(200, __('api_msg.get_record_successfully'), $response);
        } catch (Exception $e) {
            \Log::error('Store search error: ' . $e->getMessage());
            return APIResponse(500, __('api_msg.server_error'), [
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ]);
        }
    }
    
    public function orders_report_list(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'search_term' => 'nullable|string|max:255',
                'per_page' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
            ]);
            if ($validator->fails()) {
                return APIResponse(400, __('api_msg.validation_error'), ['errors' => $validator->errors()]);
            }
            $query = StoreModel::where('status', 1);
            if ($request->has('search_term') && !empty($request->search_term)) {
                $searchTerm = $request->search_term;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('store_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('owner_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('location', 'LIKE', "%{$searchTerm}%");
                });
            }
            $query->orderBy('store_name', 'asc');
            $perPage = $request->per_page ?? 10;
            $stores = $query->paginate($perPage);
            $response = [
                'data' => $stores->items(),
                'pagination' => [
                    'total' => $stores->total(),
                    'per_page' => $stores->perPage(),
                    'current_page' => $stores->currentPage(),
                    'last_page' => $stores->lastPage(),
                    'from' => $stores->firstItem(),
                    'to' => $stores->lastItem(),
                ]
            ];
            return APIResponse(200, __('api_msg.get_record_successfully'), $response);
        } catch (Exception $e) {
            \Log::error('Store search error: ' . $e->getMessage());
            return APIResponse(500, __('api_msg.server_error'), [
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ]);
        }
    }
}