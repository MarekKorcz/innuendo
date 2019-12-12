@extends('layouts.app')

@section('content')

{!! Html::style('css/property_show.css') !!}

<div class="container">

    <h1 class="text-center padding">{{ $employee->name }} {{$employee->surname}}</h1>
    
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="row">
                <div class="col-6">
                    <h3 style="padding: 9px;">@lang('common.description')</h3>
                    <p>@lang('common.name'): <strong>{{$employee->name}} {{$employee->surname}}</strong></p>
                    <p>@lang('common.email_address'): <strong>{{$employee->email}}</strong></p>
                    <p>@lang('common.working_since'): <strong>{{ $employeeCreatedAt }}</strong></p>
                </div>
                <div class="col-6">
                    <div class="text-center">
                        <!--todo: dodaj zdjęcie-->
                        @if (Storage::disk('local')->has($employee->profile_image))
                            <div style="padding: 1rem;">
                                <img src="{{ route('account.image', ['fileName' => $employee->profile_image]) }}" 
                                     alt="{{$employee->name}} {{$employee->surname}}" 
                                     style="width:100%;"
                                     border="0"
                                >
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @if ($calendars && count($calendars) == count($properties))
                <div class="text-center">
                    <h2>@lang('common.schedule_in'):</h2>
                </div>

                <ul>
                    @for ($i = 1; $i <= count($calendars); $i++)
                        <div id="properties" class="text-center">
                            @if ($user->isBoss)
                                <a href="{{ URL::to('boss/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
                            @else
                                <a href="{{ URL::to('user/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
                            @endif
                                    <li>
                                        <strong>
                                            {{$properties[$i - 1]->name}}
                                        </strong>
                                        -
                                        {{$properties[$i - 1]->street}} {{$properties[$i - 1]->street_number}} / {{$properties[$i - 1]->house_number}}, {{$properties[$i - 1]->city}}
                                    </li>
                                </a>
                        </div>
                    @endfor
                </ul>
            @endif
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection