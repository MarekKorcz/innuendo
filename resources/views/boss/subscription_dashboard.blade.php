@extends('layouts.app')
@section('content')

{!! Html::script('js/subscription_dashboard.js') !!}
{!! Html::style('css/subscription_dashboard.css') !!}

<div class="container">
    <div class="text-center">
        <h1>Widok Twoich Subskrypcji</h1>
        <h2>Wybierz lokalizacje której subskrypcje chcesz zobaczyć:</h2>
    </div>
    <div id="properties" class="wrapper cont">
        @foreach ($propertiesWithPurchasedSubscriptions as $propertyWithPurchasedSubscriptions)
            @if ($propertyWithPurchasedSubscriptions['property']->isSelected)
                <div class="text-center highlighted" data-property_id="{{$propertyWithPurchasedSubscriptions['property']->id}}">
            @else
                <div class="text-center box" data-property_id="{{$propertyWithPurchasedSubscriptions['property']->id}}">
            @endif
                    <div class="data">
                        <p><strong>{{$propertyWithPurchasedSubscriptions['property']->name}}</strong></p>
                        {!!$propertyWithPurchasedSubscriptions['property']->description!!}
                        <p>Adres: 
                            <strong>
                                {{$propertyWithPurchasedSubscriptions['property']->street}} 
                                {{$propertyWithPurchasedSubscriptions['property']->street_number}} / 
                                {{$propertyWithPurchasedSubscriptions['property']->house_number}} 
                                {{$propertyWithPurchasedSubscriptions['property']->city}}
                            </strong>
                        </p>
                    </div>
                </div>
        @endforeach
    </div>
    <div class="text-center">
        <h2>Subskrypcje wykupione dla wybranej lokalizacji:</h2>
    </div>
    <div id="subscriptions" class="wrapper cont">
        @foreach ($propertiesWithPurchasedSubscriptions as $propertyWithPurchasedSubscriptions)
            @if ($propertyWithPurchasedSubscriptions['property']->isSelected == true)
                @foreach ($propertyWithPurchasedSubscriptions['subscriptions'] as $subscription)
                    @if ($subscription->isSelected)
                        <div class="text-center highlighted" data-subscription_id="{{$subscription->id}}">
                    @else
                        <div class="text-center box" data-subscription_id="{{$subscription->id}}">
                    @endif
                        <div class="data">
                            <p>Nazwa: <strong>{{$subscription->name}}</strong></p>
                            {!!$subscription->description!!}
                            <p>Cena regularna: <strong>{{$subscription->old_price}}</strong></p>
                            <p>Cena z subskrypcją: <strong>{{$subscription->new_price}}</strong></p>
                            <p>Ilość zabiegów w miesiącu: <strong>{{$subscription->quantity}}</strong></p>
                            <p>Czas subskrypcji (w miesiącach): <strong>{{$subscription->duration}}</strong></p>
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach
    </div>
                    
    <div id="substarts-header" class="text-center">
        <h2>Okres trwania subskrypcji:</h2>
    </div>
    <div id="substarts" class="wrapper cont">
        @foreach ($substarts as $substart)
            @if ($substart->id == $newestSubstart->id)
                <div class="substart text-center highlighted" data-substart_id="{{$newestSubstart->id}}">
            @else
                <div class="substart text-center box" data-substart_id="{{$substart->id}}">
            @endif
                <div class="data">
                    <p>
                        Od: <strong>{{$substart->start_date->format('Y-m-d')}}</strong> 
                        do: <strong>{{$substart->end_date->format('Y-m-d')}}</strong>
                    </p>                    
                    @if ($substart->end_date < $today)
                        <p>Czas trwania dobiegł końca</p>
                    @elseif ($substart->start_date <= $today && $today <= $substart->end_date)
                        @if ($substart->isActive == 1)
                            <p>Aktywna</p>
                        @elseif ($substart->isActive == 0)
                            <p>Nieaktywna</p>
                        @endif
                    @endif
                </div>
            </div>            
        @endforeach
    </div>
                    
    <div id="workers">
        <div class="text-center">                        
            <p>
                <h2>Pracownicy przypisani do danej subskrypcji:</h2>
                <a class="btn btn-primary" href="{{ URL::to('boss/worker/appointment/list/' . $propertyId . '/' . $subscriptionId . '/0') }}">
                    Wszystkie wizyty pracowników
                </a>
            </p>
        </div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>                
                    <td>Imie</td>
                    <td>Nazwisko</td>
                    <td>Email</td>
                    <td>Telefon</td>
                    <td>Wizyty</td>
                </tr>
            </thead>
            <tbody id="workersTable">
                @if ($workers !== null)
                    @foreach ($workers as $worker)
                        <tr>
                            <td>{{$worker['name']}}</td>
                            <td>{{$worker['surname']}}</td>
                            <td>{{$worker['email']}}</td>
                            <td>{{$worker['phone_number']}}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ URL::to('boss/worker/appointment/list/' . $propertyId . '/' . $subscriptionId . '/' . $worker['id']) }}">
                                    Pokaż
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection