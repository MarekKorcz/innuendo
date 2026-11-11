@extends('layouts.app')
@section('content')
<div class="container">

    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4"></div>
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn btn-success" href="{{ URL::previous() }}">
                @lang('common.back_to_year')
            </a>
        </div>
    </div>

    <div class="text-center">
        <h1>@lang('common.create_month')</h1>
    </div>

    <form action="{{ action([App\Http\Controllers\MonthController::class, 'store']) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="month">@lang('common.month')</label>
            <input type="text" name="month" value="{{ old('month') }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="month_en">@lang('common.month_en')</label>
            <input type="text" name="month_en" value="{{ old('month_en') }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="month_number">@lang('common.month_number')</label>
            <input type="number" name="month_number" value="{{ old('month_number') }}" class="form-control">
        </div>
        @if ($year)
            <input type="hidden" name="year_id" value="{{ $year->id }}">
        @else
            <input type="hidden" name="year_id" value="{{ old('year_id') }}">
        @endif

        <div class="text-center" style="padding-bottom: 1rem;">
            <input type="submit" value="@lang('common.create')" class="btn btn-primary">
        </div>
    </form>


</div>
@endsection
