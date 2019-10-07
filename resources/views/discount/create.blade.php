@extends('layouts.app')
@section('content')

<div class="container">
    
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4"></div>
        <div class="col-4 text-center">
            <div style="padding-top: 1rem;">
                <a class="btn btn-primary" href="{{ URL::to('/admin/discount/index') }}">
                    @lang('common.discount_index')
                </a>
            </div>
        </div>
    </div>
    
    <div class="jumbotron" style="margin-top: 15px;">
        <h2 class="text-center">@lang('common.discount_create')</h2>

        {{ Form::open(['action' => 'DiscountController@store', 'method' => 'POST']) }}

            <div class="form-group">
                <label for="name">@lang('common.label'):</label>
                <input id="name" class="form-control" type="text" name="name">
            </div>    
            <div class="form-group">
                <label for="description">@lang('common.description'):</label>
                <input id="description" class="form-control" type="text" name="description">
            </div>
            <div class="form-group">
                <label for="worker_threshold">@lang('common.worker_threshold'):</label>
                <input id="worker_threshold" class="form-control" type="number" min="1" max="100" name="worker_threshold">
            </div>
            <div class="form-group">
                <label for="percent">@lang('common.percent'):</label>
                <input id="percent" class="form-control" type="number" min="1" max="100" name="percent">
            </div>
        
            <div class="text-center">
                <input type="submit" value="@lang('common.create')" class="btn btn-primary">
            </div>

        {{ Form::close() }}
    </div>
</div>
@endsection