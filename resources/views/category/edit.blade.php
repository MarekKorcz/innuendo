@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <h2 class="text-center">@lang('common.category_edit')</h2>

        {{ Form::open(['action' => ['CategoryController@update'], 'method' => 'POST']) }}

            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <input id="name" class="form-control" type="text" name="name" value="{{ $category->name }}">
            </div>  
            <div class="form-group">
                <label for="slug">Slug</label>
                <input id="slug" class="form-control" type="text" name="slug" value="{{ $category->slug }}">
            </div>
            <div class="form-group">
                <label for="description">@lang('common.description')</label>
                <input id="description" class="form-control" type="text" name="description" value="{{ $category->description }}">
            </div>

            {{ Form::hidden('category_id', $category->id) }}

            {{ Form::hidden('_method', 'PUT') }}
            
            <div class="text-center">
                <input type="submit" value="@lang('common.update')" class="btn btn-primary">
            </div>

        {{ Form::close() }}
    </div>
</div>
@endsection