@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    
    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li style="padding: 5px;">
                <a href="{{ URL::to('admin/employee/create') }}" class="btn btn-primary">
                    @lang('common.create_employee_account')
                </a>
            </li>
        </ul>
    </nav>
    
    <h2>@lang('common.temp_user_employee_entites') :</h2>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>@lang('common.name')</td>
                <td>@lang('common.created_at')</td>
                <td>@lang('common.activation_email')</td>
            </tr>
        </thead>
        <tbody>
            @foreach($tempEmployees as $tempEmployee)
                <tr>
                    <td>{{$tempEmployee->name}} {{$tempEmployee->surname}}</td>
                    <td>{{$tempEmployee->created_at}}</td>
                    <td>
                        <a class="btn btn-success" href="{{ URL::to('/admin/temp-user/employee/send-activation-email/' . $tempEmployee->id) }}">
                            @lang('common.send')
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <h2>@lang('common.active_employees') :</h2>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>@lang('common.name')</td>
                <td>@lang('common.created_at')</td>
                <td>@lang('common.action')</td>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{$employee->name}} {{$employee->surname}}</td>
                    <td>{{$employee->created_at}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ URL::to('/admin/employee/show/' . $employee->slug) }}">
                            @lang('common.show')
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection