<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingModel extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $keyType = 'integer';
    protected $fillable = [
    'logo',
    'fav',
    'address',
    'about',
    'phone',
    'email',
    'company_name',
    'color',
    'created_at','updated_at'];
}
