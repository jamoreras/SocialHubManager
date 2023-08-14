<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HubCrudHorario;

class CrudHorarioController extends Controller
{
    public function index()
    {
        $horarios = HubCrudHorario::all();
        return view('create', compact('horarios'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required',
            'time' => 'required'
        ]);

        HubCrudHorario::create([
            'user_id' => auth()->user()->id, // Cambia esto según tu modelo de usuario
            'day_of_week' => $request->input('day_of_week'),
            'time' => $request->input('time')
        ]);

        return back()->with('success', 'Horario creado exitosamente.');
    }

    public function destroy($id)
    {
        $horario = HubCrudHorario::findOrFail($id);
        $horario->delete();
        return back()->with('success', 'Horario eliminado exitosamente.');
    }

    public function edit(HubCrudHorario $horario)
    {
        return view('edit', compact('horario'));
    }

    public function update(Request $request, HubCrudHorario $horario)
    {
        $request->validate([
            'day_of_week' => 'required',
            'time' => 'required',
        ]);

        $horario->update([
            'day_of_week' => $request->input('day_of_week'),
            'time' => $request->input('time'),
        ]);

        return redirect()->route('index')->with('status', 'Horario de publicación actualizado exitosamente.');
    }
}
