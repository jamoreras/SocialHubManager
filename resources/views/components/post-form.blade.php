<div class="container mt-4">
    

	<h2>Publicar en Redes Sociales</h2>
	<form action="{{ route('post.create') }}" method="post">
		@csrf

		<div class="form-group">
			<label for="content">Contenido:</label>
			<textarea class="form-control" name="content" rows="4" required></textarea>
		</div>

		<div class="form-group">
			<label>Redes Sociales:</label><br>
			<input type="checkbox" name="social_media[]" value="reddit"> Reddit<br>
			<input type="checkbox" name="social_media[]" value="twitter"> Twitter<br>


		</div>

		<div class="form-group">
			<label for="scheduled_at">Programar para:</label>
			<input type="datetime-local" class="form-control" name="scheduled_at">
		</div>

		<button type="submit" class="btn btn-primary my-3">Publicar</button>
	</form>
    @if (session('success'))
        <div id="success-message" class="alert alert-success my-3">
            {{ session('success') }}
        </div>
    @endif
</div>

<script>
 
    setTimeout(function() {
        var successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000);
</script>