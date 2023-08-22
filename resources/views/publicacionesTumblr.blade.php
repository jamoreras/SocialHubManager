@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <form method="post" action="{{ route('tumblr.post') }}">
                        @csrf
                        <div class="form-group">
                            <label for="title">Post Title:</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <div class="form-group">
                            <label for="body">Post Body:</label>
                            <textarea class="form-control" name="body" id="body" required></textarea>
                        </div>
                      

                        <button type="submit" class="btn btn-primary my-3">Publicar ahora</button>
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
