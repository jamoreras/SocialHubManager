<?php

namespace App\Http\Controllers;

use League\OAuth1\Client\Server\Tumblr;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TumblrOAuthController extends Controller
{
    public function redirectToTumblr()
    {
        $tumblr = new Tumblr([
            'identifier' => env('TUMBLR_CONSUMER_KEY'),
            'secret' => env('TUMBLR_CONSUMER_SECRET'),
            'callback_uri' => env('TUMBLR_CALLBACK_URL'),
        ]);

        $temporaryCredentials = $tumblr->getTemporaryCredentials();

        session(['temporary_credentials' => serialize($temporaryCredentials)]);

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
                
                session(['tumblr_token_credentials' => serialize($tokenCredentials)]); //identifier and secret
                return redirect()->route('home')->with('success', 'Logged in with Tumblr.');
            } else {
                return redirect()->route('home')->with('error', 'Failed to authenticate with Tumblr.');
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Error during Tumblr authentication.');
        }
    }


   
}