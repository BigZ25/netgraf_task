@extends('template')
@section('content')
    <div>
        <h1>Podgląd zwierzaka</h1>
        <a href="/pets">Lista</a>
        @if(!empty($pet))
            <a href="/pets/{{$pet['id']}}/edit">Edycja</a>
            <a href="/pets/{{$pet['id']}}/destroy">Usuń</a>
            <p>Nazwa: <b>{{$pet['name']}}</b></p>
            <p>Status: <b>{{\App\Enum\StatusEnum::getList($pet['status'])}}</b></p>
            @if(isset($pet['category']) && isset($pet['category']['name']))
                <p>Kategoria: <b>{{$pet['category']['name']}}</b></p>
            @endif
        @endif
    </div>
@endsection('content')
