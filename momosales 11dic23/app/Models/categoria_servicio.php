<?php

namespace App\Models;

use App\Models\servicio;
use App\Models\recompensas_servicio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class categoria_servicio extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function servicio(){
        return $this->belongsToMany(servicio::class,'categoria_servicios_pivs');
    }
    public function RecompensasServicio()
    {
        return $this->belongsToMany(recompensas_servicio::class,'recompensas_cat_servicios');
    }
}
