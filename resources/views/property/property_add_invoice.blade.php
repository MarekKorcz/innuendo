@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/property_show.css') }}">

<div class="container">

    <div class="text-center padding">
        <h1>
            @lang('common.add_invoice')
            @lang('common.to')
            {{$property->name}}
        </h1>
    </div>

    <div class="jumbotron">
        <div class="row text-center">
            <div class="col-12">
                @if ($property->invoiceData !== null)
                    <form action="{{ action([App\Http\Controllers\PropertyController::class, 'addInvoiceUpdate']) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="month_id">@lang('common.months'):</label>
                            <select name="month_id" class="form-control">
                                @if (count($property->years) > 0)
                                    @foreach ($property->years as $year)
                                        @if (count($year->months) > 0)
                                            @foreach ($year->months as $month)
                                                <option value="{{$month->id}}">
                                                    {{$year->year}} - {{$month->month}}
                                                </option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="file" name="invoice" class="form-control">
                        </div>

                        <input type="hidden" name="property_id" value="{{ $property->id }}">

                        <div class="text-center">
                            <input type="submit" value="@lang('common.add_invoice')" class="btn pallet-2-4" style="color: white;">
                        </div>
                    </form>
                @else
                    <p>
                        @lang('common.no_invoice_datas')
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
