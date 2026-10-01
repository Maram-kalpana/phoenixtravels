<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSModel extends Model
{
    use HasFactory;
    protected $table = 'sms_general_settings';
    protected $keyType = 'integer';
    protected $fillable = [
    'sms_url',
    'api_key',
    'sender_id',
    'username',
    'created_at','updated_at'];
}
