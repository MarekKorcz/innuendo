@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a class="btn btn-success" href="{{ URL::previous() }}">
                Wróć
            </a>
        </div>
    </nav>

    <h1>Wszystkie wizyty</h1>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Data</td>
                <td>Godzina</td>
                <td>Adres</td>
                <td>Nazwa</td>
                <td>Czas</td>
                <td>Wykonawca</td>
                <td>Status</td>
                <td>Akcja</td>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td>{{$appointment->date}}</td>
                    <td>{{$appointment->start_time}} - {{$appointment->end_time}}</td>
                    <td>{{$appointment->address}}</td>
                    <td>{{$appointment->item->name}}</td>
                    <td>{{$appointment->minutes}}</td>
                    <td>{{$appointment->employee}}</td>
                    <td>
                        {{config('appointment-status.' . $appointment->status)}}
                    </td>
                    <td>
                        <a class="btn btn-primary" href="{{ URL::to('/appointment/show/' . $appointment->id) }}">
                            Pokaż
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $appointments->links() }}
</div>
@endsection