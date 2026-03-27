<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    // 1. Indicar qué campos se pueden llenar desde un formulario (Mass Assignment)
    protected $fillable = [
        'full_name',
        'identification_number',
    ];

    // 2. Relación: Un visitante puede tener muchas visitas registradas
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}