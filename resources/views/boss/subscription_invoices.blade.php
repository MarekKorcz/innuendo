@extends('layouts.app')
@section('content')

{!! Html::style('css/subscription_invoices.css') !!}
{!! Html::script('js/subscription_invoices.js') !!}

<div class="container">
    <h1 class="text-center">Subskrypcja - {{$subscription->name}}</h1>
    <h3 class="text-center">Faktury za okres:</h3>
    <div class="row">
        <div class="col"></div>
        <div class="col-9">
            <ul id="invoices" class="list-group">
                @foreach ($intervals as $interval)
                    @if ($interval->state == "existing")
                        <a href="{{ URL::to('/boss/subscription/invoice/' . $interval->id) }}">
                            <li class="list-group-item existing-invoice">
                                {{$interval->start_date->format("Y-m-d")}} - {{$interval->end_date->format("Y-m-d")}}
                            </li>
                        </a>   
                    @elseif ($interval->state == "nonexistent")
                        <li class="list-group-item nonexistent-invoice">
                            {{$interval->start_date->format("Y-m-d")}} - {{$interval->end_date->format("Y-m-d")}}
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="col"></div>
    </div>
</div>
@endsection