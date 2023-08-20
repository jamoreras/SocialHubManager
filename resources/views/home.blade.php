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
                    <!-- Botón para conectar con LinkedIn -->
                    <a href="{{ route('redirectlinkedin') }}" class="btn btn-primary">Conectar con LinkedIn</a>
                    <a href="{{ route('reddit.redirect') }}" class="btn btn-primary">Conectar con Reddit</a>
                    <a href="{{ route('tumblr.redirect') }}" class="btn btn-primary">Conectar con Tumblr</a>

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
<script>
 
    setTimeout(function() {
        var successMessage = document.getElementById('message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000);
</script>
@endsection
