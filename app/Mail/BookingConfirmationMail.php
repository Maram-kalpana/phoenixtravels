<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $invoice;
    public $attachmentFile; // Optional uploaded file

    /**
     * Create a new message instance.
     *
     * @param $booking
     * @param $invoice
     * @param UploadedFile|null $attachmentFile
     */
    public function __construct($booking, $invoice = null, UploadedFile $attachmentFile = null)
    {
        $this->booking = $booking;
        $this->invoice = $invoice;
        $this->attachmentFile = $attachmentFile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('email.booking_confirmation')
            ->subject('Booking Confirmed - ' . $this->booking->booking_number)
            ->with([
                'booking' => $this->booking,
                'invoice' => $this->invoice,
            ]);

        // Attach invoice PDF
        $this->attachInvoicePdf($email);

        // Attach any additional uploaded file
        $this->attachUploadedFile($email);

        return $email;
    }

    /**
     * Attach invoice PDF to email
     *
     * @param $email
     * @return void
     */
    protected function attachInvoicePdf($email)
    {
        if (!$this->invoice || !$this->invoice->pdf_path) {
            Log::info('No invoice PDF to attach', [
                'booking_number' => $this->booking->booking_number
            ]);
            return;
        }

        try {
            $pdfPath = $this->resolvePdfPath();
            
            if ($pdfPath && file_exists($pdfPath)) {
                $filename = $this->generateAttachmentFilename();
                
                $email->attach($pdfPath, [
                    'as' => $filename,
                    'mime' => 'application/pdf',
                ]);
                
                Log::info('Invoice PDF attached successfully', [
                    'booking_number' => $this->booking->booking_number,
                    'invoice_number' => $this->invoice->invoice_number,
                    'pdf_path' => $pdfPath,
                    'attachment_name' => $filename
                ]);
            } else {
                Log::warning('Invoice PDF file not found at any location', [
                    'booking_number' => $this->booking->booking_number,
                    'stored_path' => $this->invoice->pdf_path
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to attach invoice PDF: ' . $e->getMessage(), [
                'booking_number' => $this->booking->booking_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Attach uploaded file to email
     *
     * @param $email
     * @return void
     */
    protected function attachUploadedFile($email)
    {
        if (!$this->attachmentFile) {
            return;
        }

        try {
            $originalName = $this->attachmentFile->getClientOriginalName();
            $mimeType = $this->attachmentFile->getMimeType();
            
            $email->attach($this->attachmentFile->getRealPath(), [
                'as' => $originalName,
                'mime' => $mimeType,
            ]);
            
            Log::info('Additional file attached to email', [
                'booking_number' => $this->booking->booking_number,
                'filename' => $originalName,
                'mime_type' => $mimeType
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to attach uploaded file: ' . $e->getMessage(), [
                'booking_number' => $this->booking->booking_number,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Resolve the actual PDF file path from stored path
     *
     * @return string|null
     */
    protected function resolvePdfPath()
    {
        $storedPath = $this->invoice->pdf_path;

     

        // Check in public/storage (symbolic link)
        $publicPath = public_path($storedPath);
        if (file_exists($publicPath)) {
            return $publicPath;
        }

        // Check if it's an absolute path
        if (file_exists($storedPath)) {
            return $storedPath;
        }

        return null;
    }

    /**
     * Generate attachment filename
     *
     * @return string
     */
    protected function generateAttachmentFilename()
    {
        $invoiceNumber = $this->invoice->invoice_number ?? $this->booking->booking_number;
        return 'Invoice_' . $invoiceNumber . '.pdf';
    }
}