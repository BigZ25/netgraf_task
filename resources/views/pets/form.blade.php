@extends('template')
@section('content')
    <div>
        @if((request()->segment(3) === 'edit' && !empty($pet)) || (request()->segment(2) === 'create' && empty($pet)))
            @if(!empty($pet))
                <h1>Edycja zwierzaka</h1>
            @else
                <h1>Nowy zwierzak</h1>
            @endif
        @endif
        <a href="/pets">Lista</a>
        @if((request()->segment(3) === 'edit' && !empty($pet)) || (request()->segment(2) === 'create' && empty($pet)))
            <br>
            <br>
            <form action="/pets{{!empty($pet) ? '/'.$pet['id'] : ''}}" method="POST" style="border: 1px solid; padding: 10px;">
                @csrf
                @if(!empty($pet))
                    @method('PUT')
                @endif
                <div style="display: flex; align-items: center;">
                    <p style="margin-right: 10px;">Nazwa</p>
                    <input name="name" value="{{!empty($pet) ? $pet['name'] : ''}}">
                </div>
                <div style="display: flex; align-items: center;">
                    <p style="margin-right: 10px;">Status</p>
                    <select name="status">
                        @foreach($statuses as $value => $label)
                            <option value="{{$value}}"{{!empty($pet) && $pet['status'] === $value ? ' selected' : ''}}>
                                {{$label}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit">Wyślij</button>
            </form>
        @endif
    </div>
@endsection('content')
