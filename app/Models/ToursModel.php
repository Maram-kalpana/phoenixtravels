<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToursModel extends Model{
    
    use HasFactory;
    protected $table = 'tour';
    protected $keyType = 'integer';

}
