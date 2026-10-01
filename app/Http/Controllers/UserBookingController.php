<?php

namespace App\Http\Controllers;

use Validator, Auth, Log;
use App\Facades\ResponseHelper;
use App\Models\StoreModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserBookingController extends Controller
{
    public function index(Request $request)
    {
        return view('backend.user_booking.fixed_departures_list');
    }

    public function getFixedDeparturesData(Request $request)
    {
        try {
            $columns = [
                0 => 'id',
                1 => 'booking_number',
                2 => 'fixed_departure_id',
                3 => 'travel_date',
                4 => 'seat_numbers',
                5 => 'total_seats',
                6 => 'price_per_seat',
                7 => 'total_amount',
                8 => 'name',
                9 => 'email',
                10 => 'phone',
                11 => 'status',
                12 => 'payment_status',
                13 => 'payment_method',
                14 => 'payment_date',
            ];

            $totalData = DB::table('bookings')->count();
            $totalFiltered = $totalData;
            
            $limit = $request->input('length', 10);
            $start = $request->input('start', 0);
            $orderColumn = $columns[$request->input('order.0.column', 0)];
            $orderDir = $request->input('order.0.dir', 'desc');
            
            $query = DB::table('bookings');
            
            // Search handling
            if (!empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function($q) use ($search) {
                    $q->where('booking_number', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%");
                });
                $totalFiltered = $query->count();
            }
            
            $bookings = $query->orderBy($orderColumn, $orderDir)
                ->offset($start)
                ->limit($limit)
                ->get();
            
            $data = [];
            foreach ($bookings as $booking) {
                $data[] = [
                    'id' => $booking->id,
                    'booking_number' => '<strong>' . htmlspecialchars($booking->booking_number) . '</strong>',
                    'fixed_departure_id' => $booking->fixed_departure_id,
                    'travel_date' => $booking->travel_date ? date('d M Y', strtotime($booking->travel_date)) : '-',
                    'seat_numbers' => $this->formatSeatNumbers($booking->seat_numbers),
                    'total_seats' => $booking->total_seats,
                    'price_per_seat' => '₹' . number_format($booking->price_per_seat, 2),
                    'total_amount' => '<strong>₹' . number_format($booking->total_amount, 2) . '</strong>',
                    'name' => htmlspecialchars($booking->name ?? '-'),
                    'email' => $booking->email ?? '-',
                    'phone' => $booking->phone ?? '-',
                    'status' => $this->getStatusBadge($booking->status),
                    'payment_status' => $this->getPaymentBadge($booking->payment_status),
                    'payment_method' => $booking->payment_method ?? '-',
                    'payment_date' => $booking->payment_date ? date('d M Y h:i A', strtotime($booking->payment_date)) : '-',
                    'action' => $this->getActionButtons($booking->id)
                ];
            }
            
            $response = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('DataTables Error: ' . $e->getMessage());
            
            return response()->json([
                "draw" => intval($request->input('draw', 0)),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $e->getMessage()
            ], 200);
        }
    }

    // Get booking details for popup
    public function getBookingDetails($id)
    {
        try {
            
            $booking = DB::table('bookings')
            ->select('bookings.*', 'fixed_departures.package_name as fixed_departure_name')
            ->join('fixed_departures', 'fixed_departures.id', '=', 'bookings.fixed_departure_id')
            ->where('bookings.id', $id)
            ->first();
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching booking details'
            ]);
        }
    }

    // Export to CSV
    public function exportBookings(Request $request)
    {
        try {
            $bookings = DB::table('bookings')->orderBy('id', 'desc')->get();
            
            $filename = "fixed_departures_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($bookings) {
                $file = fopen('php://output', 'w');
                
                // Add CSV headers
                fputcsv($file, [
                    'ID', 'Booking Number', 'Fixed Departure ID', 'Travel Date', 
                    'Seat Numbers', 'Total Seats', 'Price Per Seat', 'Total Amount',
                    'Customer Name', 'Email', 'Phone', 'Status', 'Payment Status',
                    'Payment Method', 'Payment Date', 'Cancellation Reason'
                ]);
                
                // Add data rows
                foreach ($bookings as $booking) {
                    fputcsv($file, [
                        $booking->id,
                        $booking->booking_number,
                        $booking->fixed_departure_id,
                        $booking->travel_date,
                        $booking->seat_numbers,
                        $booking->total_seats,
                        $booking->price_per_seat,
                        $booking->total_amount,
                        $booking->name,
                        $booking->email,
                        $booking->phone,
                        $booking->status,
                        $booking->payment_status,
                        $booking->payment_method,
                        $booking->payment_date,
                        $booking->cancellation_reason
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Export Error: ' . $e->getMessage());
            return response()->json(['error' => 'Export failed'], 500);
        }
    }
    
    private function formatSeatNumbers($seatNumbers)
    {
        if (!$seatNumbers) return '-';
        $seats = explode(',', $seatNumbers);
        $html = '';
        foreach ($seats as $seat) {
            $html .= '<span class="badge bg-secondary me-1">' . trim($seat) . '</span>';
        }
        return $html;
    }
    
    private function getStatusBadge($status)
    {
        $badges = [
            'confirmed' => '<span class="badge bg-success">Confirmed</span>',
            'success' => '<span class="badge bg-success">Confirmed</span>',
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            'failed' => '<span class="badge bg-dark">Failed</span>',
        ];
        return $badges[$status] ?? '<span class="badge bg-secondary">' . ($status ?? 'N/A') . '</span>';
    }
    
    private function getPaymentBadge($paymentStatus)
    {
        $badges = [
            'paid' => '<span class="badge bg-success">Paid</span>',
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
            'refunded' => '<span class="badge bg-info">Refunded</span>',
        ];
        return $badges[$paymentStatus] ?? '<span class="badge bg-secondary">' . ($paymentStatus ?? 'N/A') . '</span>';
    }
    
    private function getActionButtons($id)
    {
        return '
            <button class="btn btn-sm btn-info view-booking" data-id="' . $id . '" title="View Details">
                <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-danger delete-booking" data-id="' . $id . '" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        ';
    }

    // Other existing methods
    public function holidays_list(Request $request){
        return view('backend.user_booking.holidays_list');
    }
    
    public function getHolidaysBooking(Request $request)
    {
        try {
            $columns = [
                0 => 'id',
                1 => 'booking_number',
                2 => 'fixed_departure_id',
                3 => 'travel_date',
                4 => 'seat_numbers',
                5 => 'total_seats',
                6 => 'price_per_seat',
                7 => 'total_amount',
                8 => 'name',
                9 => 'email',
                10 => 'phone',
                11 => 'status',
                12 => 'payment_status',
                13 => 'payment_method',
                14 => 'payment_date',
            ];

            $totalData = DB::table('bookings')->count();
            $totalFiltered = $totalData;
            
            $limit = $request->input('length', 10);
            $start = $request->input('start', 0);
            $orderColumn = $columns[$request->input('order.0.column', 0)];
            $orderDir = $request->input('order.0.dir', 'desc');
            
            $query = DB::table('pay_submit')->where('payment_status','success');
            
            // Search handling
            if (!empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $query->where(function($q) use ($search) {
                    $q->where('booking_id', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%")
                      ->orWhere('mail', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%");
                });
                $totalFiltered = $query->count();
            }
            
            $bookings = $query->orderBy($orderColumn, $orderDir)
                ->offset($start)
                ->limit($limit)
                ->get();
            
            $data = [];
            foreach ($bookings as $booking) {
                $data[] = [
                    'id' => $booking->id,
                    'booking_number' => '<strong>' . htmlspecialchars($booking->booking_id) . '</strong>',
                    'tour_name' => $booking->tour_name,
                    'travel_start_date' => $booking->from_date ? date('d M Y', strtotime($booking->from_date)) : '-',
                    'travel_end_date' => $booking->to_date ? date('d M Y', strtotime($booking->to_date)) : '-',
                    'total_amount' => '₹' . number_format($booking->total_amount, 2),
                    'name' => htmlspecialchars($booking->name ?? '-'),
                    'email' => $booking->mail ?? '-',
                    'phone' => $booking->phone ?? '-',
                    'status' => $this->getStatusBadge($booking->payment_status),
                    'payment_status' => $this->getPaymentBadge($booking->payment_status),
                    'payment_method' => $booking->razorpay_payment_id ?? '-',
                    'payment_date' => $booking->created_at ? date('d M Y h:i A', strtotime($booking->created_at)) : '-',
                    'action' => $this->getHolidaysActionButtons($booking->id)
                ];
            }
            
            $response = [
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            ];
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('DataTables Error: ' . $e->getMessage());
            
            return response()->json([
                "draw" => intval($request->input('draw', 0)),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $e->getMessage()
            ], 200);
        }
    }
    
    private function getHolidaysActionButtons($id){
        return '
            <button class="btn btn-sm btn-info view-booking" data-id="' . $id . '" title="View Details">
                <i class="fas fa-eye"></i>
            </button>';
    }
    
    // Get booking details for popup
    public function getHolidaysBookingDetails($id)
    {
        try {
            
            $booking = DB::table('pay_submit')->select('*')->where('id', $id)
            ->first();
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching booking details'
            ]);
        }
    }
    
    public function exportHolidaysBookings(Request $request)
    {
        try {
            $bookings = DB::table('pay_submit')->orderBy('id', 'desc')->get();
            
            $filename = "holidays_bookings_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($bookings) {
                $file = fopen('php://output', 'w');
                
                // Add CSV headers
                fputcsv($file, [
                    'ID', 'Booking Number', 'Tour Name', 'Travel Start Date', 
                    'Travel End Date', 'Total Amount',
                    'Customer Name', 'Email', 'Phone', 'Status', 'Payment Status',
                    'Payment Method', 'Payment Date', 'Cancellation Reason'
                ]);
                
                // Add data rows
                foreach ($bookings as $booking) {
                    fputcsv($file, [
                        $booking->id,
                        $booking->booking_id,
                        $booking->tour_name,
                        $booking->from_date,
                        $booking->to_date,
                        $booking->total_amount,
                        $booking->name,
                        $booking->mail,
                        $booking->phone,
                        $booking->created_at,
                        $booking->failure_reason
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Export Error: ' . $e->getMessage());
            return response()->json(['error' => 'Export failed'], 500);
        }
    }
    public function flight_list(Request $request){
        return view('backend.user_booking.flight_list');
    }
    
    public function hotel_list(Request $request){
        return view('backend.user_booking.hotel_list');
    }
    
    public function bus_list(Request $request){
        return view('backend.user_booking.bus_list');
    }
    
    public function car_list(Request $request){
        return view('backend.user_booking.car_list');
    }
    
    public function travel_insurance(Request $request){
        return view('backend.user_booking.travel_insurance');
    }
    
    public function updateBookingStatus(Request $request)
    {
        try {
            $id = $request->id;
            $status = $request->status;
            
            DB::table('bookings')
                ->where('id', $id)
                ->update(['status' => $status]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ]);
        }
    }
    
    public function destroyBooking($id)
    {
        try {
            DB::table('bookings')->where('id', $id)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Booking deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete booking'
            ]);
        }
    }
}