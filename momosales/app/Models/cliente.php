<?php

namespace App\Models;

use App\Models\categoria_cliente;
use App\Models\tarjetas_punto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class cliente extends Model
{
    use HasFactory;
    
    protected $fillable = [ 'first_name','last_name','email','phone'];

    public function deliveries(){
        return $this->belongsToMany(entrega::class,'entrega_clientes','client_id','delivery_id');
    }
    function categoria()
    {
        return $this->belongsTo(categoria_cliente::class,'categoria_cliente_id');
    }
    function tarjetaPuntos()
    {
        return $this->hasOne(tarjetas_punto::class);
    }
}
