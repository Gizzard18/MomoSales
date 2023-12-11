<?php

namespace App\Models;

use App\Models\User;
use App\Models\cliente;
use App\Models\Asignacion_servicio;
use App\Models\metodo_pago;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class cita extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'url',
        'start',
        'end',
        'date_status',
        'remember',
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
        return $this->hasMany(Asignacion_servicio::class);
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
        return $this->belongsToMany(metodo_pago::class,'metodo_pago_citas',);
    }
}
