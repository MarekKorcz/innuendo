@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li style="padding: 5px;">
                <a href="{{ URL::to('/subscription/index') }}" class="btn btn-success">
                    Subscriptions
                </a>
            </li>
            <li style="padding: 5px;">
                <a href="{{ URL::to('property/create') }}" class="btn btn-primary">
                    Create a Property
                </a>
            </li>
        </ul>
    </nav>

    <h1>All Properties</h1>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <td>Name</td>
                <td>Email</td>
                <td>Phone</td>
                <td>Street</td>
                <td>City</td>
                <td>Owner</td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
            @foreach($properties as $key => $value)
            <tr>
                <td>{{ $value->name }}</td>
                <td>{{ $value->email }}</td>
                <td>{{ $value->phone_number }}</td>
                <td>{{ $value->street }}</td>
                <td>{{ $value->city }}</td>
                <td>{{ $value->boss->name }} {{ $value->boss->surname }}</td>

                <td>
                    <a class="btn btn-small btn-success" href="{{ URL::to('property/' . $value->id) }}" style="margin-bottom: 5px;">
                        Show
                    </a>
                    <a class="btn btn-small btn-info" href="{{ URL::to('property/' . $value->id . '/edit') }}" style="margin-bottom: 5px;">
                        Edit
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $properties->links() }}
</div>
@endsection