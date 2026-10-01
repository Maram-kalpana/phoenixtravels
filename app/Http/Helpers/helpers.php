<?php
use App\Models\SettingModel;
use App\Models\SMSModel;
use App\Models\StateModel;
use App\Models\CountryModel;
use App\Models\User;
use App\Models\SubscribeModel;
use App\Models\ItineraryModel;
use App\Models\CategoryModel;
use App\Models\PackageModel;
use App\Models\FixedDepartures;
use App\Models\PackagesImagesModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

function getdomesticTourid(){
    $get_domestic_id = DB::table('tour')
        ->select('id')
        ->where('tour_category_id', 1)
        ->orderBy('id', 'desc')
        ->pluck('id')
        ->toArray();
    
    return $get_domestic_id;
}

function getinternationalTourid(){
    $get_international_id = DB::table('tour')
        ->select('id')
        ->where('tour_category_id', 2)
        ->orderBy('id', 'desc')
        ->pluck('id')
        ->toArray();
    return $get_international_id;
}

function getFixedName($cat_id){
    $get_fixed_name = DB::table('fixed_departures')->select('package_name')->where('id', $cat_id)->orderBy('id', 'desc')->first();
    return $get_fixed_name->package_name ?? '';
}

function getTourName($cat_id){
    $get_tour_name = DB::table('tour')->select('tour_name')->where('id', $cat_id)->orderBy('id', 'desc')->first();
    return $get_tour_name->tour_name ?? '';
}

function getTourCategoryName($cat_id){
    $get_tour_category_name = DB::table('tour_category')->select('name')->where('id', $cat_id)->orderBy('id', 'desc')->first();
    return $get_tour_category_name->name ?? '';
}

function getPackageName($package_id){
    $get_package_title = PackageModel::select('package_title')->where('id', $package_id)->orderBy('id', 'desc')->first();
    return $get_package_title->package_title;
}

function getMinPrice($tour_id){
    $get_min_price = PackageModel::where('status', 1)
        ->where('tour_id', $tour_id)
        ->min('new_price');
    return $get_min_price;
}
function getPackageinfo($package_id){
    $get_package = PackageModel::select('*')->where('id', $package_id)->orderBy('id', 'desc')->first();
    return $get_package;
}

function getCategoryName($cat_id){
    $get_category_name = CategoryModel::select('category_name')->where('id', $cat_id)->orderBy('id', 'desc')->first();
    return $get_category_name->category_name;
}

function getStateName($state_id){
    $get_state_name = StateModel::select('name')->where('id', $state_id)->orderBy('id', 'desc')->first();
    return $get_state_name->name ?? '';
}

function getCountryName($country_id){
    $get_country_name = CountryModel::select('name')->where('id', $country_id)->orderBy('id', 'desc')->first();
    return $get_country_name->name;
}

function getLogo(){
    $get_logo = SettingModel::select('logo')->where('id', 1)->orderBy('id', 'desc')->first();
    return $get_logo->logo;
}

function getFav(){
    $get_logo = SettingModel::select('fav')->where('id', 1)->orderBy('id', 'desc')->first();
    return $get_logo->fav;
}

function getCompanyName(){
    $get_logo = SettingModel::select('company_name')->where('id', 1)->orderBy('id', 'desc')->first();
    return $get_logo->company_name;
}

function getColorCode(){
    $get_logo = SettingModel::select('color')->where('id', 1)->orderBy('id', 'desc')->first();
    return $get_logo->color;
}

function getUserReferralId($user_id){
    $get_user_data = User::select('*')->where('id', $user_id)->orderBy('id', 'desc')->first();
    return $get_user_data->referral_id ?? 0;
}

function get_expired_date($date, $days) {
    $days = (int)$days;
    $newDate = date('Y-m-d', strtotime("+$days days", strtotime($date)));
    return $newDate;
}

function showMobileNumber($number) {
    $length = strlen($number);
    return substr_replace($number, '***', 2, $length - 4);
}

function showEmailAddress($email) {
    $endPosition = strpos($email, '@') - 1;
    return substr_replace($email, '***', 1, $endPosition);
}

function generateSalt_e($length) {
        	$random = "";
        	srand((double) microtime() * 1000000);
        	$data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        	$data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        	$data .= "0FGH45OP89";
        	for ($i = 0; $i < $length; $i++) {
        		$random .= substr($data, (rand() % (strlen($data))), 1);
        	}
        	return $random;
}

