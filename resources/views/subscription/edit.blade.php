@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <h2 class="text-center">@lang('common.subscription_edit')</h2>

        {{ Form::open(['action' => ['SubscriptionController@update', $subscription->id], 'method' => 'POST']) }}

            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <input id="name" class="form-control" type="text" name="name" value="{{ $subscription->name }}">
            </div>    
            <div class="form-group">
                <label for="description">@lang('common.description')</label>
                <input id="description" class="form-control" type="text" name="description" value="{{ $subscription->description }}">
            </div>
            <div class="form-group">
                <label for="old_price">@lang('common.old_price')</label>
                <input id="old_price" class="form-control" type="text" name="old_price" value="{{ $subscription->old_price }}">
            </div>
            <div class="form-group">
                <label for="new_price">@lang('common.new_price')</label>
                <input id="new_price" class="form-control" type="text" name="new_price" value="{{ $subscription->new_price }}">
            </div>
            <div class="form-group">
                <label for="quantity">@lang('common.quantity_per_month')</label>
                <input id="quantity" class="form-control" type="text" name="quantity" value="{{ $subscription->quantity }}">
            </div>
            <div class="form-group">
                <label for="duration">@lang('common.duration_how_many_months')</label>
                <input id="duration" class="form-control" type="text" name="duration" value="{{ $subscription->duration }}">
            </div>

            {{ Form::hidden('subscription_id', $subscription->id) }}

            {{ Form::hidden('_method', 'PUT') }}
            
            <div class="text-center">
                <input type="submit" value="@lang('common.update')" class="btn btn-primary">
            </div>

        {{ Form::close() }}
    </div>
</div>
@endsection