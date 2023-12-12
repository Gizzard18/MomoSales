<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $fillable = [
        'qty',
        'gross_price',
        'sale_price'
    ];
    function producto()
    {
        return $this->hasOne(producto::class,'producto_id');
    }
    function servicio()
    {
        return $this->belongsTo(servicio::class,'servicio_id');
    }
}
