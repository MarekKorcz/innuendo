@extends('layouts.app')
@section('content')

{!! Html::style('css/property_invoice.css') !!}
{!! Html::script('js/property_invoice.js') !!}

<div class="container" style="padding-top: 2rem;">
    
    <div class="row text-center" style="padding-bottom: 1rem;">
        <div class="col-4">
        </div>
        <div class="col-4">
            <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('property/invoice/list/' . $invoice->property->id) }}">
                @lang('common.go_back')
            </a>
        </div>
        <div class="col-4">
            <a class="btn pallet-2-2 delete-invoice" style="color: white;" data-invoice_id="{{$invoice->id}}">
                @lang('common.delete')
            </a>
        </div>
    </div>
    
    <div class="jumbotron">
        <div class="text-center">
            <h1>{{$invoice->invoice}}</h1>
            <p>
                @lang('common.date'): 
                <strong>
                    {{$invoice->month->year->year}} - {{$invoice->month->month}}
                </strong>
            </p>
        </div>
    </div>
    
    <div id="deleteInvoice" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.invoice_delete')</h4>
                <button id="deleteInvoiceCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <form method="POST" accept-charset="UTF-8">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="@lang('common.delete')" class="btn pallet-2-2" style="color: white;">
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="background"></div>
    
</div>
@endsection