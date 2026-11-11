@extends('layouts.app')
@section('content')

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <h2 class="text-center">@lang('common.create_category')</h2>

        <form action="{{ action([App\Http\Controllers\CategoryController::class, 'store']) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">@lang('common.label'):</label>
                <input id="name" class="form-control" type="text" name="name">
            </div>
            <div class="form-group">
                <label for="description">@lang('common.description'):</label>
                <input id="description" class="form-control" type="text" name="description">
            </div>

            <div class="text-center">
                <input type="submit" value="@lang('common.create')" class="btn btn-primary">
            </div>
        </form>

    </div>
</div>
@endsection
