@extends('layouts.app')

@section('content')

{!! Html::style('css/property_index.css') !!}

<div class="container">
    <div class="text-center padding-top">
        <h2>@lang('common.pick_property_to_choose_schedule')</h2>
    </div>
    
    <div class="wrapper">
        @foreach ($properties as $property)
            <div class="card">
                <div class="card-body">
                    <div class="text-center" style="padding-bottom: 1rem;">
                        <h3 class="card-title">{{$property->name}}</h3>
                        <h5>{{$property->street}} {{$property->street_number}} {{$property->house_number}}</h5>
                        @if ($property->description)
                            <div style="padding-bottom: 1rem;">
                                <p class="card-text">
                                    {!!$property->description!!}
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="text-center">
                        @if (auth()->user()->isBoss)
                            <a href="{{ URL::to('/boss/calendar/' . $property->id . '/0/0/0') }}" class="btn pallet-1-3 btn-lg" style="color: white;">
                        @else
                            <a href="{{ URL::to('/user/calendar/' . $property->id . '/0/0/0') }}" class="btn pallet-1-3 btn-lg" style="color: white;">
                        @endif
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection