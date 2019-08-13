@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    
    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li style="padding: 5px;">
                <a href="{{ URL::to('admin/employee/create') }}" class="btn btn-primary">
                    Create new Employee
                </a>
            </li>
        </ul>
    </nav>
    
    <h2>TempUser employee entites:</h2>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Name</td>
                <td>Created At</td>
                <td>Activation Email</td>
            </tr>
        </thead>
        <tbody>
            @foreach($tempEmployees as $tempEmployee)
                <tr>
                    <td>{{$tempEmployee->name}} {{$tempEmployee->surname}}</td>
                    <td>{{$tempEmployee->created_at}}</td>
                    <td>
                        <a class="btn btn-success" href="{{ URL::to('/admin/temp-user/employee/send-activation-email/' . $tempEmployee->id) }}">
                            Send
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <h2>Active Employees: </h2>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Name</td>
                <td>Created At</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{$employee->name}} {{$employee->surname}}</td>
                    <td>{{$employee->created_at}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ URL::to('/admin/employee/show/' . $employee->slug) }}">
                            Show
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection