@extends('layouts.app')
@section('content')

{!! Html::style('css/subscription_workers_edit.css') !!}
{!! Html::script('js/subscription_workers_edit.js') !!}

<div class="container">
    
    <div class="text-center">
        <h2>Osoby przypisane do subskrypcji - {{$subscription->name}}</h2>
    </div>
    <div class="wrapper cont">
        <div class="text-center">
            <div class="row">
                <div class="offset-sm-3 offset-md-3 offset-lg-3"></div>
                    <div class="col-6">
                        <label for="timePeriod" style="font-size: 24px;">Wybierz okres rozliczeniowy:</label>                        
                        <ul id="timePeriod" class="list-group">
                            @foreach ($substartIntervals as $substartInterval)
                                @if ($substartInterval->workers)
                                    <a href="{{ URL::to('/boss/subscription/workers/edit/' . $substart->id . '/' . $substartInterval->id) }}" style="text-decoration: none;">
                                        <li class="list-group-item clicked">
                                            {{$substartInterval->start_date->format("Y-m-d")}} - {{$substartInterval->end_date->format("Y-m-d")}}
                                        </li>
                                    </a>   
                                @else
                                    <a href="{{ URL::to('/boss/subscription/workers/edit/' . $substart->id . '/' . $substartInterval->id) }}" style="text-decoration: none;">
                                        <li class="list-group-item">
                                            {{$substartInterval->start_date->format("Y-m-d")}} - {{$substartInterval->end_date->format("Y-m-d")}}
                                        </li>
                                    </a>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                <div class="offset-sm-3 offset-md-3 offset-lg-3"></div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12 col-md-12 col-lg-12 col-12">
        <h2 class="text-center">
            Pracownicy przypisani
            @if (count($substartIntervals) > 0)
                @foreach ($substartIntervals as $substartInterval)
                    @if ($substartInterval->workers)
                        do okresu od {{$substartInterval->start_date->format('Y-m-d')}} do {{$substartInterval->end_date->format('Y-m-d')}}
                    @endif
                @endforeach
            @endif
        </h2>
    </div>
    
    <div id="workers-table">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <td class="text-center">Włącz</td>
                    <td class="text-center">Wyłącz</td>
                    <td>Imię i Nazwisko</td>
                    <td>Email</td>
                </tr>
            </thead>
            <tbody id="workers">
                {{ Form::open(['id' => 'subscription-workers-update', 'action' => ['BossController@subscriptionWorkersUpdate'], 'method' => 'POST']) }}
                    
                    <div class="form-row">
                        @if ($substart->isActive == 1)
                            @foreach($substartIntervals as $substartInterval)
                                @if ($substartInterval->workers)
                                    @foreach($substartInterval->workers as $worker)
                                        <tr>
                                            @if ($today > $substartInterval->start_date && $today > $substartInterval->end_date)
                                                @if ($worker->withoutSubscription == false)
                                                    <td>
                                                        <div class="text-center">
                                                            Posiadał subskrypcje
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                @else
                                                    <td></td>
                                                    <td>
                                                        <div class="text-center">
                                                            Nie posiadał subskrypcji
                                                        </div>
                                                    </td>
                                                @endif
                                            @elseif ($today >= $substartInterval->start_date && $today <= $substartInterval->end_date)
                                                @if ($worker->withoutSubscription == false)
                                                    <td>
                                                        <div class="text-center">
                                                            Posiada subskrypcje
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                @else
                                                    <td>
                                                        <div class="text-center">
                                                            <input type="checkbox" name="workers_on[]" value="{{$worker->id}}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            Nie posiada subskrypcji
                                                        </div>
                                                    </td>
                                                @endif
                                            @else
                                                @if ($worker->withoutSubscription == false)
                                                    <td>
                                                        <div class="text-center">
                                                            Posiada subskrypcje
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <input type="checkbox" name="workers_off[]" value="{{$worker->id}}">
                                                        </div>
                                                    </td>
                                                @else
                                                    <td>
                                                        <div class="text-center">
                                                            <input type="checkbox" name="workers_on[]" value="{{$worker->id}}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            Nie posiada subskrypcji
                                                        </div>
                                                    </td>
                                                @endif
                                            @endif
                                            <td>{{$worker->name}} {{$worker->surname}}</td>
                                            <td>{{$worker->email}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @else
                            @foreach($substartIntervals as $substartInterval)
                                @if ($substartInterval->workers)
                                    @foreach($substartInterval->workers as $worker)
                                        <tr>
                                            @if ($today >= $substartInterval->start_date && $today <= $substartInterval->end_date)
                                                @if ($worker->withoutSubscription == false)
                                                    <td>
                                                        <div class="text-center">
                                                            Posiada subskrypcje
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <input type="checkbox" name="workers_off[]" value="{{$worker->id}}">
                                                        </div>
                                                    </td>
                                                @else
                                                    <td>
                                                        <div class="text-center">
                                                            <input type="checkbox" name="workers_on[]" value="{{$worker->id}}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            Nie posiada subskrypcji
                                                        </div>
                                                    </td>
                                                @endif                                 
                                            @endif
                                            <td>{{$worker->name}} {{$worker->surname}}</td>
                                            <td>{{$worker->email}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </div>
            
                    @foreach($substartIntervals as $substartInterval)
                        @if ($substartInterval->workers)
                            @if ($today > $substartInterval->start_date && $today > $substartInterval->end_date)
                            
                            @else
                                {{ Form::hidden('substart_id', $substart->id) }}
                                {{ Form::hidden('interval_id', $substartInterval->id) }}
                                
                                <div class="text-center" style="margin: 1rem;">
                                    {{ Form::submit('Aktualizuj', array('class' => 'btn btn-primary')) }}
                                    <div id="submit-warning" class="warning"></div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                    
                {{ Form::close() }}
            </tbody>
        </table>
    </div>
</div>
@endsection