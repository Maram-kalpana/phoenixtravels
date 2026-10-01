<?php
namespace App\Http\Controllers;
use App\Models\GalleryModel;
use App\Models\CorporateTourEnquiry;
use App\Models\BannerModel;
use App\Models\QuickContact;
use App\Models\PackageFlight;
use App\Models\ItineraryImages;
use App\Models\FAQModel;
use App\Models\SubmitPayModel;
use App\Models\InternationalSubmitPayModel;
use App\Models\ContactModel;
use App\Models\StateModel;
use App\Models\CityModel;
use App\Models\CategoryModel;
use App\Models\CustomSection;
use App\Models\CouponModel;
use App\Models\CouponsHistoryModel;
use App\Models\CustomerModel;
use App\Models\WalletModel;
use App\Models\CountryModel;
use App\Models\BlogModel;
use App\Models\ToursModel;
use App\Models\InternationalToursModel;
use App\Models\HoneymoonToursModel;
use App\Models\PackageModel;
use App\Models\InternationalPackageModel;
use App\Models\HoneymoonPackageModel;
use App\Models\ItineraryModel;
use App\Models\InternationalItineraryModel;
use App\Models\HoneymoonItineraryModel;
use App\Models\Booking;
use App\Models\PackagesImagesModel;
use App\Models\InternationalPackagesImagesModel;
use App\Models\HoneymoonPackagesImagesModel;
use App\Models\VehicleImages;
use App\Models\HotelsImages;
use App\Models\TestimonialsModel;
use App\Models\FixedDepartures;
use App\Facades\ResponseHelper;
use Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Validator,DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WebController extends Controller{
    
    public function index(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'name'=>'required',
                'email'=>'required',
                'country_name'=>'required',
                'city'=>'required',
                'from_date'=>'required',
                'to_date'=>'required',
                'adults_count'=>'required',
                'children_count'=>'required',
                'message'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $quick_contact = new QuickContact();
                $quick_contact->name = $data['name'];
                $quick_contact->email = $data['email'];
                $quick_contact->country_name = $data['country_name'];
                $quick_contact->city = $data['city'];
                $quick_contact->from_date = $data['from_date'];
                $quick_contact->to_date = $data['to_date'];
                $quick_contact->adults_count = $data['adults_count'];
                $quick_contact->children_count = $data['children_count'];
                $quick_contact->message = $data['message'];
                $quick_contact->status = 1;
                $quick_contact->created_at = date('Y-m-d H:i:s');
                $quick_contact->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        
        $testimonials = TestimonialsModel::where('status',1)->get();
        $faq = FAQModel::where('status',1)->orderBy('created_at', 'desc')->take(5)->get();
        $blog = BlogModel::where('status',1)->orderBy('created_at', 'desc')->take(3)->get();
        $india_tour = ToursModel::where('status', 1)->where('tour_category_id',1)->orderBy('created_at', 'desc')->get();
        $international_tour = DB::table('tour')->join('packages', 'tour.id', '=', 'packages.tour_id')->select('packages.package_homepage_image', 'tour.slug', 'tour.tour_name', 'packages.s_individual')->where('tour.tour_category_id',2)->get();
        //dd($international_tour);
        $honeymoon_tour = ToursModel::where('status', 1)->where('tour_category_id',3)->orderBy('created_at', 'desc')->get();
        $get_custom_section = CustomSection::where('status',1)->where('tour_id', '!=', '')->where('tour_id', '!=', '[]')->orderBy('order_by', 'asc')->get();
        $get_banner_images = BannerModel::where('status',1)->orderBy('created_at', 'desc')->get();
        return view('frontend.home',compact('testimonials','blog','india_tour','international_tour','honeymoon_tour','faq','get_custom_section','get_banner_images'));
    }
    
    public function about(Request $request){
        $testimonials = TestimonialsModel::where('status',1)->get();
        return view('frontend.about',compact('testimonials'));
    }
    
    
    public function fixed_departures(Request $request){
    $tour = ToursModel::where('status', 1)->where('slug', request()->segment(2))->first();
        if(!$tour) {
            abort(404);
        }
        $fixed_departure = FixedDepartures::where('status', 1)
                            ->where('tour_id', $tour->id)
                            ->first();
        if(!$fixed_departure) {
            abort(404, 'No fixed departure found for this tour');
        }
        // Tour type name
        if($tour->tour_category_id == 2){
            $type_name = 'International Packages';
        } elseif($tour->tour_category_id == 4){
            $type_name = 'Cruise Packages';
        } elseif($tour->tour_category_id == 3){
            $type_name = 'Honeymoon Packages';
        } else {
            $type_name = 'India Packages';
        }
        
        // Get available dates with seat information
        $availableDates = $this->getAvailableDates($fixed_departure->id);
        
        return view('frontend.fixed_departures', compact(
            'tour',
            'type_name',
            'fixed_departure',
            'availableDates'
        ));
    }
    
    private function getAvailableDates($departureId){
    // This should come from your database - example structure
    // You'll need to adapt this based on your actual database structure
    
    $bookings = Booking::where('fixed_departure_id', $departureId)
                ->where('status', 'confirmed')
                ->get();
    
    $groupSize = FixedDepartures::find($departureId)->group_size ?? 20;
    
    $dates = [];
    $startDate = now();
    $endDate = now()->addMonths(3);
    
    // Generate dates for the next 3 months
    for ($date = $startDate; $date <= $endDate; $date->addDay()) {
        $dateStr = $date->format('Y-m-d');
        $bookedSeats = [];
        
        // Get booked seats for this date
        foreach ($bookings as $booking) {
            if ($booking->travel_date->format('Y-m-d') === $dateStr) {
                $bookedSeats = array_merge($bookedSeats, explode(',', $booking->seat_numbers));
            }
        }
        
        $seatsData = [];
        for ($i = 1; $i <= $groupSize; $i++) {
            $seatsData[] = [
                'seatNumber' => $i,
                'status' => in_array($i, $bookedSeats) ? 'booked' : 'available'
            ];
        }
        
        $dates[$dateStr] = [
            'price' => FixedDepartures::find($departureId)->price ?? 0,
            'seatsAvailable' => $groupSize - count($bookedSeats),
            'totalSeats' => $groupSize,
            'seats' => $seatsData
        ];
    }
    
    return $dates;
}

    public function tour(Request $request){
        if($request->ajax()) {
            return $this->filterTours($request);
        }
        $tour = ToursModel::where('status', 1)->where('slug', request()->segment(2))->first();
        if(!$tour) {
            abort(404);
        }
        $packages = PackageModel::where('status', 1)->where('tour_id', $tour->id)->orderBy('created_at', 'desc')->paginate(9);
        $type_name = 'India Packages';
        $tour_state = ToursModel::where('status', 1)->where('tour_category_id',1)->orderBy('tour_name', 'asc')->get();
        return view('frontend.tour', compact('tour', 'packages', 'type_name', 'tour_state'));
    }

    public function filterTours(Request $request){
    // Get the tour from slug only for initial context, but don't restrict filters
    $currentTour = ToursModel::where('status', 1)->where('slug', request()->segment(2))->first();
    
    if(!$currentTour) {
        return response()->json(['success' => false, 'message' => 'Tour not found']);
    }
    
    // Start query - initially get all active packages
    $query = PackageModel::where('status', 1);
    
    // Filter by Popular (tour types) - Multiple checkboxes
    // This is the main filter - if user selects tour types, show packages from those tours
    if($request->has('tour_types') && !empty($request->tour_types) && is_array($request->tour_types)) {
        $query->whereIn('tour_id', $request->tour_types);
    } else {
        // If no tour types selected, show packages from current tour only
        $query->where('tour_id', $currentTour->id);
    }
    
    // Filter by price range
    if($request->has('min_price') && $request->has('max_price') && 
       $request->min_price !== null && $request->max_price !== null &&
       $request->min_price != '' && $request->max_price != '') {
        
        $minPrice = (float)$request->min_price;
        $maxPrice = (float)$request->max_price;
        
        if($minPrice < $maxPrice) {
            $query->whereBetween('new_price', [$minPrice, $maxPrice]);
        }
    }
    
    // Filter by search tour type name
    if($request->has('search_tour_type') && !empty($request->search_tour_type)) {
        $searchTerm = trim($request->search_tour_type);
        if($searchTerm != '') {
            // Get tour IDs that match the search term
            $tourIds = ToursModel::where('tour_name', 'like', '%' . $searchTerm . '%')
                                ->pluck('id')
                                ->toArray();
            
            if(!empty($tourIds)) {
                $query->whereIn('tour_id', $tourIds);
            } else {
                // No matching tours, return empty result
                $query->whereRaw('1 = 0');
            }
        }
    }
    
    $packages = $query->orderBy('created_at', 'desc')->paginate(9);
    
    if($request->ajax()) {
        $view = view('partials.tour-item', compact('packages'))->render();
        $pagination = view('partials.pagination', ['packages' => $packages])->render();
        
        return response()->json([
            'success' => true,
            'data' => $view,
            'pagination' => $pagination,
            'total' => $packages->total()
        ]);
    }
    
    return redirect()->route('tour');
}
    
    
    public function international_tour(Request $request){
        if($request->ajax()) {
            return $this->InternationalfilterTours($request);
        }
        $tour = InternationalToursModel::where('status', 1)->where('slug', request()->segment(2))->first();
        
        if(!$tour) {
            abort(404);
        }
        $packages = InternationalPackageModel::where('status', 1)->where('tour_id', $tour->id)->orderBy('created_at', 'desc')->paginate(9);
        $type_name = 'International Packages';
        $tour_state = InternationalToursModel::where('status', 1)->where('tour_category_id',2)->orderBy('tour_name', 'asc')->get();
        return view('frontend.international_tour', compact('tour', 'packages', 'type_name', 'tour_state'));
    }
    
    public function InternationalfilterTours(Request $request){
    // Get the tour from slug only for initial context, but don't restrict filters
    $currentTour = InternationalToursModel::where('status', 1)->where('slug', request()->segment(2))->first();
    
    if(!$currentTour) {
        return response()->json(['success' => false, 'message' => 'Tour not found']);
    }
    
    // Start query - initially get all active packages
    $query = InternationalPackageModel::where('status', 1);
    
    // Filter by Popular (tour types) - Multiple checkboxes
    // This is the main filter - if user selects tour types, show packages from those tours
    if($request->has('tour_types') && !empty($request->tour_types) && is_array($request->tour_types)) {
        $query->whereIn('tour_id', $request->tour_types);
    } else {
        // If no tour types selected, show packages from current tour only
        $query->where('tour_id', $currentTour->id);
    }
    
    // Filter by price range
    if($request->has('min_price') && $request->has('max_price') && 
       $request->min_price !== null && $request->max_price !== null &&
       $request->min_price != '' && $request->max_price != '') {
        
        $minPrice = (float)$request->min_price;
        $maxPrice = (float)$request->max_price;
        
        if($minPrice < $maxPrice) {
            $query->whereBetween('new_price', [$minPrice, $maxPrice]);
        }
    }
    
    // Filter by search tour type name
    if($request->has('search_tour_type') && !empty($request->search_tour_type)) {
        $searchTerm = trim($request->search_tour_type);
        if($searchTerm != '') {
            // Get tour IDs that match the search term
            $tourIds = InternationalToursModel::where('tour_name', 'like', '%' . $searchTerm . '%')
                                ->pluck('id')
                                ->toArray();
            
            if(!empty($tourIds)) {
                $query->whereIn('tour_id', $tourIds);
            } else {
                // No matching tours, return empty result
                $query->whereRaw('1 = 0');
            }
        }
    }
    
    $packages = $query->orderBy('created_at', 'desc')->paginate(9);
    
    if($request->ajax()) {
        $view = view('partials.international-tour-item', compact('packages'))->render();
        $pagination = view('partials.pagination', ['packages' => $packages])->render();
        
        return response()->json([
            'success' => true,
            'data' => $view,
            'pagination' => $pagination,
            'total' => $packages->total()
        ]);
    }
    
    return redirect()->route('tour');
}
    
    
    public function honeymoon_tour(Request $request){
        if($request->ajax()) {
            return $this->HoneymoonfilterTours($request);
        }
        
        $tour = HoneymoonToursModel::where('status', 1)->where('slug', request()->segment(2))->first();
        
        if(!$tour) {
            abort(404);
        }
        
        // Get initial packages for the current tour
        $packages = HoneymoonPackageModel::where('status', 1)
                    ->where('tour_id', $tour->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(9);
        
        if($tour->tour_category_id == 2){
            $type_name = 'Honeymoon International Packages';
            $tour_state = ToursModel::where('status', 1)->where('tour_category_id',2)->orderBy('tour_name', 'asc')->get();
        }elseif($tour->tour_category_id == 4){
            $type_name = 'Cruise Packages';
            $tour_state = ToursModel::where('status', 1)->where('tour_category_id',4)->orderBy('tour_name', 'asc')->get();
        }elseif($tour->tour_category_id == 3){
            $type_name = 'Honeymoon Packages';
            $tour_state = ToursModel::where('status', 1)->where('tour_category_id',3)->orderBy('tour_name', 'asc')->get();
        }else{
            $type_name = 'Honeymoon India Packages';
            $tour_state = ToursModel::where('status', 1)->where('tour_category_id',1)->orderBy('tour_name', 'asc')->get();
        }
        
        return view('frontend.honeymoon_tour', compact('tour', 'packages', 'type_name', 'tour_state'));
    }
    
    public function HoneymoonfilterTours(Request $request){
    // Get the tour from slug only for initial context, but don't restrict filters
    $currentTour = HoneymoonToursModel::where('status', 1)->where('slug', request()->segment(2))->first();
    
    if(!$currentTour) {
        return response()->json(['success' => false, 'message' => 'Tour not found']);
    }
    
    // Start query - initially get all active packages
    $query = HoneymoonPackageModel::where('status', 1);
    
    // Filter by Popular (tour types) - Multiple checkboxes
    // This is the main filter - if user selects tour types, show packages from those tours
    if($request->has('tour_types') && !empty($request->tour_types) && is_array($request->tour_types)) {
        $query->whereIn('tour_id', $request->tour_types);
    } else {
        // If no tour types selected, show packages from current tour only
        $query->where('tour_id', $currentTour->id);
    }
    
    // Filter by price range
    if($request->has('min_price') && $request->has('max_price') && 
       $request->min_price !== null && $request->max_price !== null &&
       $request->min_price != '' && $request->max_price != '') {
        
        $minPrice = (float)$request->min_price;
        $maxPrice = (float)$request->max_price;
        
        if($minPrice < $maxPrice) {
            $query->whereBetween('new_price', [$minPrice, $maxPrice]);
        }
    }
    
    // Filter by search tour type name
    if($request->has('search_tour_type') && !empty($request->search_tour_type)) {
        $searchTerm = trim($request->search_tour_type);
        if($searchTerm != '') {
            // Get tour IDs that match the search term
            $tourIds = HoneymoonToursModel::where('tour_name', 'like', '%' . $searchTerm . '%')
                                ->pluck('id')
                                ->toArray();
            
            if(!empty($tourIds)) {
                $query->whereIn('tour_id', $tourIds);
            } else {
                // No matching tours, return empty result
                $query->whereRaw('1 = 0');
            }
        }
    }
    
    $packages = $query->orderBy('created_at', 'desc')->paginate(9);
    
    if($request->ajax()) {
        $view = view('partials.honeymoon-tour-item', compact('packages'))->render();
        $pagination = view('partials.pagination', ['packages' => $packages])->render();
        
        return response()->json([
            'success' => true,
            'data' => $view,
            'pagination' => $pagination,
            'total' => $packages->total()
        ]);
    }
    
    return redirect()->route('tour');
}

    public function CorporateTours(Request $request){
    if ($request->isMethod('post')) {
        $response_code = 200;
        $message = ['error' => ['Something went wrong']];
        $success = false;
        
        $data = $request->all();
        
        // Validation Rules
        $rules = [
            // Contact Info
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country_id' => 'required|exists:country,id',
            'state_id' => 'required|exists:state,id',
            'city_id' => 'required|exists:city,id',
            'address' => 'required|string|max:500',
            
            // Tour Info
            'starting_place' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'number_of_people' => 'required|integer|min:1',
            'preferred_date' => 'required|date',
            
            // Company Info
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'company_phone_1' => 'required|string|max:20',
            
            // Authorized Person
            'authorized_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'authorized_contact_1' => 'required|string|max:20',
        ];
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            $success = false;
            $message = $validator->errors();
        } else {
            DB::beginTransaction();
            try {
                $enquiry = new CorporateTourEnquiry();
                
                // Contact Information
                $enquiry->full_name = $data['full_name'];
                $enquiry->email = $data['email'];
                $enquiry->phone = $data['phone'];
                $enquiry->country_id = $data['country_id'];
                $enquiry->state_id = $data['state_id'];
                $enquiry->city_id = $data['city_id'];
                $enquiry->address = $data['address'];
                $enquiry->apartment_number = $data['apartment_number'] ?? null;
                
                // Tour Information
                $enquiry->starting_place = $data['starting_place'];
                $enquiry->destination = $data['destination'];
                $enquiry->number_of_people = $data['number_of_people'];
                $enquiry->number_of_buses = $data['number_of_buses'] ?? null;
                $enquiry->bus_type = $data['bus_type'] ?? null;
                $enquiry->train_class = $data['train_class'] ?? null;
                $enquiry->flight_class = $data['flight_class'] ?? null;
                $enquiry->preferred_date = $data['preferred_date'];
                $enquiry->pickup_point = $data['pickup_point'] ?? null;
                $enquiry->dropping_point = $data['dropping_point'] ?? null;
                $enquiry->sightseeing = $data['sightseeing'] ?? null;
                
                // Participants
                $enquiry->adults = $data['adults'] ?? 0;
                $enquiry->children = $data['children'] ?? 0;
                $enquiry->infants = $data['infants'] ?? 0;
                
                // Hotel Information
                $enquiry->hotel_category = $data['hotel_category'] ?? null;
                $enquiry->hotel_location = $data['hotel_location'] ?? null;
                $enquiry->number_of_rooms = $data['number_of_rooms'] ?? null;
                $enquiry->sharing_type = $data['sharing_type'] ?? null;
                
                // Conference Hall
                $enquiry->conference_hall = $data['conference_hall'] ?? 'no';
                $enquiry->seating_capacity = $data['seating_capacity'] ?? null;
                $enquiry->stage_type = $data['stage_type'] ?? null;
                $enquiry->seating_arrangement = $data['seating_arrangement'] ?? null;
                $enquiry->equipment = $request->has('equipment') ? json_encode($data['equipment']) : null;
                
                // Food Menu - Store as JSON
                $enquiry->breakfast_menu = $request->has('breakfast_menu') ? json_encode($data['breakfast_menu']) : null;
                $enquiry->lunch_menu = $request->has('lunch_menu') ? json_encode($data['lunch_menu']) : null;
                $enquiry->dinner_menu = $request->has('dinner_menu') ? json_encode($data['dinner_menu']) : null;
                $enquiry->morning_snacks_menu = $request->has('morning_snacks_menu') ? json_encode($data['morning_snacks_menu']) : null;
                $enquiry->evening_snacks_menu = $request->has('evening_snacks_menu') ? json_encode($data['evening_snacks_menu']) : null;
                $enquiry->starters_menu = $request->has('starters_menu') ? json_encode($data['starters_menu']) : null;
                
                // Night Party
                $enquiry->night_party = $data['night_party'] ?? 'no';
                $enquiry->drinks_type = $data['drinks_type'] ?? null;
                $enquiry->drinks_bottles = $request->has('drinks_bottles') ? json_encode($data['drinks_bottles']) : null;
                
                // Payment Information
                $enquiry->payment_method = $data['payment_method'] ?? null;
                $enquiry->bank_name = $data['bank_name'] ?? null;
                $enquiry->bank_branch = $data['bank_branch'] ?? null;
                $enquiry->account_number = $data['account_number'] ?? null;
                $enquiry->ifsc_code = $data['ifsc_code'] ?? null;
                
                // Terms & Policies
                $enquiry->payment_terms = $data['payment_terms'] ?? null;
                $enquiry->cancellation_policy = $data['cancellation_policy'] ?? null;
                $enquiry->refund_policy = $data['refund_policy'] ?? null;
                
                // Company Information
                $enquiry->company_name = $data['company_name'];
                $enquiry->company_address = $data['company_address'];
                $enquiry->company_phone_1 = $data['company_phone_1'];
                $enquiry->company_phone_2 = $data['company_phone_2'] ?? null;
                
                // Authorized Person
                $enquiry->authorized_name = $data['authorized_name'];
                $enquiry->designation = $data['designation'];
                $enquiry->authorized_contact_1 = $data['authorized_contact_1'];
                $enquiry->authorized_contact_2 = $data['authorized_contact_2'] ?? null;
                
                // Payment Authority
                $enquiry->payment_authority_name = $data['payment_authority_name'] ?? null;
                $enquiry->payment_authority_designation = $data['payment_authority_designation'] ?? null;
                $enquiry->payment_authority_contact_1 = $data['payment_authority_contact_1'] ?? null;
                $enquiry->payment_authority_contact_2 = $data['payment_authority_contact_2'] ?? null;
                
                // Additional Info
                $enquiry->additional_info = $data['additional_info'] ?? null;
                
                // Status (default 1 = Pending)
                $enquiry->status = 1;
                
                $enquiry->save();
                
                DB::commit();
                
                $success = true;
                $message = ['success' => ['Your corporate tour enquiry has been submitted successfully!']];
                
                // Optional: Send email notification
                // Mail::to($data['email'])->send(new CorporateTourEnquiryMail($enquiry));
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Corporate Tour Enquiry Error: ' . $e->getMessage());
                $success = false;
                $message = ['error' => ['Failed to submit enquiry. Please try again.']];
            }
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return ResponseHelper::returnResponse(200, $success, $message);
        }
        
        if ($success) {
            return redirect()->route('corporate.tours')->with('success', 'Enquiry submitted successfully!');
        } else {
            return redirect()->back()->withErrors($message)->withInput();
        }
    }
    
    // GET method - Show form
    $country = CountryModel::where('status', 1)->get();
    $breakfast_menu = CategoryModel::where('status', 1)->where('module_id', 9999)->get();
    $lunch_menu = CategoryModel::where('status', 1)->where('module_id', 9988)->get();
    $dinner_menu = CategoryModel::where('status', 1)->where('module_id', 9977)->get();
    $morning_snacks_menu = CategoryModel::where('status', 1)->where('module_id', 9966)->get();
    $evening_snacks_menu = CategoryModel::where('status', 1)->where('module_id', 9955)->get();
    $drink_menu = CategoryModel::where('status', 1)->where('module_id', 9944)->get();
    $staters_menu = CategoryModel::where('status', 1)->where('module_id', 9933)->get();
    
    return view('frontend.corporate-tours', compact(
        'country', 'breakfast_menu', 'lunch_menu', 'dinner_menu',
        'morning_snacks_menu', 'evening_snacks_menu', 'drink_menu', 'staters_menu'
    ));
}
    
    
    public function SchoolTours(Request $request){
        if ($request->isMethod('post')) {
        $response_code = 200;
        $message = ['error' => ['Something went wrong']];
        $success = false;
        
        $data = $request->all();
        
        // Validation Rules
        $rules = [
            // Contact Info
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country_id' => 'required|exists:country,id',
            'state_id' => 'required|exists:state,id',
            'city_id' => 'required|exists:city,id',
            'address' => 'required|string|max:500',
            
            // Tour Info
            'starting_place' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'number_of_people' => 'required|integer|min:1',
            'preferred_date' => 'required|date',
            
            // Company Info
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'company_phone_1' => 'required|string|max:20',
            
            // Authorized Person
            'authorized_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'authorized_contact_1' => 'required|string|max:20',
        ];
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()) {
            $success = false;
            $message = $validator->errors();
        } else {
            DB::beginTransaction();
            try {
                $enquiry = new CorporateTourEnquiry();
                
                // Contact Information
                $enquiry->full_name = $data['full_name'];
                $enquiry->email = $data['email'];
                $enquiry->phone = $data['phone'];
                $enquiry->country_id = $data['country_id'];
                $enquiry->state_id = $data['state_id'];
                $enquiry->city_id = $data['city_id'];
                $enquiry->address = $data['address'];
                $enquiry->apartment_number = $data['apartment_number'] ?? null;
                
                // Tour Information
                $enquiry->starting_place = $data['starting_place'];
                $enquiry->destination = $data['destination'];
                $enquiry->number_of_people = $data['number_of_people'];
                $enquiry->number_of_buses = $data['number_of_buses'] ?? null;
                $enquiry->bus_type = $data['bus_type'] ?? null;
                $enquiry->train_class = $data['train_class'] ?? null;
                $enquiry->flight_class = $data['flight_class'] ?? null;
                $enquiry->preferred_date = $data['preferred_date'];
                $enquiry->pickup_point = $data['pickup_point'] ?? null;
                $enquiry->dropping_point = $data['dropping_point'] ?? null;
                $enquiry->sightseeing = $data['sightseeing'] ?? null;
                
                // Participants
                $enquiry->adults = $data['adults'] ?? 0;
                $enquiry->children = $data['children'] ?? 0;
                $enquiry->infants = $data['infants'] ?? 0;
                
                // Hotel Information
                $enquiry->hotel_category = $data['hotel_category'] ?? null;
                $enquiry->hotel_location = $data['hotel_location'] ?? null;
                $enquiry->number_of_rooms = $data['number_of_rooms'] ?? null;
                $enquiry->sharing_type = $data['sharing_type'] ?? null;
                
                // Conference Hall
                $enquiry->conference_hall = $data['conference_hall'] ?? 'no';
                $enquiry->seating_capacity = $data['seating_capacity'] ?? null;
                $enquiry->stage_type = $data['stage_type'] ?? null;
                $enquiry->seating_arrangement = $data['seating_arrangement'] ?? null;
                $enquiry->equipment = $request->has('equipment') ? json_encode($data['equipment']) : null;
                
                // Food Menu - Store as JSON
                $enquiry->breakfast_menu = $request->has('breakfast_menu') ? json_encode($data['breakfast_menu']) : null;
                $enquiry->lunch_menu = $request->has('lunch_menu') ? json_encode($data['lunch_menu']) : null;
                $enquiry->dinner_menu = $request->has('dinner_menu') ? json_encode($data['dinner_menu']) : null;
                $enquiry->morning_snacks_menu = $request->has('morning_snacks_menu') ? json_encode($data['morning_snacks_menu']) : null;
                $enquiry->evening_snacks_menu = $request->has('evening_snacks_menu') ? json_encode($data['evening_snacks_menu']) : null;
                $enquiry->starters_menu = $request->has('starters_menu') ? json_encode($data['starters_menu']) : null;
                
                // Night Party
                $enquiry->night_party = $data['night_party'] ?? 'no';
                $enquiry->drinks_type = $data['drinks_type'] ?? null;
                $enquiry->drinks_bottles = $request->has('drinks_bottles') ? json_encode($data['drinks_bottles']) : null;
                
                // Payment Information
                $enquiry->payment_method = $data['payment_method'] ?? null;
                $enquiry->bank_name = $data['bank_name'] ?? null;
                $enquiry->bank_branch = $data['bank_branch'] ?? null;
                $enquiry->account_number = $data['account_number'] ?? null;
                $enquiry->ifsc_code = $data['ifsc_code'] ?? null;
                
                // Terms & Policies
                $enquiry->payment_terms = $data['payment_terms'] ?? null;
                $enquiry->cancellation_policy = $data['cancellation_policy'] ?? null;
                $enquiry->refund_policy = $data['refund_policy'] ?? null;
                
                // Company Information
                $enquiry->company_name = $data['company_name'];
                $enquiry->company_address = $data['company_address'];
                $enquiry->company_phone_1 = $data['company_phone_1'];
                $enquiry->company_phone_2 = $data['company_phone_2'] ?? null;
                
                // Authorized Person
                $enquiry->authorized_name = $data['authorized_name'];
                $enquiry->designation = $data['designation'];
                $enquiry->authorized_contact_1 = $data['authorized_contact_1'];
                $enquiry->authorized_contact_2 = $data['authorized_contact_2'] ?? null;
                
                // Payment Authority
                $enquiry->payment_authority_name = $data['payment_authority_name'] ?? null;
                $enquiry->payment_authority_designation = $data['payment_authority_designation'] ?? null;
                $enquiry->payment_authority_contact_1 = $data['payment_authority_contact_1'] ?? null;
                $enquiry->payment_authority_contact_2 = $data['payment_authority_contact_2'] ?? null;
                
                // Additional Info
                $enquiry->additional_info = $data['additional_info'] ?? null;
                
                // Status (default 1 = Pending)
                $enquiry->status = 1;
                
                $enquiry->save();
                
                DB::commit();
                
                $success = true;
                $message = ['success' => ['Your corporate tour enquiry has been submitted successfully!']];
                
                // Optional: Send email notification
                // Mail::to($data['email'])->send(new CorporateTourEnquiryMail($enquiry));
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Corporate Tour Enquiry Error: ' . $e->getMessage());
                $success = false;
                $message = ['error' => ['Failed to submit enquiry. Please try again.']];
            }
        }
        
        if ($request->ajax() || $request->wantsJson()) {
            return ResponseHelper::returnResponse(200, $success, $message);
        }
        
        if ($success) {
            return redirect()->route('corporate.tours')->with('success', 'Enquiry submitted successfully!');
        } else {
            return redirect()->back()->withErrors($message)->withInput();
        }
    }
        $country = CountryModel::where('status',1)->get();
        $breakfast_menu = CategoryModel::where('status',1)->where('module_id',9999)->get();
        $lunch_menu = CategoryModel::where('status',1)->where('module_id',9988)->get();
        $dinner_menu = CategoryModel::where('status',1)->where('module_id',9977)->get();
        $morning_snacks_menu = CategoryModel::where('status',1)->where('module_id',9966)->get();
        $evening_snacks_menu = CategoryModel::where('status',1)->where('module_id',9955)->get();
        $drink_menu = CategoryModel::where('status',1)->where('module_id',9944)->get();
        $staters_menu = CategoryModel::where('status',1)->where('module_id',9933)->get();
        return view('frontend.school-tours',compact('country','breakfast_menu','lunch_menu','dinner_menu','morning_snacks_menu','evening_snacks_menu','drink_menu','staters_menu'));
    }
    
    public function StoreSchoolTours(SchoolExcursionRequest $request){
        try {
            // Prepare equipment data
            $equipment = $request->has('equipment') ? json_encode($request->equipment) : null;
            
            // Prepare menu data (convert checkbox arrays to JSON)
            $breakfast_menu = $request->has('breakfast_menu') ? json_encode($request->breakfast_menu) : null;
            $lunch_menu = $request->has('lunch_menu') ? json_encode($request->lunch_menu) : null;
            $dinner_menu = $request->has('dinner_menu') ? json_encode($request->dinner_menu) : null;
            $morning_snacks_menu = $request->has('morning_snacks_menu') ? json_encode($request->morning_snacks_menu) : null;
            $evening_snacks_menu = $request->has('evening_snacks_menu') ? json_encode($request->evening_snacks_menu) : null;
            
            // Create record
            $excursion = SchoolExcursion::create([
                // Contact Information
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
                'apartment_number' => $request->apartment_number,
                
                // Tour Information
                'starting_place' => $request->starting_place,
                'destination' => $request->destination,
                'number_of_people' => $request->number_of_people,
                'number_of_buses' => $request->number_of_buses ?? 0,
                'bus_type' => $request->bus_type,
                'train_class' => $request->train_class,
                'flight_class' => $request->flight_class,
                'preferred_date' => $request->preferred_date,
                'pickup_point' => $request->pickup_point,
                'dropping_point' => $request->dropping_point,
                'sightseeing' => $request->sightseeing,
                
                // Participants
                'adults' => $request->adults ?? 0,
                'children' => $request->children ?? 0,
                'infants' => $request->infants ?? 0,
                
                // Hotel Information
                'hotel_category' => $request->hotel_category,
                'hotel_location' => $request->hotel_location,
                'number_of_rooms' => $request->number_of_rooms,
                'sharing_type' => $request->sharing_type,
                
                // Conference Hall
                'conference_hall' => $request->conference_hall ?? 'no',
                'seating_capacity' => $request->seating_capacity,
                'stage_type' => $request->stage_type,
                'seating_arrangement' => $request->seating_arrangement,
                'equipment' => $equipment,
                
                // Food Menus
                'breakfast_menu' => $breakfast_menu,
                'lunch_menu' => $lunch_menu,
                'dinner_menu' => $dinner_menu,
                'morning_snacks_menu' => $morning_snacks_menu,
                'evening_snacks_menu' => $evening_snacks_menu,
                
                // Payment Information
                'payment_method' => $request->payment_method,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
                
                // Terms
                'payment_terms' => $request->payment_terms,
                'cancellation_policy' => $request->cancellation_policy,
                'refund_policy' => $request->refund_policy,
                
                // Company Information
                'company_name' => $request->company_name,
                'company_address' => $request->company_address,
                'company_phone_1' => $request->company_phone_1,
                'company_phone_2' => $request->company_phone_2,
                
                // Authorized Person
                'authorized_name' => $request->authorized_name,
                'designation' => $request->designation,
                'authorized_contact_1' => $request->authorized_contact_1,
                'authorized_contact_2' => $request->authorized_contact_2,
                
                // Payment Authority
                'payment_authority_name' => $request->payment_authority_name,
                'payment_authority_designation' => $request->payment_authority_designation,
                'payment_authority_contact_1' => $request->payment_authority_contact_1,
                'payment_authority_contact_2' => $request->payment_authority_contact_2,
                
                // Additional Info
                'additional_info' => $request->additional_info,
                
                'status' => 'pending'
            ]);
            
            // Send email notification to admin
            $this->sendEmailNotification($excursion);
            
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your school excursion enquiry has been submitted successfully. We will contact you shortly.',
                'redirect_url' => route('school-excursion.thankyou'),
                'data' => $excursion
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('School Excursion Submission Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your enquiry. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    private function sendEmailNotification($excursion){
        try {
            // Send email to admin
            Mail::send('emails.school-excursion-admin', ['excursion' => $excursion], function ($message) use ($excursion) {
                $message->to(config('mail.admin_email', 'admin@phoenixtours.com'))
                        ->subject('New School Excursion Enquiry - ' . $excursion->company_name);
            });
            
            // Send confirmation email to customer
            Mail::send('emails.school-excursion-customer', ['excursion' => $excursion], function ($message) use ($excursion) {
                $message->to($excursion->email)
                        ->subject('Thank you for your school excursion enquiry - Phoenix Tours');
            });
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
        }
    }
    
    public function SchoolThankyou(){
        return view('school-excursion-thankyou');
    }

    
    public function CollegeTours(Request $request){
        $country = CountryModel::where('status',1)->get();
        $breakfast_menu = CategoryModel::where('status',1)->where('module_id',9999)->get();
        $lunch_menu = CategoryModel::where('status',1)->where('module_id',9988)->get();
        $dinner_menu = CategoryModel::where('status',1)->where('module_id',9977)->get();
        $morning_snacks_menu = CategoryModel::where('status',1)->where('module_id',9966)->get();
        $evening_snacks_menu = CategoryModel::where('status',1)->where('module_id',9955)->get();
        $drink_menu = CategoryModel::where('status',1)->where('module_id',9944)->get();
        $staters_menu = CategoryModel::where('status',1)->where('module_id',9933)->get();
        return view('frontend.college-tours',compact('country','breakfast_menu','lunch_menu','dinner_menu','morning_snacks_menu','evening_snacks_menu','drink_menu','staters_menu'));
    }
    
    
    public function tours_list(Request $request){
        $package = PackageModel::where('status', 1)->domestic()->orderBy('created_at', 'desc')->get();
        return view('frontend.tours-list',compact('package'));
    }
    
    public function getPackagesAjax(Request $request){
        try {
            $perPage = $request->get('per_page', 5); // Changed to 5 for better display
            $page = $request->get('page', 1);
            $search = $request->get('search', '');
            
            // Build query
            $query = PackageModel::where('status', 1);
            
            // Apply search
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('package_title', 'like', '%' . $search . '%')
                      ->orWhere('package_id', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
                });
            }
            
            // Get paginated results
            $packages = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
                
            // Format packages for response
            $formattedPackages = $packages->map(function($package) {
                // Get package images
                $packageImages = PackagesImagesModel::where('package_id', $package->id)->get();
                $images = $packageImages->map(function($img) {
                    return asset('uploads/package/' . $img->images);
                })->toArray();
                
                // Get pricing data using helper function
                //  $budgetPrices = [
                //     2 => get_dynamic_tour_cal('Budget', 2, $package->id,$package->days_count,$package->nights_count),
                //     3 => get_dynamic_tour_cal('Budget', 3, $package->id,$package->days_count,$package->nights_count),
                //     4 => get_dynamic_tour_cal('Budget', 4, $package->id,$package->days_count,$package->nights_count),
                //     5 => get_dynamic_tour_cal('Budget', 5, $package->id,$package->days_count,$package->nights_count),
                //     6 => get_dynamic_tour_cal('Budget', 6, $package->id,$package->days_count,$package->nights_count),
                // ];
                $standardPrices = [
                    2 => get_dynamic_tour_cal('Standard', 2, $package->tour_id,$package->days_count,$package->nights_count),
                    3 => get_dynamic_tour_cal('Standard', 3, $package->tour_id,$package->days_count,$package->nights_count),
                    4 => get_dynamic_tour_cal('Standard', 4, $package->tour_id,$package->days_count,$package->nights_count),
                    5 => get_dynamic_tour_cal('Standard', 5, $package->tour_id,$package->days_count,$package->nights_count),
                    6 => get_dynamic_tour_cal('Standard', 6, $package->tour_id,$package->days_count,$package->nights_count),
                ];
                
                $deluxePrices = [
                    2 => get_dynamic_tour_cal('Premium', 2, $package->tour_id,$package->days_count,$package->nights_count),
                    3 => get_dynamic_tour_cal('Premium', 3, $package->tour_id,$package->days_count,$package->nights_count),
                    4 => get_dynamic_tour_cal('Premium', 4, $package->tour_id,$package->days_count,$package->nights_count),
                    5 => get_dynamic_tour_cal('Premium', 5, $package->tour_id,$package->days_count,$package->nights_count),
                    6 => get_dynamic_tour_cal('Premium', 6, $package->tour_id,$package->days_count,$package->nights_count),
                ];
                
                $premiumPrices = [
                    2 => get_dynamic_tour_cal('Luxury', 2, $package->tour_id,$package->days_count,$package->nights_count),
                    3 => get_dynamic_tour_cal('Luxury', 3, $package->tour_id,$package->days_count,$package->nights_count),
                    4 => get_dynamic_tour_cal('Luxury', 4, $package->tour_id,$package->days_count,$package->nights_count),
                    5 => get_dynamic_tour_cal('Luxury', 5, $package->tour_id,$package->days_count,$package->nights_count),
                    6 => get_dynamic_tour_cal('Luxury', 6, $package->tour_id,$package->days_count,$package->nights_count),
                ];
                
                return [
                    'id' => $package->id,
                    'package_id' => $package->package_id,
                    'package_title' => $package->package_title,
                    'slug' => $package->slug,
                    'package_homepage_image' => $package->package_homepage_image ? asset($package->package_homepage_image) : null,
                    'nights_count' => $package->nights_count,
                    'days_count' => $package->days_count,
                    'amenities' => $package->amenities,
                    'pack_location_cover' => $package->pack_location_cover,
                    's_individual' => $package->s_individual,
                    'd_individual' => $package->d_individual,
                    'p_individual' => $package->p_individual,
                    'images' => $images,
                    'pricing' => [
                        
                        'standard' => $standardPrices,
                        'deluxe' => $deluxePrices,
                        'premium' => $premiumPrices,
                    ],
                    'detail_url' => route('domestic.tour.detail', $package->slug),
                    'created_at' => $package->created_at->format('M d, Y'),
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedPackages,
                'current_page' => $packages->currentPage(),
                'last_page' => $packages->lastPage(),
                'per_page' => $packages->perPage(),
                'total' => $packages->total(),
                'has_more_pages' => $packages->hasMorePages(),
                'from' => $packages->firstItem(),
                'to' => $packages->lastItem(),
                'next_page_url' => $packages->nextPageUrl(),
                'prev_page_url' => $packages->previousPageUrl(),
                'message' => 'Packages loaded successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Package AJAX error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load packages',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
     public function international_tours_list(Request $request){
        $package = PackageModel::where('status', 1)->international()->orderBy('created_at', 'desc')->get();
        return view('frontend.international-tours-list',compact('package'));
    }
    
    
     public function getInternationalPackagesAjax(Request $request){
        try {
            $perPage = $request->get('per_page', 5); // Changed to 5 for better display
            $page = $request->get('page', 1);
            $search = $request->get('search', '');
            
            // Build query
            $query = PackageModel::where('status', 1)->international();
            
            // Apply search
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('package_title', 'like', '%' . $search . '%')
                      ->orWhere('package_id', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
                });
            }
            
            // Get paginated results
            $packages = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
                
            // Format packages for response
            $formattedPackages = $packages->map(function($package) {
                // Get package images
                $packageImages = PackagesImagesModel::where('package_id', $package->id)->get();
                $images = $packageImages->map(function($img) {
                    return asset('uploads/package/' . $img->images);
                })->toArray();
                
                // Get pricing data using helper function
                $standardPrices = [
                    2 => $this->getAdultsPrice('Standard', 2, $package->id),
                    3 => $this->getAdultsPrice('Standard', 3, $package->id),
                    4 => $this->getAdultsPrice('Standard', 4, $package->id),
                    5 => $this->getAdultsPrice('Standard', 5, $package->id),
                    6 => $this->getAdultsPrice('Standard', 6, $package->id),
                ];
                
                $deluxePrices = [
                    2 => $this->getAdultsPrice('Deluxe', 2, $package->id),
                    3 => $this->getAdultsPrice('Deluxe', 3, $package->id),
                    4 => $this->getAdultsPrice('Deluxe', 4, $package->id),
                    5 => $this->getAdultsPrice('Deluxe', 5, $package->id),
                    6 => $this->getAdultsPrice('Deluxe', 6, $package->id),
                ];
                
                $premiumPrices = [
                    2 => $this->getAdultsPrice('Premium', 2, $package->id),
                    3 => $this->getAdultsPrice('Premium', 3, $package->id),
                    4 => $this->getAdultsPrice('Premium', 4, $package->id),
                    5 => $this->getAdultsPrice('Premium', 5, $package->id),
                    6 => $this->getAdultsPrice('Premium', 6, $package->id),
                ];
                
                return [
                    'id' => $package->id,
                    'package_id' => $package->package_id,
                    'package_title' => $package->package_title,
                    'slug' => $package->slug,
                    'package_homepage_image' => $package->package_homepage_image ? asset($package->package_homepage_image) : null,
                    'nights_count' => $package->nights_count,
                    'days_count' => $package->days_count,
                    'amenities' => $package->amenities,
                    'pack_location_cover' => $package->pack_location_cover,
                    's_individual' => $package->s_individual,
                    'd_individual' => $package->d_individual,
                    'p_individual' => $package->p_individual,
                    'images' => $images,
                    'pricing' => [
                        'standard' => $standardPrices,
                        'deluxe' => $deluxePrices,
                        'premium' => $premiumPrices,
                    ],
                    'detail_url' => route('international.tour.detail', $package->slug),
                    'created_at' => $package->created_at->format('M d, Y'),
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedPackages,
                'current_page' => $packages->currentPage(),
                'last_page' => $packages->lastPage(),
                'per_page' => $packages->perPage(),
                'total' => $packages->total(),
                'has_more_pages' => $packages->hasMorePages(),
                'from' => $packages->firstItem(),
                'to' => $packages->lastItem(),
                'next_page_url' => $packages->nextPageUrl(),
                'prev_page_url' => $packages->previousPageUrl(),
                'message' => 'Packages loaded successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Package AJAX error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load packages',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    private function getAdultsPrice($category, $pax, $packageId)
    {
        // Use the existing helper function if available
        if (function_exists('get_adults_cal')) {
            $price = get_adults_cal($category, $pax, $packageId);
            return $price ? number_format($price) : '--';
        }
        
        // Fallback - you can implement database query here
        return '--';
    }

    
    
    public function tour_detail(Request $request){
        $package_details = PackageModel::where('status', 1)->where('slug', request()->segment(2))->first();
        //dd($package_details->inclusions);
        $package_images = PackagesImagesModel::where('package_id', $package_details->id)->get();
        $vehicle_images = VehicleImages::where('package_id', $package_details->id)->get();
        $hotels_images = HotelsImages::where('package_id', $package_details->id)->get();
        $package_itinerary = ItineraryModel::where('package_id', $package_details->id)->orderBy('order_column', 'asc')->orderBy('id', 'asc')->get();
        $package_flight = PackageFlight::where('package_id', $package_details->id)->where('type','domestic')->first();
        $itinerary_image = ItineraryImages::where('type','domestic')->get();
        $packageId = $package_details->id ?? 0;
        $highlights_images = DB::table('package_highlights')->where('package_id',$packageId)->first();
        if(!empty($highlights_images->id)){
        $package_highlights_gallery_all = DB::table('package_highlights_gallery')->where('highlight_id',$highlights_images->id)->get();
        $package_highlights_sightseeing_all = DB::table('package_highlights_sightseeing')->where('highlight_id',$highlights_images->id)->get();
        $package_highlights_property_all = DB::table('package_highlights_property')->where('highlight_id',$highlights_images->id)->get();
        $package_highlights_gallery = DB::table('package_highlights_gallery')->where('highlight_id',$highlights_images->id)->first();
        $package_highlights_property = DB::table('package_highlights_property')->where('highlight_id',$highlights_images->id)->first();
        $package_highlights_sightseeing = DB::table('package_highlights_sightseeing')->where('highlight_id',$highlights_images->id)->first();
        }else{
            $package_highlights_gallery = '';
            $package_highlights_property = '';
            $package_highlights_sightseeing = '';
            $package_highlights_gallery_all = [];
            $package_highlights_sightseeing_all = [];
            $package_highlights_property_all = [];
        }
        //dd($highlights_images);
        $packageId = $package_details->id ?? 0;
        //dd($packageId);
        //dd($package_highlights_sightseeing->image);
        return view('frontend.tour-detail',compact('packageId','package_highlights_sightseeing_all','package_highlights_property_all','package_highlights_gallery_all','package_highlights_property','package_highlights_sightseeing','package_highlights_gallery','highlights_images','packageId','package_flight','itinerary_image','package_details','package_images','package_itinerary','vehicle_images','hotels_images'));
        
    }
    
     public function international_tour_detail(Request $request){
        $package_details = InternationalPackageModel::where('status', 1)->where('slug', request()->segment(2))->first();
        //dd($package_details->inclusions);
        $package_images = InternationalPackagesImagesModel::where('package_id', $package_details->id)->get();
        $vehicle_images = VehicleImages::where('package_id', $package_details->id)->get();
        $hotels_images = HotelsImages::where('package_id', $package_details->id)->get();
        $package_itinerary = InternationalItineraryModel::where('package_id', $package_details->id)->orderBy('order_column', 'asc')->orderBy('id', 'asc')->get();
        $package_flight = PackageFlight::where('package_id', $package_details->id)->where('type','international')->first();
        $itinerary_image = ItineraryImages::where('type','international')->get();
        
        
        return view('frontend.international-tour-detail',compact('package_flight','itinerary_image','package_details','package_images','package_itinerary','vehicle_images','hotels_images'));
        
    }
    
     public function honeymoon_tour_detail(Request $request){
        $package_details = HoneymoonPackageModel::where('status', 1)->where('slug', request()->segment(2))->first();
        //dd($package_details->inclusions);
        $package_images = HoneymoonPackagesImagesModel::where('package_id', $package_details->id)->get();
        $vehicle_images = VehicleImages::where('package_id', $package_details->id)->get();
        $hotels_images = HotelsImages::where('package_id', $package_details->id)->get();
        $package_itinerary = HoneymoonItineraryModel::where('package_id', $package_details->id)->orderBy('order_column', 'asc')->orderBy('id', 'asc')->get();
        $package_flight = PackageFlight::where('package_id', $package_details->id)->where('type','honeymoon')->first();
        $itinerary_image = ItineraryImages::where('type','honeymoon')->get();
        return view('frontend.honeymoon-tour-detail',compact('package_flight','itinerary_image','package_details','package_images','package_itinerary','vehicle_images','hotels_images'));
        
    }
    
    public function contact(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'first_name'=>'required',
                'last_name'=>'required',
                'email'=>'required',
                'phone'=>'required',
                'message'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $contact = new ContactModel();
                $contact->first_name = $data['first_name'];
                $contact->last_name = $data['last_name'];
                $contact->email = $data['email'];
                $contact->phone = $data['phone'];
                $contact->message = $data['message'];
                $contact->status = 1;
                $contact->created_at = date('Y-m-d H:i:s');
                $contact->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
        return view('frontend.contact');
    }
    
   public function blog(Request $request)
{
    return view('frontend.blog');
}

public function getBlogData(Request $request)
{
    try {
        $perPage = $request->get('per_page', 9); // 9 items per page for 3-column layout
        $page = $request->get('page', 1);
        
        // Get paginated blog data
        $blog = BlogModel::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
            
        \Log::info('Blog page ' . $page . ' loaded: ' . $blog->count() . ' items');
        
        $formattedData = $blog->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'slug' => $item->slug,
                'image' => asset($item->image),
                'created_at' => $item->created_at->format('d M Y'),
                'blog_details_url' => route('blog_details', $item->slug),
            ];
        })->values();
        
        return response()->json([
            'success' => true,
            'data' => $formattedData,
            'current_page' => $blog->currentPage(),
            'last_page' => $blog->lastPage(),
            'per_page' => $blog->perPage(),
            'total' => $blog->total(),
            'has_more_pages' => $blog->hasMorePages(),
            'next_page_url' => $blog->nextPageUrl(),
            'prev_page_url' => $blog->previousPageUrl(),
            'message' => 'Blog posts loaded successfully'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Blog load error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to load blog posts',
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}
    
    public function blog_details(Request $request,$slug){
        $blog_details = BlogModel::where('status',1)->where('slug',$slug)->orderBy('created_at', 'desc')->first();
        return view('frontend.blog_details',compact('blog_details'));
    }
    
    public function gallery(Request $request){
        $gallery = GalleryModel::where('status',1)->latest()->get();
        return view('frontend.gallery',compact('gallery'));
    }
    
    public function privacy_policy(Request $request){
        return view('frontend.privacy_policy');
    }
    
    public function term_conditions(Request $request){
        return view('frontend.term_conditions');
    }
    
    public function getGalleryData(Request $request){
        try {
            $perPage = $request->get('per_page', 12); // 12 items per page
            $page = $request->get('page', 1);
            
            // Get paginated gallery data
            $gallery = GalleryModel::where('status', 1)
                ->latest()
                ->paginate($perPage, ['*'], 'page', $page);
                
            \Log::info('Gallery page ' . $page . ' loaded: ' . $gallery->count() . ' items');
    
            $formattedData = $gallery->map(function ($item) {
                $imagePath = $item->image;
                $imageUrl = asset($imagePath);
                
                return [
                    'id' => $item->id,
                    'image_url' => $imageUrl,
                    'title' => $item->title ?? 'Gallery Image ' . $item->id,
                    'alt' => $item->alt_text ?? 'Gallery Image ' . $item->id,
                    'created_at' => optional($item->created_at)->format('M d, Y') ?? '',
                ];
            });
    
            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'current_page' => $gallery->currentPage(),
                'last_page' => $gallery->lastPage(),
                'per_page' => $gallery->perPage(),
                'total' => $gallery->total(),
                'has_more_pages' => $gallery->hasMorePages(),
                'next_page_url' => $gallery->nextPageUrl(),
                'prev_page_url' => $gallery->previousPageUrl(),
                'message' => 'Gallery loaded successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Gallery load error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load gallery',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    
    public function changeDate(Request $request){
    $package = PackageModel::select('nights_count')->where('id',$request->package_id)->first();
        if (!$package) {
            return response()->json(['error' => 'Package not found'], 404);
        }
        $get_day = $package->nights_count;
        $date = Carbon::parse($request->date);
        $new_date = $date->addDays($get_day)->format('d-m-Y');
        return response()->json($new_date);
    }
    
    
    public function getPrice(Request $request){
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'package_type' => 'required|in:Budget,Standard,Premium,Luxury'
        ]);
        $package = PackageModel::find($request->package_id);
        if (!$package) {
            return response()->json(['error' => 'Package not found'], 404);
        }
        
        switch ($request->package_type) {
            // case 'Budget':
            //     $price = get_tour_cal('Budget', 2, $request->tour_id,$package->days_count,$package->nights_count);
            //     break;
            case 'Standard':
                $price = get_dynamic_tour_cal('Standard', 2, $package->tour_id,$package->days_count,$package->nights_count);
                break;
            case 'Premium':
                $price = get_dynamic_tour_cal('Premium', 2, $package->tour_id,$package->days_count,$package->nights_count);
                break;
            case 'Luxury':
                $price = get_dynamic_tour_cal('Luxury', 2, $package->tour_id,$package->days_count,$package->nights_count);
                break;
            default:
                $price = 0;
                break;
        }

        return response()->json((int) $price);
    }

    public function PaySubmit(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'package_price_type'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $children_count  = $data['children'] + $data['infants'];
                $booking_session_id = Str::uuid()->toString();
                $request->session()->put('booking_session_id', $booking_session_id);
                $contact = new SubmitPayModel();
                $customer_id = \Session::get('customer_id');
                $contact->user_id = $customer_id;
                $contact->package_id = $data['package_id'];
                $contact->tour_name = $data['tour_name'];
                $contact->type = 'Tour';
                $contact->name = $data['name'] ?? '';
                $contact->adults_count = $data['adults'];
                $contact->children_count = $children_count;
                $contact->price = $data['price'];
                $contact->nights_count = $data['nights_count'];
                $contact->days_count = $data['days_count'];
                $contact->booking_session_id = $booking_session_id;
                $contact->package_price_type = $data['package_price_type'];
                $contact->to_date = $data['to_date'];
                $contact->from_date = $data['from_date'];
                $contact->status = 1;
                $contact->created_at = date('Y-m-d H:i:s');
                $contact->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
    }
    
    public function UpdatePaySubmit(Request $request)
    {
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false;
            $data = $request->all();
            
            // Log incoming data for debugging
            Log::info('UpdatePaySubmit Request Data: ', $data);
            
            // Complete validation rules including all required fields
            $rules = [
                'mail' => 'required|email',
                'phone' => 'required|regex:/^[0-9+ ]{10,14}$/',
                'address' => 'required|string|min:3',
                'country_id' => 'required|exists:country,id',
                'state_id' => 'required|exists:state,id',
                'city_id' => 'required|exists:city,id',
                'adults' => 'required|array|min:1',
                'adults.*.first' => 'required|string|min:2',
                'adults.*.last' => 'required|string|min:2',
                'adults.*.age' => 'required|integer|min:18',
                'adults.*.gender' => 'required|string',
                'payment_id' => 'required|exists:pay_submit,id',
                'total_amount' => 'required|numeric|min:0'
            ];
            
            // Add conditional validation for children
            if(isset($data['children']) && !empty($data['children']) && is_array($data['children'])) {
                $rules['children.*.first'] = 'required|string|min:2';
                $rules['children.*.last'] = 'required|string|min:2';
                $rules['children.*.age'] = 'required|integer|max:17';
                $rules['children.*.gender'] = 'required|string';
            }
            
            $customMessages = [
                'mail.required' => 'Email address is required',
                'mail.email' => 'Please enter a valid email address',
                'phone.required' => 'Mobile number is required',
                'phone.regex' => 'Please enter a valid mobile number (10-14 digits)',
                'address.required' => 'Street address is required',
                'address.min' => 'Address must be at least 3 characters',
                'country_id.required' => 'Please select a country',
                'country_id.exists' => 'Selected country is invalid',
                'state_id.required' => 'Please select a state',
                'state_id.exists' => 'Selected state is invalid',
                'city_id.required' => 'Please select a city',
                'city_id.exists' => 'Selected city is invalid',
                'adults.required' => 'Adult information is required',
                'adults.min' => 'At least one adult is required',
                'adults.*.first.required' => 'Adult first name is required',
                'adults.*.last.required' => 'Adult last name is required',
                'adults.*.age.required' => 'Adult age is required',
                'adults.*.age.min' => 'Adult age must be 18 or above',
                'adults.*.gender.required' => 'Adult gender is required',
                'payment_id.required' => 'Payment information is missing',
                'payment_id.exists' => 'Invalid payment record',
                'total_amount.required' => 'Total amount is required',
                'total_amount.numeric' => 'Total amount must be a number',
                'children.*.first.required' => 'Child first name is required',
                'children.*.last.required' => 'Child last name is required',
                'children.*.age.required' => 'Child age is required',
                'children.*.age.max' => 'Child age must be 17 or below',
                'children.*.gender.required' => 'Child gender is required',
            ];
            
            $validator = Validator::make($data, $rules, $customMessages);
            
            if($validator->fails()){
                Log::error('Validation Failed: ', $validator->errors()->toArray());
                $success = false;
                $message = $validator->errors();
                return ResponseHelper::returnResponse(422, $success, $message);
            }
            
            try {
                // Get the payment record
                $payment = SubmitPayModel::where('id', $data['payment_id'])->first();
                
                if(!$payment) {
                    $success = false;
                    $message = array('error'=>array('Payment record not found'));
                    return ResponseHelper::returnResponse(404, $success, $message);
                }
                
                // ==========================================
                // CRITICAL: Check if payment is already processed
                // ==========================================
                if ($payment->payment_status == 'success' && !empty($payment->booking_id)) {
                    Log::warning('Booking already processed for payment ID: ' . $payment->id);
                    $success = true;
                    $message = array(
                        'success' => array('Booking already confirmed'),
                        'booking_id' => $payment->booking_id,
                        'booking_url' => url('/thank-you/' . $payment->booking_id),
                        'already_processed' => true
                    );
                    return ResponseHelper::returnResponse(200, $success, $message);
                }
                
                // ==========================================
                // CHECK IF THIS IS A VALID PAYMENT (Has Razorpay ID)
                // Email should ONLY be sent when Razorpay payment ID exists
                // ==========================================
                $isPaymentCompleted = !empty($data['razorpay_payment_id']) && !empty($data['razorpay_order_id']);
                
                if (!$isPaymentCompleted) {
                    // This is just a validation/initial request, not the actual payment completion
                    Log::info('Payment not yet completed for payment ID: ' . $payment->id . ' - Skipping email and final processing');
                    $success = true;
                    $message = array(
                        'success' => array('Data validated successfully'),
                        'payment_pending' => true
                    );
                    return ResponseHelper::returnResponse(200, $success, $message);
                }
                
                // ==========================================
                // ONLY PROCEED IF PAYMENT IS COMPLETED
                // ==========================================
                Log::info('Payment completed for ID: ' . $payment->id . ' with Razorpay ID: ' . $data['razorpay_payment_id']);
                
                // Generate booking ID
                $bookingId = 'BK' . date('Ymd') . rand(1000, 9999);
                
                // Re-index adults array to ensure it starts from 0
                $adults = array_values($data['adults']);
                
                // Prepare update data
                $travellers = json_encode($adults);
                $children = (isset($data['children']) && !empty($data['children'])) ? json_encode(array_values($data['children'])) : null;
                $customer_id = \Session::get('customer_id');
                $customer_details = CustomerModel::where('id',$customer_id)->orderBy('created_at', 'desc')->first();
                
                $update_arr = [
                    "payment_status" => "success",
                    "razorpay_payment_id" => $data['razorpay_payment_id'],
                    "razorpay_order_id" => $data['razorpay_order_id'],
                    "name"=>$customer_details->full_name ?? '',
                    "mail" => $data['mail'],
                    "sub_total" => $data['sub_total'] ?? 0,
                    "discount_amount" => $data['discount_amount'] ?? 0,
                    "gst_amount" => $data['gst_amount'] ?? 0,
                    "phone" => $data['phone'],
                    'booking_id' => $bookingId,
                    'total_amount' => $data['total_amount'],
                    "address" => $data['address'],
                    "apartment_number" => $data['apartment_number'] ?? null,
                    "country_id" => $data['country_id'],
                    "state_id" => $data['state_id'],
                    "city_id" => $data['city_id'],
                    "adults" => $travellers,
                    "children" => $children,
                    "invoice_generated_at" => date('Y-m-d H:i:s'),
                    "email_sent" => 0 // Reset email sent flag
                ];
                
                // Update payment record
                $affectedRows = SubmitPayModel::where("id", $payment->id)->update($update_arr);
                
                if($affectedRows) {
                    // Refresh payment model to get updated data
                    $payment = SubmitPayModel::find($payment->id);
                    
                    Log::info('Payment updated successfully for ID: ' . $payment->id . ' with Booking ID: ' . $bookingId);
                    
                    // ==========================================
                    // SAVE COUPON HISTORY (Only after payment)
                    // ==========================================
                    if (isset($data['coupon_code']) && !empty($data['coupon_code'])) {
                        try {
                            // Check if coupon history already exists for this payment
                            $existingHistory = CouponsHistoryModel::where('payment_id', $payment->id)->first();
                            
                            if (!$existingHistory) {
                                $coupon = CouponModel::select('id', 'coupons_name', 'type', 'discount')
                                            ->where('coupons_code', $data['coupon_code'])
                                            ->first();
                                
                                if ($coupon) {
                                    $coupon_id = $coupon->id;
                                    $coupon_type = $coupon->type;
                                    $discount_percentage = ($coupon_type == 'P') ? $coupon->discount : null;
                                } else {
                                    $coupon_id = null;
                                    $coupon_type = null;
                                    $discount_percentage = null;
                                }
                                
                                // Get customer name from first adult
                                $firstAdult = $adults[0] ?? [];
                                $customerName = trim(($firstAdult['first'] ?? '') . ' ' . ($firstAdult['last'] ?? ''));
                                
                                $history_data = [
                                    'coupon_id' => $coupon_id,
                                    'coupon_code' => $data['coupon_code'],
                                    'user_id' => $customer_id,
                                    'payment_id' => $payment->id,
                                    'status' => '1',
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                                
                                CouponsHistoryModel::create($history_data);
                                Log::info('Coupon history saved for payment: ' . $payment->id);
                            } else {
                                Log::info('Coupon history already exists for payment: ' . $payment->id);
                            }
                        } catch (\Exception $couponHistoryError) {
                            Log::error('Coupon History Save Error: ' . $couponHistoryError->getMessage());
                        }
                    }
                    
                    // ==========================================
                    // Generate PDF (Only after payment)
                    // ==========================================
                    $pdfPath = null;
                    $pdfFileName = null;
                    $pdfGenerated = false;
                    $invoice = null;
                    
                    try {
                        // Get country, state, city names
                        $country = CountryModel::find($data['country_id']);
                        $state = StateModel::find($data['state_id']);
                        $city = CityModel::find($data['city_id']);
                        
                        // Get customer name from first adult
                        $firstAdult = $adults[0];
                        $customerName = trim($firstAdult['first'] . ' ' . $firstAdult['last']);
                        
                        // Calculate final amount
                        $subtotal = $payment->sub_total ?? 0;
                        $discountAmount = $payment->discount_amount ?? 0;
                        $gstAmount = $payment->gst_amount ?? 0;
                        $finalAmount = $subtotal + $gstAmount - $discountAmount;
                        
                        // Create invoice object
                        $invoice = (object)[
                            'id' => $payment->id,
                            'booking_id' => $bookingId,
                            'invoice_number' => 'INV/TR/' . $payment->id . '/' . date('Ymd'),
                            'invoice_date' => date('d M Y'),
                            'customer_name' => $customerName,
                            'customer_email' => $data['mail'],
                            'customer_phone' => $data['phone'],
                            'customer_address' => trim($data['address'] . ', ' . ($data['apartment_number'] ?? '') . ', ' . ($city->name ?? '') . ', ' . ($state->name ?? '') . ' - ' . ($country->name ?? ''), ', '),
                            'package_name' => $payment->tour_name ?? 'Tour Package',
                            'package_type' => ($payment->package_price_type ?? 'Standard') . ' Package',
                            'departure_date' => $payment->from_date ?? date('d M Y'),
                            'departure_from' => $payment->departure_from ?? 'Mumbai International Airport (BOM)',
                            'return_date' => $payment->to_date ?? date('d M Y', strtotime('+5 days')),
                            'return_to' => $payment->return_to ?? 'Mumbai International Airport (BOM)',
                            'accommodation' => ($payment->package_price_type ?? 'Standard') . ' Package',
                            'adults_count' => count($adults),
                            'children_count' => isset($data['children']) ? count($data['children']) : 0,
                            'adults_details' => $adults,
                            'children_details' => isset($data['children']) ? array_values($data['children']) : [],
                            'subtotal' => $subtotal,
                            'discount' => $discountAmount,
                            'discount_amount' => $discountAmount,
                            'coupon_code' => $data['coupon_code'] ?? null,
                            'tax' => $gstAmount,
                            'tax_amount' => $gstAmount,
                            'advance_payment' => $payment->advance_payment ?? 0,
                            'total_amount' => $finalAmount,
                            'total_paid' => $finalAmount,
                            'balance_due' => max(0, $finalAmount - ($payment->advance_payment ?? 0)),
                            'payment_status' => 'Paid',
                            'payment_method' => 'Razorpay',
                            'razorpay_payment_id' => $data['razorpay_payment_id'],
                            'razorpay_order_id' => $data['razorpay_order_id'],
                            'booking_date' => date('d M Y', strtotime($payment->created_at)),
                            'created_at' => $payment->created_at,
                            'travel_agents' => 'Phoenix Tours & Travels',
                            'company_name' => 'Phoenix Tours & Travels',
                            'company_email' => 'support@phoenixtoursandtravels.in',
                            'company_phone' => '+91 8790006059',
                            'company_address' => '18 & 19 1, Gullapallis Movva Nest Complex, 1st Floor, Plot. No. 17, Nizampet Rd, opp. Vijetha Super Market, Hyder Nagar, Kukatpally, Hyderabad, Telangana 500085'
                        ];
                        
                        // Check if PDF view exists
                        if (view()->exists('pdf.invoice')) {
                            // Load PDF view and generate
                            $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $invoice]);
                            $pdf->setPaper('A4', 'portrait');
                            
                            // Create directory if not exists
                            $pdfDirectory = public_path('invoices');
                            if (!file_exists($pdfDirectory)) {
                                mkdir($pdfDirectory, 0755, true);
                            }
                            
                            // Save PDF
                            $pdfFileName = 'invoice_' . $payment->id . '_' . date('Ymd_His') . '.pdf';
                            $pdfPath = public_path('invoices/' . $pdfFileName);
                            $pdf->save($pdfPath);
                            
                            // Verify PDF was saved successfully
                            if (file_exists($pdfPath) && is_readable($pdfPath)) {
                                $pdfGenerated = true;
                                
                                // Store PDF path in database
                                SubmitPayModel::where("id", $payment->id)->update([
                                    'invoice_pdf' => 'invoices/' . $pdfFileName,
                                    'invoice_generated_at' => date('Y-m-d H:i:s')
                                ]);
                                
                                Log::info('PDF generated successfully: ' . $pdfPath);
                            }
                        } else {
                            Log::warning('PDF view "pdf.invoice" not found');
                        }
                        
                    } catch (\Exception $pdfError) {
                        Log::error('PDF Generation Error: ' . $pdfError->getMessage());
                        Log::error('PDF Error Trace: ' . $pdfError->getTraceAsString());
                    }
                    
                    // ==========================================
                    // SEND EMAIL (Only after payment is completed)
                    // ==========================================
                    try {
                        // Get customer name
                        $firstAdult = $adults[0] ?? [];
                        $customerName = trim(($firstAdult['first'] ?? '') . ' ' . ($firstAdult['last'] ?? ''));
                        
                        // Calculate final amount
                        $subtotal = $payment->sub_total ?? 0;
                        $gstAmount = $payment->gst_amount ?? 0;
                        $discountAmount = $payment->discount_amount ?? 0;
                        $finalAmount = $subtotal + $gstAmount - $discountAmount;
                        
                        // Prepare email data
                        $emailData = [
                            'customer_name' => $customerName,
                            'booking_id' => $bookingId,
                            'payment' => $payment,
                            'total_amount' => $finalAmount,
                            'invoice' => $invoice
                        ];
                        
                        // Check if PDF exists and is readable
                        if ($pdfGenerated && $pdfPath && file_exists($pdfPath) && is_readable($pdfPath)) {
                            // Send email with PDF attachment
                            Mail::send('email.booking_confirmation', $emailData, function($message) use ($data, $pdfPath, $pdfFileName, $bookingId) {
                                $message->to($data['mail'])
                                        ->subject('Booking Confirmation - ' . $bookingId . ' | Phoenix Tours & Travels')
                                        ->from(config('mail.from.address'), config('mail.from.name'))
                                        ->attach($pdfPath, [
                                            'as' => $pdfFileName,
                                            'mime' => 'application/pdf',
                                        ]);
                            });
                            
                            Log::info('Booking confirmation email sent to: ' . $data['mail']);
                        } else {
                            // Send email without PDF attachment
                            Log::warning('PDF not available, sending email without attachment to: ' . $data['mail']);
                            
                            Mail::send('email.booking_confirmation', $emailData, function($message) use ($data, $bookingId) {
                                $message->to($data['mail'])
                                        ->subject('Booking Confirmation - ' . $bookingId . ' | Phoenix Tours & Travels')
                                        ->from(config('mail.from.address'), config('mail.from.name'));
                            });
                            
                            Log::info('Booking confirmation email sent without PDF to: ' . $data['mail']);
                        }
                        
                        // Mark email as sent
                        SubmitPayModel::where("id", $payment->id)->update(['email_sent' => 1]);
                        
                    } catch (\Exception $mailError) {
                        Log::error('Email Sending Error: ' . $mailError->getMessage());
                        Log::error('Email Error Stack Trace: ' . $mailError->getTraceAsString());
                    }
                    
                    // Wallet entry (if user_id exists)
                    try {
                        if(!empty($payment->user_id)) {
                            $existingWallet = WalletModel::where('booking_id', $payment->id)->first();
                            if(!$existingWallet) {
                                $wallet = WalletModel::where('user_id', $payment->user_id)->first();
                                if(empty($wallet)){
                                    WalletModel::create([
                                        'transaction_id' => 'TXN' . strtoupper(\Illuminate\Support\Str::random(10)),
                                        'user_id' => getUserReferralId($payment->user_id),
                                        'referral_id' => $payment->user_id,
                                        'booking_id' => $payment->id,
                                        'booking_data' => date('Y-m-d H:i:s'),
                                        'amount' => '500',
                                        'type' => $finalAmount ?? 0,
                                        'created_at' => date('Y-m-d H:i:s')
                                    ]);
                                    Log::info('Wallet entry created for user: ' . $payment->user_id);
                                }
                            }
                        }
                    } catch (\Exception $walletError) {
                        Log::error('Wallet Entry Error: ' . $walletError->getMessage());
                    }
                    
                    $success = true;
                    $message = array(
                        'success' => array('Booking confirmed successfully'),
                        'booking_id' => $bookingId,
                        'booking_url' => url('/thank-you/' . $bookingId),
                        'invoice_path' => ($pdfGenerated && $pdfPath && file_exists($pdfPath)) ? asset('invoices/' . $pdfFileName) : null
                    );
                    return ResponseHelper::returnResponse(200, $success, $message);
                    
                } else {
                    Log::error('Failed to update payment record: ' . $payment->id);
                    $success = false;
                    $message = array('error'=>array('Failed to update booking. Please try again.'));
                    return ResponseHelper::returnResponse(500, $success, $message);
                }
                
            } catch (\Exception $e) {
                Log::error('Booking Process Error: ' . $e->getMessage());
                Log::error('Error Stack Trace: ' . $e->getTraceAsString());
                $success = false;
                $message = array('error'=>array('An error occurred while processing your booking. Please try again.'));
                return ResponseHelper::returnResponse(500, $success, $message);
            }
        }
        
        $success = false;
        $message = array('error'=>array('Invalid request method'));
        return ResponseHelper::returnResponse(405, $success, $message);
    }
    
    
    
    public function InternationalPaySubmit(Request $request){
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false ;
            $data = $request->all();
            $rules=[
                'package_price_type'=>'required'
            ];
            $validator = Validator::make($data,$rules);
            if($validator->fails()){
                $success = false;
                $message = $validator->errors();
            }else{
                $children_count  = $data['children'] + $data['infants'];
                $booking_session_id = Str::uuid()->toString();
                $request->session()->put('booking_session_id', $booking_session_id);
                $contact = new InternationalSubmitPayModel();
                $customer_id = \Session::get('customer_id');
                $contact->user_id = $customer_id;
                $contact->package_id = $data['package_id'];
                $contact->tour_name = $data['tour_name'];
                $contact->type = 'Tour';
                $contact->name = $data['name'] ?? '';
                $contact->adults_count = $data['adults'];
                $contact->children_count = $children_count;
                $contact->price = $data['price'];
                $contact->nights_count = $data['nights_count'];
                $contact->days_count = $data['days_count'];
                $contact->booking_session_id = $booking_session_id;
                $contact->package_price_type = $data['package_price_type'];
                $contact->to_date = $data['to_date'];
                $contact->from_date = $data['from_date'];
                $contact->status = 1;
                $contact->created_at = date('Y-m-d H:i:s');
                $contact->save();
                $success = true;
                $message = array('success'=>array('added successfully'));
            }
         return ResponseHelper::returnResponse(200,$success,$message);
        }
    }
    
    public function InternationalUpdatePaySubmit(Request $request)
    {
        if($request->isMethod('post')){
            $response_code = 200;
            $message = array('error'=>array('something went wrong'));
            $success = false;
            $data = $request->all();
            
            // Log incoming data for debugging
            Log::info('UpdatePaySubmit Request Data: ', $data);
            
            // Complete validation rules including all required fields
            $rules = [
                'mail' => 'required|email',
                'phone' => 'required|regex:/^[0-9+ ]{10,14}$/',
                'address' => 'required|string|min:3',
                'country_id' => 'required|exists:country,id',
                'state_id' => 'required|exists:state,id',
                'city_id' => 'required|exists:city,id',
                'adults' => 'required|array|min:1',
                'adults.*.first' => 'required|string|min:2',
                'adults.*.last' => 'required|string|min:2',
                'adults.*.age' => 'required|integer|min:18',
                'adults.*.gender' => 'required|string',
                'payment_id' => 'required|exists:pay_submit,id',
                'total_amount' => 'required|numeric|min:0'
            ];
            
            // Add conditional validation for children
            if(isset($data['children']) && !empty($data['children']) && is_array($data['children'])) {
                $rules['children.*.first'] = 'required|string|min:2';
                $rules['children.*.last'] = 'required|string|min:2';
                $rules['children.*.age'] = 'required|integer|max:17';
                $rules['children.*.gender'] = 'required|string';
            }
            
            $customMessages = [
                'mail.required' => 'Email address is required',
                'mail.email' => 'Please enter a valid email address',
                'phone.required' => 'Mobile number is required',
                'phone.regex' => 'Please enter a valid mobile number (10-14 digits)',
                'address.required' => 'Street address is required',
                'address.min' => 'Address must be at least 3 characters',
                'country_id.required' => 'Please select a country',
                'country_id.exists' => 'Selected country is invalid',
                'state_id.required' => 'Please select a state',
                'state_id.exists' => 'Selected state is invalid',
                'city_id.required' => 'Please select a city',
                'city_id.exists' => 'Selected city is invalid',
                'adults.required' => 'Adult information is required',
                'adults.min' => 'At least one adult is required',
                'adults.*.first.required' => 'Adult first name is required',
                'adults.*.last.required' => 'Adult last name is required',
                'adults.*.age.required' => 'Adult age is required',
                'adults.*.age.min' => 'Adult age must be 18 or above',
                'adults.*.gender.required' => 'Adult gender is required',
                'payment_id.required' => 'Payment information is missing',
                'payment_id.exists' => 'Invalid payment record',
                'total_amount.required' => 'Total amount is required',
                'total_amount.numeric' => 'Total amount must be a number',
                'children.*.first.required' => 'Child first name is required',
                'children.*.last.required' => 'Child last name is required',
                'children.*.age.required' => 'Child age is required',
                'children.*.age.max' => 'Child age must be 17 or below',
                'children.*.gender.required' => 'Child gender is required',
            ];
            
            $validator = Validator::make($data, $rules, $customMessages);
            
            if($validator->fails()){
                Log::error('Validation Failed: ', $validator->errors()->toArray());
                $success = false;
                $message = $validator->errors();
                return ResponseHelper::returnResponse(422, $success, $message);
            }
            
            try {
                // Get the payment record
                $payment = InternationalSubmitPayModel::where('id', $data['payment_id'])->first();
                
                if(!$payment) {
                    $success = false;
                    $message = array('error'=>array('Payment record not found'));
                    return ResponseHelper::returnResponse(404, $success, $message);
                }
                
                // ==========================================
                // CRITICAL: Check if payment is already processed
                // ==========================================
                if ($payment->payment_status == 'success' && !empty($payment->booking_id)) {
                    Log::warning('Booking already processed for payment ID: ' . $payment->id);
                    $success = true;
                    $message = array(
                        'success' => array('Booking already confirmed'),
                        'booking_id' => $payment->booking_id,
                        'booking_url' => url('/thank-you/' . $payment->booking_id),
                        'already_processed' => true
                    );
                    return ResponseHelper::returnResponse(200, $success, $message);
                }
                
                // ==========================================
                // CHECK IF THIS IS A VALID PAYMENT (Has Razorpay ID)
                // Email should ONLY be sent when Razorpay payment ID exists
                // ==========================================
                $isPaymentCompleted = !empty($data['razorpay_payment_id']) && !empty($data['razorpay_order_id']);
                
                if (!$isPaymentCompleted) {
                    // This is just a validation/initial request, not the actual payment completion
                    Log::info('Payment not yet completed for payment ID: ' . $payment->id . ' - Skipping email and final processing');
                    $success = true;
                    $message = array(
                        'success' => array('Data validated successfully'),
                        'payment_pending' => true
                    );
                    return ResponseHelper::returnResponse(200, $success, $message);
                }
                
                // ==========================================
                // ONLY PROCEED IF PAYMENT IS COMPLETED
                // ==========================================
                Log::info('Payment completed for ID: ' . $payment->id . ' with Razorpay ID: ' . $data['razorpay_payment_id']);
                
                // Generate booking ID
                $bookingId = 'BK' . date('Ymd') . rand(1000, 9999);
                
                // Re-index adults array to ensure it starts from 0
                $adults = array_values($data['adults']);
                
                // Prepare update data
                $travellers = json_encode($adults);
                $children = (isset($data['children']) && !empty($data['children'])) ? json_encode(array_values($data['children'])) : null;
                $customer_id = \Session::get('customer_id');
                $customer_details = CustomerModel::where('id',$customer_id)->orderBy('created_at', 'desc')->first();
                
                $update_arr = [
                    "payment_status" => "success",
                    "razorpay_payment_id" => $data['razorpay_payment_id'],
                    "razorpay_order_id" => $data['razorpay_order_id'],
                    "name"=>$customer_details->full_name ?? '',
                    "mail" => $data['mail'],
                    "sub_total" => $data['sub_total'] ?? 0,
                    "discount_amount" => $data['discount_amount'] ?? 0,
                    "gst_amount" => $data['gst_amount'] ?? 0,
                    "phone" => $data['phone'],
                    'booking_id' => $bookingId,
                    'total_amount' => $data['total_amount'],
                    "address" => $data['address'],
                    "apartment_number" => $data['apartment_number'] ?? null,
                    "country_id" => $data['country_id'],
                    "state_id" => $data['state_id'],
                    "city_id" => $data['city_id'],
                    "adults" => $travellers,
                    "children" => $children,
                    "invoice_generated_at" => date('Y-m-d H:i:s'),
                    "email_sent" => 0 // Reset email sent flag
                ];
                
                // Update payment record
                $affectedRows = InternationalSubmitPayModel::where("id", $payment->id)->update($update_arr);
                
                if($affectedRows) {
                    // Refresh payment model to get updated data
                    $payment = InternationalSubmitPayModel::find($payment->id);
                    
                    Log::info('Payment updated successfully for ID: ' . $payment->id . ' with Booking ID: ' . $bookingId);
                    
                    // ==========================================
                    // SAVE COUPON HISTORY (Only after payment)
                    // ==========================================
                    if (isset($data['coupon_code']) && !empty($data['coupon_code'])) {
                        try {
                            // Check if coupon history already exists for this payment
                            $existingHistory = CouponsHistoryModel::where('payment_id', $payment->id)->first();
                            
                            if (!$existingHistory) {
                                $coupon = CouponModel::select('id', 'coupons_name', 'type', 'discount')
                                            ->where('coupons_code', $data['coupon_code'])
                                            ->first();
                                
                                if ($coupon) {
                                    $coupon_id = $coupon->id;
                                    $coupon_type = $coupon->type;
                                    $discount_percentage = ($coupon_type == 'P') ? $coupon->discount : null;
                                } else {
                                    $coupon_id = null;
                                    $coupon_type = null;
                                    $discount_percentage = null;
                                }
                                
                                // Get customer name from first adult
                                $firstAdult = $adults[0] ?? [];
                                $customerName = trim(($firstAdult['first'] ?? '') . ' ' . ($firstAdult['last'] ?? ''));
                                
                                $history_data = [
                                    'coupon_id' => $coupon_id,
                                    'coupon_code' => $data['coupon_code'],
                                    'user_id' => $customer_id,
                                    'payment_id' => $payment->id,
                                    'status' => '1',
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ];
                                
                                CouponsHistoryModel::create($history_data);
                                Log::info('Coupon history saved for payment: ' . $payment->id);
                            } else {
                                Log::info('Coupon history already exists for payment: ' . $payment->id);
                            }
                        } catch (\Exception $couponHistoryError) {
                            Log::error('Coupon History Save Error: ' . $couponHistoryError->getMessage());
                        }
                    }
                    
                    // ==========================================
                    // Generate PDF (Only after payment)
                    // ==========================================
                    $pdfPath = null;
                    $pdfFileName = null;
                    $pdfGenerated = false;
                    $invoice = null;
                    
                    try {
                        // Get country, state, city names
                        $country = CountryModel::find($data['country_id']);
                        $state = StateModel::find($data['state_id']);
                        $city = CityModel::find($data['city_id']);
                        
                        // Get customer name from first adult
                        $firstAdult = $adults[0];
                        $customerName = trim($firstAdult['first'] . ' ' . $firstAdult['last']);
                        
                        // Calculate final amount
                        $subtotal = $payment->sub_total ?? 0;
                        $discountAmount = $payment->discount_amount ?? 0;
                        $gstAmount = $payment->gst_amount ?? 0;
                        $finalAmount = $subtotal + $gstAmount - $discountAmount;
                        
                        // Create invoice object
                        $invoice = (object)[
                            'id' => $payment->id,
                            'booking_id' => $bookingId,
                            'invoice_number' => 'INV/TR/' . $payment->id . '/' . date('Ymd'),
                            'invoice_date' => date('d M Y'),
                            'customer_name' => $customerName,
                            'customer_email' => $data['mail'],
                            'customer_phone' => $data['phone'],
                            'customer_address' => trim($data['address'] . ', ' . ($data['apartment_number'] ?? '') . ', ' . ($city->name ?? '') . ', ' . ($state->name ?? '') . ' - ' . ($country->name ?? ''), ', '),
                            'package_name' => $payment->tour_name ?? 'Tour Package',
                            'package_type' => ($payment->package_price_type ?? 'Standard') . ' Package',
                            'departure_date' => $payment->from_date ?? date('d M Y'),
                            'departure_from' => $payment->departure_from ?? 'Mumbai International Airport (BOM)',
                            'return_date' => $payment->to_date ?? date('d M Y', strtotime('+5 days')),
                            'return_to' => $payment->return_to ?? 'Mumbai International Airport (BOM)',
                            'accommodation' => ($payment->package_price_type ?? 'Standard') . ' Package',
                            'adults_count' => count($adults),
                            'children_count' => isset($data['children']) ? count($data['children']) : 0,
                            'adults_details' => $adults,
                            'children_details' => isset($data['children']) ? array_values($data['children']) : [],
                            'subtotal' => $subtotal,
                            'discount' => $discountAmount,
                            'discount_amount' => $discountAmount,
                            'coupon_code' => $data['coupon_code'] ?? null,
                            'tax' => $gstAmount,
                            'tax_amount' => $gstAmount,
                            'advance_payment' => $payment->advance_payment ?? 0,
                            'total_amount' => $finalAmount,
                            'total_paid' => $finalAmount,
                            'balance_due' => max(0, $finalAmount - ($payment->advance_payment ?? 0)),
                            'payment_status' => 'Paid',
                            'payment_method' => 'Razorpay',
                            'razorpay_payment_id' => $data['razorpay_payment_id'],
                            'razorpay_order_id' => $data['razorpay_order_id'],
                            'booking_date' => date('d M Y', strtotime($payment->created_at)),
                            'created_at' => $payment->created_at,
                            'travel_agents' => 'Phoenix Tours & Travels',
                            'company_name' => 'Phoenix Tours & Travels',
                            'company_email' => 'support@phoenixtoursandtravels.in',
                            'company_phone' => '+91 8790006059',
                            'company_address' => '18 & 19 1, Gullapallis Movva Nest Complex, 1st Floor, Plot. No. 17, Nizampet Rd, opp. Vijetha Super Market, Hyder Nagar, Kukatpally, Hyderabad, Telangana 500085'
                        ];
                        
                        // Check if PDF view exists
                        if (view()->exists('pdf.invoice')) {
                            // Load PDF view and generate
                            $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $invoice]);
                            $pdf->setPaper('A4', 'portrait');
                            
                            // Create directory if not exists
                            $pdfDirectory = public_path('invoices');
                            if (!file_exists($pdfDirectory)) {
                                mkdir($pdfDirectory, 0755, true);
                            }
                            
                            // Save PDF
                            $pdfFileName = 'invoice_' . $payment->id . '_' . date('Ymd_His') . '.pdf';
                            $pdfPath = public_path('invoices/' . $pdfFileName);
                            $pdf->save($pdfPath);
                            
                            // Verify PDF was saved successfully
                            if (file_exists($pdfPath) && is_readable($pdfPath)) {
                                $pdfGenerated = true;
                                
                                // Store PDF path in database
                                InternationalSubmitPayModel::where("id", $payment->id)->update([
                                    'invoice_pdf' => 'invoices/' . $pdfFileName,
                                    'invoice_generated_at' => date('Y-m-d H:i:s')
                                ]);
                                
                                Log::info('PDF generated successfully: ' . $pdfPath);
                            }
                        } else {
                            Log::warning('PDF view "pdf.invoice" not found');
                        }
                        
                    } catch (\Exception $pdfError) {
                        Log::error('PDF Generation Error: ' . $pdfError->getMessage());
                        Log::error('PDF Error Trace: ' . $pdfError->getTraceAsString());
                    }
                    
                    // ==========================================
                    // SEND EMAIL (Only after payment is completed)
                    // ==========================================
                    try {
                        // Get customer name
                        $firstAdult = $adults[0] ?? [];
                        $customerName = trim(($firstAdult['first'] ?? '') . ' ' . ($firstAdult['last'] ?? ''));
                        
                        // Calculate final amount
                        $subtotal = $payment->sub_total ?? 0;
                        $gstAmount = $payment->gst_amount ?? 0;
                        $discountAmount = $payment->discount_amount ?? 0;
                        $finalAmount = $subtotal + $gstAmount - $discountAmount;
                        
                        // Prepare email data
                        $emailData = [
                            'customer_name' => $customerName,
                            'booking_id' => $bookingId,
                            'payment' => $payment,
                            'total_amount' => $finalAmount,
                            'invoice' => $invoice
                        ];
                        
                        // Check if PDF exists and is readable
                        if ($pdfGenerated && $pdfPath && file_exists($pdfPath) && is_readable($pdfPath)) {
                            // Send email with PDF attachment
                            Mail::send('email.booking_confirmation', $emailData, function($message) use ($data, $pdfPath, $pdfFileName, $bookingId) {
                                $message->to($data['mail'])
                                        ->subject('Booking Confirmation - ' . $bookingId . ' | Phoenix Tours & Travels')
                                        ->from(config('mail.from.address'), config('mail.from.name'))
                                        ->attach($pdfPath, [
                                            'as' => $pdfFileName,
                                            'mime' => 'application/pdf',
                                        ]);
                            });
                            
                            Log::info('Booking confirmation email sent to: ' . $data['mail']);
                        } else {
                            // Send email without PDF attachment
                            Log::warning('PDF not available, sending email without attachment to: ' . $data['mail']);
                            
                            Mail::send('email.booking_confirmation', $emailData, function($message) use ($data, $bookingId) {
                                $message->to($data['mail'])
                                        ->subject('Booking Confirmation - ' . $bookingId . ' | Phoenix Tours & Travels')
                                        ->from(config('mail.from.address'), config('mail.from.name'));
                            });
                            
                            Log::info('Booking confirmation email sent without PDF to: ' . $data['mail']);
                        }
                        
                        // Mark email as sent
                        InternationalSubmitPayModel::where("id", $payment->id)->update(['email_sent' => 1]);
                        
                    } catch (\Exception $mailError) {
                        Log::error('Email Sending Error: ' . $mailError->getMessage());
                        Log::error('Email Error Stack Trace: ' . $mailError->getTraceAsString());
                    }
                    
                    // Wallet entry (if user_id exists)
                    try {
                        if(!empty($payment->user_id)) {
                            $existingWallet = WalletModel::where('booking_id', $payment->id)->first();
                            if(!$existingWallet) {
                                $wallet = WalletModel::where('user_id', $payment->user_id)->first();
                                if(empty($wallet)){
                                    WalletModel::create([
                                        'transaction_id' => 'TXN' . strtoupper(\Illuminate\Support\Str::random(10)),
                                        'user_id' => getUserReferralId($payment->user_id),
                                        'referral_id' => $payment->user_id,
                                        'booking_id' => $payment->id,
                                        'booking_data' => date('Y-m-d H:i:s'),
                                        'amount' => '500',
                                        'type' => $finalAmount ?? 0,
                                        'created_at' => date('Y-m-d H:i:s')
                                    ]);
                                    Log::info('Wallet entry created for user: ' . $payment->user_id);
                                }
                            }
                        }
                    } catch (\Exception $walletError) {
                        Log::error('Wallet Entry Error: ' . $walletError->getMessage());
                    }
                    
                    $success = true;
                    $message = array(
                        'success' => array('Booking confirmed successfully'),
                        'booking_id' => $bookingId,
                        'booking_url' => url('/thank-you/' . $bookingId),
                        'invoice_path' => ($pdfGenerated && $pdfPath && file_exists($pdfPath)) ? asset('invoices/' . $pdfFileName) : null
                    );
                    return ResponseHelper::returnResponse(200, $success, $message);
                    
                } else {
                    Log::error('Failed to update payment record: ' . $payment->id);
                    $success = false;
                    $message = array('error'=>array('Failed to update booking. Please try again.'));
                    return ResponseHelper::returnResponse(500, $success, $message);
                }
                
            } catch (\Exception $e) {
                Log::error('Booking Process Error: ' . $e->getMessage());
                Log::error('Error Stack Trace: ' . $e->getTraceAsString());
                $success = false;
                $message = array('error'=>array('An error occurred while processing your booking. Please try again.'));
                return ResponseHelper::returnResponse(500, $success, $message);
            }
        }
        
        $success = false;
        $message = array('error'=>array('Invalid request method'));
        return ResponseHelper::returnResponse(405, $success, $message);
    }
/**
 * Send booking confirmation email WITH PDF attachment
 */
private function sendBookingConfirmationEmailWithPDF($email, $payment, $pdfPath, $pdfFileName)
{
    try {
        // Get customer name from adults data
        $customerName = 'Valued Customer';
        if (!empty($payment->adults)) {
            $adults = is_string($payment->adults) ? json_decode($payment->adults, true) : $payment->adults;
            if (!empty($adults) && is_array($adults) && !empty($adults[0])) {
                $customerName = trim(($adults[0]['first'] ?? '') . ' ' . ($adults[0]['last'] ?? ''));
            }
        }
        
        $data = [
            'booking_id' => $payment->booking_id,
            'customer_name' => $customerName,
            'package_name' => $payment->package_name ?? 'Tour Package',
            'total_amount' => $payment->total_amount ?? 0,
            'booking_date' => date('d M Y', strtotime($payment->created_at)),
            'payment_status' => 'success',
            'has_invoice' => true
        ];
        
        // Check if email view exists
        if (!view()->exists('email.booking_confirmation')) {
            \Log::error('Email view "emails.booking_confirmation" not found');
            throw new \Exception('Email template not found');
        }
        
        \Mail::send('email.booking_confirmation', $data, function($message) use ($email, $payment, $pdfPath, $pdfFileName) {
            $message->to($email)
                    ->subject('Booking Confirmed! - ' . $payment->booking_id . ' | Phoenix Tours & Travels');
            
            if (file_exists($pdfPath)) {
                $message->attach($pdfPath, [
                    'as' => 'Invoice-' . $payment->booking_id . '.pdf',
                    'mime' => 'application/pdf',
                ]);
            }
        });
        
        \Log::info('Email with PDF sent successfully to: ' . $email);
        return true;
        
    } catch (\Exception $e) {
        \Log::error('Failed to send email with PDF: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Send booking confirmation email WITHOUT PDF
 */
private function sendBookingConfirmationEmail($email, $payment)
{
    try {
        // Get customer name from adults data
        $customerName = 'Valued Customer';
        if (!empty($payment->adults)) {
            $adults = is_string($payment->adults) ? json_decode($payment->adults, true) : $payment->adults;
            if (!empty($adults) && is_array($adults) && !empty($adults[0])) {
                $customerName = trim(($adults[0]['first'] ?? '') . ' ' . ($adults[0]['last'] ?? ''));
            }
        }
        
        $data = [
            'booking_id' => $payment->booking_id,
            'customer_name' => $customerName,
            'package_name' => $payment->package_name ?? 'Tour Package',
            'total_amount' => $payment->total_amount ?? 0,
            'booking_date' => date('d M Y', strtotime($payment->created_at)),
            'payment_status' => 'success',
            'has_invoice' => false
        ];
        
        // Check if email view exists
        if (!view()->exists('email.booking_confirmation')) {
            \Log::error('Email view "emails.booking_confirmation" not found');
            throw new \Exception('Email template not found');
        }
        
        \Mail::send('email.booking_confirmation', $data, function($message) use ($email, $payment) {
            $message->to($email)
                    ->subject('Booking Confirmed! - ' . $payment->booking_id . ' | Phoenix Tours & Travels');
        });
        
        \Log::info('Email without PDF sent successfully to: ' . $email);
        return true;
        
    } catch (\Exception $e) {
        \Log::error('Failed to send email: ' . $e->getMessage());
        throw $e;
    }
}

    public function paymentFailure(Request $request){
        $data = $request->response;
        SubmitPayModel::create([
            'payment_status' => 'failed',
            'failure_reason' => json_encode($data)
        ]);
        return response()->json(['success' => true]);
    }
    public function ThankYou(Request $request,$booking_id){
        $booking = SubmitPayModel::where('booking_id',$booking_id)->orderBy('created_at', 'desc')->first();
        return view('frontend.thank_you',compact('booking'));
    }
    public function SubmitInfo(Request $request){
        $customer_id = \Session::get('customer_id');
        $booking_session_id = \Session::get('booking_session_id');
        $payment = SubmitPayModel::where('user_id',$customer_id)->where('booking_session_id',$booking_session_id)->orderBy('created_at', 'desc')->first();
        $get_state_id = PackageModel::select('tour_id')->where('id',$payment->package_id)->first();
        //dd($get_state_id->tour_id);
        $person_count = $payment->adults_count + $payment->children_count;
        $price = get_dynamic_tour_cal($payment->package_price_type,$person_count,$get_state_id->tour_id,$payment->days_count,$payment->nights_count);
        $main_price = $price * $person_count;
        $hotel_info = GetHotelinfo($payment->package_id,$person_count,$payment->package_price_type);
        //dd($hotel_info);
        $vehicle_info = DB::table('package_car_prices')->where('pax',$person_count)->where('package_id',$payment->package_id)->first();
        $data = array(
                 'room_cost'=>$hotel_info->rooms_cost,
                 'extra_bed_cost'=>$hotel_info->extra_bed_cost,
                 'extra_beds'=>$hotel_info->extra_bed_count,
                 'attraction_cost'=>$hotel_info->attract_tickets,
                 'persons' =>$person_count,
                 'days'=>$payment->days_count,
                 'nights'=>$payment->nights_count,
                 'vehicle_cost'=>$vehicle_info->car_per_day ?? 0,
                 'driver_batta'=>$vehicle_info->driver_batta ?? 0,
            );
        $tour_cal = calculateTourCost($data);
        //dd($tour_cal);
        $total_tour_cost = $main_price;
        $attractions_cost = 0;
        $with_out_attractions_cost = $main_price - $tour_cal['attractions_cost'];
        $attractions_cost = $tour_cal['attractions_cost'];
        $country = CountryModel::where('status',1)->get();
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::where('id',$customer_id)->orderBy('created_at', 'desc')->first();
        $coupon_list = CouponModel::where('tour_id',$get_state_id->tour_id)->where('status',1)->get();
        return view('frontend.submit_info',compact('payment','country','customer_details','total_tour_cost','with_out_attractions_cost','attractions_cost','coupon_list','person_count'));
    }
    
    
    public function InternationalSubmitInfo(Request $request){
        $customer_id = \Session::get('customer_id');
        $booking_session_id = \Session::get('booking_session_id');
        $payment = InternationalSubmitPayModel::where('user_id',$customer_id)->where('booking_session_id',$booking_session_id)->orderBy('created_at', 'desc')->first();
        $get_state_id = InternationalPackageModel::select('*')->where('id',$payment->package_id)->first();
        $person_count = $payment->adults_count + $payment->children_count;
        $main_price = $get_state_id->s_individual * $person_count;
        $country = CountryModel::where('status',1)->get();
        $customer_id = \Session::get('customer_id');
        $customer_details = CustomerModel::where('id',$customer_id)->orderBy('created_at', 'desc')->first();
        $coupon_list = CouponModel::where('tour_id',$get_state_id->tour_id)->where('status',1)->get();
        $total_tour_cost = $main_price;
        return view('frontend.international_submit_info',compact('payment','country','customer_details','coupon_list','person_count','total_tour_cost'));
    }
    
    
   public function applyCouponAjax(Request $request){
        $request->validate([
            'coupon_code' => 'required',
            'price' => 'required|numeric'
        ]);
        //dd($request->all());
        $coupon = CouponModel::where('coupons_code', $request->coupon_code)
                             ->where('status', 1)
                             ->first();
        
        if (!$coupon) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid coupon code.'
            ]);
        }
        
        // Check if coupon is expired
        $currentDate = Carbon::now()->format('Y-m-d');
        if ($coupon->date_end && $currentDate > $coupon->date_end) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon has expired on ' . date('d M Y', strtotime($coupon->date_end)) . '.'
            ]);
        }
        
        // Check if coupon has started
        if ($coupon->start_date && $currentDate < $coupon->start_date) {
            return response()->json([
                'status' => false,
                'message' => 'Coupon is not yet active. Valid from ' . date('d M Y', strtotime($coupon->start_date)) . '.'
            ]);
        }
        $price_cal = $request->price + $request->attractions_cost;
        // Check minimum total amount requirement
        if ($coupon->total_amount > 0 && $price_cal < $coupon->total_amount) {
            return response()->json([
                'status' => false,
                'message' => 'Minimum order amount of ₹' . $coupon->total_amount . ' required to apply this coupon.'
            ]);
        }
        
        // Check usage limits
        $usedCount = CouponsHistoryModel::where('coupon_id', $coupon->id)
                                     ->where('payment_id', '!=', null) // Only count successful uses
                                     ->count();
        
        if ($coupon->max_no_of_uses > 0 && $usedCount >= $coupon->max_no_of_uses) {
            return response()->json([
                'status' => false,
                'message' => 'This coupon has reached its maximum usage limit.'
            ]);
        }
        
        // For user-specific limits, check if current user has already used this coupon
        if ($coupon->max_no_of_customer > 0) {
            $userId = \Session::get('customer_id');
            $userUsedCount = CouponsHistoryModel::where('coupon_id', $coupon->id)
                                             ->where('user_id', $userId)
                                             ->where('payment_id', '!=', null)
                                             ->count();
            
            if ($userUsedCount >= $coupon->max_no_of_customer) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already used this coupon maximum ' . $coupon->max_no_of_customer . ' time(s).'
                ]);
            }
        }
        
        // Calculate discount
        $price = $request->price;
        $discount = 0;
        
        // Fix: Check for uppercase 'P' as in database
        if (strtoupper($coupon->type) == 'P') {
            // Percentage discount
            $discount = round(($price * $coupon->discount) / 100);
        } else if (strtoupper($coupon->type) == 'F') {
            // Fixed discount
            $discount = $coupon->discount * $request->person_count;
        }
        
        // Ensure discount doesn't exceed the price
        if ($discount > $price) {
            $discount = $price;
        }
        
        if(strtoupper($coupon->type) == 'F'){
        $attractionsCost = $request->attractions_cost ?? 0;
        $fixed_amount = $price + $attractionsCost;
        $gst = ($fixed_amount * 5) / 100;
        $priceAfterDiscount = $price + $attractionsCost + $gst;
        $finalTotal = $priceAfterDiscount - $discount;
        }else{
            $attractionsCost = $request->attractions_cost ?? 0;
            $priceAfterDiscount = $price + $attractionsCost;
            $gst = round(($priceAfterDiscount * 5) / 100);
            $with_amount = $priceAfterDiscount + $gst;
            $finalTotal =  $with_amount - $discount;
        }
        // Store coupon details in session for verification before final payment
        session([
            'applied_coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->coupons_code,
                'discount' => round($discount, 2),
                'type' => $coupon->type,
                'original_price' => $price,
                'discounted_price' => $priceAfterDiscount
            ]
        ]);
        
        return response()->json([
            'status' => true,
            'message' => 'Coupon applied successfully! You saved ₹' . round($discount, 2),
            'discount' => round($discount, 2),
            'coupon_code' => $coupon->coupons_code,
            'coupon_description' => $coupon->coupons_description,
            'new_total' => round($finalTotal)
        ]);
    }
    
    public function store(Request $request){
    $validated = $request->validate([
        'fixed_departure_id' => 'required|exists:fixed_departures,id',
        'selected_date' => 'required|date',
        'seat_numbers' => 'required|string',
        'total_seats' => 'required|integer|min:1',
        'total_amount' => 'required|numeric',
        'price_per_seat' => 'required|numeric',
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|string|max:15',
    ]);
    
    // Convert seat numbers string to array
    $seatNumbers = explode(',', $validated['seat_numbers']);
    
    // Create booking
    $booking = Booking::create($validated);
    
    // Redirect to success page
    return redirect()->route('booking.success', ['booking' => $booking->id]);
}




}
