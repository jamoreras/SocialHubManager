<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HubCrudHorario extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'day_of_week',
        'time',
    ];

    // Definir la relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}