<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller //Este controlador  maneja las acciones relacionadas con la administración de la cola de publicaciones:
{
    public function index()  // Muestra las publicaciones pendientes y enviadas en la vista 'queue', recupera las publicaciones pendientes y enviadas de la base de datos y las pasa a la vista para mostrarlas.
    {
        $pendingPublications = Publication::where('status', 'pending')->get();
        $sentPublications = Publication::where('status', 'sent')->get();

        return view('queue', compact('pendingPublications', 'sentPublications'));
    }

    public function addToQueue(Request $request) // Agrega una nueva publicación a la cola  Valida los datos del formulario, como el mensaje de la publicación. Luego, crea un nuevo registro en la base de datos con la información proporcionada, incluido el usuario actual y el estado pendiente. Este controlador es responsable de administrar la cola de publicaciones, agregando nuevas publicaciones a la cola y mostrando las publicaciones pendientes y enviadas en la vista. Las rutas asociadas, como 'queue', deben estar definidas en tu archivo routes/web.php.
    {
        $userID = Auth::id();
        $scheduleNull = $request->input('social_media');

        if (!$scheduleNull) {
            $scheduleNull = "linkedin";
        }

        $validatedData = $request->validate([  // Valida los datos ingresados en el formulario
            'message' => 'required',


        ]);

        Publication::create([  // Crea una nueva publicación en la base de datos
            'message' => $validatedData['message'],
            'scheduled_at' => $request->input('scheduled_at'),
            'social_media' => $scheduleNull,
            'status' => 'pending',
            'user_id' => $userID,

        ]);

        return redirect()->route('queue')->with('success', 'Publicación agregada a la cola');
    }
}
