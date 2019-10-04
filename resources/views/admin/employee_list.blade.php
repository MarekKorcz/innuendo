@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    
    <div style="padding: 1rem 0 1rem 0">
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
        
        <h2 class="text-center" style="padding-bottom: 1rem;">@lang('common.active_employees') :</h2>

        @if (count($employees) > 0)
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
        @else
            <div class="text-center">
                <h3>@lang('common.no_employees_description')</h3>
            </div>
        @endif

        <h2 class="text-center" style="padding: 1rem 0 1rem 0;">@lang('common.temp_user_employee_entites') :</h2>

        @if (count($tempEmployees) > 0)
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
        @else
            <div class="text-center">
                <h3>@lang('common.no_temp_employees_description')</h3>
            </div>
        @endif
    </div>
</div>
@endsection