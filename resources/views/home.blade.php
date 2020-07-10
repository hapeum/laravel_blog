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

                    <a href="/posts/create" class="btn btn-primary">Create Post</a>
                    <h3>The Posts written by me.</h3>
                    @if (count($posts) > 0)
                        <table class="table task-table">
                            <tr>
                                <th class="item-center">Title</th>
                                <th></th>
                                <th class="rows-col-1"></th>
                            </tr>
                            @foreach ($posts as $post)
                                <tr>
                                    <th>{{ $post->title }}</th>
                                    <th><a href="{{ route('posts.show', $post->id) }}" class="btn btn-default">View</th></th>
                                    <th><a href="/posts/{{$post->id}}/edit" class="btn btn-default">Edit</th>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p>You have no posts.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
