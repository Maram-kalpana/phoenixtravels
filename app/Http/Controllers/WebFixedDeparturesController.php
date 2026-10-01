<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToursModel;
use App\Models\PackageModel;
use App\Models\ItineraryModel;
use App\Models\ItineraryImages;
use App\Models\FixedDepartures;
use App\Models\Booking;
use App\Models\PackageFlight;
use App\Models\Invoice;
use App\Models\PackagesImagesModel;
use App\Models\DepartureDate;
use App\Services\InvoiceService;
use App\Mail\BookingConfirmationMail;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;  // <-- ADD THIS LINE
use Barryvdh\DomPDF\Facade\Pdf;      // <-- ALSO ADD THIS LINE

class WebFixedDeparturesController extends Controller
{
    protected $invoiceService;
    
    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }
    
    public function fixed_departures_itinerary(Request $request){
        $package_details = PackageModel::where('status', 1)->where('package_id', request()->segment(3))->first();
        $tour = PackageModel::where('status', 1)->where('package_id', request()->segment(3))->first();
        $package_itinerary = ItineraryModel::where('status', 1)->where('package_id', $tour->id)->get();
        $itinerary_image = ItineraryImages::where('type',request()->segment(2))->get();
        $package_flight = PackageFlight::where('package_id', $package_details->id)->where('type','domestic')->first();
        $package_images = PackagesImagesModel::where('package_id', $package_details->id)->get();
        //dd($tour);
        return view('frontend.fixed_departures_itinerary',compact('package_itinerary','package_images','tour','package_flight','package_details','itinerary_image'));
    }
    
    /**
     * Display the fixed departures page
     */
    public function main_fixed_departures(Request $request){
        if($request->ajax()) {
            return $this->filterTours($request);
        }
        
        $tour = ToursModel::where('status', 1)->where('slug', request()->segment(3))->first();
        
        if(!$tour) {
            abort(404);
        }
        
        $packages = FixedDepartures::where('status', 1)
                    ->where('tour_id', $tour->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(9);
                    
        if($tour->tour_category_id == 2){
            $type_name = 'International Packages';
            $tour_state = ToursModel::where('status', 1)->where('tour_category_id',2)->orderBy('tour_name', 'asc')->get();
        }elseif($tour->tour_category_id == 4){
            $type_name = 'Cruise Packages';
            $tour_state = ToursModel::where('status', 1)->where('tour_category_id',4)->orderBy('tour_name', 'asc')->get();
        }elseif($tour->tour_category_id == 3){
            $type_name = 'Honeymoon Packages';
            $tour_state = ToursModel::where('status', 1)->where('tour_category_id',3)->orderBy('tour_name', 'asc')->get();
        }else{
            $type_name = 'India Packages';
            $tour_state = ToursModel::where('status', 1)->where('tour_category_id',1)->orderBy('tour_name', 'asc')->get();
        }
        
        return view('frontend.main_fixed_departures', compact('tour', 'packages', 'type_name', 'tour_state'));
    }
    
    public function filterTours(Request $request){
        $currentTour = ToursModel::where('status', 1)->where('slug', request()->segment(2))->first();
        
        if(!$currentTour) {
            return response()->json(['success' => false, 'message' => 'Tour not found']);
        }
        
        $query = FixedDepartures::select('*')->where('status', 1);
        
        if($request->has('tour_types') && !empty($request->tour_types) && is_array($request->tour_types)) {
            $query->whereIn('tour_id', $request->tour_types);
        } else {
            $query->where('tour_id', $currentTour->id);
        }
        
        if($request->has('min_price') && $request->has('max_price') && 
           $request->min_price !== null && $request->max_price !== null &&
           $request->min_price != '' && $request->max_price != '') {
            
            $minPrice = (float)$request->min_price;
            $maxPrice = (float)$request->max_price;
            
            if($minPrice < $maxPrice) {
                $query->whereBetween('price', [$minPrice, $maxPrice]);
            }
        }
        
        if($request->has('search_tour_type') && !empty($request->search_tour_type)) {
            $searchTerm = trim($request->search_tour_type);
            if($searchTerm != '') {
                $tourIds = FixedDepartures::where('package_name', 'like', '%' . $searchTerm . '%')
                                    ->pluck('id')
                                    ->toArray();
                
                if(!empty($tourIds)) {
                    $query->whereIn('tour_id', $tourIds);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
        }
        
        $packages = $query->orderBy('created_at', 'desc')->paginate(9);
        
        if($request->ajax()) {
            $view = view('partials.fixed-departures-tour-item', compact('packages'))->render();
            $pagination = view('partials.pagination', ['packages' => $packages])->render();
            
            return response()->json([
                'success' => true,
                'data' => $view,
                'pagination' => $pagination,
                'total' => $packages->total()
            ]);
        }
        
        return redirect()->route('fixed-departures');
    }
    
    public function fixed_departures(Request $request, $slug = null)
    {
        if (!$slug) {
            $slug = request()->segment(2);
        }
        
        $tour = FixedDepartures::where('status', 1)
            ->where('slug', $slug)
            ->first();
            
        if (!$tour) {
            abort(404, 'Tour not found');
        }
        
        $fixed_departure = FixedDepartures::where('status', 1)
            ->where('id', $tour->id)
            ->first();
            
        if (!$fixed_departure) {
            abort(404, 'No fixed departure found for this tour');
        }
        
        $type_name = $this->getTourTypeName($tour->tour_category_id);
        $availableDates = $this->getAvailableDatesFromTable($fixed_departure);
        return view('frontend.fixed_departures', compact(
            'tour',
            'type_name',
            'fixed_departure',
            'availableDates'
        ));
    }
    
    /**
     * Get tour type name based on category ID
     */
    private function getTourTypeName($categoryId)
    {
        switch ($categoryId) {
            case 2:
                return 'International Packages';
            case 4:
                return 'Cruise Packages';
            case 3:
                return 'Honeymoon Packages';
            default:
                return 'India Packages';
        }
    }
    
    /**
     * Get available dates from departure_dates table with seat information
     */
    private function getAvailableDatesFromTable($fixedDeparture)
    {
        $dates = [];
        
        // Get all departure dates for this fixed departure that are active
        $departureDates = DepartureDate::where('fixed_departure_id', $fixedDeparture->id)
            ->where('status', 1) // Active dates only
            ->orderBy('departure_date', 'asc')
            ->get();
        
        foreach ($departureDates as $departureDate) {
            // Format the date properly
            if ($departureDate->departure_date instanceof \Carbon\Carbon) {
                $dateStr = $departureDate->departure_date->format('Y-m-d');
            } else {
                $dateStr = date('Y-m-d', strtotime($departureDate->departure_date));
            }
            
            $totalSeats = (int)$departureDate->available_seats;
            $price = (float)$departureDate->price;
            
            // Get bookings for this specific date
            $bookings = Booking::where('fixed_departure_id', $fixedDeparture->id)
                ->where('travel_date', $dateStr)
                ->where('status', '!=', 'cancelled')
                ->get();
            
            $bookedSeats = [];
            foreach ($bookings as $booking) {
                $seats = $booking->seat_numbers;
                if (is_string($seats)) {
                    $seats = explode(',', $seats);
                }
                if (is_array($seats)) {
                    $bookedSeats = array_merge($bookedSeats, array_map('intval', $seats));
                }
            }
            $bookedSeats = array_unique($bookedSeats);
            
            $seatsAvailable = $totalSeats - count($bookedSeats);
            
            // Generate seats data
            $seats = [];
            for ($i = 1; $i <= $totalSeats; $i++) {
                $seats[] = [
                    'seatNumber' => $i,
                    'status' => in_array($i, $bookedSeats) ? 'booked' : 'available'
                ];
            }
            
            $dates[$dateStr] = [
                'price' => $price,
                'seatsAvailable' => max(0, $seatsAvailable),
                'totalSeats' => $totalSeats,
                'seats' => $seats,
                'bookedSeats' => array_values($bookedSeats),
                'hasAvailability' => $seatsAvailable > 0,
                'departureDateId' => $departureDate->id
            ];
        }
        
        return $dates;
    }
    
    /**
     * Get detailed information for a specific date
     */
    private function getDateDetails($departureId, $dateStr, $price, $groupSize)
    {
        $bookings = Booking::where('fixed_departure_id', $departureId)
            ->where('travel_date', $dateStr)
            ->where('status', '!=', 'cancelled')
            ->get();
            
        $bookedSeats = [];
        foreach ($bookings as $booking) {
            $seats = $booking->seat_numbers;
            if (is_string($seats)) {
                $seats = explode(',', $seats);
            }
            if (is_array($seats)) {
                $bookedSeats = array_merge($bookedSeats, array_map('intval', $seats));
            }
        }
        $bookedSeats = array_unique($bookedSeats);
        
        $seatsData = [];
        for ($i = 1; $i <= $groupSize; $i++) {
            $seatsData[] = [
                'seatNumber' => $i,
                'status' => in_array($i, $bookedSeats) ? 'booked' : 'available'
            ];
        }
        
        return [
            'price' => $price,
            'seatsAvailable' => max(0, $groupSize - count($bookedSeats)),
            'totalSeats' => $groupSize,
            'seats' => $seatsData,
            'bookedSeats' => array_values($bookedSeats),
        ];
    }
    
    /**
     * AJAX: Get date information
     */
    public function getDateInfo(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'departure_id' => 'required|exists:fixed_departures,id'
            ]);
            
            $fixedDeparture = FixedDepartures::findOrFail($request->departure_id);
            
            // Format the requested date
            $requestedDate = date('Y-m-d', strtotime($request->date));
            
            // First check if there's a specific departure date record
            $departureDate = DepartureDate::where('fixed_departure_id', $request->departure_id)
                ->whereDate('departure_date', $requestedDate)
                ->where('status', 1)
                ->first();
            
            if ($departureDate) {
                $price = (float)$departureDate->price;
                $groupSize = (int)$departureDate->available_seats;
            } else {
                // Fallback to fixed departure defaults
                $price = (float)($fixedDeparture->price ?? 0);
                $groupSize = (int)($fixedDeparture->group_size ?? 20);
            }
            
            $dateInfo = $this->getDateDetails(
                $request->departure_id,
                $requestedDate,
                $price,
                $groupSize
            );
            
            return response()->json([
                'success' => true,
                'data' => $dateInfo
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting date info: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load date information: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Helper function to check if invoice PDF exists
     */
    private function invoicePdfExists($invoice)
    {
        if (!$invoice || !$invoice->pdf_path) {
            Log::warning('Invoice PDF check failed - no invoice or no pdf_path', [
                'invoice_id' => $invoice->id ?? null,
                'has_invoice' => isset($invoice),
                'has_pdf_path' => isset($invoice) && !empty($invoice->pdf_path)
            ]);
            return false;
        }
        
        // Check in public path
        $publicPath = public_path($invoice->pdf_path);
        if (file_exists($publicPath)) {
            Log::info('Invoice PDF found in public path', [
                'invoice_number' => $invoice->invoice_number,
                'path' => $publicPath,
                'size' => filesize($publicPath)
            ]);
            return true;
        }
        
        // Check in storage path as fallback
        $storagePath = storage_path('app/public/' . $invoice->pdf_path);
        if (file_exists($storagePath)) {
            Log::info('Invoice PDF found in storage path', [
                'invoice_number' => $invoice->invoice_number,
                'path' => $storagePath,
                'size' => filesize($storagePath)
            ]);
            return true;
        }
        
        Log::warning('Invoice PDF not found in any location', [
            'invoice_number' => $invoice->invoice_number,
            'expected_public_path' => $publicPath,
            'expected_storage_path' => $storagePath,
            'stored_pdf_path' => $invoice->pdf_path
        ]);
        
        return false;
    }
    
    /**
     * Create a new booking
     */
    public function createBooking(Request $request)
    {
        try {
            $validated = $request->validate([
                'fixed_departure_id' => 'required|exists:fixed_departures,id',
                'travel_date' => 'required|date',
                'seat_numbers' => 'required|string',
                'total_seats' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0',
                'price_per_seat' => 'required|numeric|min:0',
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|min:10|max:15',
            ]);
            
            // Check seat availability
            $seatNumbers = array_map('intval', explode(',', $request->seat_numbers));
            $travelDate = date('Y-m-d', strtotime($request->travel_date));
            
            // Get the departure date record for seat validation
            $departureDate = DepartureDate::where('fixed_departure_id', $request->fixed_departure_id)
                ->whereDate('departure_date', $travelDate)
                ->where('status', 1)
                ->first();
            
            $maxSeats = $departureDate ? (int)$departureDate->available_seats : 20;
            
            // Validate seat numbers are within range
            foreach ($seatNumbers as $seatNum) {
                if ($seatNum < 1 || $seatNum > $maxSeats) {
                    return response()->json([
                        'success' => false,
                        'message' => "Seat number {$seatNum} is invalid. Valid range: 1-{$maxSeats}"
                    ], 422);
                }
            }
            
            $existingBookings = Booking::where('fixed_departure_id', $request->fixed_departure_id)
                ->where('travel_date', $travelDate)
                ->where('status', '!=', 'cancelled')->where('status', '!=', 'pending')
                ->get();
                
            $bookedSeats = [];
            foreach ($existingBookings as $booking) {
                $seats = $booking->seat_numbers;
                if (is_string($seats)) {
                    $seats = explode(',', $seats);
                }
                if (is_array($seats)) {
                    $bookedSeats = array_merge($bookedSeats, array_map('intval', $seats));
                }
            }
            
            $conflictingSeats = array_intersect($seatNumbers, $bookedSeats);
            if (!empty($conflictingSeats)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some seats are already booked: ' . implode(', ', $conflictingSeats)
                ], 422);
            }
            
            // Check if total booked seats exceed available seats
            $totalBookedAfterThis = count($bookedSeats) + count($seatNumbers);
            if ($totalBookedAfterThis > $maxSeats) {
                return response()->json([
                    'success' => false,
                    'message' => "Not enough seats available. Only " . ($maxSeats - count($bookedSeats)) . " seats remaining."
                ], 422);
            }
            
            $razorpayKey = config('services.razorpay_live.key');
            $razorpaySecret = config('services.razorpay_live.secret');
            
            $bookingStatus = 'confirmed';
            $paymentStatus = 'pending';
            
            if (!empty($razorpayKey) && !empty($razorpaySecret)) {
                $bookingStatus = 'pending';
            }
            
            // Create booking
            $booking = Booking::create([
                'fixed_departure_id' => $request->fixed_departure_id,
                'travel_date' => $travelDate,
                'seat_numbers' => $request->seat_numbers,
                'total_seats' => $request->total_seats,
                'total_amount' => $request->total_amount,
                'price_per_seat' => $request->price_per_seat,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $bookingStatus,
                'payment_status' => $paymentStatus,
                'payment_method' => empty($razorpayKey) ? 'manual' : null,
            ]);
            
            Log::info('Booking created successfully', [
                'booking_number' => $booking->booking_number,
                'email' => $booking->email,
                'departure_date' => $travelDate,
            ]);
            
            // Generate invoice FIRST before sending emails
            $invoice = null;
            try {
                $invoice = $this->invoiceService->generateInvoice($booking);
                Log::info('Invoice generated for new booking', [
                    'invoice_number' => $invoice->invoice_number,
                    'booking_number' => $booking->booking_number,
                    'invoice_path' => $invoice->pdf_path ?? 'No path'
                ]);
            } catch (\Exception $e) {
                Log::error('Invoice generation failed during booking: ' . $e->getMessage(), [
                    'booking_number' => $booking->booking_number,
                    'trace' => $e->getTraceAsString()
                ]);
            }
            
            // Send confirmation email ONLY for non-Razorpay bookings (direct confirmations)
            if (empty($razorpayKey)) {
                // For direct/manual bookings, send confirmation email immediately
                if ($invoice && $invoice->pdf_path && $this->invoicePdfExists($invoice)) {
                    try {
                        Mail::to($booking->email)->send(new BookingConfirmationMail($booking, $invoice));
                        Log::info('Booking confirmation email sent with invoice (direct booking)', [
                            'booking_number' => $booking->booking_number,
                            'email' => $booking->email,
                            'invoice_path' => $invoice->pdf_path
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send confirmation email with invoice (direct booking): ' . $e->getMessage(), [
                            'booking_number' => $booking->booking_number,
                            'trace' => $e->getTraceAsString()
                        ]);
                        // Fallback to simple email
                        try {
                            $this->sendSimpleConfirmationEmail($booking);
                        } catch (\Exception $fallbackError) {
                            Log::error('Failed to send fallback email (direct booking): ' . $fallbackError->getMessage());
                        }
                    }
                } else {
                    // No invoice or PDF doesn't exist, send simple confirmation
                    try {
                        $this->sendSimpleConfirmationEmail($booking);
                        Log::info('Simple confirmation email sent (direct booking)', [
                            'booking_number' => $booking->booking_number,
                            'reason' => !$invoice ? 'no invoice' : 'pdf not found'
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send simple confirmation (direct booking): ' . $e->getMessage());
                    }
                }
            } else {
                // For Razorpay bookings, don't send confirmation yet - wait for payment
                Log::info('Razorpay booking created - confirmation email will be sent after payment', [
                    'booking_number' => $booking->booking_number
                ]);
            }
            
            // If Razorpay is configured, create order
            if (!empty($razorpayKey) && !empty($razorpaySecret)) {
                try {
                    $api = new Api($razorpayKey, $razorpaySecret);
                    
                    $orderData = [
                        'receipt' => $booking->booking_number,
                        'amount' => (int)($booking->total_amount * 100),
                        'currency' => 'INR',
                        'payment_capture' => 1,
                        'notes' => [
                            'booking_id' => $booking->id,
                            'booking_number' => $booking->booking_number,
                        ]
                    ];
                    
                    $razorpayOrder = $api->order->create($orderData);
                    $booking->update(['razorpay_order_id' => $razorpayOrder->id]);
                    
                    return response()->json([
                        'success' => true,
                        'booking_id' => $booking->id,
                        'booking_number' => $booking->booking_number,
                        'invoice_number' => $invoice ? $invoice->invoice_number : null,
                        'razorpay_order_id' => $razorpayOrder->id,
                        'amount' => (int)($booking->total_amount * 100),
                        'key' => $razorpayKey,
                        'name' => config('app.name', 'Phoenix Tours & Travels'),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Razorpay order creation failed: ' . $e->getMessage(), [
                        'booking_number' => $booking->booking_number,
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Booking confirmed successfully!',
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number,
                'invoice_number' => $invoice ? $invoice->invoice_number : null,
                'redirect_url' => route('booking.success', $booking->booking_number),
                'test_mode' => empty($razorpayKey),
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Booking creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token'])
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Verify Razorpay payment and send confirmation with invoice
     */
    /**
 * Verify Razorpay payment and send confirmation with invoice
 */
public function verifyPayment(Request $request)
{
    try {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'booking_id' => 'required|exists:bookings,id',
        ]);
        
        $api = new Api(
            config('services.razorpay_live.key'),
            config('services.razorpay_live.secret')
        );
        
        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        ];
        
        $api->utility->verifyPaymentSignature($attributes);
        
        // Update booking status to confirmed and paid
        $booking = Booking::findOrFail($request->booking_id);
        $booking->update([
            'status' => 'confirmed',
            'payment_id' => $request->razorpay_payment_id,
            'payment_status' => 'paid',
            'payment_method' => 'razorpay',
            'payment_date' => now(),
        ]);
        
        Log::info('Payment verified successfully', [
            'booking_number' => $booking->booking_number,
            'payment_id' => $request->razorpay_payment_id
        ]);
        
        // First update invoice status if exists
        $invoice = Invoice::where('booking_id', $booking->id)->first();
        if ($invoice) {
            $invoice->update(['status' => 'paid']);
            Log::info('Invoice status updated to paid', [
                'invoice_number' => $invoice->invoice_number
            ]);
        }
        
        // Try to send email with invoice, but don't fail if it doesn't work
        try {
            $emailSent = $this->sendPaymentConfirmationWithInvoice($booking);
            
            if (!$emailSent) {
                Log::warning('Failed to send payment confirmation with invoice, trying fallback', [
                    'booking_number' => $booking->booking_number
                ]);
                // Fallback to simple email
                $this->sendPaymentConfirmationSimpleEmail($booking);
            }
        } catch (\Exception $emailException) {
            Log::error('Email sending failed entirely: ' . $emailException->getMessage(), [
                'booking_number' => $booking->booking_number,
                'trace' => $emailException->getTraceAsString()
            ]);
            
            // Last resort - try simple email
            try {
                $this->sendPaymentConfirmationSimpleEmail($booking);
            } catch (\Exception $lastResortException) {
                Log::error('Even simple email failed: ' . $lastResortException->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Payment successful! Your booking is confirmed.',
            'redirect_url' => route('booking.success', $booking->booking_number)
        ]);
        
    } catch (\Exception $e) {
        Log::error('Payment verification failed: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        if (isset($booking)) {
            $booking->update([
                'status' => 'failed',
                'payment_id' => $request->razorpay_payment_id ?? null,
                'payment_status' => 'failed',
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Payment verification failed. Please contact support.'
        ], 400);
    }
}
    
    /**
     * Send payment confirmation email with invoice PDF
     * Returns true if email was sent successfully, false otherwise
     */
   /**
 * Send payment confirmation email with invoice PDF
 * Returns true if email was sent successfully, false otherwise
 */
private function sendPaymentConfirmationWithInvoice($booking)
{
    try {
        Log::info('Attempting to send payment confirmation email with invoice', [
            'booking_number' => $booking->booking_number
        ]);
        
        // Find existing invoice for this booking
        $invoice = Invoice::where('booking_id', $booking->id)->first();
        
        // If no invoice exists, try to generate one WITHOUT PDF first
        if (!$invoice) {
            try {
                Log::info('No invoice found for paid booking, generating new one', [
                    'booking_number' => $booking->booking_number
                ]);
                $invoice = $this->invoiceService->generateInvoice($booking);
                Log::info('New invoice generated for paid booking', [
                    'invoice_number' => $invoice->invoice_number ?? 'N/A',
                    'booking_number' => $booking->booking_number
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to generate invoice for paid booking: ' . $e->getMessage(), [
                    'booking_number' => $booking->booking_number,
                    'trace' => $e->getTraceAsString()
                ]);
                return false;
            }
        }
        
        // Update invoice status to paid
        if ($invoice) {
            $invoice->update(['status' => 'paid']);
            Log::info('Invoice status updated to paid', [
                'invoice_number' => $invoice->invoice_number
            ]);
            
            // Check if PDF exists
            $pdfExists = $this->invoicePdfExists($invoice);
            
            if ($pdfExists) {
                // PDF exists, send email with invoice
                try {
                    Mail::to($booking->email)
                        ->send(new BookingConfirmationMail($booking, $invoice));
                    
                    Log::info('Payment confirmation email sent successfully with invoice', [
                        'booking_number' => $booking->booking_number,
                        'email' => $booking->email,
                        'invoice_number' => $invoice->invoice_number,
                        'invoice_status' => $invoice->status,
                        'invoice_path' => $invoice->pdf_path
                    ]);
                    
                    return true;
                } catch (\Exception $e) {
                    Log::error('Failed to send email with invoice: ' . $e->getMessage(), [
                        'booking_number' => $booking->booking_number,
                        'trace' => $e->getTraceAsString()
                    ]);
                    return false;
                }
            } else {
                // PDF doesn't exist, try to generate it safely
                Log::warning('Invoice PDF not found, attempting to generate', [
                    'invoice_number' => $invoice->invoice_number,
                    'booking_number' => $booking->booking_number
                ]);
                
                try {
                    // Generate new PDF
                    $pdfPath = $this->generateInvoicePDFSafely($invoice, $booking);
                    
                    if ($pdfPath) {
                        // Update invoice with PDF path
                        $invoice->update(['pdf_path' => $pdfPath]);
                        $invoice->refresh();
                        
                        // Now send email with invoice
                        Mail::to($booking->email)
                            ->send(new BookingConfirmationMail($booking, $invoice));
                        
                        Log::info('Payment confirmation email sent with regenerated invoice', [
                            'booking_number' => $booking->booking_number,
                            'invoice_number' => $invoice->invoice_number,
                            'pdf_path' => $pdfPath
                        ]);
                        
                        return true;
                    } else {
                        Log::warning('Could not generate PDF, sending without attachment', [
                            'booking_number' => $booking->booking_number,
                            'invoice_number' => $invoice->invoice_number
                        ]);
                        return false;
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to generate PDF during payment confirmation: ' . $e->getMessage(), [
                        'booking_number' => $booking->booking_number,
                        'invoice_number' => $invoice->invoice_number,
                        'trace' => $e->getTraceAsString()
                    ]);
                    return false;
                }
            }
        }
        
        return false;
        
    } catch (\Exception $e) {
        Log::error('Failed to send payment confirmation with invoice: ' . $e->getMessage(), [
            'booking_number' => $booking->booking_number ?? 'N/A',
            'trace' => $e->getTraceAsString()
        ]);
        return false;
    }
}

/**
 * Generate invoice PDF safely with better error handling
 */
private function generateInvoicePDFSafely($invoice, $booking)
{
    try {
        // Create invoices directory if it doesn't exist
        $invoicesDirectory = public_path('invoices');
        if (!File::exists($invoicesDirectory)) {
            File::makeDirectory($invoicesDirectory, 0755, true);
        }
        
        // Create month-based subdirectory
        $monthDirectory = public_path('invoices/' . date('Y-m'));
        if (!File::exists($monthDirectory)) {
            File::makeDirectory($monthDirectory, 0755, true);
        }
        
        // Load PDF view
        $pdf = Pdf::loadView('invoices.template', [
            'invoice' => $invoice,
            'booking' => $booking,
        ]);
        
        // Configure PDF settings
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false, // Disable remote to prevent issues
            'isPhpEnabled' => true,
            'dpi' => 96, // Lower DPI for faster generation
            'defaultPaperSize' => 'a4',
            'chroot' => public_path(),
            'isFontSubsettingEnabled' => true,
        ]);
        
        // Generate filename
        $filename = $invoice->invoice_number . '.pdf';
        $fullPath = $monthDirectory . '/' . $filename;
        
        // Save PDF
        $pdfOutput = $pdf->output();
        file_put_contents($fullPath, $pdfOutput);
        
        // Verify file was created
        if (File::exists($fullPath) && File::size($fullPath) > 0) {
            $relativePath = 'invoices/' . date('Y-m') . '/' . $filename;
            
            Log::info('PDF generated safely', [
                'invoice_number' => $invoice->invoice_number,
                'relative_path' => $relativePath,
                'file_size' => File::size($fullPath)
            ]);
            
            return $relativePath;
        } else {
            Log::error('PDF file was not created properly', [
                'invoice_number' => $invoice->invoice_number,
                'full_path' => $fullPath
            ]);
            return null;
        }
        
    } catch (\Exception $e) {
        Log::error('Safe PDF generation failed: ' . $e->getMessage(), [
            'invoice_number' => $invoice->invoice_number ?? 'N/A',
            'booking_number' => $booking->booking_number ?? 'N/A',
            'trace' => $e->getTraceAsString()
        ]);
        return null;
    }
}
    
    /**
     * Send simple confirmation email without invoice
     */
    private function sendSimpleConfirmationEmail($booking)
    {
        $tour = null;
        try {
            $tour = ToursModel::find($booking->fixedDeparture->tour_id ?? null);
        } catch (\Exception $e) {
            Log::warning('Could not load tour details for simple email', [
                'booking_number' => $booking->booking_number,
                'error' => $e->getMessage()
            ]);
        }
        
        $data = [
            'booking' => $booking,
            'tour' => $tour,
        ];
        
        try {
            Mail::send('email.simple_booking_confirmation', $data, function($message) use ($booking) {
                $message->to($booking->email, $booking->name)
                    ->subject('Booking Confirmed - ' . $booking->booking_number . ' | Phoenix Tours & Travels')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            Log::info('Simple confirmation email sent successfully', [
                'booking_number' => $booking->booking_number,
                'email' => $booking->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send simple confirmation email', [
                'error' => $e->getMessage(),
                'booking_number' => $booking->booking_number
            ]);
            return false;
        }
    }
    
    /**
     * Send payment confirmation simple email (after successful payment)
     */
    private function sendPaymentConfirmationSimpleEmail($booking)
    {
        $tour = null;
        try {
            $tour = ToursModel::find($booking->fixedDeparture->tour_id ?? null);
        } catch (\Exception $e) {
            Log::warning('Could not load tour details for payment email', [
                'booking_number' => $booking->booking_number,
                'error' => $e->getMessage()
            ]);
        }
        
        $data = [
            'booking' => $booking,
            'tour' => $tour,
            'invoice' => Invoice::where('booking_id', $booking->id)->first()
        ];
        
        try {
            Mail::send('email.payment_confirmation', $data, function($message) use ($booking) {
                $message->to($booking->email, $booking->name)
                    ->subject('Payment Confirmed - ' . $booking->booking_number . ' | Phoenix Tours & Travels')
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            Log::info('Payment confirmation simple email sent successfully', [
                'booking_number' => $booking->booking_number,
                'email' => $booking->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send payment confirmation simple email', [
                'error' => $e->getMessage(),
                'booking_number' => $booking->booking_number
            ]);
            return false;
        }
    }
    
    /**
     * Display booking success page
     */
    public function bookingSuccess($bookingNumber)
    {
        $booking = Booking::where('booking_number', $bookingNumber)
            ->with('fixedDeparture')
            ->firstOrFail();
            
        $tour = ToursModel::find($booking->fixedDeparture->tour_id ?? null);
        $invoice = Invoice::where('booking_id', $booking->id)->first();
        
        return view('frontend.booking_success', compact('booking', 'tour', 'invoice'));
    }
}