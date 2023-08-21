<?php

namespace App\Console\Commands;

use App\Models\Publication;
use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;
use App\Models\HubCrudHorario;
use App\Models\LinkedInCredential;
use Illuminate\Support\Facades\Http;

class tarea extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hub:tarea';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Esta tarea lo que hace es verificar si hay que publicar algo  cada 1 minuto';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $instancia = new Publication();
        $datos = $instancia->ultimasPublicacionesPorUsuario();
        $linkInts = new LinkedInCredential();
        foreach ($datos as $cola) {
            if ($cola->status === 'pending' && $this->validarHorarios($cola) && empty($cola->scheduled_at)) {//valida el estado, los horarios y que no sea programada
                if ($cola->social_media === 'linkedin') {
                    $credlinke= $linkInts->getByUserId($cola->user_id);
                    $estructura = [//estructura
                        "owner" => "urn:li:person:{$credlinke->client_id}",
                        "text" => [
                            "text" => $cola->message,
                        ],
                    ];                 
                    $response = Http::withHeaders([//envio
                        'Authorization' => "Bearer {$credlinke->access_token}",
                        'Content-Type' => 'application/json',
                    ])->post('https://api.linkedin.com/v2/shares', $estructura);
                    $cola->status = 'sent';//actualiza estado en cola
                    $cola->save();
                }else{//reddit
                    //enviar mensaje
                    $cola->status = 'sent';
                    $cola->save();
                }


             
                

                Storage::append("archivo.txt", "Publicación enviada - " . $cola->message);
            } else {///logica programadas
                Storage::append("archivo.txt", "Publicación pendiente - " . $cola->message);
            }
        }


        $texto = "[" . date("Y-m-d H:i:s") . "]: Creado con éxito";
        Storage::append("archivo.txt", $texto);
       
    }
    public function validarHorarios($cola)
    {
        // Obtiene los horarios de publicación del usuario
        $horarios = HubCrudHorario::where('user_id', $cola->user_id)->get();
        //dd($horarios);
        // Obtén el día de la semana actual y la hora actual (sin los segundos)
        $diaActual = strtolower(now()->format('l')); // Ejemplo: "sunday"
        $horaActual = now()->format('H:i'); // Ejemplo: "14:55"
       
       // dd( $horaActual, $diaActual);
        foreach ($horarios as $horario) {
            // Obtiene la hora y los minutos de la columna time (sin los segundos)
            $horaHorario = substr($horario->time, 0, 5); // Ejemplo: "14:55"
            //dd($horaHorario, $horaActual, $diaActual, $horario->day_of_week );
            if ($horario->day_of_week == $diaActual && $horaHorario == $horaActual) {
               
                return true; // La publicación debe ser enviada en este horario
            }
        }

        return false; // La publicación no debe ser enviada en este horario
    }
    public function sentToLinkedin($crede)
    {
        $shareData = [
            "owner" => "urn:li:person:{$code}",
            "text" => [
                "text" => $request->input('message'),
            ],
        ];
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
        ])->post('https://api.linkedin.com/v2/shares', $shareData);
    }
}
