<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpModel extends Model
{
    use HasFactory;

    protected $table = 'smtp_settings';
    protected $keyType = 'integer';
    protected $fillable = [
    'protocol',
    'smtp_host',
    'smtp_port',
    'smtp_user',
    'smtp_pass','created_at','updated_at'];
}