function autoGenerate(){
    $last = PackageModel::selectRaw('RIGHT(id, 3) as kode')->orderBy('id', 'DESC')->first();
    if ($last) {
        $kode = intval($last->kode) + 1;
    } else {
        $kode = 1;
    }
    $kodemax = str_pad($kode, 5, '0', STR_PAD_LEFT);
    return $kodemax;
}

function autoFGenerate(){
    $last = FixedDepartures::selectRaw('RIGHT(id, 3) as kode')->orderBy('id', 'DESC')->first();
    if ($last) {
        $kode = intval($last->kode) + 1;
    } else {
        $kode = 1;
    }
    $kodemax = str_pad($kode, 5, '0', STR_PAD_LEFT);
    return $kodemax;
}
        
function getRealIP() {
    $ip = $_SERVER["REMOTE_ADDR"];
    if (filter_var(@$_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    }
    if (filter_var(@$_SERVER['HTTP_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if ($ip == '::1') {
        $ip = '127.0.0.1';
    }

    return $ip;
}

function displayRating(float $val) {
    $result = '';
    for ($i = 0; $i < intval($val); $i++) {
        $result .= '<i class="la la-star"></i>';
    }
    if (fmod($val, 1) == 0.5) {
        $i++;
        $result .= '<i class="las la-star-half-alt"></i>';
    }
    for ($k = 0; $k < 5 - $i; $k++) {
        $result .= '<i class="lar la-star"></i>';
    }
    return $result;
}

function generateAccountNumber() {
    $accountNumber = gs('account_no_prefix');
    $uniqueId      = substr(hexdec(uniqid()), -2);
    $accountNumber .= date('ydis') . rand(11, 99) . $uniqueId;
    $suffix = getNumber(gs('account_no_length') - strlen($accountNumber));
    $accountNumber .= $suffix;

    return $accountNumber;
}

function getImage($image, $size = null, $avatar = false) {
    $clean = '';
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    }
    if ($size) {
        return route('placeholder.image', $size);
    }

    if ($avatar) {
        return asset('assets/images/avatar.png');
    }
    return asset('assets/images/default.png');
}

function getTrx($length = 12) {
    $characters       = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getAmount($amount, $length = 2) {
    $amount = round($amount ?? 0, $length);
    return $amount + 0;
}

function showAmount($amount, $decimal = 2, $separate = true, $exceptZeros = false) {
    $separator = '';
    if ($separate) {
        $separator = ',';
    }
    $printAmount = number_format($amount, $decimal, '.', $separator);
    if ($exceptZeros) {
        $exp = explode('.', $printAmount);
        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        } else {
            $printAmount = rtrim($printAmount, '0');
        }
    }
    return $printAmount;
}

function slug($string) {
    return Illuminate\Support\Str::slug($string);
}

function verificationCode($length) {
    if ($length == 0) {
        return 0;
    }

    $min = pow(10, $length - 1);
    $max = (int) ($min - 1) . '9';
    return random_int($min, $max);
}

function getNumber($length = 8) {
    $characters       = '1234567890';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function systemDetails() {
    $system['name']          = 'viserbank';
    $system['version']       = '2.3';
    $system['build_version'] = '4.4.7';
    return $system;
}


function send_mobile_otp($mobileno, $message, $templateid){
    $senderid = "PHINTR";
    $entityid = '1701177631539549702';
    $accessToken        = "Q07SCD6AR5IO053";
    $accessTokenKey     = "TDP9SVi08[F&LQ3*a[wtfEHcNJX*gK)-";
    $requestFor          = "send-sms";
    $expire             = strtotime("+1 minute");
    $timeKey = md5($requestFor."sms@rits-v1.0".$expire);
    $timeAccessTokenKey = md5($accessToken.$timeKey);
    $signature = md5($timeAccessTokenKey.$accessTokenKey);
    
    $requestParams = [
        'accessToken'           => $accessToken,
        'expire'                => $expire,
        'authSignature'         => $signature,
        'route'                 => "transactional",
        'smsHeader'             => $senderid,
        'messageContent'        => $message,
        'recipients'            => [$mobileno],
        'contentType'           => "text",
        'entityId'              => $entityid,
        'templateId'            => $templateid,
        'removeDuplicateNumbers'=> "1",
        'webHookId'             => "0CTDZW98"
    ];

    // Log request data before sending
    $logData = [
        'mobile_no' => $mobileno,
        'message' => $message,
        'template_id' => $templateid,
        'request_params' => json_encode($requestParams),
        'api_url' => 'https://bulksmsprovider.infinitylogical.com/api/sms/v1.0/send-sms',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://bulksmsprovider.infinitylogical.com/api/sms/v1.0/send-sms',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
        ],
    ]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestParams));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    curl_close($curl);
    
    // Update log data with response
    $logData['response'] = $response;
    $logData['http_code'] = $httpCode;
    $logData['curl_error'] = $err;
    $logData['status'] = 'failed';
    
    if ($err) {
        error_log("cURL Error: " . $err);
        $logData['error_message'] = $err;
        
        // Save to database
        DB::table('sms_logs')->insert($logData);
        return false;
    }
    
    // Parse response
    $responseData = json_decode($response, true);
    
    if($responseData) {
        $logData['response_status'] = $responseData['status'] ?? 'unknown';
        $logData['response_message'] = $responseData['message'] ?? '';
        $logData['response_timestamp'] = $responseData['timestamp'] ?? '';
        $logData['http_status_code'] = $responseData['httpStatusCode'] ?? '';
        $logData['api_version'] = $responseData['apiVersion'] ?? '';
        
        // Check if SMS was sent successfully
        if(isset($responseData['status']) && $responseData['status'] == 'success') {
            $logData['status'] = 'success';
        } elseif(isset($responseData['status']) && $responseData['status'] == 'failed') {
            $logData['status'] = 'failed';
            $logData['error_message'] = $responseData['message'] ?? 'Unknown error';
        }
    }
    
    // Save to database
    DB::table('sms_logs')->insert($logData);
    
    return $response;
}


