<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'prisoner_id', 
        'visitor_id', 
        'user_id', 
        'start_time', 
        'end_time'
    ];

    // Cambiamos el nombre de 'guard' a 'user' o 'officer'
    public function officer() 
    {
        // El segundo parámetro 'user_id' le dice a Laravel 
        // qué columna buscar en la tabla 'visits'
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prisoner()
    {
        return $this->belongsTo(Prisoner::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
