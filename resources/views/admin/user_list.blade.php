@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a class="btn btn-success" href="{{ URL::previous() }}">
                Wróć
            </a>
        </div>
    </nav>

    <h1>All users</h1>
    
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
                            Pokaż
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection