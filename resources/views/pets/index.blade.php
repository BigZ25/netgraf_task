@extends('template')
@section('content')
    <div>
        <h1>Lista zwierzaków</h1>
        <a href="/pets/create">Dodaj zwierzaka</a>
        <div style="display: flex; align-items: center;">
            <p style="margin-right: 10px;">Status</p>
            <select id="statusSelect">
                @foreach($statuses as $value => $label)
                    <option value="{{$value}}"{{$status === $value ? ' selected' : ''}}>
                        {{$label}}
                    </option>
                @endforeach
            </select>
        </div>
        @if(count($pets) > 0)
            <p>Znalezionych wyników: {{count($pets)}}</p>
            <table border="1">
                <tr>
                    <th>Nazwa</th>
                    <th>Kategoria</th>
                    <th>Pokaż</th>
                    <th>Edytuj</th>
                    <th>Usuń</th>
                </tr>
                @foreach($pets as $pet)
                    <tr>
                        <td>{{isset($pet['name']) ? $pet['name'] : '-'}}</td>
                        <td>{{isset($pet['category']) && isset($pet['category']['name']) ? $pet['category']['name'] : '-'}}</td>
                        <td><a href="/pets/{{$pet['id']}}">pokaż</a></td>
                        <td><a href="/pets/{{$pet['id']}}/edit">edytuj</a></td>
                        <td><a href="/pets/{{$pet['id']}}/destroy">usuń</a></td>
                    </tr>
                @endforeach
            </table>
        @else
            <h3>Brak wyników</h3>
        @endif
    </div>
    <script>
        document.getElementById('statusSelect').addEventListener('change', function () {
            window.location.href = '/pets?status=' + this.value;
        });
    </script>
@endsection('content')
