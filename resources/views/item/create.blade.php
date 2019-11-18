@extends('layouts.app')
@section('content')

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <h2 class="text-center">@lang('common.create_item')</h2>

        {{ Form::open(['action' => 'ItemController@store', 'method' => 'POST']) }}

            <div class="form-group">
                <label for="name">@lang('common.label'):</label>
                <input id="name" class="form-control" type="text" name="name">
            </div>    
            <div class="form-group">
                <label for="description">@lang('common.description'):</label>
                <input id="description" class="form-control" type="text" name="description">
            </div>
            <div class="form-group">
                <label for="minutes">@lang('common.minutes_capital'):</label>
                <input id="minutes" class="form-control" type="text" name="minutes">
            </div>
            <div class="form-group">
                <label for="price">@lang('common.price'):</label>
                <input id="price" class="form-control" type="text" name="price">
            </div>
        
            @if ($category !== null)
                {{ Form::hidden('category_id', $category->id) }}
            @else
                {{ Form::hidden('category_id', Input::old('category_id')) }}
            @endif
                        
            <div class="text-center">
                <input type="submit" value="@lang('common.create')" class="btn btn-primary">
            </div>

        {{ Form::close() }}
    </div>
</div>
@endsection