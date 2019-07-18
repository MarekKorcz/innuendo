@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    <h2>TempUser boss entites:</h2>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Name</td>
                <td>Created At</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($tempBosses as $tempBoss)
                <tr>
                    <td>{{$tempBoss->name}} {{$tempBoss->surname}}</td>
                    <td>{{$tempBoss->created_at}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ URL::to('/admin/temp-user/boss/show/' . $tempBoss->id) }}">
                            Show
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Active bosses:</h2>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Name</td>
                <td>Is Approved</td>
                <td>Created At</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($bosses as $boss)
                <tr>
                    <td>{{$boss->name}} {{$boss->surname}}</td>
                    <td>
                        @if ($boss->isApproved == 1)
                            Tak
                        @else
                            Nie
                        @endif
                    </td>
                    <td>{{$boss->created_at}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ URL::to('/admin/boss/show/' . $boss->id) }}">
                            Show
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection