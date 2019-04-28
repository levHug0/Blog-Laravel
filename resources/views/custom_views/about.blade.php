@extends('layouts/app')

@section('content')
    <h3>About, called content_grab</h3>
    <h6>Below is what's passed as a property from controller</h6>
    <p>{{$title}}</p>
@endsection