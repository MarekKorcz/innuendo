@extends('layouts.app')
@section('content')

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <div class="row text-center" style="padding: 1rem 0 1rem 0;">
            <div class="col-4"></div>            
            <div class="col-4">
                <a class="btn btn-success" href="{{ URL::to('/category/show/' . $item->category->id) }}">
                    @lang('common.category')
                </a>
            </div>            
            <div class="col-4">
                <a class="btn btn-primary" href="{{ URL::to('/item/' . $item->id . '/edit') }}">
                    @lang('common.edit')
                </a>
            </div>            
        </div>
        
        <div class="text-center">
            <h2>
                @lang('common.label'):
                <strong>
                    {!! $item->name !!}
                </strong>
            </h2>
            <h3>
                Slug:
                <strong>
                    {!! $item->slug !!}
                </strong>
            </h3>
            <h4>
                @lang('common.description'):
                <strong>
                    {!! $item->description !!}
                </strong>
            </h4>
            <h3>
                @lang('common.minutes_capital'):
                <strong>
                    {{ $item->minutes }}
                </strong>
            </h3>
            <h3>
                @lang('common.price'):
                <strong>
                    {{ $item->price }}
                </strong>
            </h3>
        </div>
    </div>
</div>
@endsection