@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    <h1>TempUser users entites:</h1>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Name</td>
                <td>Created At</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($tempUsers as $tempUser)
                <tr>
                    <td>{{$tempUser->name}} {{$tempUser->surname}}</td>
                    <td>{{$tempUser->created_at}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ URL::to('/admin/temp-user/user/show/' . $tempUser->id) }}">
                            Show
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h1>Active users:</h1>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Name</td>
                <td>Created At</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{$user->name}} {{$user->surname}}</td>
                    <td>{{$user->created_at}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ URL::to('/admin/user/show/' . $user->id) }}">
                            Show
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection