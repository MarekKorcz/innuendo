@extends('layouts.app')
@section('content')

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <h2 class="text-center">@lang('common.promo_edit')</h2>

        <form action="{{ action([App\Http\Controllers\AdminController::class, 'promoUpdate']) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">@lang('common.title'):</label>
                <input type="text" name="title" value="{{ $promo->title }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="title">@lang('common.title_en'):</label>
                <input type="text" name="title_en" value="{{ $promo->title_en }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="description">@lang('common.description'):</label>
                <textarea name="description" class="form-control">{{ $promo->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="description_en">@lang('common.description_en'):</label>
                <textarea name="description_en" class="form-control">{{ $promo->description_en }}</textarea>
            </div>

            <input type="hidden" name="promo_id" value="{{ $promo->id }}">

            <input type="submit" value="@lang('common.update')" class="btn btn-primary">
        </form>

    </div>
</div>
@endsection
