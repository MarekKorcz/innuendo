@extends('layouts.app')
@section('content')

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <h2 class="text-center">@lang('common.promo_edit')</h2>

        {{ Form::open(['action' => ['AdminController@promoUpdate']]) }}

            <div class="form-group">
                <label for="title">@lang('common.title'):</label>
                {{ Form::text('title', $promo->title, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="title">@lang('common.title_en'):</label>
                {{ Form::text('title_en', $promo->title_en, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="description">@lang('common.description'):</label>
                {{ Form::textarea('description', $promo->description, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="description_en">@lang('common.description_en'):</label>
                {{ Form::textarea('description_en', $promo->description_en, array('class' => 'form-control')) }}
            </div>
        
            {{ Form::hidden('promo_id', $promo->id) }}

            {{ Form::hidden('_method', 'PUT') }}
            
            <input type="submit" value="@lang('common.update')" class="btn btn-primary">

        {{ Form::close() }}
    </div>
</div>
@endsection