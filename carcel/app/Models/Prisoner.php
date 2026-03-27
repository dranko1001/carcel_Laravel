<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prisoner extends Model
{
    use HasFactory;

    // Campos que permites llenar masivamente
    protected $fillable = ['full_name', 'birth_date', 'entry_date', 'crime', 'assigned_cell'];

    // Un prisionero puede tener muchas visitas
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
