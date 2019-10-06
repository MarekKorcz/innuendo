@extends('layouts.app')

@section('content')

{!! Html::style('css/property_index.css') !!}

<div class="container">
    <h1 class="text-center padding-top">@lang('common.pick_property_to_choose_schedule')</h1>
    
    <div class="wrapper">
        @foreach ($properties as $property)
<!--            @if ($property->isPurchased)
                <div class="card" style="background-color: lightgreen;">
            @else
                <div class="card">
            @endif-->
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center">{{$property->name}}</h3>
                    @if ($property->description)
                        <div class="text-center" style="padding-bottom: 1rem;">
                            <p class="card-text">
                                {!!$property->description!!}
                            </p>
                        </div>
                    @endif
                    <div class="text-center">
                        <a href="{{ URL::to('user/property/' . $property->id) }}" class="btn btn-success btn-lg">
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection