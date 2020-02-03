@extends('layouts.app')
@section('content')
<div class="container">

    <div style="padding: 2rem 0 2rem 0">
        
        <div class="row text-center" style="padding: 1rem 0 2rem 0;">
            <div class="col-4">
                <a href="{{ URL::to('property/' . $property->id) }}" class="btn btn-success">
                    @lang('common.go_back')
                </a>
            </div>
            <div class="col-4">
                <a href="{{ URL::to('property/add-invoice/' . $property->id) }}" class="btn pallet-1-3" style="color: white;">
                    @lang('common.add_invoice')
                </a>
            </div>
            <div class="col-4"></div>
        </div>

        
        <div class="text-center" style="padding-bottom: 1rem;">
            <h1>
                @lang('common.invoices')
                @lang('common.in')
                {{$property->name}}
            </h1>
        </div>

        @if (count($invoices) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td>@lang('common.invoice')</td>
                        <td>@lang('common.date')</td>
                        <td>@lang('common.action')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice }}</td>
                        <td>{{ $invoice->month->year->year }} {{ $invoice->month->month }}</td>
                        <td>
                            <a class="btn btn-small pallet-1-3" style="color: white;" href="{{ URL::to('property/invoice/' . $invoice->id) }}">
                                @lang('common.show')
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center">
                <h3>@lang('common.no_invoices')</h3>
            </div>
        @endif
    </div>
</div>
@endsection