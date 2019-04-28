@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-link">Back</a>

    <h3>{{$post->title}}</h3>

    <img style="width: 100%;" src="/storage/cover_images/{{$post->cover_image}}" alt="Blog Post Image" />

    <br><br>

    <div>{!! $post->body !!}</div>

    <hr>
    <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>

    <hr>
    

    <!-- Below is to delete the post 
         For reference  https://github.com/LaravelCollective/docs/blob/5.6/html.md -->

    @auth<!-- If user is authenticated i.e. not a guest -->
        @if(Auth::user()->id === $post->user_id) <!-- Only the same user can update/delete their post -->
            <a href="/posts/{{$post->id}}/edit" class="btn btn-light">Edit</a>

            {{ Form::open(['action' => ['PostsController@destroy', $post->id], 'method' => 'DELETE', 'class' => 'float-right']) }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
            {{ Form::close() }}
        @endif
    @endauth
@endsection