<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable= [
        'name',
        'image',
        'active'
    ];
    public  function products(){
        return $this->hasMany(Product::class,'category_id');
    }

}
