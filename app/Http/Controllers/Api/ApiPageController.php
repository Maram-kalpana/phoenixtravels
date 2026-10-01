<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ApiPageController extends Controller{
    
    public function index(){
        return view('api.privacy_policy_view');
    }
    
    public function terms_and_conditions(){
        return view('api.term_conditions_view');
    }
    
    public function about(){
        return view('api.about_view');
    }
    
    
   
}
