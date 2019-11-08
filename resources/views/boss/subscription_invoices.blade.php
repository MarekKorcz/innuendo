@extends('layouts.app')
@section('content')

{!! Html::style('css/subscription_invoices.css') !!}
{!! Html::script('js/subscription_invoices.js') !!}

<div class="container"  style="padding: 2rem 0 2rem 0;">
    <div class="jumbotron">
        <div class="text-center">
            <h2>
                @lang('common.subscription_capital') 
                {!! $subscription->name !!}
            </h2>
        </div>
        
        <div class="text-center" style="margin: 1rem;">
            <a href="{{ URL::to('/boss/subscription/invoice/edit/' . $invoiceData->id . '/' . $substart->id) }}" class="btn pallet-1-3" style="color: white;">
                @lang('common.edit_invoice_data')
            </a> 
        </div>
        <h4 class="text-center">@lang('common.invoice_for_period'):</h4>
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
</div>
@endsection