<?php

namespace App\Http\Controllers;

use League\OAuth1\Client\Server\Tumblr;
//use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\TumblrCredential;
use Illuminate\Support\Facades\Auth;


use Tumblr\API\Client;
use Tumblr\OAuth1\OAuth1Provider;


class TumblrOAuthController extends Controller
{
    public function show(Request $request)
    {

        return view('publicacionesTumblr');
    }
    public function redirectToTumblr()
    {
        $tumblr = new Tumblr([
            'identifier' => env('TUMBLR_CONSUMER_KEY'),
            'secret' => env('TUMBLR_CONSUMER_SECRET'),
            'callback_uri' => env('TUMBLR_CALLBACK_URL'),
        ]);

        $temporaryCredentials = $tumblr->getTemporaryCredentials();

        session(['temporary_credentials' => serialize($temporaryCredentials)]);
        $tumblr->authorize($temporaryCredentials);

        return redirect()->away($tumblr->getAuthorizationUrl($temporaryCredentials));


    }

    public function handleTumblrCallback(Request $request)
    {
        $serializedTemporaryCredentials = session('temporary_credentials');

        if (!$serializedTemporaryCredentials) {
            return redirect()->route('home')->with('error', 'Temporary credentials not found.');
        }

        $temporaryCredentials = unserialize($serializedTemporaryCredentials);

        $tumblr = new Tumblr([
            'identifier' => env('TUMBLR_CONSUMER_KEY'),
            'secret' => env('TUMBLR_CONSUMER_SECRET'),
            'callback_uri' => env('TUMBLR_CALLBACK_URL'),
        ]);

        try {
            $tokenCredentials = $tumblr->getTokenCredentials(
                $temporaryCredentials,
                $request->oauth_token,
                $request->oauth_verifier
            );

            if ($tokenCredentials) {

                TumblrCredential::updateOrCreate(
                    ['user_id' => Auth::id()],
                    [
                        'identifier' => $tokenCredentials->getIdentifier(),
                        'secret' => $tokenCredentials->getSecret(),
                    ]
                );


                session(['tumblr_token_credentials' => serialize($tokenCredentials)]); //identifier and secret
                return redirect()->route('home')->with('success', 'Logged in with Tumblr.');
            } else {
                return redirect()->route('home')->with('error', 'Failed to authenticate with Tumblr.');
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Error during Tumblr authentication.');
        }
    }


    public function sendTumblrMessage(Request $request)
    {
   
        $tumblrCredential = TumblrCredential::where('user_id', Auth::id())->first();

        if ($tumblrCredential) {

            $identifier = $tumblrCredential->identifier;
            $secret = $tumblrCredential->secret;
            $client = new Client(env('TUMBLR_CONSUMER_KEY'), env('TUMBLR_CONSUMER_SECRET'));

            $client->setToken($identifier, $secret);

            $data = [
                'type' => 'text',
                'title' => $request->title,
                'body' => $request->body,
            ];
    
            try {
                $client->createPost(env('TUMBLR_BLOG_NAME'), $data);
    
                return redirect()->route('home')->with('success', 'Post created successfully.');
            } catch (\Tumblr\API\RequestException $e) {
    
                return redirect()->back()->with('error', 'An error occurred while creating the post: ' . $e->getMessage());
            }

            
        } else {
            return redirect('/home')->with('error', '¡No se encontraron las credenciales de Tumblr!');
        }
       

    }
}