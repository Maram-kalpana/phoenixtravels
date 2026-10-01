<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FAQModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Mail;
use Exception;

class FaqController extends Controller{
    
     public function list(Request $request){
             try {
            $validator = Validator::make($request->all(), [
                'per_page' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
            ]);
            if ($validator->fails()) {
                return APIResponse(400, __('api_msg.validation_error'), ['errors' => $validator->errors()]);
            }
            $query = FAQModel::select('id','question','answer')->where('status', 1);
            $perPage = $request->per_page ?? 10;
            $faq_list = $query->paginate($perPage);
            $response = [
                'data' => $faq_list->items(),
                'pagination' => [
                    'total' => $faq_list->total(),
                    'per_page' => $faq_list->perPage(),
                    'current_page' => $faq_list->currentPage(),
                    'last_page' => $faq_list->lastPage(),
                    'from' => $faq_list->firstItem(),
                    'to' => $faq_list->lastItem(),
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