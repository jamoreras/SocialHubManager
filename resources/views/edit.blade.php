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
                                <option value="monday" {{ $horario->day_of_week === 'monday' ? 'selected' : '' }}>Lunes</option>
                                <option value="tuesday" {{ $horario->day_of_week === 'tuesday' ? 'selected' : '' }}>Martes</option>
                                <option value="wednesday" {{ $horario->day_of_week === 'wednesday' ? 'selected' : '' }}>Miércoles</option>
                                <option value="thursday" {{ $horario->day_of_week === 'thursday' ? 'selected' : '' }}>Jueves</option>
                                <option value="friday" {{ $horario->day_of_week === 'friday' ? 'selected' : '' }}>Viernes</option>
                                <option value="saturday" {{ $horario->day_of_week === 'saturday' ? 'selected' : '' }}>Sábado</option>
                                <option value="sunday" {{ $horario->day_of_week === 'sunday' ? 'selected' : '' }}>Domingo</option>
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