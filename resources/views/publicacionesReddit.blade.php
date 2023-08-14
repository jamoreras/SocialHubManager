@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <form method="post" action="{{ route('reddit.post') }}">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <div class="form-group">
                            <label for="text">Text:</label>
                            <textarea class="form-control" name="text" id="text" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="subreddit">Subreddit:</label>
                            <input type="text" class="form-control" name="subreddit" id="subreddit" required>
                        </div>
                        <button type="submit" class="btn btn-primary my-3">Submit Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection