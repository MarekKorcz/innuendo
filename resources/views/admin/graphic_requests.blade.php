@extends('layouts.app')
@section('content')
<div class="container">

    <h1 class="text-center">All graphic requests</h1>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Owner</td>
                <td>Date</td>
                <td>Time</td>
                <td>Comment</td>
                <td>Employees</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($graphicRequests as $graphicRequest)
                <tr>
                    <td>{{$graphicRequest->property->name}} - {{$graphicRequest->boss->name}} {{$graphicRequest->boss->surname}}</td>
                    <td>{{$graphicRequest->year->year}} {{$graphicRequest->month->month}} {{$graphicRequest->day->day_number}}</td>
                    <td>{{$graphicRequest->start_time}} - {{$graphicRequest->end_time}}</td>
                    <td>{{$graphicRequest->comment}}</td>
                    <td>{{count($graphicRequest->employees)}}</td>
                    <td>
                        <a class="btn btn-success" href="{{ URL::to('/admin/graphic-request/' . $graphicRequest->id . '/0') }}">
                            Show
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection