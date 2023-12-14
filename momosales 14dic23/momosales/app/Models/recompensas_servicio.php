<?php

namespace App\Models;

use App\Models\categoria_servicio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class recompensas_servicio extends Model
{
    use HasFactory;
    public function categorias()
    {
        return $this->belongsToMany(categoria_servicio::class,'recompensas_cat_servicios');
    }
}
