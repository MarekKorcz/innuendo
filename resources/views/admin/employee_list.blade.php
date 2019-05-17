@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    <h2>Employees: </h2>

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
                        <a class="btn btn-primary" href="{{ URL::to('/admin/employee/show/' . $employee->id) }}">
                            Show
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection