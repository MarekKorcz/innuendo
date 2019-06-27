@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    
    <h2>Temporary boss values: </h2>
    
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
                <td>{{$tempBoss->name}} {{$tempBoss->surname}}</td>
                <td>{{$tempBoss->email}}</td>
                <td>{{$tempBoss->phone_number}}</td>
                <td>{{$tempBoss->created_at}}</td>
            </tr>
        </tbody>
    </table>

    <h2>Temporary boss properties: </h2>
    
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
            <tr>
                <td>{{$tempProperty->name}}</td>
                <td>{{$tempProperty->street}} {{$tempProperty->street_number}} / {{$tempProperty->house_number}}</td>
                <td>{{$tempProperty->created_at}}</td>
                <td>
                    <a class="btn btn-success" href="{{ URL::to('/temp-property/' . $tempProperty->id) }}">
                        Show
                    </a>
                    <a class="btn btn-primary" href="{{ URL::to('temp-property/' . $tempProperty->id . '/edit') }}">
                        Edit
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection