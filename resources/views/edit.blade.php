@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Editar Horario de Publicación') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('horarios.update', $horario->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="day_of_week" class="form-label">Día de la Semana</label>
                            <select class="form-select" id="day_of_week" name="day_of_week">
                                <option value="Monday" {{ $horario->day_of_week === 'Monday' ? 'selected' : '' }}>Lunes</option>
                                <option value="Tuesday" {{ $horario->day_of_week === 'Tuesday' ? 'selected' : '' }}>Martes</option>
                                <option value="Wednesday" {{ $horario->day_of_week === 'Wednesday' ? 'selected' : '' }}>Miércoles</option>
                                <option value="Thursday" {{ $horario->day_of_week === 'Thursday' ? 'selected' : '' }}>Jueves</option>
                                <option value="Friday" {{ $horario->day_of_week === 'Friday' ? 'selected' : '' }}>Viernes</option>
                                <option value="Saturday" {{ $horario->day_of_week === 'Saturday' ? 'selected' : '' }}>Sábado</option>
                                <option value="Sunday" {{ $horario->day_of_week === 'Sunday' ? 'selected' : '' }}>Domingo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="time" class="form-label">Hora de Publicación</label>
                            <input type="time" class="form-control" id="time" name="time" value="{{ $horario->time }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection