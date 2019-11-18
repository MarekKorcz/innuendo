@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <h2 class="text-center">@lang('common.item_edit')</h2>

        {{ Form::open(['action' => ['ItemController@update'], 'method' => 'POST']) }}

            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <input id="name" class="form-control" type="text" name="name" value="{{ $item->name }}">
            </div>  
            <div class="form-group">
                <label for="slug">Slug</label>
                <input id="slug" class="form-control" type="text" name="slug" value="{{ $item->slug }}">
            </div>
            <div class="form-group">
                <label for="description">@lang('common.description')</label>
                <input id="description" class="form-control" type="text" name="description" value="{{ $item->description }}">
            </div>
            <div class="form-group">
                <label for="minutes">@lang('common.minutes_capital')</label>
                <input id="minutes" class="form-control" type="text" name="minutes" value="{{ $item->minutes }}">
            </div>
            <div class="form-group">
                <label for="price">@lang('common.price')</label>
                <input id="price" class="form-control" type="text" name="price" value="{{ $item->price }}">
            </div>

            {{ Form::hidden('item_id', $item->id) }}

            {{ Form::hidden('_method', 'PUT') }}
            
            <div class="text-center">
                <input type="submit" value="@lang('common.update')" class="btn btn-primary">
            </div>

        {{ Form::close() }}
    </div>
</div>
@endsection