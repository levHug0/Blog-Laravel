@extends('layouts.app')

@section('content')
    <h3>Posts</h3>

    @if (count($posts) > 0)
        @foreach($posts as $post)
            <div class="well">
                <div class="row" style="padding-bottom: 20px;">
                    <div class="col-md-4 col-sm-4">
                        <img style="width: 100%;" src="/storage/cover_images/{{$post->cover_image}}" alt="Blog Post Image"/>
                    </div>

                    <div class="col-md-8 col-sm-8">
                        <h3><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
                        <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p>No posts found</p>
    @endif
@endsection