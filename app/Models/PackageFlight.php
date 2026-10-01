<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageFlight extends Model{
    
    use HasFactory;
    protected $table = 'package_flight';
    protected $keyType = 'integer';
    public $timestamps = false;

}
