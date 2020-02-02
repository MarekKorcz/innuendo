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
    
    <div class="jumbotron">
        <div class="text-center">
            <h2>
                @lang('common.invoices_in') 
                {{$property->name}}
                @lang('common.for_period') 
            </h2>
        </div>
        
        <div class="row">
            <div class="col"></div>
            <div class="col-9">
                
                show invoices!!
                
<!--                <ul id="invoices" class="list-group">
                    
                </ul>-->
            </div>
            <div class="col"></div>
        </div>
    </div>
</div>
@endsection