@extends('layouts.app')

@section('content')

<!--{!! Html::script('js/property_show.js') !!}-->

<div class="container">

    <h1 class="text-center" style="padding: 2rem;">{{$purchase->subscription->name}}</h1>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3>Opis</h3>
                <p>Nazwa: <strong>{{$purchase->subscription->name}}</strong></p>
                <p>Opis: <strong>{{$purchase->subscription->description}}</strong></p>
                
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h2>Stan subskrypcji</h2>
                <p>Dostępne zabiegi w obecnym miesiącu: <strong>{{$purchase->available_units}}</strong></p>
                <p>Ważny do: <strong>{{$expirationDate}}r</strong></p>
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
        </div>
    </div>
</div>
@endsection