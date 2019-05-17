@extends('layouts.app')
@section('content')
<div class="container">
    <h2 class="text-center">Subscriptions</h2>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Name</td>
                <td>Description</td>
                <td>Old price</td>
                <td>New price</td>
                <td>Quantity</td>
                <td>Duration</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $subscription)
                <tr>
                    <td>{{$subscription->name}}</td>
                    <td>{{$subscription->description}}</td>
                    <td>{{$subscription->old_price}}</td>
                    <td>{{$subscription->new_price}}</td>
                    <td>{{$subscription->quantity}}</td>
                    <td>{{$subscription->duration}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ URL::to('/subscription/show/' . $subscription->id) }}">
                            Show
                        </a>
                        <a class="btn btn-success" href="{{ URL::to('/subscription/' . $subscription->id . '/edit') }}">
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection