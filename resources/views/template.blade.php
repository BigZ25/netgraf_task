@extends('index')

@section('template')
    @if(isset($messages))
        @foreach($messages as $message)
            <p style="padding: 10px; color: white; background-color: {{$message['type'] === 'error' ? 'red' : 'green'}}">{{$message['message']}}</p>
        @endforeach
    @endif
    @yield('content')
@endsection()
