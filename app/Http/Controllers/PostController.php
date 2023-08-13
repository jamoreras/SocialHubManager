<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
   
    public function create(Request $request)
    {
        
        $post = new Post();
        $post->content = $request->input('content');
        $post->social_media = json_encode($request->input('social_media'));
        $post->scheduled_at = $request->input('scheduled_at');
        $post->save();

        // agregar código para interactuar con la API 

        return redirect()->back()->with('success', '¡La publicación se ha creado exitosamente!');
    }
}
