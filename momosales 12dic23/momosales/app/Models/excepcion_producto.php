<?php

namespace App\Models;

use App\Models\Comision;
use App\Models\producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class excepcion_producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'qty',
        'type_comission',
        'producto_id',
        'comision_id'
    ];
    function comision()
    {
        return $this->belongsTo(Comision::class);
    }
    function producto()
    {
        return $this->belongsTo(producto::class);
    }
}
