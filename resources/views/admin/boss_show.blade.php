@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    <h2>Boss values: </h2>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Name</td>
                <td>Email</td>
                <td>Phone</td>
                <td>Created At</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$boss->name}} {{$boss->surname}}</td>
                <td>{{$boss->email}}</td>
                <td>{{$boss->phone_number}}</td>
                <td>{{$boss->created_at}}</td>
            </tr>
        </tbody>
    </table>

    <h2>Properties owned by boss: </h2>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Name</td>
                <td>Street</td>
                <td>Created At</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($properties as $property)
                <tr>
                    <td>{{$property->name}}</td>
                    <td>{{$property->street}}</td>
                    <td>{{$property->created_at}}</td>
                    <td>
                        <a class="btn btn-success" href="{{ URL::to('/property/' . $property->id) }}">
                            Show
                        </a>
                        <a class="btn btn-primary" href="{{ URL::to('/property/' . $property->id . '/edit') }}">
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <h2>Boss purchased subscriptions: </h2>
    
    <p class="lead">todo: panel dla admina z danymi dotyczącymi wykupionej subskrypcji</p>
</div>
@endsection