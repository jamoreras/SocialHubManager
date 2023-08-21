<?php

namespace App\Console\Commands;

use App\Models\LinkedInCredential;
use App\Models\Publication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Tareaprogramada extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fecha  description';

    /**
     * Execute the console command.
     */
    public function handle()
    {     
        $instancia = new Publication();
        $publicaciones = $instancia->all(); // Obtener todas las publicaciones
        $linkInts = new LinkedInCredential();
        foreach ($publicaciones as $publicacion) {
            // Realiza alguna operación con cada publicación
            if ($publicacion->status === 'pending' && !empty($publicacion->scheduled_at) && $this->validarFecha($publicacion)) {
                $credlinke= $linkInts->getByUserId($publicacion->user_id);// Obtiene las credenciales de LinkedIn del usuario
                $estructura = [//estructura
                    "owner" => "urn:li:person:{$credlinke->client_id}",
                    "text" => [
                        "text" => $publicacion->message,
                    ],
                ];                              
                $response = Http::withHeaders([//envio  // Realiza la solicitud de envío a LinkedIn
                    'Authorization' => "Bearer {$credlinke->access_token}",
                    'Content-Type' => 'application/json',
                ])->post('https://api.linkedin.com/v2/shares', $estructura);
                $publicacion->status = 'sent';//actualiza estado en cola 
                $publicacion->save();
                Storage::append("archivo.txt", "Mensaje de publicación: " . $publicacion->message);
            } else {//if igual al de arriba pero para reddit

            }
        
                    }
    }
    public function validarFecha($cola)     // Función para validar horarios de publicación
    {

        $fechaActual = now()->format('Y-m-d H:i:00');

        // Obtener la fecha y hora programada para la publicación
        $fechaProgramada = $cola->scheduled_at; // Asumiendo que $cola->scheduled_at contiene la fecha y hora programada
        // Comparar la fecha y hora actual con la programada
        if ($fechaActual === $fechaProgramada) {
            //dd($fechaActual, $fechaProgramada);
            // La publicación no debe ser enviada en este horario
            return true;
        } else {
            // La publicación puede ser enviada en este horario
            return false;
        }


        return false; // La publicación no debe ser enviada en este horario
    }
}
