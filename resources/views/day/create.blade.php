@extends('layouts.app')
@section('content')
<div class="container">

    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4"></div>
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn btn-success" href="{{ URL::previous() }}">
                @lang('common.back_to_month')
            </a>
        </div>
    </div>

    <div class="text-center">
        <h2>@lang('common.create_days')</h2>
    </div>

    <form action="{{ action([App\Http\Controllers\DayController::class, 'store']) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="start_day">@lang('common.start_day')</label>
            <input type="number" name="start_day" value="{{ old('start_day') }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="end_day">@lang('common.end_day')</label>
            <input type="number" name="end_day" value="{{ old('end_day') }}" class="form-control">
        </div>
        @if ($month)
            <input type="hidden" name="month_id" value="{{ $month->id }}">
        @else
            <input type="hidden" name="month_id" value="{{ old('month_id') }}">
        @endif

        <div class="text-center" style="padding-bottom: 2rem;">
            <input type="submit" value="@lang('common.create')" class="btn btn-primary">
        </div>
    </form>


</div>
@endsection
