<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'booking_id',
        'fixed_departure_id',
        'invoice_date',
        'due_date',
        'customer_name',
        'customer_email',
        'customer_phone',
        'subtotal',
        'gst_percentage',
        'gst_amount',
        'service_fee',
        'total_amount',
        'status',
        'notes',
        'pdf_path',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = self::generateInvoiceNumber();
            }
            if (empty($invoice->invoice_date)) {
                $invoice->invoice_date = now();
            }
            if (empty($invoice->due_date)) {
                $invoice->due_date = now()->addDays(7);
            }
        });
    }

    /**
     * Generate unique invoice number
     */
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        $invoiceNumber = $prefix . $date . $random;

        while (self::where('invoice_number', $invoiceNumber)->exists()) {
            $random = strtoupper(substr(uniqid(), -4));
            $invoiceNumber = $prefix . $date . $random;
        }

        return $invoiceNumber;
    }

    /**
     * Relationships
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function fixedDeparture()
    {
        return $this->belongsTo(FixedDepartures::class);
    }
}