function get_adults_cal($package_price_type,$adults_count,$package_id){
     $query = DB::table('packages')->select('*')->where('status', 1)->where('id', $package_id)->first();
     $cal_query = DB::table('package_percentage')->select('*')->where('id', 1)->first();
     $result = '';
     if($package_price_type == 'Standard'){
        if($adults_count == 1){
        $result = $query->s_individual;
        }elseif($adults_count == 2){
        $total = $query->s_individual;
        $sub_result = $total * ($cal_query->second_person_percentage/100);
        $result = $sub_result * 2;
        }elseif($adults_count == 3){
        $total = $query->s_individual;
        $sub_result = $total * ($cal_query->third_person_percentage/100);
        $result = $sub_result * 3;
        }elseif($adults_count == 4){
            $total = $query->s_individual;
        $sub_result = $total * ($cal_query->four_person_percentage/100);
        $result = $sub_result * 4;
        }elseif($adults_count == 5){
            $total = $query->s_individual;
        $sub_result = $total * ($cal_query->five_person_percentage/100);
        $result = $sub_result * 5;
        }elseif($adults_count == 6){
        $total = $query->s_individual;
        $sub_result = $total * ($cal_query->six_person_percentage/100);
        $result = $sub_result * 6;
        }
     }elseif($package_price_type == 'Deluxe'){
         if($adults_count == 1){
        $result = $query->d_individual;
        }elseif($adults_count == 2){
        $total = $query->d_individual;
        $sub_result = $total * ($cal_query->second_person_percentage/100);
        $result = $sub_result * 2;
        }elseif($adults_count == 3){
        $total = $query->d_individual;
        $sub_result = $total * ($cal_query->third_person_percentage/100);
        $result = $sub_result * 3;
        }elseif($adults_count == 4){
        $total = $query->d_individual;
        $sub_result = $total * ($cal_query->four_person_percentage/100);
        $result = $sub_result * 4;
        }elseif($adults_count == 5){
            $total = $query->d_individual;
        $sub_result = $total * ($cal_query->five_person_percentage/100);
        $result = $sub_result * 5;
        }elseif($adults_count == 6){
        $total = $query->d_individual;
        $sub_result = $total * ($cal_query->six_person_percentage/100);
        $result = $sub_result * 6;
        } 
     }else{
          if($adults_count == 1){
        $result = $query->p_individual;
        }elseif($adults_count == 2){
        $total = $query->p_individual;
        $sub_result = $total * ($cal_query->second_person_percentage/100);
        $result = $sub_result * 2;
        }elseif($adults_count == 3){
        $total = $query->p_individual;
        $sub_result = $total * ($cal_query->third_person_percentage/100);
        $result = $sub_result * 3;
        }elseif($adults_count == 4){
        $total = $query->p_individual;
        $sub_result = $total * ($cal_query->four_person_percentage/100);
        $result = $sub_result * 4;
        }elseif($adults_count == 5){
        $total = $query->p_individual;
        $sub_result = $total * ($cal_query->five_person_percentage/100);
        $result = $sub_result * 5;
        }elseif($adults_count == 6){
        $total = $query->p_individual;
        $sub_result = $total * ($cal_query->six_person_percentage/100);
        $result = $sub_result * 6;
        }
     }
     return $result;  
 }
 
 
