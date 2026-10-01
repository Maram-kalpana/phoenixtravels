<?php

namespace App\Http\Controllers;
use Validator,Auth;
use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\TicketMessageModel;
use App\Models\SupportTicketsModel;
use App\Models\CityModel;
use App\Models\CustomerModel;
use App\Models\WalletModel;
use App\Models\SubmitPayModel;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Facades\ResponseHelper;
class CustomerController extends Controller{

    function index(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        return view('user.dashboard',compact('customer_details'));
    }
    function BookingHistory(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $bookings = Payment::select('*')->where('user_id',$customer_id)->orderBy('id', 'desc')->get();
        return view('user.bookings_history',compact('customer_details','bookings'));
    }
    function Refer(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $referral_link = array();
        $referrals = array();
        return view('user.refer',compact('customer_details','referral_link','referrals'));
    }
    function Notifications(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $notifications = array();
        return view('user.notifications',compact('customer_details','notifications'));
    }
    
      public function PaymentHistory(Request $request)
    {
        $customer_id = \Session::get('customer_id');
        
        // If no customer ID in session, redirect to login
        if (!$customer_id) {
            return redirect()->route('login')->with('error', 'Please login to continue');
        }
        
        $customer_details = CustomerModel::select('*')
            ->where('id', $customer_id)
            ->orderBy('id', 'desc')
            ->first();
        
        // Handle AJAX request for pagination and filtering
        if ($request->ajax()) {
            try {
                $perPage = $request->get('per_page', 10);
                $searchTerm = $request->get('search', '');
                $statusFilter = $request->get('status', 'all');
                $dateRangeFilter = $request->get('date_range', '');
                
                $query = SubmitPayModel::where('user_id', $customer_id);
                
                // Apply search filter
                if (!empty($searchTerm)) {
                    $query->where(function($q) use ($searchTerm) {
                        $q->where('id', 'LIKE', '%' . $searchTerm . '%')
                          ->orWhere('payment_id', 'LIKE', '%' . $searchTerm . '%')
                          ->orWhere('service_name', 'LIKE', '%' . $searchTerm . '%')
                          ->orWhere('service_type', 'LIKE', '%' . $searchTerm . '%')
                          ->orWhere('transaction_id', 'LIKE', '%' . $searchTerm . '%');
                    });
                }
                
                // Apply status filter
                if (!empty($statusFilter) && $statusFilter !== 'all') {
                    $query->where('payment_status', $statusFilter);
                }
                
                // Apply date range filter
                if (!empty($dateRangeFilter)) {
                    $dates = explode(' - ', $dateRangeFilter);
                    if (count($dates) == 2) {
                        $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                        $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();
                        
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    }
                }
                
                // Get paginated results
                $payments = $query->orderBy('id', 'desc')->paginate($perPage);
                
                // Transform data for response
                $transformedData = [];
                foreach ($payments as $payment) {
                    $transformedData[] = [
                        'id' => $payment->id,
                        'payment_id' => $payment->payment_id ?? 'PA-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT),
                        'service_name' => $payment->service_name ?? 'N/A',
                        'service_type' => $payment->service_type ?? 'N/A',
                        'payment_method' => $payment->payment_method ?? $payment->payment_type ?? 'N/A',
                        'payment_status' => $payment->payment_status ?? 'pending',
                        'amount' => $payment->amount ?? 0,
                        'created_at' => $payment->created_at ? $payment->created_at->format('Y-m-d H:i:s') : null,
                        'transaction_id' => $payment->transaction_id ?? null,
                        'invoice_id' => $payment->invoice_id ?? null,
                    ];
                }
                
                return response()->json([
                    'success' => true,
                    'data' => $transformedData,
                    'pagination' => [
                        'current_page' => $payments->currentPage(),
                        'last_page' => $payments->lastPage(),
                        'per_page' => $payments->perPage(),
                        'total' => $payments->total(),
                        'from' => $payments->firstItem(),
                        'to' => $payments->lastItem(),
                    ]
                ]);
                
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading payments: ' . $e->getMessage()
                ], 500);
            }
        }
        
        // Initial page load - get first page of payments
        $payments = SubmitPayModel::where('user_id', $customer_id)
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('user.payments', compact('customer_details', 'payments'));
    }
    function WalletHistory(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $transactions = WalletModel::select('*')->where('user_id',$customer_id)->orderBy('id', 'desc')->get();
        return view('user.wallet',compact('customer_details','transactions'));
    }
    
    function Support(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $tickets = SupportTicketsModel::select('*')->where('user_id', $customer_id)->orderBy('id', 'desc')->get();
        return view('user.support',compact('customer_details','tickets'));
    }
    function viewTicket(Request $request, $id){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $ticket = SupportTicketsModel::select('*')->where('id', $id)->where('user_id', $customer_id)->orderBy('id', 'desc')->first();
        $messages = TicketMessageModel::where('ticket_id',$id)->orderBy('created_at','asc')->get();
        return view('user.view_ticket',compact('customer_details','ticket','messages'));
    }
    public function replyTicket(Request $request,$id){
        TicketMessageModel::create(['ticket_id'=>$id,'type'=>'user','message'=>$request->message]);
        SupportTicketsModel::where('id',$id)->update(['status'=>'open']);
        return back();
    }
    function createTicket(Request $request){
        $customer_id = \Session::get('customer_id');
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'subject'=>'required',
                'message'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $tickets = new SupportTicketsModel();
                $tickets->user_id = $customer_id;
                $tickets->subject = $data['subject'];
                $tickets->message = $data['message'];
                $tickets->status = 'open';
                $tickets->created_at = date('Y-m-d H:i:s');
                $tickets->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        return view('user.create_ticket',compact('customer_details'));
    }
    function UpdateProfile(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'full_name'=>'required',
                'phone'=>'required',
                'email'=>'required',
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $customer_id = \Session::get('customer_id');
                if($request->file('profile_image') != ''){
                    $logo_image = 'uploads/profile/'.$request->file('profile_image')->getClientOriginalName();
                    $file_name = time().rand(100,999).$logo_image;
                    $request->file('profile_image')->move('uploads/profile/', $file_name);
                    }else{
                        $get_setting = CustomerModel::where('id',$customer_id)->first();
                        $logo_image = $get_setting->profile_image;
                }
                $update_arr = array(
                    "profile_image" => $logo_image,
                    "full_name" => $data['full_name'],
                    "phone" => $data['phone'],
                    "address" => $data['address'],
                    "city_id" => $data['city_id'],
                    "state_id" => $data['state_id'],
                    "country_id" => $data['country_id'],
                    "email" => $data['email'],
                );
                $customer_id = \Session::get('customer_id');
                $affectedRows = CustomerModel::where("id", $customer_id)->update($update_arr);
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $country = CountryModel::where('status',1)->get();
        $state = StateModel::where('status',1)->get();
        $city = CityModel::where('status',1)->get();
        return view('user.profile',compact('customer_details','country','state','city'));
    }
     
     
    function FlightsBooking(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $notifications = array();
        return view('user.flights_booking',compact('customer_details','notifications'));
    }
    
    function HotelBooking(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $notifications = array();
        return view('user.hotel_booking',compact('customer_details','notifications'));
    }
    
    function CarBooking(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $notifications = array();
        return view('user.car_booking',compact('customer_details','notifications'));
    }
    
    function CruiseBooking(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $notifications = array();
        return view('user.cruise_booking',compact('customer_details','notifications'));
    }
    
    function TourBooking(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $notifications = array();
        return view('user.tour_booking',compact('customer_details','notifications'));
    }
    
    
    function BusBooking(Request $request){
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::select('*')->where('id', $customer_id)->orderBy('id', 'desc')->first();
        $notifications = array();
        return view('user.bus_booking',compact('customer_details','notifications'));
    }
    
}
