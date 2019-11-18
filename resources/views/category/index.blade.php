@extends('layouts.app')
@section('content')
<div class="container">
    
    <div style="padding: 2rem 0 2rem 0">
        <h1 class="text-center" style="padding-bottom: 1rem;">@lang('common.categories_and_items')</h1>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>                
                    <td>@lang('common.name')</td>
                    <td>Slug</td>
                    <td>@lang('common.description')</td>
                    <td>@lang('common.action')</td>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{!! $category->name !!}</td>
                        <td>{!! $category->slug !!}</td>
                        <td>{!! $category->description !!}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ URL::to('/category/show/' . $category->id) }}">
                                @lang('common.show')
                            </a>
                            <a class="btn btn-success" href="{{ URL::to('/category/' . $category->id . '/edit') }}">
                                @lang('common.edit')
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection