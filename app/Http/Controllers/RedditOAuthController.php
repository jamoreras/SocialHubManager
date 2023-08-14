<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class RedditOAuthController extends Controller
{
    public function show(Request $request){

        return view('publicacionesReddit');
    }
    public function redirectToReddit()
    {
        $state = Str::random(40);

        session(['reddit_state' => $state]);

        $queryParams = http_build_query([
            'client_id' => env('REDDIT_CLIENT_ID'),
            'response_type' => 'code',
            'state' => $state,
            'redirect_uri' => env('REDDIT_REDIRECT_URI'),
            'duration' => 'temporary', // or 'temporary'
            'scope' => 'identity', // Add more scopes as needed
        ]);

        $url = 'https://www.reddit.com/api/v1/authorize?' . $queryParams;

        return redirect()->away($url);
    }

    public function handleRedditCallback(Request $request)
    {

        $state = $request->input('state');
        $redditState = session('reddit_state');
    
        if ($state !== $redditState) {
            return redirect('/')->with('error', 'State mismatch. Possible CSRF attack.');
        }
    
        $code = $request->input('code');
    
        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('REDDIT_CLIENT_ID') . ':' . env('REDDIT_CLIENT_SECRET')),
        ])->post('https://www.reddit.com/api/v1/access_token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => route('reddit.callback'), 
        ]);
    
        if ($response->successful()) {
            $accessToken = $response['access_token'];
    
      

           
            Session::put('reddit_access_token', $accessToken);
            Session::put('reddit_user_id', $code);
    
            return redirect('/home')->with('success', 'Logged in with Reddit.');
        } else {
            return redirect('/home')->with('error', 'Failed to authenticate with Reddit.');
        }
    }



    public function sendRedditMessage(Request $request)
    {
        
        $accessToken = Session::get('reddit_access_token');
       

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'User-Agent' => 'isw811',
        ])->post('https://oauth.reddit.com/api/submit', [
                    'title' => $request->title,
                    'text' => $request->text,
                    'sr' => $request->subreddit,
                ]);

        if ($response->successful()) {
            dd($response->json()); 
            return redirect('/home')->with('success', '¡Publicación en Reddit exitosa!');
        } else {
            dd($response->json()); 
            return redirect('/home')->with('error', '¡Publicación en Reddit ha fallado!');
        }
    }

}