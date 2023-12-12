<?php

namespace App\Models;

use App\Models\User;
use App\Models\cliente;
use App\Models\Asignacion_venta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class venta extends Model
{
    use HasFactory;
    protected $fillable = [
        'total',
        'disccount',
        'items',
        'status',
        'customer_id',
        'user_id',
        'reference',
        'payment_method',
        'tips',
    ];

    function details()
    {
        return $this->hasMany(Asignacion_venta::class);
    }
    function customer()
    {
        return $this->belongsTo(cliente::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
    public function metodosPago()
    {
        return $this->belongsToMany(metodo_pago::class,'metodo_pago_ventas',);
    }

}
