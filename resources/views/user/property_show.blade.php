@extends('layouts.app')

@section('content')

{!! Html::style('css/property_show.css') !!}

<div class="container">

    <h1 class="text-center" style="padding-top: 1rem;">@lang('common.schedule_in') <strong>{{ $property->name }}</strong></h1>
   
<!--    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="jumbotron text-center" style="font-size: 21px;">
                <h3>@lang('common.description')</h3>
                <p>@lang('common.label'): <strong>{{$property->name}}</strong></p>
                <p>@lang('common.creation_date'): <strong>{{ $propertyCreatedAt }}</strong></p>
                @if ($property->description)
                    <span>@lang('common.description'): {!! $property->description !!}</span>
                @endif
                <p>@lang('common.address'): <strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}</strong></p>
            </div>
        </div>
        <div class="col-1"></div>
    </div>-->
    
    <div class="text-center">
        <h2 style="padding-top: 1rem;">@lang('common.our_employees_schedules'):</h2>
    </div>
    
    <div class="wrapper">
        @if (count($employees) == 1)
            <div class="row">
                <div class="col-1 col-sm-2 col-md-3 col-lg-4"></div>
                <div class="col-10 col-sm-8 col-md-6 col-lg-4">
                    <div class="card-body">
                        @foreach ($employees as $employee)
                            <h3 class="card-title text-center">{{$employee->name}} {{$employee->surname}}</h3>
                            @if (Storage::disk('local')->has($employee->profile_image))
                                <div style="padding: 1rem;">
                                    <img src="{{ route('account.image', ['fileName' => $employee->profile_image]) }}" 
                                         alt="{{$employee->name}} {{$employee->surname}}" 
                                         style="width: 100%;"
                                         border="0"
                                    >
                                </div>
                            @endif
                            <p class="card-text">
                                {!!$employee->description!!}
                            </p>
                            <div class="text-center">
                                <a href="{{ URL::to('employee/' . $employee->slug) }}" class="btn pallet-1-3 btn-lg" style="color: white;">
                                    @lang('common.profile')
                                </a>
                                <a href="{{ URL::to('employee/calendar/' . $employee->calendar . '/' . $today['year'] . '/' . $today['month'] . '/' . $today['day'] ) }}" class="btn pallet-2-3 btn-lg" style="color: white;">
                                    @lang('common.schedule')
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-1 col-sm-2 col-md-3 col-lg-4"></div>
            </div>
        @elseif (count($employees) > 0)
            @foreach ($employees as $employee)
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">{{$employee->name}} {{$employee->surname}}</h3>
                        @if (Storage::disk('local')->has($employee->profile_image))
                            <div style="padding: 1rem;">
                                <img src="{{ route('account.image', ['fileName' => $employee->profile_image]) }}" 
                                     alt="{{$employee->name}} {{$employee->surname}}" 
                                     style="width: 100%;"
                                     border="0"
                                >
                            </div>
                        @endif
                        <p class="card-text">
                            {!!$employee->description!!}
                        </p>
                        <div class="text-center">
                            <a href="{{ URL::to('employee/' . $employee->slug) }}" class="btn pallet-1-3 btn-lg" style="color: white;">
                                @lang('common.profile')
                            </a>
                            <a href="{{ URL::to('employee/calendar/' . $employee->calendar . '/' . $today['year'] . '/' . $today['month'] . '/' . $today['day'] ) }}" class="btn pallet-2-3 btn-lg" style="color: white;">
                                @lang('common.schedule')
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <h3 class="text-center">
                @lang('common.no_employee_assigned_to_property')
            </h3>
        @endif
    </div>
</div>
@endsection