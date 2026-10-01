<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    use HasFactory;
    protected $table = 'customer';
    protected $keyType = 'integer';
    protected $fillable = [
    'phone',
    'first_name',
    'last_name',
    'address',
    'profile_status',
    'status',
    'created_at','updated_at'];
}
