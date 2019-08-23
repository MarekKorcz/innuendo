@extends('layouts.app')
@section('content')

<div class="container">
    
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::to('boss/property/' . $property->id . '/edit') }}">
                    @lang('navbar.property_edit')
                </a>
            </li>
        </ul>
    </nav>

    <h1 class="text-center" style="padding: 2rem;">{{ $property->name }}</h1>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3>@lang('common.description')</h3>
                <p>@lang('common.company_name') : <strong>{{$property->name}}</strong></p>
                <p>@lang('common.creation_date') : <strong>{{ $propertyCreatedAt }}</strong></p>
                @if ($property->description)
                    <span>@lang('common.description') : {!!$property->description!!}</span>
                @endif
                <p>@lang('common.address') : <strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}</strong></p>
                @if ($property->city)
                    <p>@lang('common.city') : <strong>{{$property->city}}</strong></p>
                @endif
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <img class="img-fluid" src="/img/column2.jpg">
            </div>
        </div>
    </div>
    
    <h2 class="text-center" style="padding: 2rem;">
        @lang('common.your_employees') :
    </h2>
    
    @if ($workers !== null)
        <table class="table table-striped table-bordered">
            <thead>
                <tr>                
                    <td>@lang('common.name')</td>
                    <td>@lang('common.email_address')</td>
                    <td>@lang('common.phone_number')</td>
                </tr>
            </thead>
            <tbody>
                @foreach($workers as $worker)
                    <tr>
                        <td>{{$worker->name}} {{$worker->surname}}</td>
                        <td>{{$worker->email}}</td>
                        <td>{{$worker->phone_number}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <h3 class="text-center">
            @lang('common.no_employee_assigned_to_property')
        </h3>      
    @endif
    
    <br>
</div>
@endsection