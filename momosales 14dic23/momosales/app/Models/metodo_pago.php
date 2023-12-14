<?php

namespace App\Models;

use App\Models\cita;
use App\Models\venta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class metodo_pago extends Model
{
    use HasFactory;
    protected $fillable = [
        'Payment_method','reference','amount'
    ];
    
    public function ventas()
    {
        return $this->belongsToMany(venta::class,'metodo_pago_ventas',);
    }
    public function citas()
    {
        return $this->belongsToMany(cita::class,'metodo_pago_citas',);
    }
}
