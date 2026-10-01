<?php

namespace App\Http\Controllers\Api; // Add namespace
use App\Http\Controllers\Controller; // Import Controller
use App\Models\ToursModel;
use App\Models\CustomerModel;
use App\Models\PackagesImagesModel;
use App\Models\PackageModel;
use App\Services\RazorpayService;
use App\Models\CouponModel;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ItineraryModel;
use App\Models\SubmitPayModel;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Validator;
use Exception;

class PackageController extends Controller // Extend Controller
{
    protected $razorpay;

    public function __construct(RazorpayService $razorpay)
    {
        $this->razorpay = $razorpay;
    }
    
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tour_id' => 'required|numeric', // Added exists validation
                'per_page' => 'nullable|numeric|min:1|max:100',
                'page' => 'nullable|numeric|min:1'
            ], [
                'tour_id.required' => __('api_msg.please_enter_required_fields'),
                'tour_id.exists' => __('api_msg.tour_not_found') // Added custom message
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()->first()
                ]);
            }

            $tour_id = $request->tour_id;
            $perPage = $request->per_page ?? 15;
            
            // Get packages for the tour
            $packages = PackageModel::where('tour_id', $tour_id)
                ->where('status', 1)
                ->select(
                    'id', 
                    'package_id', 
                    'package_title', 
                    's_individual', 
                    'days_count', 
                    'nights_count', 
                    'inclusions', 
                    'exclusions', 
                    'pack_location_cover', 
                    'package_homepage_image',
                    'status',
                    'created_at',
                    'updated_at'
                )
                ->paginate($perPage);
                
            if ($packages->isEmpty()) {
                return $this->apiResponse(400, __('api_msg.data_not_found'));
            }
            
            // Format the packages
            $tour = ToursModel::select('tour_name')->where('id', $tour_id)->first();
            if (!$tour) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Tour not found'
                ]);
            }
            $formattedPackages = collect($packages->items())->map(fn($package) => $this->formatPackageDetails($package));
            
            
            
           
            $response = [
                 'state_name' => $tour->tour_name,
                'data' => $formattedPackages,
                'pagination' => [
                    'current_page' => $packages->currentPage(),
                    'last_page' => $packages->lastPage(),
                    'per_page' => $packages->perPage(),
                    'total' => $packages->total()
                ]
            ];

            return $this->apiResponse(200, __('api_msg.get_record_successfully'), $response);

        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
    
    

    private function formatPackageDetails($package)
    {
        return [
            'id' => $package->id,
            'package_id' => $package->package_id,
            'package_title' => $package->package_title,
            's_individual' => $package->s_individual,
            'days_count' => $package->days_count,
            'nights_count' => $package->nights_count,
            'inclusions' => $package->inclusions,
            'exclusions' => $package->exclusions,
            'pack_location_cover' => $package->pack_location_cover,
            'package_homepage_image' => $package->package_homepage_image ? asset($package->package_homepage_image) : '',
            'status' => $package->status,
            'created_at' => $package->created_at ? $package->created_at->toDateTimeString() : null,
            'updated_at' => $package->updated_at ? $package->updated_at->toDateTimeString() : null,
        ];
    }

    /**
     * Custom API Response helper
     */
    private function apiResponse($status, $message, $data = null)
    {
        $response = [
            'status' => $status,
            'message' => $message
        ];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        return response()->json($response, $status);
    }

    private function handleError(Exception $e)
    {
        return response()->json([
            'status' => 500, // Changed to 500 for server errors
            'errors' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
    
     public function tours_details(Request $request){
    try {
        $request->validate([
            'package_id' => 'required|numeric',
        ]);
        
        $tour = PackageModel::where('id', $request->package_id)->where('status', 1)->first();
        
        if (!$tour) {
            return APIResponse(400, __('api_msg.data_not_found'));
        }
        
        $package_images = PackagesImagesModel::where('package_id', $tour->id)
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                   'image' => $image->images ? asset('uploads/package/' . $image->images) : '',
                ];
            });
            
        $package_itinerary = ItineraryModel::where('package_id', $tour->id)
            ->get()
            ->map(function ($itinerary) {
                $images = [];
                if ($itinerary->image) $images[] = asset($itinerary->image);
                if ($itinerary->image_two) $images[] = asset($itinerary->image_two);
                return [
                    'id' => $itinerary->id,
                    'question' => $itinerary->question,
                    'answer' => $itinerary->answer,
                    'images' => $images
                ];
            });
        
        // Create pricing modes array
        $pricing_modes = [];
        
        if ($tour->s_individual) {
            $pricing_modes[] = [
                'type' => 'Standard',
                'price' => $tour->s_individual
            ];
        }
        
        if ($tour->d_individual) {
            $pricing_modes[] = [
                'type' => 'Deluxe',
                'price' => $tour->d_individual
            ];
        }
        
        if ($tour->p_individual) {
            $pricing_modes[] = [
                'type' => 'Premium',
                'price' => $tour->p_individual
            ];
        }
       $words = explode("##", $tour->amenities);
        $amenitie = [];
        
        if (!empty($tour->amenities)) {
            foreach ($words as $word) {
                $items = array_map('trim', explode(',', $word));
                foreach ($items as $item) {
                    if (!empty($item)) { // Skip empty items
                        $amenitie[] = [
                            "title" => $item,
                            "icon" => ''
                        ];
                    }
                }
            }
        }
        
        // If $amenitie is empty, it will automatically be an empty array
        // You can explicitly check if needed
        if (empty($amenitie)) {
            $amenitie = [];
        }
        $Data = [
            'id' => $tour->id,
            'package_id' => $tour->package_id,
            'tour_id' => $tour->tour_id,
            'package_title' => $tour->package_title,
            'destination_type' => $tour->destination_type,
            'days_count' => $tour->days_count,
            'nights_count' => $tour->nights_count,
            'pack_location_cover' => $tour->pack_location_cover,
            'overview' => $tour->overview,
            'inclusions' => $tour->inclusions,
            'exclusions' => $tour->exclusions,
            'package_homepage_image' => $tour->package_homepage_image ? asset($tour->package_homepage_image) : null,
            'package_banner_image' => $tour->package_banner_image ? asset($tour->package_banner_image) : null,
            'status' => $tour->status,
            'created_at' => $tour->created_at->toDateTimeString(),
            'updated_at' => $tour->updated_at->toDateTimeString(),
            'package_images' => $package_images,
            'package_itinerary' => $package_itinerary,
            'mode' => $pricing_modes, // Added array mode
            // Individual fields (keeping them for backward compatibility if needed)
            's_individual' => $tour->s_individual,
            'd_individual' => $tour->d_individual,
            'p_individual' => $tour->p_individual,
            'amenities' => $amenitie
        ];

        return APIResponse(200, __('api_msg.get_record_successfully'), $Data);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'errors' => $e->getMessage()
        ]);
    }
}
    
    
    public function submit_pay(Request $request){
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'name' => 'required'
                ],
                [
                    'name.required' => __('api_msg.please_enter_required_fields')
                ]
            );
            
            if ($validation->fails()) {
                $errorMessage = $validation->errors()->first();
                return APIResponse(400, $errorMessage ?: __('api_msg.validation_error'));
            }
            $transaction_id = 'TXN' . date('YmdHis') . strtoupper(Str::random(4));
            $notes = "Payment of amount Rs. ".$transaction_id;
            $formattedNotes = [];
            if (!empty($notes) && is_array($notes)) {
                    foreach ($notes as $key => $value) {
                        $formattedNotes[$key] = (string) $value;
                    }
            }
            $razorpayOrder = $this->razorpay->createOrder($request->price,'INR',$transaction_id,$formattedNotes);
            $order = Order::create(['order_id' => $razorpayOrder['id'],'receipt' => $razorpayOrder['receipt'],'amount' => $request->price,'currency' => $razorpayOrder['currency'],'status' => $razorpayOrder['status'],'notes' => json_encode($razorpayOrder['notes'] ?? [])]);
            $customer_details = CustomerModel::where('id',$request->user_id)->orderBy('created_at', 'desc')->first();
            $package_details = PackageModel::where('id',$request->package_id)->orderBy('created_at', 'desc')->first();
            //dd($order);
            $originalData = json_decode($request->adults, true);
            $transformedData = [];
            foreach ($originalData as $item) {
                $transformedData[] = [
                    "first" => $item['first'] ?? "",  // Keep original or default
                    "last" => $item['last'] ?? "",
                    "age" => $item['age'] ?? "",
                    "gender" => $item['gender'] ?? ""
                ];
            }
            
            $originalchildrenData = json_decode($request->children, true);
            $transformedchildrenData = [];
            foreach ($originalchildrenData as $item) {
                $transformedchildrenData[] = [
                    "first" => $item['first'] ?? "",  // Keep original or default
                    "last" => $item['last'] ?? "",
                    "age" => $item['age'] ?? "",
                    "gender" => $item['gender'] ?? ""
                ];
            }
            
            $contact = new SubmitPayModel();
            $contact->razorpay_order_id = $razorpayOrder['id'];
            $contact->user_id = $request->user_id;
            $contact->booking_session_id = Str::uuid()->toString();
            $contact->package_id = $request->package_id;
            $contact->name = $request->name;
            $contact->mail = $customer_details->email ?? '';
            $contact->phone = $customer_details->phone ?? '';
            $contact->address = $customer_details->address ?? '';
            $contact->type = 'tour';
            $contact->tour_name = $package_details->package_title ?? '';
            $contact->adults_count = $request->adults_count;
            $contact->children_count = $request->children_count;
            $contact->children = json_encode($transformedchildrenData);
            $contact->adults = json_encode($transformedData);
            $contact->price = $request->price;
            $contact->nights_count = $request->nights_count;
            $contact->days_count = $request->days_count;
            $contact->package_price_type = $request->package_price_type;
            $contact->to_date = $request->to_date;
            $contact->from_date = $request->from_date;
            $contact->status = 1;
            $contact->created_at = now();
            $contact->save();
            $lastRecord = SubmitPayModel::latest()->first();
            return APIResponse(200, __('api_msg.add_successfully'), [
                'data' => [
                    'order_id' => $razorpayOrder['id'],
                    'amount' => $razorpayOrder['amount'] / 100,
                    'currency' => $razorpayOrder['currency'],
                    'key_id' => config('razorpay.key_id'),
                    'order_data' => $order
                ],
                'message' => 'Record saved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json(array(
                'status' => 400, 
                'errors' => $e->getMessage()
            ));
        }
    }
    
    
    public function  package_payment(Request $request){
         try {
            $validation = Validator::make(
                $request->all(),
                [
                    'name' => 'required'
                ],
                [
                    'name.required' => __('api_msg.please_enter_required_fields')
                ]
            );
            if ($validation->fails()) {
                $errorMessage = $validation->errors()->first();
                return APIResponse(400, $errorMessage ?: __('api_msg.validation_error'));
            }
            $payment = SubmitPayModel::orderBy('created_at', 'desc')->first();
            $travellers = json_encode($data['adults']);
            $update_arr = array("payment_status"=>"success","razorpay_payment_id" => $data['razorpay_payment_id'],"razorpay_order_id" => $data['razorpay_order_id'],"mail" => $data['mail'],"phone" => $data['phone'],"address" => $data['address'],"apartment_number" => $data['apartment_number'],"country_id" => $data['country_id'],"state_id" => $data['state_id'],"city_id" => $data['city_id'],"adults" => $travellers);
            $affectedRows = SubmitPayModel::where("id", $payment->id)->update($update_arr);
            $lastRecord = SubmitPayModel::latest()->first();
            return APIResponse(200, __('api_msg.add_successfully'), [
                'saved_data' => $lastRecord,
                'message' => 'Record saved successfully'
            ]);
        } catch (Exception $e) {
            return response()->json(array(
                'status' => 400, 
                'errors' => $e->getMessage()
            ));
        }
    }
    
    
    public function package_list(Request $request){
        try {
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            
            $get_package = PackageModel::select(
                    'id', 'package_id', 'tour_id', 'package_title', 'destination_type', 
                    'days_count', 'nights_count', 's_individual', 'd_individual', 
                    'p_individual', 'pack_location_cover', 'package_homepage_image', 
                    'package_banner_image'
                )
                ->where('status', 1)
                ->paginate($perPage, ['*'], 'page', $page);
    
            // Format the packages with full image paths
            $formattedPackages = [];
            foreach($get_package as $package) {
                $formattedPackage = [
                    'id' => $package->id,
                    'package_id' => $package->package_id,
                    'tour_id' => $package->tour_id,
                    'package_title' => $package->package_title,
                    'destination_type' => $package->destination_type,
                    'days_count' => $package->days_count,
                    'nights_count' => $package->nights_count,
                    's_individual' => $package->s_individual,
                    'd_individual' => $package->d_individual,
                    'p_individual' => $package->p_individual,
                    'pack_location_cover' => $package->pack_location_cover,
                    'package_homepage_image' => $package->package_homepage_image ? url($package->package_homepage_image) : '',
                    'package_banner_image' => $package->package_banner_image ? url($package->package_banner_image) : ''
                ];
                
                $formattedPackages[] = $formattedPackage;
            }
            
            // Build the response according to the desired format
            $response = [
                'title' => 'All Package', 
                'image' => 'https://phoenixtoursandtravels.in/uploads/package/Del10.jpg',
                'packages' => $formattedPackages,
                'pagination' => [
                    'total' => $get_package->total(),
                    'per_page' => $get_package->perPage(),
                    'current_page' => $get_package->currentPage(),
                    'last_page' => $get_package->lastPage(),
                ]
            ];
            
            return APIResponse(200, __('api_msg.get_record_successfully'), $response);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 400, 
                'errors' => $e->getMessage()
            ]);
        }
    }
    
    public function table_package_list(Request $request){
        try {
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            
            $get_package = PackageModel::select(
                    'id', 'package_id', 'tour_id', 'package_title', 'destination_type', 
                    'days_count', 'nights_count', 's_individual', 'd_individual', 'amenities',
                    'p_individual', 'pack_location_cover', 'package_homepage_image', 
                    'package_banner_image'
                )
                ->where('status', 1)
                ->paginate($perPage, ['*'], 'page', $page);
              
            // Format the packages with full image paths
            $formattedPackages = [];
            foreach($get_package as $package) {
                // Add pricing structure for each package
                $pricing = [
                        [
                            'category' => 'Standard',
                            '2_pax' => '₹'.get_adults_cal('Standard','2',$package->id),
                            '3_pax' => '₹'.get_adults_cal('Standard','3',$package->id),
                            '4_pax' => '₹'.get_adults_cal('Standard','4',$package->id),
                            '5_pax' => '₹'.get_adults_cal('Standard','5',$package->id),
                            '6_pax' => '₹'.get_adults_cal('Standard','6',$package->id),
                        ],
                        [
                            'category' => 'Deluxe',
                            '2_pax' => '₹'.get_adults_cal('Deluxe','2',$package->id),
                            '3_pax' => '₹'.get_adults_cal('Deluxe','3',$package->id),
                            '4_pax' => '₹'.get_adults_cal('Deluxe','4',$package->id),
                            '5_pax' => '₹'.get_adults_cal('Deluxe','5',$package->id),
                            '6_pax' => '₹'.get_adults_cal('Deluxe','6',$package->id),
                        ],
                        [
                            'category' => 'Premium',
                            '2_pax' => '₹'.get_adults_cal('Premium','2',$package->id),
                            '3_pax' => '₹'.get_adults_cal('Premium','3',$package->id),
                            '4_pax' => '₹'.get_adults_cal('Premium','4',$package->id),
                            '5_pax' => '₹'.get_adults_cal('Premium','5',$package->id),
                            '6_pax' => '₹'.get_adults_cal('Premium','6',$package->id),
                        ]
                ];
             
                $words = explode("##", $package->amenities);
                $amenitie = [];
                
                if (!empty($package->amenities)) {
                    foreach ($words as $word) {
                        $items = array_map('trim', explode(',', $word));
                        foreach ($items as $item) {
                            if (!empty($item)) { // Skip empty items
                                $amenitie[] = [
                                    "title" => $item,
                                    "icon" => ''
                                ];
                            }
                        }
                    }
                }
                if (empty($amenitie)) {
                    $amenitie = [];
                }
                $formattedPackage = [
                    'id' => $package->id,
                    'package_id' => $package->package_id,
                    'tour_id' => $package->tour_id,
                    'package_title' => $package->package_title,
                    'destination_type' => $package->destination_type,
                    'days_count' => $package->days_count,
                    'nights_count' => $package->nights_count,
                    's_individual' => $package->s_individual,
                    'd_individual' => $package->d_individual,
                    'p_individual' => $package->p_individual,
                    'pack_location_cover' => $package->pack_location_cover,
                    'package_homepage_image' => $package->package_homepage_image ? url($package->package_homepage_image) : '',
                    'package_banner_image' => $package->package_banner_image ? url($package->package_banner_image) : '',
                    'pricing' => $pricing,
                    'amenities' => $amenitie // Add the pricing structure
                ];
                
                $formattedPackages[] = $formattedPackage;
            }
            
            // Build the response according to the desired format
            $response = [
                'title' => 'All Package', 
                'image' => 'https://phoenixtoursandtravels.in/uploads/package/Del10.jpg',
                'packages' => $formattedPackages,
                'pagination' => [
                    'total' => $get_package->total(),
                    'per_page' => $get_package->perPage(),
                    'current_page' => $get_package->currentPage(),
                    'last_page' => $get_package->lastPage(),
                ]
            ];
            
            return APIResponse(200, __('api_msg.get_record_successfully'), $response);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 400, 
                'errors' => $e->getMessage()
            ]);
        }
    }
   
}