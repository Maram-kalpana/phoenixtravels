<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternationalToursModel extends Model{
    
    use HasFactory;
    protected $table = 'international_tour';
    protected $keyType = 'integer';

}
