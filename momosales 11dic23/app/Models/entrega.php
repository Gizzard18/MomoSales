<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class entrega extends Model
{
    use HasFactory;

    protected $fillable = [
    'first_name',
    'last_name',
    'company',
    'primary_address',
    'secondary_address',
    'city',
    'state',    
    'postcode',
    'email',
    'phone',
    'country',
    'type'
    ];

    public function customers(){
        return $this->belongsToMany(entrega::class,'entrega_clientes','delivery_id','client_id');
    }
}
