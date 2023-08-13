<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Publication;

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
        $validatedData = $request->validate([
            'message' => 'required',
            'scheduled_at' => 'required|date',
            'social_media' => 'required'
        ]);

        Publication::create([
            'message' => $validatedData['message'],
            'scheduled_at' => $validatedData['scheduled_at'],
            'social_media' => $validatedData['social_media'],
            'status' => 'pending'
            
        ]);

        return redirect()->route('queue')->with('success', 'Publicación agregada a la cola');
    }
}
