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
                <td>Gdzie</td>
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
                    <td>{{$appointment->name}}</td>
                    <td>{{$appointment->item->name}}</td>
                    <td>{{$appointment->minutes}}</td>
                    <td>{{$appointment->employee}}</td>
                    <td>
                        {{config('appointment-status.' . $appointment->status)}}
                    </td>
                    <td>
                        <a class="btn btn-primary" href="{{ URL::to('/employee/backend-appointment/show/' . $appointment->id) }}">
                            @lang('common.show')
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $appointments->links() }}
</div>
@endsection