function APIResponse($status, $message, $result = ""){
    $data['status'] = $status;
    $data['message'] = $message;
    if ($result != "") {
        if (isset($result)) {
            $data['result'] = $result;
        }
    }
    return $data;
}

 function get_gst_cal($total_price){
    $gstRate = 0.05;
    $amount = $total_price;
    $gstAmount = ($amount * $gstRate);
    $totalAmount = $amount + $gstAmount;
    $new_value = ltrim(rtrim($totalAmount, '.0'), '0');
    return ceil($totalAmount);  
}

function get_images_package($package_id) {
    $get_package_image = PackagesImagesModel::select('*')
        ->where('package_id', $package_id)
        ->orderBy('id', 'desc')
        ->get();
    return $get_package_image;
}


    function calculateCutoff($amount, $percentage) {
        return round(($percentage / 100) * $amount);
    }
    
   function booking_fees($amount, $percentage){
    return ($percentage / 100) * $amount;
   }
   
   function get_itinerary($package_id){
      $get_inineraty_data = ItineraryModel::select('*')->where('package_id', $package_id)->orderBy('id', 'desc')->get();
    return $get_inineraty_data;
   }
   
   
   



  
/**
 * Calculate Complete Tour Cost
 * Simple function with all calculations
 */
if (!function_exists('calculateTourCost')) {
    function calculateTourCost($data) {
        // Extract values with defaults
        $roomCost = $data['room_cost'] ?? 0;
        $rooms = $data['rooms'] ?? 1;
        $nights = $data['nights'] ?? 1;
        $extraBedCost = $data['extra_bed_cost'] ?? 0;
        $extraBeds = $data['extra_beds'] ?? 0;
        
        $vehicleCost = $data['vehicle_cost'] ?? 0;
        $driverBatta = $data['driver_batta'] ?? 0;
        $days = $data['days'] ?? ($nights * 2); // 1 Night = 2 Days
        
        $attractionCost = $data['attraction_cost'] ?? 0;
        $persons = $data['persons'] ?? 1;
        
        $margin = 22; // 40% margin
        $discount = 0;
        
        // 1. Hotel Cost: (Room cost × Rooms × Nights) + (Extra bed cost × Extra beds × Nights)
        $hotelCost = ($roomCost * $rooms * $nights) + ($extraBedCost * $extraBeds * $nights);
        
        // 2. Vehicle Cost: (Vehicle cost × Days) + (Driver batta × Days)
        $vehicleTotal = ($vehicleCost + $driverBatta) * $days;
        
        // 3. Attractions Cost: Attraction cost × Persons
        $attractionsTotal = $attractionCost * $persons;
        
        // 4. Total Tour Cost
        $totalCost = $hotelCost + $vehicleTotal + $attractionsTotal;
        
        // 5. Apply Discount (only on Hotel + Vehicle)
        $discountableAmount = $hotelCost + $vehicleTotal;
        $discountAmount = $discountableAmount * ($discount / 100);
        $costAfterDiscount = ($discountableAmount - $discountAmount) + $attractionsTotal;
        
        // 6. Add Margin
        $marginAmount = $costAfterDiscount * ($margin / 100);
        $costWithMargin = $costAfterDiscount + $marginAmount;
        
        // 7. Add GST (5%)
        $gstAmount = $costWithMargin * 0.05;
        $finalCost = $costWithMargin;
        $withoutattractionsTotal = $finalCost - $attractionsTotal;
        // Return simplified result
        return [
            'hotel_cost' => $hotelCost,
            'vehicle_cost' => $vehicleTotal,
            'attractions_cost' => $attractionsTotal,
            'with_out_attractions_cost' => $withoutattractionsTotal,
            'subtotal' => $totalCost,
            'discount' => $discountAmount,
            'margin' => $marginAmount,
            'gst' => $gstAmount,
            'total' => $finalCost,
            'per_person' => $finalCost / $persons
        ];
    }
}


