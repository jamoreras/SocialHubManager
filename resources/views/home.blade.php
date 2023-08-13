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

                    <a href="{{ route('linkedin.redirect') }}" class="btn btn-primary">Conectar con LinkedIn</a>
                </div>

                <x-post-form />

                
            </div>
        </div>
    </div>
</div>
@endsection
