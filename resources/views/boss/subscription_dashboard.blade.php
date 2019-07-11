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
        @foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
            @if ($propertyWithSubscriptions['property']->isChecked)
                <div class="text-center highlighted" data-property_id="{{$propertyWithSubscriptions['property']->id}}">
            @else
                <div class="text-center box" data-property_id="{{$propertyWithSubscriptions['property']->id}}">
            @endif
                    <div class="data">
                        <p><strong>{{$propertyWithSubscriptions['property']->name}}</strong></p>
                        @if ($propertyWithSubscriptions['property']->description)
                            {!!$propertyWithSubscriptions['property']->description!!}
                        @endif
                        <p>Adres: 
                            <strong>
                                {{$propertyWithSubscriptions['property']->street}} 
                                {{$propertyWithSubscriptions['property']->street_number}} / 
                                {{$propertyWithSubscriptions['property']->house_number}} 
                                {{$propertyWithSubscriptions['property']->city}}
                            </strong>
                        </p>
                    </div>
                    <a class="btn btn-primary" href="{{ URL::to('boss/property/' . $propertyWithSubscriptions['property']->id . '/edit') }}">
                        Edytuj
                    </a>
                    <a class="btn btn-light" href="{{ URL::to('user/property/' . $propertyWithSubscriptions['property']->id) }}">
                        Grafiki
                    </a>
                </div>
        @endforeach
    </div>
            
    <div class="text-center">
        <h2>Dostępne subskrypcje:</h2>
    </div>
    <div id="subscriptions" class="wrapper cont">
        @foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
            @if ($propertyWithSubscriptions['property']->isChecked == true)
                @foreach ($propertyWithSubscriptions['subscriptions'] as $subscription)
                    @if ($subscription->isChecked)
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
                        @if (count($subscription->purchases) == 0)
                            <a class="btn btn-primary" href="{{ URL::to('/boss/subscription/purchase/' . $propertyWithSubscriptions['property']->id . '/' . $subscription->id) }}">
                                Kup subskrypcje
                            </a>
                        @endif
                    </div>
                @endforeach
            @endif
        @endforeach
    </div>
            
    <div id="substarts-header" class="text-center">
        <h2>Okres trwania subskrypcji:</h2>
    </div>
    <div id="substarts" class="wrapper cont">
        @foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
            @if ($propertyWithSubscriptions['property']->isChecked == true)
                @foreach ($propertyWithSubscriptions['subscriptions'] as $subscription)
                    @if ($subscription->isChecked && count($subscription->purchases) > 0)
                        @foreach ($subscription->purchases as $purchase)
                            @if ($purchase->substart !== null)
                                @if ($purchase->substart->isCurrent)
                                    <div class="substart text-center highlighted" data-substart_id="{{$purchase->substart->id}}">
                                @else
                                    <div class="substart text-center box" data-substart_id="{{$purchase->substart->id}}">
                                @endif
                                    <div class="data">
                                        <p>
                                            Od: <strong>{{$purchase->substart->start_date->format('Y-m-d')}}</strong> 
                                            do: <strong>{{$purchase->substart->end_date->format('Y-m-d')}}</strong>
                                        </p>
                                        @if ($purchase->substart->isCurrent)
                                            @if ($purchase->substart->isActive == 1)
                                                <p>Aktywowana</p>
                                            @elseif ($purchase->substart->isActive == 0)
                                                <p>Nieaktywowana</p>
                                            @endif
                                        @else
                                            <p>Czas trwania dobiegł końca</p>
                                        @endif
                                    </div>
                                    @if ($purchase->substart->isActive == 1)
                                        <a class="btn btn-primary" href="{{ URL::to('/boss/subscription/invoices/' . $purchase->substart->id) }}">
                                            Rozliczenia
                                        </a>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>
                                
    <div id="workers">
        @foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
            @if ($propertyWithSubscriptions['property']->isChecked == true)
                @foreach ($propertyWithSubscriptions['subscriptions'] as $subscription)
                    @if ($subscription->isChecked && count($subscription->purchases) > 0)
                        @foreach ($subscription->purchases as $purchase)
                            @if ($purchase->substart !== null)
                                @if ($purchase->substart->isCurrent)
                                    @if (count($purchase->substart->workers) > 0)
                                        <div class="text-center">
                                            <h2>Osoby przypisane do danej subskrypcji:</h2>
                                            <a class="btn btn-primary" href="{{ URL::to('boss/subscription/workers/edit/' . $substart->id . '/0') }}">
                                                Edycja
                                            </a>
                                            <a class="btn btn-primary" href="{{ URL::to('boss/worker/appointment/list/' . $substart->id . '/0') }}">
                                                Wszystkie wizyty
                                            </a>
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
                                                @foreach ($purchase->substart->workers as $worker)
                                                    <tr>
                                                        <td>{{$worker['name']}}</td>
                                                        <td>{{$worker['surname']}}</td>
                                                        <td>{{$worker['email']}}</td>
                                                        <td>{{$worker['phone_number']}}</td>
                                                        <td>
                                                            <a class="btn btn-primary" href="{{ URL::to('boss/worker/appointment/list/' . $substart->id . '/' . $worker['id']) }}">
                                                                Pokaż
                                                            </a>
                                                        </td>
                                                    </tr>                 
                                                @endforeach
                                                </tbody> 
                                            </table>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>
</div>
@endsection