if (!function_exists('InternationalcalculateTourCost')) {
    function InternationalcalculateTourCost($data) {
        // Extract values with defaults
        $roomCost = $data['room_cost'] ?? 0;
        $rooms = $data['rooms'] ?? 1;
        $nights = $data['nights'] ?? 1;
        $extraBedCost = $data['extra_bed_cost'] ?? 0;
        $extraBeds = $data['extra_beds'] ?? 0;
        
        $vehicleCost = $data['vehicle_cost'] ?? 0;
        $driverBatta = $data['driver_batta'] ?? 0;
        $days = $data['days'] ?? ($nights * 2); // 1 Night = 2 Days
        
        $attractionCost = $data['attraction_cost'] ?? 0;
        $persons = $data['persons'] ?? 1;
        
        $margin = 22; // 40% margin
        $discount = 0;
        
        // 1. Hotel Cost: (Room cost × Rooms × Nights) + (Extra bed cost × Extra beds × Nights)
        $hotelCost = ($roomCost * $rooms * $nights) + ($extraBedCost * $extraBeds * $nights);
        
        // 2. Vehicle Cost: (Vehicle cost × Days) + (Driver batta × Days)
        $vehicleTotal = ($vehicleCost + $driverBatta) * $days;
        
        // 3. Attractions Cost: Attraction cost × Persons
        $attractionsTotal = $attractionCost * $persons;
        
        // 4. Total Tour Cost
        $totalCost = $hotelCost + $vehicleTotal + $attractionsTotal;
        
        // 5. Apply Discount (only on Hotel + Vehicle)
        $discountableAmount = $hotelCost + $vehicleTotal;
        $discountAmount = $discountableAmount * ($discount / 100);
        $costAfterDiscount = ($discountableAmount - $discountAmount) + $attractionsTotal;
        
        // 6. Add Margin
        $marginAmount = $costAfterDiscount * ($margin / 100);
        $costWithMargin = $costAfterDiscount + $marginAmount;
        
        // 7. Add GST (5%)
        $gstAmount = $costWithMargin * 0.05;
        $finalCost = $costWithMargin;
        $withoutattractionsTotal = $finalCost - $attractionsTotal;
        // Return simplified result
        return [
            'hotel_cost' => $hotelCost,
            'vehicle_cost' => $vehicleTotal,
            'attractions_cost' => $attractionsTotal,
            'with_out_attractions_cost' => $withoutattractionsTotal,
            'subtotal' => $totalCost,
            'discount' => $discountAmount,
            'margin' => $marginAmount,
            'gst' => $gstAmount,
            'total' => $finalCost,
            'per_person' => $finalCost / $persons
        ];
    }
}

/**
 * Calculate Hotel Cost Only
 */


/**
 * Calculate Vehicle Cost Only
 */
if (!function_exists('calculateVehicleCost')) {
    function calculateVehicleCost($vehicleCost, $driverBatta, $days) {
        return ($vehicleCost + $driverBatta) * $days;
    }
}

/**
 * Calculate Attractions Cost Only
 */
if (!function_exists('calculateAttractionCost')) {
    function calculateAttractionCost($costPerPerson, $persons) {
        return $costPerPerson * $persons;
    }
}


