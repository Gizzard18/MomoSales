<?php

namespace App\Models;

use App\Models\producto;
use App\Models\categoria_servicio;
use App\Models\excepcion_servicio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class servicio extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'gross_price',
        'discount_price',
        'duration',
        'reward_points'
    ];
    public function categorias()
    {
        return $this->belongsToMany(categoria_servicio::class,'categoria_servicios_pivs');
    }

    function excepciones()
    {
        return $this->hasMany(excepcion_servicio::class);
    }

}
