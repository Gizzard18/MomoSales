<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
