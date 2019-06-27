@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <nav class="navbar navbar-inverse">
            <div class="navbar-header">
                {!!Form::open(['action' => ['PropertyController@tempPropertyDestroy', $tempProperty->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                {!!Form::close()!!}
            </div>
            <ul class="nav navbar-nav">
                <li>
                    <a class="btn btn-success" href="{{ URL::to('temp-property/' . $tempProperty->id . '/edit') }}">
                        Edit
                    </a>
                </li>
            </ul>
        </nav>

        <h2 class="text-center">Temporary property</h2>
    
        <table class="table table-striped">
            <tr>
                <th>Name:</th>
                <th>Street:</th>
                <th>Street number:</th>
                <th>House number:</th>
                <th>City:</th>
                <th>Owner:</th>
            </tr>
            <tr>
                <td>{{ $tempProperty->name }}</td>
                <td>{{ $tempProperty->street }}</td>
                <td>{{ $tempProperty->street_number }}</td>
                <td>{{ $tempProperty->house_number }}</td>
                <td>{{ $tempProperty->city }}</td>
                @if ($tempProperty->temp_user_id > 0)
                    <td>{{ $tempProperty->owner->name }} {{ $tempProperty->owner->surname }}</td>
                @else
                    <td>None</td>
                @endif
            </tr>
        </table>
        
        <h3 class="text-center">Subscriptions</h3>

        @if (count($subscriptions) > 0)
            <table class="table table-striped">
                <tr>
                    <th>Name:</th>
                    <th>Description:</th>
                    <th>Old Price:</th>
                    <th>New price:</th>
                    <th>Quantity:</th>
                    <th>Duration:</th>
                </tr>
                    @foreach ($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->name }}</td>
                            <td>{!! $subscription->description !!}</td>
                            <td>{{ $subscription->old_price }}</td>
                            <td>{{ $subscription->new_price }}</td>
                            <td>{{ $subscription->quantity }}</td>
                            <td>{{ $subscription->duration }}</td>
                        </tr>
                    @endforeach
            </table>
        @else
            <p class="text-center">There is no subscriptions attached</p>
        @endif
    </div>
</div>
@endsection