@extends('layouts.app')

@section('content')

<!--{!! Html::script('js/property_show.js') !!}-->

<div class="container">

    <h1 class="text-center" style="padding: 2rem;">{{$purchase->subscription->name}}</h1>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3>@lang('common.description')</h3>
                <p>Nazwa: <strong>{{$purchase->subscription->name}}</strong></p>
                <p>{{$purchase->subscription->description}}</p>
                
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h2>Status subskrypcji</h2>
                @if ($expirationDate !== null)
                    @if ($intervalAvailableUnits !== null)
                        <p>Dostępne zabiegi w obecnym miesiącu (od {{$substartInterval->start_date->format('Y-m-d')}} do {{$substartInterval->end_date->format('Y-m-d')}}): <strong>{{$intervalAvailableUnits}}</strong></p>
                    @endif
                    <p>Ważny do: <strong>{{$expirationDate}}r</strong></p>
                @else
                    <p>Subskrypcja nieaktywna. Aktywacja nastąpi wraz z pierwszym wykonanym zabiegiem</p>
                @endif
            </div>
        </div>
    </div>
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12">
                <h2 class="text-center">Lista wizyt</h2>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>                
                            <td>Data</td>
                            <td>Godzina</td>
                            <td>@lang('common.address')</td>
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
                                <td>
                                    <a href="{{ URL::to('/employee/' . $appointment->employee_slug) }}" target="_blanc">
                                        {{$appointment->employee}}
                                    </a>
                                </td>
                                <td>
                                    {{config('appointment-status.' . $appointment->status)}}
                                </td>
                                <td>
                                    <a class="btn btn-primary" href="{{ URL::to('/appointment/show/' . $appointment->id) }}">
                                        @lang('common.show')
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection