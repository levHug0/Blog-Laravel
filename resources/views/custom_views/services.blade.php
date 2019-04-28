@extends('layouts.app')

@section('content')
    <h3>This is the services page! Using a layout!</h3>
    <h5>Below is what's passed from property</h5>
    <h6>{{$title}}</h6>

    @if (count($services) > 0)
        <ul class="list-group">
            @foreach($services as $s)
                <li class="list-group-item">{{$s}}</li>
            @endforeach
        </ul>
    @endif
@endsection