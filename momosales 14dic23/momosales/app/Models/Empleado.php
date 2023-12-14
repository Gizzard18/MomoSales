<?php

namespace App\Models;

use App\Models\Comision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empleado extends Model
{

    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'birth_date',
        'is_active',
        'comissions',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }    
    function comision()
    {
        return $this->hasOne(Comision::class);
    }
}
