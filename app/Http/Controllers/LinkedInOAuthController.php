<?php

namespace App\Http\Controllers;

use App\Models\LinkedInCredential;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LinkedInOAuthController extends Controller
{
//I need to capture a message and create a global variable from the user to post on linkedin
 

    public function redirectToLinkedIn(Request $request)// Redirige a la página de autorización de LinkedIn
    {       
       
        $state = bin2hex(random_bytes(16)); // Generar un valor único para el estado
        session(['linkedin_state' => $state]);  // Almacena el estado en la sesión
  

        $queryParams = http_build_query([
            'response_type' => 'code',
            'client_id' => env('LINKEDIN_CLIENT_ID'),
            'redirect_uri' => env('LINKEDIN_REDIRECT_URI'),
            'state' => $state,
            'scope' => 'r_liteprofile r_emailaddress w_member_social'
        ]);

        return redirect('https://www.linkedin.com/oauth/v2/authorization?' . $queryParams);
    }
   
    public function handleLinkedInCallback(Request $request)  // Maneja el callback de autenticación de LinkedIn
    {
        $code = $request->query('code');
        $state = $request->query('state');
        

        // Verificar que el estado coincida con el almacenado en la sesión
        if ($state !== session('linkedin_state')) {
            return redirect()->route('home')->with('error', 'Error de autenticación con LinkedIn');
        }

        // Intercambiar el código de autorización por un token de acceso
        $response = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => env('LINKEDIN_REDIRECT_URI'),
            'client_id' => env('LINKEDIN_CLIENT_ID'),
            'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        ]);
        
        
        $accessToken = $response['access_token'];
        
        
               // Obtener el ID del usuario de LinkedIn
         $profile = Http::withToken($accessToken)->get('https://api.linkedin.com/v2/me');
         $code = $profile["id"];
         Session::put('linkedin_access_token', $accessToken);
         Session::put('linkedin_user_id', $code);

        LinkedInCredential::updateOrCreate(      // Almacena el token de acceso y el ID del usuario en la sesión  
            ['user_id' => Auth::id()], //
            [
                'client_id' => $code,
                'access_token' => $accessToken,
            ]
        );

        

        return redirect()->route('publicacionesLinkedin')->with('success', '¡Autenticación con LinkedIn exitosa!'); 
    }
    public function sendLinkedInMessage(Request $request)   // Envía un mensaje a LinkedIn
    {
        $accessToken = Session::get('linkedin_access_token');
        $code = Session::get('linkedin_user_id');
       
         // Construye la estructura del mensaje
      
        $estructura = [
            "owner" => "urn:li:person:{$code}",
            "text" => [
                "text" => $request->input('message'),
            ],
        ];
        $response = Http::withHeaders([         // Realiza la solicitud de envío a LinkedIn
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type' => 'application/json',
        ])->post('https://api.linkedin.com/v2/shares', $estructura);

        // Limpiar la sesión
        session()->forget('linkedin_message');

        //return back()->with('success', '¡Publicación en LinkedIn exitosa!');
        if ($response->successful()) {
           
            session()->forget('linkedin_message');
            return back()->with('success', '¡Publicación en LinkedIn exitosa!');
        } else {
           
            return back()->with('error', 'Error al publicar en LinkedIn');
        }
    }
    public function publicacionesLinkedin(Request $request){  // Muestra la vista para las publicaciones en LinkedIn

        return view('publicacionesLinkedin');
    }
 
}
 
    

