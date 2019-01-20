@extends('layouts.app')
@section('content')
<div class="container">

    <h1>Employees</h1>
    
    <hr><br>

    <div class="container">
        @foreach ($employees as $employee)
            <a href="{{ URL::to('employee/' . $employee->slack) }}">
                <div class="d-inline-block bg-warning" style="width: 250px; height: 350px; margin-left: 30px;">
                    <h3 class="text-center">{{$employee->name}}</h3>
                    <div class="text-center">
                        <img src="{{ asset('img/rick-and-morty.jpg') }}" style="width: 200px; height: 280px;"/>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection