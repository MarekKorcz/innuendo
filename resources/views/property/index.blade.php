@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
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
                <td>ID</td>
                <td>Name</td>
                <td>Slug</td>
                <td>Description</td>
                <td>Phone number</td>
                <td>Street</td>
                <td>Street number</td>
                <td>House number</td>
                <td>City</td>
                <td>Owner ID</td>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody>
            @foreach($properties as $key => $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->slug }}</td>
                <td>{{ $value->description }}</td>
                <td>{{ $value->phone_number }}</td>
                <td>{{ $value->street }}</td>
                <td>{{ $value->street_number }}</td>
                <td>{{ $value->house_number }}</td>
                <td>{{ $value->city }}</td>
                <td>{{ $value->user_id }}</td>

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