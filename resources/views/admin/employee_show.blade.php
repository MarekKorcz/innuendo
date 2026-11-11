@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/property_show.css') }}">

<div class="container">

    <h1 class="text-center padding">{{ $employee->name }} {{$employee->surname}}</h1>

    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <form action="{{ action([\App\Http\Controllers\AdminController::class, 'employeeUpdate']) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="name">@lang('common.name')</label>
                        <input type="text" name="name" id="name" value="{{ $employee->name }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="surname">@lang('common.surname')</label>
                        <input type="text" name="surname" id="surname" value="{{ $employee->surname }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ $employee->slug }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">@lang('common.email_address')</label>
                        <input type="text" name="email" id="email" value="{{ $employee->email }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">@lang('common.phone_number')</label>
                        <input type="number" name="phone_number" id="phone_number" value="{{ $employee->phone_number }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="profile_image">@lang('common.profile_image')</label><br>
                        <input type="file" name="profile_image" id="profile_image" class="form-control">
                    </div>

                    <input type="hidden" name="id" value="{{ $employee->id }}">

                    <div class="text-center">
                        <input type="submit" value="@lang('common.update')" class="btn pallet-2-4" style="color: white;">
                    </div>
                </form>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <div class="text-center">
                    @if (Storage::disk('local')->has($employee->profile_image))
                        <div style="padding: 1rem;">
                            <img src="{{ route('account.image', ['fileName' => $employee->profile_image]) }}"
                                 alt="{{$employee->name}} {{$employee->surname}}"
                                 style="width:100%;"
                                 border="0"
                            >
                        </div>
                    @else
                        todo: doać defaultowe zdjęcie?
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
