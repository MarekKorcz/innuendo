@extends('layouts.app')

@section('content')

{!! Html::style('css/property_show.css') !!}

<div class="container">

    <h1 class="text-center padding">{{ $employee->name }} {{$employee->surname}}</h1>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                {{ Form::open(['action' => 'AdminController@employeeUpdate', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
    
                    <div class="form-group">
                        {{ Form::label('name', 'Name') }}
                        {{ Form::text('name', $employee->name, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('surname', 'Surname') }}
                        {{ Form::text('surname', $employee->surname, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('slug', 'Slug') }}
                        {{ Form::text('slug', $employee->slug, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('email', 'Email') }}
                        {{ Form::text('email', $employee->email, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('phone_number', 'Phone number') }}
                        {{ Form::number('phone_number', $employee->phone_number, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('profile_image', 'Profile image') }}<br>
                        {{ Form::file('profile_image', null, array('class' => 'form-control')) }}
                    </div>
                
                    {{ Form::hidden('id', $employee->id) }}

                    {{ Form::submit('Upload', array('class' => 'btn btn-primary')) }}

                {{ Form::close() }}
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <div class="text-center">
                    @if (Storage::disk('local')->has($employee->profile_image))
                        <div style="padding: 1rem;">
                            <img src="{{ route('account.image', ['fileName' => $employee->profile_image]) }}" 
                                 alt="{{$employee->name}} {{$employee->surname}}" 
                                 style="width:100%;"
                                 border="0"
                            >
                        </div>
                    @else
                        todo: doać defaultowe zdjęcie?
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($calendars && count($calendars) == count($properties))
    
        <h2 class="text-center padding-top">Grafik w:</h2>
    
        @for ($i = 1; $i <= count($calendars); $i++)
            @if ($i == 1 || $i == 4 || $i == 7 || $i == 10)
                <div class="row padding">
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
                                    <a class="btn btn-success" href="{{ URL::to('employee/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
                                        Zobacz
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            @elseif ($i % 3 == 0)
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
                                    <a class="btn btn-success" href="{{ URL::to('employee/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
                                        Zobacz
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
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
                                <a class="btn btn-success" href="{{ URL::to('employee/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
                                    Zobacz
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endfor   

        @if (count($calendars) % 3 != 0)
            </div>
        @endif
    @endif
</div>
@endsection