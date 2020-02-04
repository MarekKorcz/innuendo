@extends('layouts.app')
@section('content')

{!! Html::style('css/invoices_in.css') !!}
{!! Html::script('js/invoices_in.js') !!}

<div class="container" style="padding: 1rem 0 1rem 0;">
    
    <div class="row text-center" style="padding-bottom: 1rem;">
        <div class="col-4"></div>
        <div class="col-4">
            <a href="{{ URL::to('/boss/invoice/edit/' . $property->id) }}" class="btn pallet-1-3" style="color: white;">
                @lang('common.edit_invoice_data')
            </a>
        </div>
        <div class="col-4"></div>
    </div>
    
    <div class="jumbotron" style="padding: 1rem;">
        @if (count($invoices) > 0)
            <div class="text-center">
                <h2>
                    @lang('common.invoices_in') 
                    {{$property->name}}
                    @lang('common.for_period') 
                </h2>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <ul id="invoices" class="list-group text-center" style="padding: 2rem;">
                        @foreach ($invoices as $invoice)
                            <a download="{{$invoice->invoice}}" href="{{ Storage::url($invoice->invoice) }}">
                                <li class="list-group-item">
                                    {{$invoice->invoice}}
                                </li>
                            </a>
                        @endforeach
                    </ul>
                </div>
                <div class="col-1"></div>
            </div>
        @else
            <div class="text-center">
                <h2>
                    @lang('common.invoices_in') 
                    {{$property->name}}
                </h2>
                <p>Brak faktur</p>
            </div>
        @endif
    </div>
</div>
@endsection