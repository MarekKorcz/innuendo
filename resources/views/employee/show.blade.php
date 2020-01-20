@extends('layouts.app')

@section('content')

{!! Html::style('css/property_show.css') !!}

<div class="container">

    <h1 class="text-center padding">{{ $employee->name }} {{$employee->surname}}</h1>
    
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10" style="padding-bottom: 2rem;">
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
            
            @if (count($properties) > 0)
                <div class="text-center">
                    <h2>@lang('common.schedule_in'):</h2>
                </div>

                <ul>
                    @foreach ($properties as $property)
                        <div id="properties" class="text-center">
                            @if ($user->isBoss)
                                <a href="{{ URL::to('boss/calendar/' . $property->id . '/0/0/0') }}">
                            @else
                                <a href="{{ URL::to('user/calendar/' . $property->id . '/0/0/0') }}">
                            @endif
                                    <li>
                                        <strong>
                                            {{$property->name}}
                                        </strong>
                                        -
                                        {{$property->street}} {{$property->street_number}} {{$property->house_number}}, {{$property->city}}
                                    </li>
                                </a>
                        </div>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection