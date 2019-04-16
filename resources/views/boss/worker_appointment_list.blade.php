@extends('layouts.app')
@section('content')

{!! Html::style('css/worker_appointment_list.css') !!}

<div class="container">
    
    <div class="text-center">
        <h2>Zabiegi pracowników dotyczące subskrypcji - {{$subscription->name}}</h2>
    </div>
    <div id="workers-panel" class="wrapper cont">
        <div class="text-center">
            <label for="workers">Wpisz imię i nazwisko pracownika:</label>
            @if($worker !== null)
                <input id="workers" class="form-control" type="text" value="{{$worker->name . " " . $worker->surname}}">
            @else
                <input id="workers" class="form-control" type="text" value="">          
            @endif
            <div style="padding: 6px;">
                <a href="#" id="workers-panel-button" class="btn btn-lg btn-warning">
                    Szukaj
                </a>
            </div>
        </div>
        <div class="text-center">
            <h3>Wyszukano:</h3>
            @if ($worker !== null)
                <h2>{{$worker->name}} {{$worker->surname}}</h2>
                <h3>{{$worker->email}}</h3>
                <h4>{{$worker->phone_number}}</h4>
            @else
                <h4>Wszystkie wizyty pracowników przypisanych do subskrypcji</h4>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-6">
            <h2 class="text-center">
                Wszystkie wizyty
            </h2>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-6">
            <div class="text-center">
                <select class="form-control">
                    <option value="volvo">Volvo</option>
                    <option value="saab">Saab</option>
                    <option value="mercedes">Mercedes</option>
                    <option value="audi">Audi</option>
                </select>
            </div>
        </div>
    </div>
    
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Data</td>
                <td>Godzina</td>
                <td>Zabieg</td>
                <td>Wykonawca</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody id="appointmentsTable">
            @foreach($appointments as $appointment)
                <tr>
                    <td>{{$appointment->date}}</td>
                    <td>{{$appointment->start_time}} - {{$appointment->end_time}}</td>
                    <td>{{$appointment->item->name}}</td>
                    <td>{{$appointment->employee}}</td>
                    <td>
                        {{config('appointment-status.' . $appointment->status)}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection