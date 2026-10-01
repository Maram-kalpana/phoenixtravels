<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductModel;
class CategoryModel extends Model{
    use HasFactory;
    protected $table = 'category';
    protected $keyType = 'integer';
    
    
    public function products(){
       return $this->hasMany(ProductModel::class, 'category_id');
    }
}