if (!function_exists('GetHotelinfo')) {

    function GetHotelinfo($package_id, $persons, $type)
    {
        // Determine star category based on type
        if ($type == 'Standard') {
            $starCategory = 3;
        } elseif ($type == 'Premium') {
            $starCategory = 4;
        } else {
            $starCategory = 5; // Luxury or default
        }
        
        // Calculate room count and extra bed count based on persons
        $roomCount = 1; // default
        $extraBedCount = 0;
        
        if ($persons == 1) {
            $roomCount = 1;
            $extraBedCount = 0;
            $HoneymoonPersons = 0;
        } elseif ($persons == 2) {
            $roomCount = 1;
            $extraBedCount = 0;
            $HoneymoonPersons = 1;
        } elseif ($persons == 3) {
            $roomCount = 1;
            $extraBedCount = 1;
            $HoneymoonPersons = 1;
        } elseif ($persons == 4) {
            $roomCount = 2;
            $extraBedCount = 0;
            $HoneymoonPersons = 2;
        } elseif ($persons == 5) {
            $roomCount = 2;
            $extraBedCount = 1;
            $HoneymoonPersons = 2;
        } elseif ($persons == 6) {
            $roomCount = 2;
            $extraBedCount = 2;
            $HoneymoonPersons = 3;
        } 
        
        // Get hotel info based on type
        $hotel = \DB::table('package_room_prices')
            ->select('rooms_cost', 'extra_bed_cost', 'attract_tickets', 'honeymoon_cost')
            ->where('star_category', $starCategory)
            ->where('package_id', $package_id)
            ->first();
        
        // Return default object if null
        if ($hotel === null) {
            \Log::warning("Hotel info not found for package_id: {$package_id}, star_category: {$starCategory}, persons: {$persons}, type: {$type}");
            
            return (object) [
                'rooms_cost' => 0,
                'extra_bed_cost' => 0,
                'attract_tickets' => 0,
                'room_count' => $roomCount,
                'extra_bed_count' => $extraBedCount,
                'total_room_cost' => 0,
                'total_extra_bed_cost' => 0,
                'total_hotel_cost' => 0,
                'total_honeymoon_cost' => 0,
                'star_category' => $starCategory,
                'type' => $type
            ];
        }
        
        // Calculate total costs
        $totalHoenymoonCost = $HoneymoonPersons*$hotel->honeymoon_cost;
        $totalRoomCost = $hotel->rooms_cost * $roomCount;
        $totalExtraBedCost = $hotel->extra_bed_cost * $extraBedCount;
        $totalHotelCost = $totalRoomCost + $totalExtraBedCost + $totalHoenymoonCost;
        
        // Add calculated fields to the returned object
        $hotel->room_count = $roomCount;
        $hotel->extra_bed_count = $extraBedCount;
        $hotel->total_room_cost = $totalRoomCost;
        $hotel->total_extra_bed_cost = $totalExtraBedCost;
        $hotel->total_hotel_cost = $totalHotelCost;
        $hotel->star_category = $starCategory;
        $hotel->type = $type;
        
        return $hotel;
    }
}



if (!function_exists('GetInternationalHotelinfo')) {

    function GetInternationalHotelinfo($package_id, $persons, $type)
    {
        // Determine star category based on type
        if ($type == 'Standard') {
            $starCategory = 3;
        } elseif ($type == 'Premium') {
            $starCategory = 4;
        } else {
            $starCategory = 5; // Luxury or default
        }
        
        // Calculate room count and extra bed count based on persons
        $roomCount = 1; // default
        $extraBedCount = 0;
        
        if ($persons == 1) {
            $roomCount = 1;
            $extraBedCount = 0;
            $HoneymoonPersons = 0;
        } elseif ($persons == 2) {
            $roomCount = 1;
            $extraBedCount = 0;
            $HoneymoonPersons = 1;
        } elseif ($persons == 3) {
            $roomCount = 1;
            $extraBedCount = 1;
            $HoneymoonPersons = 1;
        } elseif ($persons == 4) {
            $roomCount = 2;
            $extraBedCount = 0;
            $HoneymoonPersons = 2;
        } elseif ($persons == 5) {
            $roomCount = 2;
            $extraBedCount = 1;
            $HoneymoonPersons = 2;
        } elseif ($persons == 6) {
            $roomCount = 2;
            $extraBedCount = 2;
            $HoneymoonPersons = 3;
        } 
        
        // Get hotel info based on type
        $hotel = \DB::table('international_package_room_prices')
            ->select('rooms_cost', 'extra_bed_cost', 'attract_tickets', 'honeymoon_cost')
            ->where('star_category', $starCategory)
            ->where('package_id', $package_id)
            ->first();
        
        // Return default object if null
        if ($hotel === null) {
            \Log::warning("Hotel info not found for package_id: {$package_id}, star_category: {$starCategory}, persons: {$persons}, type: {$type}");
            
            return (object) [
                'rooms_cost' => 0,
                'extra_bed_cost' => 0,
                'attract_tickets' => 0,
                'room_count' => $roomCount,
                'extra_bed_count' => $extraBedCount,
                'total_room_cost' => 0,
                'total_extra_bed_cost' => 0,
                'total_hotel_cost' => 0,
                'total_honeymoon_cost' => 0,
                'star_category' => $starCategory,
                'type' => $type
            ];
        }
        
        // Calculate total costs
        $totalHoenymoonCost = $HoneymoonPersons*$hotel->honeymoon_cost;
        $totalRoomCost = $hotel->rooms_cost * $roomCount;
        $totalExtraBedCost = $hotel->extra_bed_cost * $extraBedCount;
        $totalHotelCost = $totalRoomCost + $totalExtraBedCost + $totalHoenymoonCost;
        
        // Add calculated fields to the returned object
        $hotel->room_count = $roomCount;
        $hotel->extra_bed_count = $extraBedCount;
        $hotel->total_room_cost = $totalRoomCost;
        $hotel->total_extra_bed_cost = $totalExtraBedCost;
        $hotel->total_hotel_cost = $totalHotelCost;
        $hotel->star_category = $starCategory;
        $hotel->type = $type;
        
        return $hotel;
    }
}



