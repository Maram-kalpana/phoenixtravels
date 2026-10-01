<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Mail\OTPMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class FixedItineraryController extends Controller
{
    public function sendOTP(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|min:10',
                'tour_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Check if tour exists
            try {
                $tour = \App\Models\PackageModel::findOrFail($request->tour_id);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid tour selected. Please try again.'
                ], 404);
            }

            // Generate 6-digit OTP
            $otp = rand(100000, 999999);
            
            // Store OTP in cache
            $otpData = [
                'otp' => $otp,
                'name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'tour_id' => $request->tour_id,
                'expires_at' => Carbon::now()->addMinutes(10)
            ];
            
            Cache::put('otp_' . $request->email, $otpData, 600);
            
            // Try to send email, but don't fail if email fails
            try {
                Mail::to($request->email)->send(new OTPMail($otp, $request->full_name));
            } catch (\Exception $e) {
                Log::error('Failed to send OTP email: ' . $e->getMessage());
                // Continue even if email fails - OTP is stored in cache
            }
            
            // Log OTP in debug mode
            if (config('app.debug')) {
                Log::info('OTP for ' . $request->email . ': ' . $otp);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your email',
                'debug_otp' => config('app.debug') ? $otp : null
            ]);
            
        } catch (\Exception $e) {
            Log::error('Send OTP Error: ' . $e->getMessage());
            Log::error('Send OTP Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }
    
    public function verifyOTP(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Verify OTP Request:', $request->all());
            
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string',
                'tour_id' => 'required',
                'otp' => 'required|string|size:6'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Verify OTP
            $cacheKey = 'otp_' . $request->email;
            $storedOtpData = Cache::get($cacheKey);
            
            Log::info('Cache Key: ' . $cacheKey);
            Log::info('Stored OTP Data:', ['data' => $storedOtpData]);
            
            if (!$storedOtpData) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP not found or expired. Please request a new OTP.'
                ], 422);
            }
            
            if ($storedOtpData['otp'] != $request->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP. Please try again.'
                ], 422);
            }
            
            // Check if OTP expired
            if (isset($storedOtpData['expires_at']) && Carbon::parse($storedOtpData['expires_at'])->isPast()) {
                Cache::forget($cacheKey);
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new one.'
                ], 422);
            }
            
            // Clear OTP from cache
            Cache::forget($cacheKey);
            
            // Generate a unique download token
            $downloadToken = Str::random(64);
            
            // Store download data with token
            $downloadData = [
                'tour_id' => $request->tour_id,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'expires_at' => Carbon::now()->addMinutes(30)
            ];
            
            Cache::put('download_' . $downloadToken, $downloadData, 1800);
            
            // Generate download URL using url() helper instead of route()
            $downloadUrl = url('/download-itinerary/' . $downloadToken);
            
            // Log download request (optional)
            try {
                if (class_exists('\App\Models\ItineraryDownload')) {
                    \App\Models\ItineraryDownload::create([
                        'full_name' => $request->full_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'tour_id' => $request->tour_id,
                        'downloaded_at' => Carbon::now()
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to log download: ' . $e->getMessage());
                // Don't fail if logging fails
            }
            
            Log::info('Download URL generated: ' . $downloadUrl);
            
            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully',
                'download_url' => $downloadUrl
            ]);
            
        } catch (\Exception $e) {
            Log::error('Verify OTP Error: ' . $e->getMessage());
            Log::error('Verify OTP Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Verification failed. Please try again. Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function downloadItinerary($token)
    {
        try {
            Log::info('Download attempt with token: ' . $token);
            
            // Verify download token
            $downloadData = Cache::get('download_' . $token);
            
            if (!$downloadData) {
                Log::error('Invalid download token: ' . $token);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired download link. Please verify again.'
                ], 404);
            }
            
            // Check if download link expired
            if (isset($downloadData['expires_at']) && Carbon::parse($downloadData['expires_at'])->isPast()) {
                Cache::forget('download_' . $token);
                return response()->json([
                    'success' => false,
                    'message' => 'Download link has expired. Please verify again.'
                ], 410);
            }
            
            // Get tour details
            $tour = \App\Models\PackageModel::findOrFail($downloadData['tour_id']);
            
            // Get itineraries
            $itineraries = \App\Models\ItineraryModel::where('package_id', $downloadData['tour_id'])->get();
            $itineraries_images = \App\Models\ItineraryImages::where('type','domestic')->get();
            // If no itineraries found, create a basic one
            if ($itineraries->isEmpty()) {
                $itineraries = collect([
                    (object)[
                        'day_number' => 1,
                        'title' => 'Tour Itinerary',
                        'description' => $tour->description ?? 'Enjoy your tour with Phoenix Tours & Travels!'
                    ]
                ]);
            }
            
            $data = [
                'tour' => $tour,
                'itineraries' => $itineraries,
                'itineraries_images' => $itineraries_images,
                'user_name' => $downloadData['full_name'],
                'download_date' => Carbon::now()->format('d M Y')
            ];
            
            // Generate PDF
            $pdf = Pdf::loadView('pdf.itinerary', $data);
            $pdf->setPaper('a4', 'portrait');
            
            // Generate filename
            $fileName = 'itinerary_' . $downloadData['tour_id'] . '_' . time() . '.pdf';
            
            // Clear download token after successful generation
            Cache::forget('download_' . $token);
            
            Log::info('PDF generated successfully: ' . $fileName);
            
            // Return PDF for download
            return $pdf->download($fileName);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Tour not found: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Tour not found.'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('PDF download error: ' . $e->getMessage());
            Log::error('PDF download trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to download itinerary. Error: ' . $e->getMessage()
            ], 500);
        }
    }
}