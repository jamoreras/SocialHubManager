<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Publication;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    public function index()
    {
        $pendingPublications = Publication::where('status', 'pending')->get();
        $sentPublications = Publication::where('status', 'sent')->get();

        return view('queue', compact('pendingPublications', 'sentPublications'));
    }

    public function addToQueue(Request $request)
    {
        $userID = Auth::id();
        $scheduleNull = $request->input('social_media');
        if(!$scheduleNull){
        $scheduleNull ="linkedin";

        }
       
        $validatedData = $request->validate([
            'message' => 'required',

       
        ]);

        Publication::create([
            'message' => $validatedData['message'],
            'scheduled_at' =>$request->input('scheduled_at'),
            'social_media' => $scheduleNull,
            'status' => 'pending',
            'user_id' => $userID,
            
        ]);

        return redirect()->route('queue')->with('success', 'Publicación agregada a la cola');
    }

}
