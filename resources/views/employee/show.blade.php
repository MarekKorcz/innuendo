@extends('layouts.app')

@section('content')

{!! Html::style('css/property_show.css') !!}

<div class="container">

    <h1 class="text-center padding">{{ $employee->name }} {{$employee->surname}}</h1>
    
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="jumbotron">
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
            </div>
        </div>
        <div class="col-1"></div>
    </div>

    @if ($calendars && count($calendars) == count($properties))
    
        <h2 class="text-center padding-top">@lang('common.schedule_in') :</h2>
    
        @for ($i = 1; $i <= count($calendars); $i++)
            @if ($i == 1 || $i == 4 || $i == 7 || $i == 10)
                <div class="row padding">
                    @if (count($properties[$i - 1]) > 0)
                        <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                            <div class="card">
                                <div class="text-center">
                                    @svg('solid/home')
                                </div>
                                <div class="card-body">
                                        <h5 class="card-title text-center">{{$properties[$i - 1]->name}}</h5>
                                        <p class="card-text">
                                            {!!$properties[$i - 1]->description!!}
                                        </p>
                                    <div class="text-center">
                                        <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('employee/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
                                            @lang('common.show')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
            @elseif ($i % 3 == 0)
                    @if (count($properties[$i - 1]) > 0)
                        <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                            <div class="card">
                                <div class="text-center">
                                    @svg('solid/home')
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{$properties[$i - 1]->name}}</h5>
                                    <p class="card-text">
                                        {!!$properties[$i - 1]->description!!}
                                    </p>
                                    <div class="text-center">
                                        <a class="btn btn-pallet-1-3" style="color: white;" href="{{ URL::to('employee/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
                                            @lang('common.show')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                @if (count($properties[$i - 1]) > 0)
                    <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                        <div class="card">
                            <div class="text-center">
                                @svg('solid/home')
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">{{$properties[$i - 1]->name}}</h5>
                                <p class="card-text">
                                    {!!$properties[$i - 1]->description!!}
                                </p>
                                <div class="text-center">
                                    <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('employee/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
                                        @lang('common.show')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endfor   

        @if (count($calendars) % 3 != 0)
            </div>
        @endif
    @endif
</div>
@endsection