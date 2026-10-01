<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class InvoiceService
{
    /**
     * Generate invoice for a booking
     */
    public function generateInvoice(Booking $booking)
    {
        try {
            // Calculate invoice amounts
            $subtotal = $booking->price_per_seat * $booking->total_seats;
            $gstPercentage = 5.00;
            $gstAmount = $subtotal * ($gstPercentage / 100);
            $serviceFee = 50.00; // Fixed service fee
            
            // Check if invoice already exists
            $existingInvoice = Invoice::where('booking_id', $booking->id)->first();
            if ($existingInvoice) {
                // Only try to generate PDF if it doesn't exist
                if (!$existingInvoice->pdf_path || !File::exists(public_path($existingInvoice->pdf_path))) {
                    try {
                        $pdfPath = $this->generatePDF($existingInvoice, $booking);
                        $existingInvoice->update(['pdf_path' => $pdfPath]);
                    } catch (\Exception $e) {
                        Log::error('Failed to generate PDF for existing invoice: ' . $e->getMessage(), [
                            'invoice_number' => $existingInvoice->invoice_number,
                            'booking_number' => $booking->booking_number
                        ]);
                        // Don't throw - return invoice without PDF
                    }
                }
                return $existingInvoice;
            }
            
            // Create invoice record
            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'fixed_departure_id' => $booking->fixed_departure_id,
                'invoice_date' => now(),
                'due_date' => now()->addDays(7),
                'customer_name' => $booking->name,
                'customer_email' => $booking->email,
                'customer_phone' => $booking->phone,
                'subtotal' => $subtotal,
                'gst_percentage' => $gstPercentage,
                'gst_amount' => $gstAmount,
                'service_fee' => $serviceFee,
                'total_amount' => $booking->total_amount,
                'status' => $booking->payment_status === 'paid' ? 'paid' : 'pending',
            ]);
            
            // Generate PDF and save to public folder
            try {
                $pdfPath = $this->generatePDF($invoice, $booking);
                $invoice->update(['pdf_path' => $pdfPath]);
                
                Log::info('Invoice generated successfully with PDF', [
                    'invoice_number' => $invoice->invoice_number,
                    'booking_number' => $booking->booking_number,
                    'pdf_path' => $pdfPath,
                ]);
            } catch (\Exception $e) {
                Log::error('PDF generation failed for new invoice: ' . $e->getMessage(), [
                    'invoice_number' => $invoice->invoice_number,
                    'booking_number' => $booking->booking_number,
                    'trace' => $e->getTraceAsString()
                ]);
                // Continue without PDF - invoice record is created
            }
            
            return $invoice;
            
        } catch (\Exception $e) {
            Log::error('Invoice generation failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Generate PDF for invoice and save to public folder
     */
    private function generatePDF(Invoice $invoice, Booking $booking)
    {
        try {
            // Create invoices directory if it doesn't exist
            $invoicesDirectory = public_path('invoices');
            if (!File::exists($invoicesDirectory)) {
                File::makeDirectory($invoicesDirectory, 0755, true);
            }
            
            // Create month-based subdirectory for better organization
            $monthDirectory = public_path('invoices/' . date('Y-m'));
            if (!File::exists($monthDirectory)) {
                File::makeDirectory($monthDirectory, 0755, true);
            }
            
            // Load PDF view with error handling
            $viewData = [
                'invoice' => $invoice,
                'booking' => $booking,
            ];
            
            try {
                $pdf = Pdf::loadView('invoices.template', $viewData);
            } catch (\Exception $e) {
                Log::error('Failed to load PDF view: ' . $e->getMessage(), [
                    'invoice_number' => $invoice->invoice_number,
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
            
            // Configure PDF settings
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'dpi' => 150,
                'defaultPaperSize' => 'a4',
                'chroot' => public_path(),
            ]);
            
            // Generate filename
            $filename = $invoice->invoice_number . '.pdf';
            $fullPath = $monthDirectory . '/' . $filename;
            
            // Save PDF to public folder
            $pdfOutput = $pdf->output();
            file_put_contents($fullPath, $pdfOutput);
            
            // Return relative path from public folder
            $relativePath = 'invoices/' . date('Y-m') . '/' . $filename;
            
            Log::info('PDF saved successfully', [
                'full_path' => $fullPath,
                'relative_path' => $relativePath,
                'file_exists' => File::exists($fullPath),
                'file_size' => File::exists($fullPath) ? File::size($fullPath) : 0,
            ]);
            
            return $relativePath;
            
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage(), [
                'invoice_number' => $invoice->invoice_number,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Get full invoice PDF path
     */
    public function getInvoiceFullPath(Invoice $invoice)
    {
        if (!$invoice->pdf_path) {
            Log::error('Invoice has no PDF path', [
                'invoice_number' => $invoice->invoice_number
            ]);
            return null;
        }
        
        $path = public_path($invoice->pdf_path);
        
        if (!File::exists($path)) {
            Log::error('Invoice PDF not found', [
                'invoice_number' => $invoice->invoice_number,
                'path' => $path,
            ]);
            return null;
        }
        
        return $path;
    }
    
    /**
     * Get invoice PDF URL
     */
    public function getInvoiceUrl(Invoice $invoice)
    {
        if (!$invoice->pdf_path) {
            Log::warning('Cannot get invoice URL - no PDF path', [
                'invoice_number' => $invoice->invoice_number
            ]);
            return null;
        }
        
        $path = $invoice->pdf_path;
        
        if (!File::exists(public_path($path))) {
            Log::warning('Invoice PDF not found for URL', [
                'invoice_number' => $invoice->invoice_number,
                'path' => public_path($path)
            ]);
            return null;
        }
        
        return asset($path);
    }
    
    /**
     * Download invoice PDF
     */
    public function downloadInvoice(Invoice $invoice)
    {
        $fullPath = $this->getInvoiceFullPath($invoice);
        
        if (!$fullPath) {
            Log::error('Cannot download invoice - PDF not found', [
                'invoice_number' => $invoice->invoice_number
            ]);
            abort(404, 'Invoice PDF not found');
        }
        
        $filename = 'Invoice-' . $invoice->invoice_number . '.pdf';
        
        return response()->download($fullPath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    
    /**
     * Stream invoice PDF (view in browser)
     */
    public function streamInvoice(Invoice $invoice)
    {
        $fullPath = $this->getInvoiceFullPath($invoice);
        
        if (!$fullPath) {
            Log::error('Cannot stream invoice - PDF not found', [
                'invoice_number' => $invoice->invoice_number
            ]);
            abort(404, 'Invoice PDF not found');
        }
        
        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    
    /**
     * Delete invoice and PDF
     */
    public function deleteInvoice(Invoice $invoice)
    {
        try {
            // Delete PDF file
            if ($invoice->pdf_path) {
                $fullPath = public_path($invoice->pdf_path);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                    Log::info('Invoice PDF deleted', [
                        'invoice_number' => $invoice->invoice_number,
                        'path' => $fullPath,
                    ]);
                }
            }
            
            // Delete invoice record
            $invoice->delete();
            
            Log::info('Invoice record deleted', [
                'invoice_number' => $invoice->invoice_number,
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete invoice: ' . $e->getMessage(), [
                'invoice_number' => $invoice->invoice_number,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Regenerate PDF for existing invoice
     * This is only for manual regeneration, not during payment flow
     */
    public function regeneratePDF(Invoice $invoice)
    {
        try {
            // Delete old PDF if exists
            if ($invoice->pdf_path) {
                $oldPath = public_path($invoice->pdf_path);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                    Log::info('Old invoice PDF deleted for regeneration', [
                        'invoice_number' => $invoice->invoice_number,
                        'path' => $oldPath
                    ]);
                }
            }
            
            // Get booking data
            $booking = Booking::findOrFail($invoice->booking_id);
            
            // Generate new PDF
            $newPath = $this->generatePDF($invoice, $booking);
            
            // Update invoice with new path
            $invoice->update(['pdf_path' => $newPath]);
            
            // Refresh the model to get updated data
            $invoice->refresh();
            
            Log::info('Invoice PDF regenerated successfully', [
                'invoice_number' => $invoice->invoice_number,
                'new_path' => $newPath,
                'file_exists' => File::exists(public_path($newPath))
            ]);
            
            return $newPath;
            
        } catch (\Exception $e) {
            Log::error('Failed to regenerate invoice PDF: ' . $e->getMessage(), [
                'invoice_number' => $invoice->invoice_number,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    /**
     * Check if invoice PDF exists
     */
    public function pdfExists(Invoice $invoice)
    {
        if (!$invoice->pdf_path) {
            return false;
        }
        
        return File::exists(public_path($invoice->pdf_path));
    }
    
    /**
     * Get invoices directory size
     */
    public function getInvoicesDirectorySize()
    {
        $path = public_path('invoices');
        $size = 0;
        
        if (File::exists($path)) {
            $files = File::allFiles($path);
            foreach ($files as $file) {
                $size += $file->getSize();
            }
        }
        
        return $this->formatSize($size);
    }
    
    /**
     * Format file size
     */
    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    /**
     * Clean old invoices (older than specified days)
     */
    public function cleanOldInvoices($days = 365)
    {
        $cutoffDate = now()->subDays($days);
        $count = 0;
        
        // Get old invoices
        $oldInvoices = Invoice::where('created_at', '<', $cutoffDate)->get();
        
        foreach ($oldInvoices as $invoice) {
            try {
                $this->deleteInvoice($invoice);
                $count++;
            } catch (\Exception $e) {
                Log::error('Failed to clean old invoice: ' . $e->getMessage(), [
                    'invoice_number' => $invoice->invoice_number
                ]);
            }
        }
        
        Log::info('Old invoices cleaned', [
            'count' => $count,
            'older_than_days' => $days,
        ]);
        
        return $count;
    }
}