/**
 * Calculate Complete Tour Cost
 * Simple function with all calculations
 */

if (!function_exists('get_dynamic_tour_cal')) {
    function get_dynamic_tour_cal($type, $persons, $package_id, $days_count, $nights_count) {
        try {
            // ---------- STAR CATEGORY ----------
            // Standard = 2, Premium = 3, Luxury = 4
            $starCategory = ($type == 'Standard') ? 2 
                          : (($type == 'Premium')  ? 3 
                          : 4);

            // ---------- ROOM CONFIG ----------
            $roomConfig = [
                1 => ['rooms' => 1, 'extra_beds' => 0],
                2 => ['rooms' => 1, 'extra_beds' => 0],
                3 => ['rooms' => 1, 'extra_beds' => 1],
                4 => ['rooms' => 2, 'extra_beds' => 0],
                5 => ['rooms' => 2, 'extra_beds' => 2],
                6 => ['rooms' => 3, 'extra_beds' => 0],
            ];

            $roomCount     = $roomConfig[$persons]['rooms']      ?? 1;
            $extraBedCount = $roomConfig[$persons]['extra_beds'] ?? 0;

            // ---------- HOTEL PRICES ----------
            $hotel = \DB::table('dynamic_price')
                ->where('star_category', $starCategory)
                ->where('state_id', $package_id)
                ->first();

            // ---------- VEHICLE PRICES ----------
            $vehicle = \DB::table('dynamic_car_prices')
                ->where('pax', $persons)
                ->where('state_id', $package_id)
                ->first();

            // ---------- HOTEL COST ----------
            $hotelCost = 0;
            if ($hotel) {
                $hotelCost = ($hotel->rooms_cost * $roomCount * $nights_count)
                           + ($hotel->extra_bed_cost * $extraBedCount * $nights_count);
            }

            // ---------- VEHICLE COST ----------
            $vehicleCost = 0;
            if ($vehicle) {
                $vehicleCost = ((float) $vehicle->car_per_day + (float) $vehicle->driver_batta)
                             * $days_count;
            }

            // ---------- ATTRACTIONS COST ----------
            $attractionsCost = ($hotel ? $hotel->attract_tickets : 0) * $persons;

            // ---------- PER PERSON COSTS ----------
            $perPersonHotelCost       = $hotelCost / $persons;
            $perPersonVehicleCost     = $vehicleCost / $persons;
            $perPersonAttractionsCost = $attractionsCost / $persons;

            $perPersonSubtotal = $perPersonHotelCost
                               + $perPersonVehicleCost
                               + $perPersonAttractionsCost;

            // ---------- 22% MARGIN ----------
            $marginAmount         = $perPersonSubtotal * 0.22;
            $perPersonFinalAmount = $perPersonSubtotal + $marginAmount;

            return round($perPersonFinalAmount);

        } catch (\Exception $e) {
            \Log::error('Error in get_dynamic_tour_cal: ' . $e->getMessage());
            return 0;
        }
    }
}

