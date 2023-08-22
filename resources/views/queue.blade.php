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
                    <div class="form-group" style="display: none;" id="title_div">
                            <label for="title">Titulo:</label>
                            <input type="text" class="form-control" name="title" id="title">
                    </div>

                    <div class="form-group">
                        <label for="message">Mensaje:</label>
                        <input type="text" name="message" class="form-control" required>
                    </div>
                    <div class="form-group" id="subreddit_div" style="display: none;">
                            <label for="subreddit">Subreddit:</label>
                            <input type="text" class="form-control" name="subreddit" id="subreddit">
                        </div>
                    <div class="form-group">
                        <label for="scheduled_at">Programar para</label>
                        <input type="datetime-local" name="scheduled_at" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="social_media">Red Social</label>
                        <select name="social_media" class="form-control" id="social_media">
                            <option value="linkedin">LinkedIn</option>
                            <option value="reddit">Reddit</option>
                            <option value="tumblr">Tumblr</option>
                            <!-- Agrega otras opciones de redes sociales según necesites -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar a la Cola</button>
                </form>
            </div>
        </div>
    </div>

<script>
    const socialMediaSelect = document.getElementById('social_media');
const titleDiv = document.getElementById('title_div');
const subredditDiv = document.getElementById('subreddit_div');

socialMediaSelect.addEventListener('change', function() {
    console.log('Selected value:', socialMediaSelect.value);
    
    if (socialMediaSelect.value === 'reddit') {
        console.log('Showing subreddit_div and title_div');
        subredditDiv.style.display = 'block';
        titleDiv.style.display = 'block';
    } else if (socialMediaSelect.value === 'tumblr') {
        console.log('Hiding subreddit_div and showing title_div');
        subredditDiv.style.display = 'none';
        titleDiv.style.display = 'block';
    } else {
        console.log('Hiding both divs');
        titleDiv.style.display = 'none';
        subredditDiv.style.display = 'none';
    }
});
</script>
@endsection
