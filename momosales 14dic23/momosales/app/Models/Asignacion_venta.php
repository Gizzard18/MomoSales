<?php

namespace App\Models;

use App\Models\venta;
use App\Models\Empleado;
use App\Models\producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asignacion_venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'current_price',
        'comission',
        'iva'
    ];
    function product()
    {
        return $this->belongsTo(producto::class,'selected_item');
    }
    function empleado()
    {
        return $this->hasOne(Empleado::class);
    }
    function sale()
    {
        return $this->belongsTo(venta::class);
    }

}