if (!function_exists('get_tour_cal')) {
    function get_tour_cal($type, $persons, $package_id, $days_count, $nights_count) {
        try {
            // Get star category
            $starCategory = ($type == 'Standard') ? 3 : (($type == 'Premium') ? 4 : (($type == 'Budget') ? 2 : 5));
            
            // Get room configuration
            $roomConfig = [
                1 => ['rooms' => 1, 'extra_beds' => 0],
                2 => ['rooms' => 1, 'extra_beds' => 0],
                3 => ['rooms' => 1, 'extra_beds' => 1],
                4 => ['rooms' => 2, 'extra_beds' => 0],
                5 => ['rooms' => 2, 'extra_beds' => 1],
                6 => ['rooms' => 2, 'extra_beds' => 2],
            ];
            
            $roomCount = $roomConfig[$persons]['rooms'] ?? 1;
            $extraBedCount = $roomConfig[$persons]['extra_beds'] ?? 0;
            
            // Get hotel prices
            $hotel = \DB::table('package_room_prices')
                ->where('star_category', $starCategory)
                ->where('package_id', $package_id)
                ->first();
            
            // Get vehicle prices
            $vehicle = \DB::table('package_car_prices')
                ->where('pax', $persons)
                ->where('package_id', $package_id)
                ->first();
            
            // Calculate total hotel cost
            $hotelCost = 0;
            if ($hotel) {
                $hotelCost = ($hotel->rooms_cost * $roomCount * $nights_count) + 
                            ($hotel->extra_bed_cost * $extraBedCount * $nights_count);
            }
            
            // Calculate total vehicle cost
            $vehicleCost = 0;
            if ($vehicle) {
                $vehicleCost = ($vehicle->car_per_day + $vehicle->driver_batta) * $days_count;
            }
            
            // Calculate total attractions cost
            $attractionsCost = ($hotel ? $hotel->attract_tickets : 0) * $persons;
            
            // Calculate per person costs (Option 1 - Simple)
            $perPersonHotelCost = $hotelCost / $persons;
            $perPersonVehicleCost = $vehicleCost / $persons;
            $perPersonAttractionsCost = $attractionsCost / $persons;
            
            // Calculate total per person cost before margin
            $perPersonSubtotal = $perPersonHotelCost + $perPersonVehicleCost + $perPersonAttractionsCost;
            
            // Add 40% margin
            $marginAmount = $perPersonSubtotal * 0.22;
            $perPersonFinalAmount = $perPersonSubtotal + $marginAmount;
            
            return round($perPersonFinalAmount);
            
        } catch (\Exception $e) {
            \Log::error('Error in get_tour_cal: ' . $e->getMessage());
            return 0;
        }
    }
}

if (!function_exists('get_international_tour_cal')) {
    function get_international_tour_cal($type, $persons, $package_id, $days_count, $nights_count) {
        try {
            // Get star category
            $starCategory = ($type == 'Standard') ? 3 : (($type == 'Premium') ? 4 : (($type == 'Budget') ? 2 : 5));
            
            // Get room configuration
            $roomConfig = [
                1 => ['rooms' => 1, 'extra_beds' => 0],
                2 => ['rooms' => 1, 'extra_beds' => 0],
                3 => ['rooms' => 1, 'extra_beds' => 1],
                4 => ['rooms' => 2, 'extra_beds' => 0],
                5 => ['rooms' => 2, 'extra_beds' => 1],
                6 => ['rooms' => 2, 'extra_beds' => 2],
            ];
            
            $roomCount = $roomConfig[$persons]['rooms'] ?? 1;
            $extraBedCount = $roomConfig[$persons]['extra_beds'] ?? 0;
            
            // Get hotel prices
            $hotel = \DB::table('international_package_room_prices')
                ->where('star_category', $starCategory)
                ->where('package_id', $package_id)
                ->first();
            
            // Get vehicle prices
            $vehicle = \DB::table('international_package_car_prices')
                ->where('pax', $persons)
                ->where('package_id', $package_id)
                ->first();
            
            // Calculate total hotel cost
            $hotelCost = 0;
            if ($hotel) {
                $hotelCost = ($hotel->rooms_cost * $roomCount * $nights_count) + 
                            ($hotel->extra_bed_cost * $extraBedCount * $nights_count);
            }
            
            // Calculate total vehicle cost
            $vehicleCost = 0;
            if ($vehicle) {
                $vehicleCost = ($vehicle->car_per_day + $vehicle->driver_batta) * $days_count;
            }
            
            // Calculate total attractions cost
            $attractionsCost = ($hotel ? $hotel->attract_tickets : 0) * $persons;
            
            // Calculate per person costs (Option 1 - Simple)
            $perPersonHotelCost = $hotelCost / $persons;
            $perPersonVehicleCost = $vehicleCost / $persons;
            $perPersonAttractionsCost = $attractionsCost / $persons;
            
            // Calculate total per person cost before margin
            $perPersonSubtotal = $perPersonHotelCost + $perPersonVehicleCost + $perPersonAttractionsCost;
            
            // Add 40% margin
            $marginAmount = $perPersonSubtotal * 0.22;
            $perPersonFinalAmount = $perPersonSubtotal + $marginAmount;
            
            return round($perPersonFinalAmount);
            
        } catch (\Exception $e) {
            \Log::error('Error in get_tour_cal: ' . $e->getMessage());
            return 0;
        }
    }
}

 function returnResponse($statusCode, $success, $message)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'error' => [
                'code' => $statusCode,
                'message' => $message
            ],
            'result' => []
        ], $statusCode);
    }
?>
