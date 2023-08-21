<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HubCrudHorario;
use App\Models\LinkedInCredential;
use App\Models\Publication;


class CrudHorarioController extends Controller
{
    public function index()
    {
        $horarios = HubCrudHorario::all(); // Recupera todos los horarios desde la tabla 'hub_crud_horarios'
        return view('create', compact('horarios'));  // Retorna la vista 'create' y pasa la variable $horarios a la vista
    }
    public function validarFecha($cola)     // Función para validar horarios de publicación
    {

        $fechaActual = now()->format('Y-m-d H:i:00');

        // Obtener la fecha y hora programada para la publicación
        $fechaProgramada = $cola->scheduled_at; // Asumiendo que $cola->scheduled_at contiene la fecha y hora programada
        // Comparar la fecha y hora actual con la programada
        if ($fechaActual === $fechaProgramada) {
            //dd($fechaActual, $fechaProgramada);
            // La publicación no debe ser enviada en este horario
            return true;
        } else {
            // La publicación puede ser enviada en este horario
            return false;
        }


        return false; // La publicación no debe ser enviada en este horario
    }

    public function store(Request $request)
    {
        $request->validate([ // Valida los datos ingresados en el formulario
            'day_of_week' => 'required',
            'time' => 'required'
        ]);

        $instancia = new Publication();
        $datos = $instancia->all();
        foreach ($datos as $cola) {


            if ($cola->status === 'pending' && !empty($cola->scheduled_at) && $this->validarFecha($cola)) {
                
            } else {
            }
        }
        //dd( $datos);



        // dd($datos);

        HubCrudHorario::create([    // Crea un nuevo registro en la tabla 'hub_crud_horarios'
            'user_id' => auth()->user()->id, // Cambia esto según tu modelo de usuario
            'day_of_week' => $request->input('day_of_week'),
            'time' => $request->input('time')
        ]);

        return back()->with('success', 'Horario creado exitosamente.');  // Redirige de vuelta con un mensaje de éxito
    }

    public function destroy($id)
    {
        $horario = HubCrudHorario::findOrFail($id); // Encuentra el horario por su ID y elimínalo
        $horario->delete();
        return back()->with('success', 'Horario eliminado exitosamente.'); // Redirige de vuelta con un mensaje de éxito
    }

    public function edit(HubCrudHorario $horario)
    {
        return view('edit', compact('horario')); // Muestra la vista 'edit' para editar un horario específico
    }

    public function update(Request $request, HubCrudHorario $horario)
    {
        $request->validate([ // Valida los datos ingresados en el formulario de edición
            'day_of_week' => 'required',
            'time' => 'required',
        ]);

        $horario->update([  // Actualiza el horario con los nuevos valores
            'day_of_week' => $request->input('day_of_week'),
            'time' => $request->input('time'),
        ]);
        // Redirige a la ruta 'index' con un mensaje de éxito
        return redirect()->route('index')->with('status', 'Horario de publicación actualizado exitosamente.');
    }
}
