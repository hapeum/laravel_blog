@extends('layouts.app')

@section('content')
    <h1>Create Post</h1>
    <div class="form-group">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            <!-- CSRF保護 -->
            @csrf
            <div class="form-group">
                <label for="title"></label>Title</label>
                <input class="form-control" type=text name="title" id="title" value="">
            </div>
            <div class="form-group">
                <label for="body">Body</label>
                <textarea class="form-control" name="body" id="body"></textarea>
            </div>
            <div class="form-group">
                <input type="file" name="cover_image">
            </div>
            <input class="btn btn-primary" type="submit" value="Submit">
        </form>
    </div>
@endsection