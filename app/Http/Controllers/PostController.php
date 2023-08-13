<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
   
    public function create(Request $request)
    {
        
        $post = new Post();
        $user = Auth::user();
        $post->content = $request->input('content');
        $post->social_media = json_encode($request->input('social_media'));
        $post->scheduled_at = $request->input('scheduled_at');
        $post->user_id = $user->id;
        $post->save();

        // agregar código para interactuar con la API 

        return redirect()->back()->with('success', '¡La publicación se ha creado exitosamente!');
    }
}
