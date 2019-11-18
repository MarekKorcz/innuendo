@extends('layouts.app')
@section('content')

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <div class="row text-center" style="padding: 1rem 0 1rem 0;">
            <div class="col-4"></div>            
            <div class="col-4">
                <a class="btn btn-success" href="{{ URL::to('/category/index') }}">
                    @lang('common.category_index')
                </a>
            </div>            
            <div class="col-4">
                <a class="btn btn-primary" href="{{ URL::to('/category/' . $category->id . '/edit') }}">
                    @lang('common.edit')
                </a>
            </div>            
        </div>
        
        <div class="text-center">
            <h2>
                @lang('common.label'):
                <strong>
                    {!! $category->name !!}
                </strong>
            </h2>
            <h3>
                Slug:
                <strong>
                    {!! $category->slug !!}
                </strong>
            </h3>
            <h4>
                @lang('common.description'):
                <strong>
                    {!! $category->description !!}
                </strong>
            </h4>
        </div>
        <div id="items">
            <h3 class="text-center">@lang('common.items'):</h3>
            @if (count($category->items) > 0)
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        <ul>
                            @foreach($category->items as $item)
                                <a href="{{ URL::to('/item/show/' . $item->id) }}">
                                    <li class="form-control text-center">
                                        {!! $item->name !!}
                                    </li>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-3"></div>
                </div>
            @endif
            <div class="text-center">
                <a class="btn btn-success" href="{{ URL::to('/item/create/' . $category->id) }}">
                    @lang('common.create_item')
                </a>
            </div>
        </div>
    </div>
</div>
@endsection