<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class marca extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','phone_number','contact_name','email'
    ];

    protected $table = 'marcas';

    function products()
    {
        return $this->hasMany(Producto::class);
    }

}
