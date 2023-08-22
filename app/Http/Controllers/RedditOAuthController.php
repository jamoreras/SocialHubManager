<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use App\Models\RedditCredential;

class RedditOAuthController extends Controller
{
    public function show(Request $request)
    {

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
            'duration' => 'temporary',
            // or 'temporary'
            'scope' => 'submit', // Add more scopes as needed
        ]);

        $url = 'https://www.reddit.com/api/v1/authorize?' . $queryParams;

        return redirect()->away($url);
    }

    public function handleRedditCallback(Request $request)
    {

        $state = $request->input('state');
        $redditState = session('reddit_state');

        if ($state !== $redditState) {//seguridad ante terceros
            return redirect('/home')->with('error', 'State mismatch. Possible attack.');
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


            RedditCredential::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'access_token' => $accessToken,
                    'client_id' => $code,
                ]
            );

            Session::put('reddit_access_token', $accessToken);
            Session::put('reddit_user_id', $code);

            return redirect('/home')->with('success', 'Logged in with Reddit.');
        } else {
            return redirect('/home')->with('error', 'Failed to authenticate with Reddit.');
        }
    }



    public function sendRedditMessage(Request $request)
    {
        $apiCallEndpoint = 'https://oauth.reddit.com/api/submit';
        $username = 'joe-tony';
        $accessTokenType = 'Bearer';
        //$accessToken = Session::get('reddit_access_token');
        $postData = array(
            'text' => $request->text,
            'title' => $request->title,
            'sr' => $request->subreddit,
            'kind' => 'self'
        );

        $redditCredential = RedditCredential::where('user_id', Auth::id())->first();

        if ($redditCredential) {
            $accessToken = $redditCredential->access_token;

            // curl settings and call to post to the subreddit
            $ch = curl_init($apiCallEndpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $request->subreddit . ' by /u/' . $username . ' (ISW 1.0)');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: " . $accessTokenType . " " . $accessToken));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            // curl response from our post call
            $response_raw = curl_exec($ch);
            $response = json_decode($response_raw);
            curl_close($ch);


            if ($response->success) {

                return redirect('/home')->with('success', '¡Publicación en Reddit exitosa!');
            } else {

                return redirect('/home')->with('error', '¡Publicación en Reddit ha fallado!');
            }
        } else {
            return redirect('/home')->with('error', '¡No se encontraron las credenciales de Reddit!');
        }





    }
}