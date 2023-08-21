@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Crear Horario de Publicación') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('storage-horario') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="day_of_week" class="form-label">Día de la Semana</label>
                            <select class="form-select" id="day_of_week" name="day_of_week">
                                <option value="monday">Lunes</option>
                                <option value="tuesday">Martes</option>
                                <option value="wednesday">Miércoles</option>
                                <option value="thursday">Jueves</option>
                                <option value="friday">Viernes</option>
                                <option value="saturday">Sábado</option>
                                <option value="sunday">Domingo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="time" class="form-label">Hora de Publicación</label>
                            <input type="time" class="form-control" id="time" name="time" value="{{ old('time') }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Horario de Publicación</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">{{ __('Horarios de Publicación Registrados') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Día de la Semana</th>
                                <th scope="col">Hora de Publicación</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($horarios as $horario)
                            <tr>
                                <td>{{ $horario->day_of_week }}</td>
                                <td>{{ $horario->time }}</td>

                                <td>
                                <a href="{{ route('horarios.edit', $horario) }}" class="btn btn-primary">Editar</a>
                                    <form action="{{ route('horarios.destroy', ['id' => $horario->id]) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection