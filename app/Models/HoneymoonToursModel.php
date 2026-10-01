<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoneymoonToursModel extends Model{
    
    use HasFactory;
    protected $table = 'honeymoon_tour';
    protected $keyType = 'integer';

}
