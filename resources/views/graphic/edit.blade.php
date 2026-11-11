@extends('layouts.app')
@section('content')
<div class="container">

    <div class="row text-center" style="padding: 2rem 0 2rem 0;">
        <div class="col-4"></div>
        <div class="col-4"></div>
        <div class="col-4">
            <a href="{{ URL::to('/day/show/' . $day->id) }}" class="btn btn-success">
                @lang('common.back_to_day')
            </a>
        </div>
    </div>

    <div class="jumbotron">
        <div class="text-center">
            <h1>@lang('common.edit_schedule')</h1>
        </div>

        <form action="{{ action([App\Http\Controllers\GraphicController::class, 'update']) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="start_time">@lang('common.start_time')</label>
                <input class="form-control" type="time" name="start_time" value="{{$graphic->start_time}}">
            </div>
            <div class="form-group">
                <label for="end_time">@lang('common.end_time')</label>
                <input class="form-control" type="time" name="end_time" value="{{$graphic->end_time}}">
            </div>
            <div class="form-group">
                <label for="employee_id">@lang('common.employees'):</label>
                <select name="employee_id" class="form-control">
                    @foreach ($employees as $employee)
                        @if ($employee->id == $graphic->employee->id)
                            <option value="{{$employee->id}}" selected="true">{{$employee->name}} {{$employee->surname}}</option>
                        @else
                            <option value="{{$employee->id}}">{{$employee->name}} {{$employee->surname}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="graphic_id" value="{{ $graphic->id }}">

            <div class="text-center">
                <input type="submit" value="@lang('common.update')" class="btn btn-primary">
            </div>
        </form>

    </div>
</div>
@endsection
