@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <h2 class="text-center">@lang('common.category_edit')</h2>

        <form action="{{ action([App\Http\Controllers\CategoryController::class, 'update']) }}" method="POST">
            @csrf
            @method('PUT')

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

            <input type="hidden" name="category_id" value="{{ $category->id }}">

            <div class="text-center">
                <input type="submit" value="@lang('common.update')" class="btn btn-primary">
            </div>
        </form>

    </div>
</div>
@endsection
