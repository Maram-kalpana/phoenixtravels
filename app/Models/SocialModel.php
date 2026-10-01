<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialModel extends Model
{
    use HasFactory;

    protected $table = 'social';
    protected $keyType = 'integer';
    protected $fillable = [
    'name',
    'code',
    'link',
    'status',
    'created_at','updated_at'];
}
