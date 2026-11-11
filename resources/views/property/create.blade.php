@extends('layouts.app')
@section('content')

<script src="https://ckeditor.com"></script>
<script>
    CKEDITOR.replace('article-ckeditor');
</script>

<div class="container">

    <div class="row text-center" style="padding-top: 1rem;">
        <div class="col-4"></div>
        <div class="col-4">
            <a href="{{ URL::to('/property/index') }}" class="btn pallet-1-3" style="color: white;">
                @lang('common.all_properties')
            </a>
        </div>
        <div class="col-4"></div>
    </div>

    <div class="row" style="padding: 1rem 0 1rem 0;">
        <div class="col-1"></div>
        <div class="col-10">

            <div class="text-center">
                <h1>@lang('common.create_property')</h1>
            </div>

            <form action="{{ action([App\Http\Controllers\PropertyController::class, 'store']) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">@lang('common.name'):</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="description">@lang('common.description'):</label>
                    <textarea id="article-ckeditor" name="description" class="form-control">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="street">@lang('common.street'):</label>
                    <input type="text" name="street" value="{{ old('street') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="street_number">@lang('common.street_number'):</label>
                    <input type="text" name="street_number" value="{{ old('street_number') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="house_number">@lang('common.house_number'):</label>
                    <input type="text" name="house_number" value="{{ old('house_number') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="city">@lang('common.city'):</label>
                    <input type="text" name="city" value="{{ old('city') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="user">@lang('common.users'):</label>
                    <select id="user" name="user" class="form-control">
                        <option value="0"></option>
                        @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->name}} {{$user->surname}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="text-center">
                    <input type="submit" value="@lang('common.create')" class="btn pallet-1-3" style="color: white;">
                </div>
            </form>

        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection
