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
                        <label for="name">@lang('common.name')</label>
                        {{ Form::text('name', $employee->name, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="surname">@lang('common.surname')</label>
                        {{ Form::text('surname', $employee->surname, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('slug', 'Slug') }}
                        {{ Form::text('slug', $employee->slug, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="email">@lang('common.email_address')</label>
                        {{ Form::text('email', $employee->email, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="phone_number">@lang('common.phone_number')</label>
                        {{ Form::number('phone_number', $employee->phone_number, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="profile_image">@lang('common.profile_image')</label><br>
                        {{ Form::file('profile_image', null, array('class' => 'form-control')) }}
                    </div>
                
                    {{ Form::hidden('id', $employee->id) }}

                    <div class="text-center">
                        <input type="submit" value="@lang('common.update')" class="btn pallet-2-4" style="color: white;">
                    </div>                    

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
</div>
@endsection