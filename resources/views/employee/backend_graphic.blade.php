@extends('layouts.app')
@section('content')

{!! Html::style('css/backend_graphic.css') !!}

<div class="container">

    <div class="text-center" style="padding: 1rem;">
        <h1>@lang('common.schedule_in') :</h1>
    </div>

    <div class="row" style="padding-bottom: 1rem;">
        <div class="offset-1"></div>
        <div class="col-10">
            <table class="table table-striped table-bordered text-center">
                <thead>
                    <tr>                
                        <td>@lang('common.label')</td>
                        <!--<td>@lang('common.description')</td>-->
                        <td>@lang('common.action')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($calendars as $calendar)            
                        <tr>
                            <td>{{$calendar->property->name}}</td>
                            <!--<td>{!!$calendar->property->description!!}</td>-->
                            <td>
                                <a class="btn btn-success" href="{{ URL::to('employee/backend-calendar/' . $calendar->id . '/0/0/0') }}">
                                    @lang('common.show')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="offset-1"></div>
    </div>
</div>
@endsection