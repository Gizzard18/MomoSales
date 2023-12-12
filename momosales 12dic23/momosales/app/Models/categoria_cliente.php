<?php

namespace App\Models;

use App\Models\cliente;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class categoria_cliente extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function cliente(){
        return $this->hasMany(cliente::class);
    }
}
