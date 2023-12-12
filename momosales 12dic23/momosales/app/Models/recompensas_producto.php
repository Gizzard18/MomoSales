<?php

namespace App\Models;

use App\Models\categoria_producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class recompensas_producto extends Model
{
    use HasFactory;
    public function categorias()
    {
        return $this->belongsToMany(categoria_producto::class,'recompensas_cat_productos');
    }
}
