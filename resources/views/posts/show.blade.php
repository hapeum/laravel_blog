@extends('layouts.app')

@section('content')
    <a href="{{ route('posts.index') }}" class="btn bun-default">Go Back</a>
    <h1>{{$post->title}}</h1>
    <div class="row">
        <div class="col-md-12">
            <img style="width: 100%" src="/storage/cover_images/{{$post->cover_image}}" alt="">
        </div>
    </div>
    <p>{{$post->body}}</p>
    <hr>
    <small>Writeten on {{ $post->created_at }}</small>
    <hr>
    @if (!Auth::guest())
        @if (Auth()->user()->id == $post->user_id)
        <div class="row">
        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary btnEdit">Edit</a>
        <br>
        <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
            @method('DELETE')
            <!-- CSRF保護 -->
            @csrf
            <input type="submit" value="Detele" class="btn btn-danger" onclick='return confirm("Are you sure?");'>
        </form>
    </div>
        @endif
    @endif
@endsection