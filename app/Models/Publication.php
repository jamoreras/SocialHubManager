<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','message', 'scheduled_at', 'status', 'social_media', 'title', 'subreddit'];


    public function ultimasPublicacionesPorUsuario()
    {
        // Crea una subconsulta para obtener los IDs de las últimas publicaciones por usuario
        $subquery = $this->selectRaw('MAX(id) as id')
        ->where('status', 'pending')
        ->groupBy('user_id');

        // Usa la subconsulta para filtrar las filas por los IDs de las últimas publicaciones
        return $this->whereIn('id', $subquery)
            ->orderBy('created_at', 'asc')
            ->get();
    }

}
