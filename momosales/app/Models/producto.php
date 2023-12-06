<?php

namespace App\Models;

use App\Models\marca;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'type_product',
        'status',
        'visibility',
        'gross_price',
        'disccount_price',
        'cost',
        'stock_status',
        'manage_stock',
        'stock_qty',
        'min_stock',
        'brand_id',
        'platform_id'
    ];
    function marca()
    {
        return $this->belongsTo(marca::class,'brand_id');
    }

    public function categorias()
    {
        return $this->belongsToMany(categoria_producto::class,'categoria_productos_pivs');
    }

    public function images(){
        return $this->morphMany(image::class,'model');
    }

    public function latestImage(){
        //recent image
        return $this->morphOne(image::class,'model')->latestOfMany();
    }

    public function getPhotoAttribute()
    {
        if(count($this->images)){
            return "storage/productos/" . $this->images->last()->file;
        }else{
            return 'storage/no-image.jpg'; 
        }
    }

}
