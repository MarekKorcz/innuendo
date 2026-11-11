@extends('layouts.app')
@section('content')
<div class="container">

    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4"></div>
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn btn-success" href="{{ URL::to('/property/' . $property->id) }}">
                @lang('common.back_to_property')
            </a>
        </div>
    </div>

    <div class="text-center" style="padding: 1rem;">
        <h1>@lang('common.create_year')</h1>

        <form action="{{ action([App\Http\Controllers\YearController::class, 'store']) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="year">@lang('common.year')</label>
                <input type="number" name="year" value="{{ old('year') }}" class="form-control">
            </div>
            @if ($property !== null)
                <input type="hidden" name="property_id" value="{{ $property->id }}">
            @else
                <input type="hidden" name="property_id" value="{{ old('property_id') }}">
            @endif

            <input type="submit" value="@lang('common.create')" class="btn btn-primary">
        </form>

    </div>

</div>
@endsection
