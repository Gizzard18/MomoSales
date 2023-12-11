<?php

namespace App\Models;

use App\Models\cita;
use App\Models\Empleado;
use App\Models\producto;
use App\Models\servicio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asignacion_servicio extends Model
{
    use HasFactory;
    protected $fillable = [
        'selected_service',
        'cita_id',
        'empleado_id',
        'disccount_percent',
        'generated_points',
        'current_price',
        'disccount_price',
        'commission',
        'iva',
        'duration'
    ];
    function servicio()
    {
        return $this->hasOne(servicio::class,'selected_service');
    }
    function empleado()
    {
        return $this->hasOne(Empleado::class);
    }
    function date()
    {
        return $this->belongsTo(cita::class);
    }
    function materiales()
    {
        return $this->belongsToMany(producto::class,'materials');
    }
}
