@extends('layouts.app')
@section('content')

{!! Html::style('css/worker_appointment_list.css') !!}
{!! Html::script('js/worker_appointment_list.js') !!}

<div class="container">
    
    <div class="text-center">
        <h2>Zabiegi dotyczące subskrypcji - {{$subscription->name}}</h2>
    </div>
    <div id="workers-panel" class="wrapper cont">
        <div class="text-center">
            <label for="search">Wpisz imię lub nazwisko:</label>
            @if($worker !== null)
                <input id="search" class="form-control" type="text" value="{{$worker->name . " " . $worker->surname}}" autocomplete="off">
            @else
                <input id="search" class="form-control" type="text" value="" autocomplete="off">          
            @endif
            <input id="subscriptionId" type="hidden" name="subscriptionId" value="{{$subscription->id}}">
            <input id="propertyId" type="hidden" name="propertyId" value="{{$property->id}}">
            <ul id="result" class="list-group"></ul>
        </div>
        <div class="text-center">
            @if ($substart->isActive)
                <label for="timePeriod">Wybierz okres rozliczeniowy:</label>
                <select id="timePeriod" class="form-control" data-substart_id="{{$substart->id}}">
                    @foreach ($intervals as $interval)
                        @if ($interval->start_date <= $today && $interval->end_date >= $today)
                            <option value="{{$interval->id}}" selected>{{$interval->start_date->format('Y-m-d')}} - {{$interval->end_date->format('Y-m-d')}}</option>
                        @else
                            <option value="{{$interval->id}}">{{$interval->start_date->format('Y-m-d')}} - {{$interval->end_date->format('Y-m-d')}}</option>
                        @endif
                    @endforeach
                </select>
            @else
                <p>Subskrypcja nie została jeszcze aktywowana. Aktywacja nastąpi wraz ze zrealizowaniem pierwszego zabiegu</p>
            @endif
        </div>
    </div>
    
    <div class="col-sm-12 col-md-12 col-lg-12 col-12">
        <h2 class="text-center">
            Wszystkie wizyty
            @if ($worker !== null)
                należące do {{$worker->name}} {{$worker->surname}}
            @endif
            @if ($intervals)
                @foreach ($intervals as $interval)
                    @if ($interval->start_date <= $today && $interval->end_date >= $today)
                        za okres od {{$interval->start_date->format('Y-m-d')}} do {{$interval->end_date->format('Y-m-d')}}
                    @endif
                @endforeach
            @endif
        </h2>
    </div>
    
    <div id="appointments-table">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>                
                    <td>Data</td>
                    <td>Godzina</td>
                    <td>Imię i Nazwisko</td>
                    <td>Zabieg</td>
                    <td>Wykonawca</td>
                    <td>Status</td>
                </tr>
            </thead>
            <tbody id="appointments">
                <!--todo: dodać linki do userów i wykonujących-->
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{$appointment->date}}</td>
                        <td>{{$appointment->start_time}} - {{$appointment->end_time}}</td>
                        <td>{{$appointment->user->name}} {{$appointment->user->surname}}</td>
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
</div>
@endsection