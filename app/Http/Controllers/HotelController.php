<?php
namespace App\Http\Controllers;
use App\Facades\ResponseHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Validator,DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HotelController extends Controller{
    
    
    
    public function index(Request $request){
    
        return view('frontend.hotel-list');
    }


}
