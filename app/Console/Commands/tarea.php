<?php

namespace App\Console\Commands;

use App\Models\Publication;
use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;
use App\Models\HubCrudHorario;
use App\Models\LinkedInCredential;
use Illuminate\Support\Facades\Http;
use App\Models\RedditCredential;
use App\Models\TumblrCredential;
use Tumblr\API\Client;


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
    public function handle() // Obtiene las últimas publicaciones pendientes por usuario
    {
        $instancia = new Publication();
        $datos = $instancia->ultimasPublicacionesPorUsuario();
        $linkInts = new LinkedInCredential();

        foreach ($datos as $cola) {            // Verifica si la publicación es pendiente, cumple los horarios y no está programada
            if ($cola->status === 'pending' && $this->validarHorarios($cola) && empty($cola->scheduled_at)) {//valida el estado, los horarios y que no sea programada
                if ($cola->social_media === 'linkedin') {
                    $credlinke= $linkInts->getByUserId($cola->user_id);// Obtiene las credenciales de LinkedIn del usuario
                    $estructura = [//estructura
                        "owner" => "urn:li:person:{$credlinke->client_id}",
                        "text" => [
                            "text" => $cola->message,
                        ],
                    ];                 
                    $response = Http::withHeaders([//envio  // Realiza la solicitud de envío a LinkedIn
                        'Authorization' => "Bearer {$credlinke->access_token}",
                        'Content-Type' => 'application/json',
                    ])->post('https://api.linkedin.com/v2/shares', $estructura);
                    $cola->status = 'sent';//actualiza estado en cola 
                    $cola->save();
                }else if ($cola->social_media === 'reddit') {
                  
                    $apiCallEndpoint = 'https://oauth.reddit.com/api/submit';
                    $username = 'joe-tony';
                    $accessTokenType = 'Bearer';
                    $postData = array(
                        'text' => $cola->message,
                        'title' => $cola->title,
                        'sr' => $cola->subreddit,
                        'kind' => 'self'
                    );

                    $redditCredential = RedditCredential::where('user_id', $cola->user_id)->first();

                    if ($redditCredential) {
                        $accessToken = $redditCredential->access_token;

                        // curl settings and call to post to the subreddit
                        $ch = curl_init($apiCallEndpoint);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_USERAGENT, $cola->subreddit . ' by /u/' . $username . ' (ISW 1.0)');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: " . $accessTokenType . " " . $accessToken));
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

                        // curl response from our post call
                        $response_raw = curl_exec($ch);
                        $response = json_decode($response_raw);
                        curl_close($ch);
                    }
                    
                    $cola->status = 'sent';
                    $cola->save();
                }
                else if ($cola->social_media === 'tumblr') {
                  
                    $tumblrCredential = TumblrCredential::where('user_id', $cola->user_id)->first();

                    if ($tumblrCredential) {

                        $identifier = $tumblrCredential->identifier;
                        $secret = $tumblrCredential->secret;
                        $client = new Client(env('TUMBLR_CONSUMER_KEY'), env('TUMBLR_CONSUMER_SECRET'));

                        $client->setToken($identifier, $secret);

                        $data = [
                            'type' => 'text',
                            'title' => $cola->title,
                            'body' => $cola->message,
                        ];
                        $client->createPost(env('TUMBLR_BLOG_NAME'), $data);

                    }
                    $cola->status = 'sent';
                    $cola->save();
                }

                Storage::append("archivo.txt", "Publicación enviada - " . $cola->message);  // Agrega una entrada al archivo de registro
            } 
            
        }


        $texto = "[" . date("Y-m-d H:i:s") . "]: Creado con éxito";         // Agrega una entrada al archivo de registro con la marca de tiempo
        Storage::append("archivo.txt", $texto);
       
    }
    public function validarHorarios($cola)     // Función para validar horarios de publicación
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

    public function validarFecha($cola)     // Función para validar horarios de publicación
    {

        
        return false; // La publicación no debe ser enviada en este horario
    }



   
}
