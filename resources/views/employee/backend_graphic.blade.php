@extends('layouts.app')
@section('content')

{!! Html::style('css/backend_graphic.css') !!}

<div class="container" style="padding: 2rem 0 2rem 0;">

    <div class="text-center" style="padding-top: 1rem;">
        <h2>@lang('common.schedule_in'):</h2>
    </div>

    <div class="row" style="padding-bottom: 1rem;">
        <div class="offset-1"></div>
        <div class="col-10">            
            <ul>
                @foreach($properties as $property)  
                    <div id="schedules" class="text-center">
                        <a href="{{ URL::to('employee/backend-calendar/' . $property->id . '/0/0/0') }}">
                            <li>
                                <strong>
                                    {{$property->name}}
                                </strong>
                                -
                                {{$property->street}} {{$property->street_number}} / {{$property->house_number}}, {{$property->city}}
                            </li>
                        </a>
                    </div>
                @endforeach
            </ul>
        </div>
        <div class="offset-1"></div>
    </div>
</div>
@endsection