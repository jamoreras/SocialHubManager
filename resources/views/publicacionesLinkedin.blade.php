@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    

                    <p>{{ __('Hola, bienvenido al Hub!') }}</p>
                     <!-- Formulario para ingresar el mensaje a publicar -->
                     <form method="POST" action="{{ route('linkedin.post') }}">
                     @csrf
                        <div class="mb-3">
                            <label for="message" class="form-label">Mensaje a publicar en LinkedIn</label>
                            <input type="text" class="form-control" id="message" name="message" value="" required>
                        </div>
                        <input type="hidden" name="social_media" value="linkedin">
                        <button type="submit" class="btn btn-primary">Publicar ahora</button>
                        <button formaction="{{ route('addToQueue') }}" type="submit" class="btn btn-warning">Publicar en Cola</button>
                    </form>

                    @if (session('success'))
                        <div class="alert alert-success my-3" id="message">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger my-3" id="message">{{ session('error') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

