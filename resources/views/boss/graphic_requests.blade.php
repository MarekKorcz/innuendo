@extends('layouts.app')
@section('content')
<div class="container">

    <h1>Wszystkie zapytania o grafiki</h1>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Data</td>
                <td>Godzina</td>
                <td>Komentarz</td>
                <td>Ilu pracowników</td>
                <td>Akcja</td>
            </tr>
        </thead>
        <tbody>
            @foreach($graphicRequests as $graphicRequest)
                <tr>
                    <td>{{$graphicRequest->year->year}} {{$graphicRequest->month->month}} {{$graphicRequest->day->day_number}}</td>
                    <td>{{$graphicRequest->start_time}} - {{$graphicRequest->end_time}}</td>
                    <td>{{$graphicRequest->comment}}</td>
                    <td>{{count($graphicRequest->employees)}}</td>
                    <td>
                        <a class="btn btn-success" href="{{ URL::to('/boss/graphic-request/' . $graphicRequest->id . '/0') }}">
                            @lang('common.show')
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection