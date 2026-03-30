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
        'relationship',
        'user_id',
        'start_time',
        'end_time'
    ];

    public function officer()
    {
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