<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'receipt',
        'amount',
        'currency',
        'status',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'notes' => 'array'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}