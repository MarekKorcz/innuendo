@extends('layouts.app')

@section('content')

{!! Html::style('css/property_index.css') !!}

<div class="container">
    <div class="text-center padding-top">
        <h2>@lang('common.pick_property_to_see_appointmetns')</h2>
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
                        <a href="{{ URL::to('/boss/worker/appointment/list/' . $property->id) }}" class="btn pallet-1-3 btn-lg" style="color: white;">
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection