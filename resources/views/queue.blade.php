@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>Cola de Publicaciones</h1>

                <h2>Publicaciones Pendientes</h2>
                <ul>
                    @foreach ($pendingPublications as $publication)
                        <li>{{ $publication->message }} - {{ $publication->scheduled_at }} - {{ $publication->social_media }}</li>
                    @endforeach
                </ul>

                <h2>Publicaciones Enviadas</h2>
                <ul>
                    @foreach ($sentPublications as $publication)
                        <li>{{ $publication->message }} - {{ $publication->scheduled_at }} - {{ $publication->social_media }}</li>
                    @endforeach
                </ul>

                <h2>Agregar Publicación a la Cola</h2>
                <form method="POST" action="{{ route('addToQueue') }}">
                    @csrf
                    <div class="form-group">
                        <label for="message">Mensaje</label>
                        <input type="text" name="message" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="scheduled_at">Programar para</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="social_media">Red Social</label>
                        <select name="social_media" class="form-control">
                            <option value="linkedin">LinkedIn</option>
                            <option value="reddit">Reddit</option>
                            <!-- Agrega otras opciones de redes sociales según necesites -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar a la Cola</button>
                </form>
            </div>
        </div>
    </div>
@endsection
