<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class metodo_pago_venta extends Model
{
    
    protected $fillable = ['Payment_method','reference','amount','venta_id'];
    
    public function ventas()
    {
        return $this->belongsTo(venta::class,'metodo_pago_ventas',);
    }
}
